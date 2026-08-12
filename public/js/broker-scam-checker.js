(function () {
    'use strict';

    var app = document.getElementById('bscApp');
    if (!app) {
        return;
    }

    var searchUrl = app.dataset.searchUrl;
    var compareUrl = app.dataset.compareUrl;
    var reportUrl = app.dataset.reportUrl;
    var openReport = app.dataset.openReport === '1';
    var currentSlug = app.dataset.currentSlug || '';
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrf ? csrf.getAttribute('content') : '';

    var input = document.getElementById('bscSearchInput');
    var dropdown = document.getElementById('bscSearchDropdown');
    var searchTimer = null;

    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function qsa(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    function hide(el) {
        if (el) {
            el.classList.add('bsc-hidden');
        }
    }

    function show(el) {
        if (el) {
            el.classList.remove('bsc-hidden');
        }
    }

    function openModal(el) {
        if (!el) {
            return;
        }
        el.hidden = false;
        el.classList.add('is-open');
        el.setAttribute('aria-hidden', 'false');
        document.body.classList.add('bsc-modal-open');
    }

    function closeModal(el) {
        if (!el) {
            return;
        }
        el.hidden = true;
        el.classList.remove('is-open');
        el.setAttribute('aria-hidden', 'true');
        if (!document.querySelector('.bsc-modal.is-open')) {
            document.body.classList.remove('bsc-modal-open');
        }
    }

    function closeAllModals() {
        qsa('.bsc-modal.is-open').forEach(closeModal);
    }

    qsa('[data-bsc-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal(qs(btn.getAttribute('data-bsc-open')));
        });
    });

    qsa('[data-bsc-close]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeModal(btn.closest('.bsc-modal'));
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    function animateScoreRing() {
        qsa('.bsc-score-ring[data-score]').forEach(function (ring) {
            var score = parseInt(ring.getAttribute('data-score'), 10) || 0;
            var fill = qs('.bsc-ring-fill', ring);
            if (fill) {
                var circumference = 326.7;
                fill.style.strokeDashoffset = String(circumference - (circumference * score / 100));
            }

            var value = qs('#bscScoreValue', ring) || qs('#bscScoreValue');
            if (value && ring.contains(value)) {
                var current = 0;
                var step = Math.max(1, Math.round(score / 40));
                var interval = setInterval(function () {
                    current += step;
                    if (current >= score) {
                        current = score;
                        clearInterval(interval);
                    }
                    value.textContent = String(current);
                }, 20);
            }
        });
    }

    function animateMeters() {
        qsa('[data-meter]').forEach(function (el) {
            var value = parseInt(el.getAttribute('data-meter'), 10) || 0;
            el.style.width = value + '%';
        });
    }

    function renderDropdown(items) {
        if (!dropdown) {
            return;
        }
        if (!items.length) {
            hide(dropdown);
            dropdown.innerHTML = '';
            return;
        }

        dropdown.innerHTML = items.map(function (item) {
            return '<button type="button" class="bsc-search-item" data-url="' + item.url + '">' +
                '<img src="' + item.logo_url + '" alt="" loading="lazy" decoding="async" width="32" height="32">' +
                '<span><strong>' + item.name + '</strong><br><small class="bsc-muted">' + item.risk_label + ' · ' + item.overall_score + '/100</small></span>' +
                '</button>';
        }).join('');
        show(dropdown);
    }

    if (input) {
        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(searchTimer);

            if (q.length < 2) {
                hide(dropdown);
                if (dropdown) {
                    dropdown.innerHTML = '';
                }
                return;
            }

            searchTimer = setTimeout(function () {
                fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                    headers: { Accept: 'application/json' }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (response) {
                        renderDropdown(response.results || []);
                    })
                    .catch(function () {});
            }, 250);
        });
    }

    if (dropdown) {
        dropdown.addEventListener('click', function (e) {
            var item = e.target.closest('.bsc-search-item');
            if (item) {
                window.location.href = item.getAttribute('data-url');
            }
        });
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.bsc-search__wrap')) {
            hide(dropdown);
        }
    });

    qsa('[data-bsc-example]').forEach(function (chip) {
        chip.addEventListener('click', function () {
            if (input) {
                input.value = chip.getAttribute('data-bsc-example');
            }
            qs('#bscSearchForm')?.submit();
        });
    });

    var compareToggle = qs('#bscCompareToggle');
    if (compareToggle) {
        compareToggle.addEventListener('click', function () {
            if (currentSlug) {
                var nameEl = qs('.bsc-broker-ident__name');
                var compare1 = qs('#bscCompare1');
                if (nameEl && compare1) {
                    compare1.value = nameEl.textContent.trim();
                }
            }
            openModal(qs('#bscCompareModal'));
        });
    }

    var runCompare = qs('#bscRunCompare');
    if (runCompare) {
        runCompare.addEventListener('click', function () {
            var brokers = ['#bscCompare1', '#bscCompare2', '#bscCompare3']
                .map(function (selector) {
                    var el = qs(selector);
                    return el ? el.value.trim() : '';
                })
                .filter(Boolean);

            if (brokers.length < 2) {
                alert('Please enter at least two broker names.');
                return;
            }

            fetch(compareUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json'
                },
                body: JSON.stringify({ brokers: brokers })
            })
                .then(function (res) {
                    return res.json().then(function (data) {
                        return { ok: res.ok, data: data };
                    });
                })
                .then(function (result) {
                    if (!result.ok) {
                        var message = result.data && result.data.message
                            ? result.data.message
                            : 'Comparison failed. Please check broker names.';
                        qs('#bscCompareResults').innerHTML = '<p class="bsc-form-alert bsc-form-alert--error">' + message + '</p>';
                        return;
                    }
                    renderCompareTable(result.data.brokers || []);
                })
                .catch(function () {
                    qs('#bscCompareResults').innerHTML = '<p class="bsc-form-alert bsc-form-alert--error">Comparison failed. Please check broker names.</p>';
                });
        });
    }

    function renderCompareTable(brokers) {
        var wrap = qs('#bscCompareResults');
        if (!wrap) {
            return;
        }
        if (!brokers.length) {
            wrap.innerHTML = '<p class="bsc-muted">No results.</p>';
            return;
        }

        var headers = brokers.map(function (b) {
            return '<th>' + b.broker.name + '<br><small>' + b.overall_score + '/100</small></th>';
        }).join('');

        function row(label, getter) {
            return '<tr><th>' + label + '</th>' + brokers.map(function (b) {
                return '<td>' + getter(b) + '</td>';
            }).join('') + '</tr>';
        }

        wrap.innerHTML = '<div class="bsc-table-wrap"><table class="bsc-compare-table">' +
            '<thead><tr><th>Metric</th>' + headers + '</tr></thead><tbody>' +
            row('Risk level', function (b) { return b.risk_icon + ' ' + b.risk_label; }) +
            row('Regulation tier', function (b) { return b.regulation.tier; }) +
            row('Trust label', function (b) { return b.trust.label; }) +
            row('Founded', function (b) { return b.company.founded; }) +
            row('Country', function (b) { return b.company.country; }) +
            row('Investor protection', function (b) {
                return b.protection.items[0] && b.protection.items[0].active ? 'Yes' : 'No';
            }) +
            row('Negative balance protection', function (b) {
                return b.protection.items[2] && b.protection.items[2].active ? 'Yes' : 'No';
            }) +
            '</tbody></table></div>';
    }

    function clearReportErrors() {
        qsa('#bscReportForm .bsc-field-error').forEach(function (el) {
            hide(el);
            el.textContent = '';
        });
        qsa('#bscReportForm .is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        var alertEl = qs('#bscReportAlert');
        if (alertEl) {
            hide(alertEl);
            alertEl.classList.remove('bsc-form-alert--success', 'bsc-form-alert--error');
            alertEl.textContent = '';
        }
    }

    function showReportFieldError(field, message) {
        var fieldEl = qs('#bscReportForm [name="' + field + '"]');
        if (fieldEl) {
            fieldEl.classList.add('is-invalid');
        }
        var errorEl = qs('#bscReportForm [data-error-for="' + field + '"]');
        if (errorEl) {
            show(errorEl);
            errorEl.textContent = message;
        }
    }

    function showReportAlert(type, message) {
        var alertEl = qs('#bscReportAlert');
        if (!alertEl) {
            return;
        }
        alertEl.classList.remove('bsc-hidden', 'bsc-form-alert--success', 'bsc-form-alert--error');
        alertEl.classList.add(type === 'success' ? 'bsc-form-alert--success' : 'bsc-form-alert--error');
        alertEl.textContent = message;
    }

    function validateReportForm() {
        clearReportErrors();
        var valid = true;
        var issueType = (qs('#bscIssueType') || {}).value || '';
        var message = ((qs('#bscReportMessage') || {}).value || '').trim();

        if (!issueType) {
            showReportFieldError('issue_type', 'Please select an issue type.');
            valid = false;
        }

        if (!message) {
            showReportFieldError('message', 'Please describe the issue.');
            valid = false;
        } else if (message.length < 20) {
            showReportFieldError('message', 'Your message must be at least 20 characters.');
            valid = false;
        } else if (message.length > 5000) {
            showReportFieldError('message', 'Your message may not exceed 5000 characters.');
            valid = false;
        }

        return valid;
    }

    function setReportSubmitting(isSubmitting) {
        var btn = qs('#bscReportSubmit');
        if (!btn) {
            return;
        }
        btn.disabled = isSubmitting;
        var label = qs('.bsc-submit-label', btn);
        var spinner = qs('.bsc-submit-spinner', btn);
        if (isSubmitting) {
            hide(label);
            show(spinner);
        } else {
            show(label);
            hide(spinner);
        }
    }

    var reportMessage = qs('#bscReportMessage');
    var messageCount = qs('#bscMessageCount');
    if (reportMessage && messageCount) {
        var updateCount = function () {
            messageCount.textContent = String(reportMessage.value.length);
        };
        reportMessage.addEventListener('input', updateCount);
        updateCount();
    }

    var reportForm = qs('#bscReportForm');
    if (reportForm) {
        reportForm.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!reportUrl || !validateReportForm()) {
                return;
            }

            setReportSubmitting(true);
            clearReportErrors();

            fetch(reportUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(reportForm)
            })
                .then(function (res) {
                    return res.json().then(function (data) {
                        return { status: res.status, data: data };
                    });
                })
                .then(function (result) {
                    if (result.status === 401) {
                        showReportAlert('error', 'Your session has expired. Please log in again.');
                        return;
                    }
                    if (result.status === 422) {
                        var payload = result.data || {};
                        if (payload.errors) {
                            Object.keys(payload.errors).forEach(function (field) {
                                var messages = payload.errors[field];
                                if (messages && messages.length) {
                                    showReportFieldError(field, messages[0]);
                                }
                            });
                        }
                        showReportAlert('error', payload.message || 'Please fix the errors below and try again.');
                        return;
                    }
                    if (result.status >= 400) {
                        showReportAlert('error', 'Something went wrong. Please try again in a moment.');
                        return;
                    }

                    showReportAlert('success', result.data.message || 'Report submitted successfully.');
                    reportForm.reset();
                    if (messageCount) {
                        messageCount.textContent = '0';
                    }
                    var issueType = qs('#bscIssueType');
                    if (issueType) {
                        issueType.selectedIndex = 0;
                    }

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Report submitted',
                            text: result.data.message,
                            confirmButtonColor: '#007AAD'
                        });
                    }

                    window.setTimeout(function () {
                        closeModal(qs('#bscReportModal'));
                    }, 1200);
                })
                .catch(function () {
                    showReportAlert('error', 'Something went wrong. Please try again in a moment.');
                })
                .finally(function () {
                    setReportSubmitting(false);
                });
        });
    }

    if (openReport) {
        openModal(qs('#bscReportModal'));
    }

    animateScoreRing();
    animateMeters();
})();
