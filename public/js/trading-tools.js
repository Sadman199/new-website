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
        } else if (slug === 'cost') {
            add('Spread cost', result.spread_available ? money(result.spread_cost, result.account_currency) : 'Unavailable');
            add('Commission', result.commission_available ? money(result.commission_cost, result.account_currency) : 'Unavailable');
            add('Swap', result.swap_available ? money(result.swap_cost, result.account_currency) : 'Unavailable');
            add(
                'Estimated total',
                result.total_cost != null ? money(result.total_cost, result.account_currency) : 'Unavailable',
                'calc-result-total'
            );
            if (result.unavailable && result.unavailable.length) {
                rows.push('<p class="tt-results__note">Unavailable: ' + result.unavailable.join(', ') + '. Missing broker values are not invented.</p>');
            }
            if (result.note) {
                rows.push('<p class="tt-results__note">' + result.note + '</p>');
            }
        }

        box.innerHTML = rows.join('') || '<p class="tt-results__placeholder">No results</p>';
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function bindCostBroker() {
        var combobox = root.querySelector('[data-broker-combobox]');
        var hidden = root.querySelector('[data-field="broker_id"]');
        var input = root.querySelector('#cost-broker-search');
        var list = root.querySelector('#cost-broker-list');
        if (!hidden || !input || !list) {
            return;
        }

        var brokers = [];
        try {
            brokers = JSON.parse(root.getAttribute('data-brokers') || '[]');
        } catch (error) {
            brokers = [];
        }

        var searchUrl = root.getAttribute('data-broker-search-url') || '';
        var hint = root.querySelector('[data-broker-hint]');
        var spread = root.querySelector('[data-field="spread_pips"]');
        var selectedBroker = null;
        var visibleBrokers = [];
        var activeIndex = -1;
        var debounceTimer = null;
        var lastQuery = null;
        var requestSeq = 0;

        function applyBroker(broker) {
            selectedBroker = broker || null;
            hidden.value = broker ? String(broker.id) : '';

            if (!broker) {
                if (hint) {
                    hint.hidden = true;
                    hint.textContent = '';
                }
                return;
            }

            input.value = broker.name;

            if (spread) {
                spread.value = broker.spread_known && broker.spread_pips != null ? broker.spread_pips : '';
            }

            if (hint) {
                var parts = [];
                if (broker.spread_known) {
                    parts.push('Parsed published spread: ' + broker.spread_pips + ' pips.');
                    if (broker.spread_raw) {
                        parts.push('Source text: “' + broker.spread_raw + '”.');
                    }
                } else {
                    parts.push(broker.spread_raw
                        ? 'Numeric spread unavailable (published: ' + broker.spread_raw + '). Enter it from the broker spec.'
                        : 'Numeric spread unavailable — enter it from the broker spec.');
                }
                parts.push(broker.commission
                    ? 'Published commission: ' + broker.commission + '.'
                    : 'Commission unavailable in the database.');
                hint.textContent = parts.join(' ');
                hint.hidden = false;
            }
        }

        function closeList() {
            list.hidden = true;
            list.innerHTML = '';
            visibleBrokers = [];
            input.setAttribute('aria-expanded', 'false');
            input.removeAttribute('aria-activedescendant');
            activeIndex = -1;
            if (combobox) {
                combobox.classList.remove('is-open');
            }
        }

        function setActive(index) {
            var options = list.querySelectorAll('[role="option"]');
            if (!options.length) {
                activeIndex = -1;
                input.removeAttribute('aria-activedescendant');
                return;
            }

            activeIndex = Math.max(0, Math.min(index, options.length - 1));
            options.forEach(function (option, i) {
                var isActive = i === activeIndex;
                option.classList.toggle('is-active', isActive);
                option.setAttribute('aria-selected', isActive ? 'true' : 'false');
                if (isActive) {
                    input.setAttribute('aria-activedescendant', option.id);
                    if (option.scrollIntoView) {
                        option.scrollIntoView({ block: 'nearest' });
                    }
                }
            });
        }

        function renderList(items, query) {
            visibleBrokers = items || [];
            list.innerHTML = '';

            if (!visibleBrokers.length) {
                var empty = document.createElement('li');
                empty.className = 'calc-broker-combobox__empty';
                empty.textContent = query
                    ? 'No matching brokers in our database.'
                    : 'Type a broker name to search.';
                list.appendChild(empty);
            } else {
                visibleBrokers.forEach(function (broker, i) {
                    var option = document.createElement('li');
                    option.id = 'cost-broker-option-' + broker.id;
                    option.className = 'calc-broker-combobox__option';
                    option.setAttribute('role', 'option');
                    option.setAttribute('aria-selected', 'false');
                    option.dataset.index = String(i);
                    option.innerHTML = '<span class="calc-broker-combobox__name">' + escapeHtml(broker.name) + '</span>';
                    if (broker.spread_known && broker.spread_pips != null) {
                        option.innerHTML += '<span class="calc-broker-combobox__meta">' + escapeHtml(broker.spread_pips + ' pips') + '</span>';
                    }
                    option.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                        applyBroker(broker);
                        closeList();
                    });
                    list.appendChild(option);
                });
            }

            list.hidden = false;
            input.setAttribute('aria-expanded', 'true');
            if (combobox) {
                combobox.classList.add('is-open');
            }
            setActive(visibleBrokers.length ? 0 : -1);
        }

        function localMatches(query) {
            var needle = query.toLowerCase().replace(/[\s\-]/g, '');
            if (!needle) {
                return brokers.slice(0, 12);
            }

            return brokers.filter(function (item) {
                var name = String(item.name || '').toLowerCase();
                var compact = name.replace(/[\s\-]/g, '');
                return name.indexOf(query.toLowerCase()) !== -1 || compact.indexOf(needle) !== -1;
            }).slice(0, 12);
        }

        function searchBrokers(query, immediate) {
            var run = function () {
                if (query === lastQuery && list.hidden === false && immediate !== true) {
                    return;
                }
                lastQuery = query;

                if (!searchUrl) {
                    renderList(localMatches(query), query);
                    return;
                }

                var seq = ++requestSeq;
                fetch(searchUrl + '?q=' + encodeURIComponent(query), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (json) {
                        if (seq !== requestSeq) {
                            return;
                        }
                        var results = json && json.brokers ? json.brokers : [];
                        results.forEach(function (item) {
                            var exists = brokers.some(function (known) {
                                return Number(known.id) === Number(item.id);
                            });
                            if (!exists) {
                                brokers.push(item);
                            }
                        });
                        renderList(results, query);
                    })
                    .catch(function () {
                        if (seq !== requestSeq) {
                            return;
                        }
                        renderList(localMatches(query), query);
                    });
            };

            if (immediate) {
                run();
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(run, 180);
        }

        input.addEventListener('focus', function () {
            searchBrokers(input.value.trim(), true);
        });

        input.addEventListener('input', function () {
            var query = input.value.trim();
            if (selectedBroker && query !== selectedBroker.name) {
                applyBroker(null);
                input.value = query;
            }
            searchBrokers(query);
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                if (list.hidden) {
                    searchBrokers(input.value.trim(), true);
                    return;
                }
                setActive(activeIndex + 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                setActive(activeIndex - 1);
            } else if (event.key === 'Enter') {
                if (!list.hidden && activeIndex >= 0 && visibleBrokers[activeIndex]) {
                    event.preventDefault();
                    applyBroker(visibleBrokers[activeIndex]);
                    closeList();
                }
            } else if (event.key === 'Escape') {
                closeList();
            }
        });

        input.addEventListener('blur', function () {
            setTimeout(closeList, 120);
        });
    }

    bindCostBroker();

    root.querySelectorAll('.tt-calc-btn, .calc-tool__submit[data-calc]').forEach(function (btn) {
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
