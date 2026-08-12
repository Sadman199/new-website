(function () {
    'use strict';

    var root = document.getElementById('toolsDashboard');
    if (!root) {
        return;
    }

    var calcUrl = root.getAttribute('data-calc-url');
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';

    function findPanel(slug) {
        return root.querySelector('.tt-panel[data-panel="' + slug + '"]')
            || root.querySelector('.tt-tool__panel')
            || root;
    }

    root.querySelectorAll('.tt-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.getAttribute('data-tool');

            root.querySelectorAll('.tt-tab').forEach(function (tab) {
                tab.classList.remove('is-active');
                tab.setAttribute('aria-selected', 'false');
            });

            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');

            root.querySelectorAll('.tt-panel').forEach(function (panel) {
                panel.classList.toggle('is-hidden', panel.getAttribute('data-panel') !== slug);
            });

            if (history.replaceState) {
                history.replaceState(null, '', '?tool=' + encodeURIComponent(slug));
            }
        });
    });

    root.querySelectorAll('[data-dir-group]').forEach(function (group) {
        group.querySelectorAll('.dir-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                group.querySelectorAll('.dir-btn').forEach(function (item) {
                    item.classList.remove('active-buy', 'active-sell');
                });

                var dir = button.getAttribute('data-dir');
                button.classList.add(dir === 'buy' ? 'active-buy' : 'active-sell');

                var hidden = group.querySelector('input[type="hidden"]');
                if (hidden) {
                    hidden.value = dir;
                }
            });
        });
    });

    function collect(panel) {
        var data = {};
        panel.querySelectorAll('[data-field]').forEach(function (el) {
            data[el.getAttribute('data-field')] = el.value;
        });
        return data;
    }

    function money(n, ccy) {
        var value = Number(n);
        if (isNaN(value)) {
            return '—';
        }
        return (ccy ? ccy + ' ' : '') + value.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 4,
        });
    }

    function render(slug, result) {
        var box = root.querySelector('.tt-results[data-results="' + slug + '"]');
        var status = root.querySelector('.tt-status[data-status="' + slug + '"]');
        if (!box) {
            return;
        }

        if (status) {
            status.textContent = 'Updated';
        }

        var rows = [];

        function add(label, value, cls) {
            rows.push(
                '<div class="tt-result-row">' +
                    '<span class="tt-result-label">' + label + '</span>' +
                    '<span class="tt-result-value ' + (cls || '') + '">' + value + '</span>' +
                '</div>'
            );
        }

        if (slug === 'pip') {
            add('Pip size', result.pip_size);
            add('Pip value', money(result.pip_value, result.account_currency));
            add('Position value', money(result.position_value, result.account_currency));
            add('Price used', result.price);
        } else if (slug === 'position') {
            add('Risk amount', money(result.risk_amount, result.account_currency));
            add('Position size', result.position_size_lots + ' lots');
            add('Pip value / lot', money(result.pip_value_per_lot, result.account_currency));
            add('Stop loss', result.sl_pips + ' pips');
        } else if (slug === 'profit') {
            add('Pips', result.pips);
            add('Pip value', money(result.pip_value, result.account_currency));
            add('Profit / Loss', money(result.profit_loss, result.account_currency), result.is_profit ? 'pos' : 'neg');
        } else if (slug === 'margin') {
            add('Position value', money(result.position_value, result.account_currency));
            add('Required margin', money(result.required_margin, result.account_currency));
            add('Leverage', '1:' + result.leverage);
        } else if (slug === 'risk') {
            add('Balance', money(result.balance));
            add('Risk amount', money(result.risk_amount), 'neg');
            add('Reward amount', money(result.reward_amount), 'pos');
            add('Break-even win rate', result.break_even_winrate + '%');
        } else if (slug === 'pivot') {
            add('Method', result.method);
            add('Pivot (PP)', result.pivot);
            add('R1', result.r1);
            add('R2', result.r2);
            add('R3', result.r3);
            add('S1', result.s1);
            add('S2', result.s2);
            add('S3', result.s3);
        } else if (slug === 'fibonacci') {
            (result.levels || []).forEach(function (level) {
                add(level.label, level.price);
            });
        } else if (slug === 'converter') {
            add('Rate', '1 ' + result.from + ' = ' + result.rate + ' ' + result.to);
            add('Converted', money(result.converted, result.to), 'pos');
            if (result.note) {
                rows.push('<p class="tt-results__note">' + result.note + '</p>');
            }
        }

        box.innerHTML = rows.join('') || '<p class="tt-results__placeholder">No results</p>';
    }

    root.querySelectorAll('.tt-calc-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.getAttribute('data-calc');
            var panel = findPanel(slug);
            var status = root.querySelector('.tt-status[data-status="' + slug + '"]');

            if (status) {
                status.textContent = 'Calculating…';
            }

            var payload = collect(panel);
            payload.tool = slug;

            fetch(calcUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            })
                .then(function (response) {
                    return response.json().then(function (json) {
                        return { ok: response.ok, json: json };
                    });
                })
                .then(function (res) {
                    if (!res.ok || !res.json.result) {
                        if (status) {
                            status.textContent = 'Error';
                        }
                        var box = root.querySelector('.tt-results[data-results="' + slug + '"]');
                        if (box) {
                            box.innerHTML = '<p class="tt-results__error">' + (res.json.error || 'Calculation failed') + '</p>';
                        }
                        return;
                    }
                    render(slug, res.json.result);
                })
                .catch(function () {
                    if (status) {
                        status.textContent = 'Error';
                    }
                });
        });
    });
})();
