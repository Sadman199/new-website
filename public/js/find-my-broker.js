(function () {
    'use strict';

    var app = document.getElementById('fmb-app');
    if (!app) return;

    var endpoint = app.getAttribute('data-endpoint') || '/find-my-broker';
    var resultsEl = document.getElementById('fmb-results');
    var drawer = document.getElementById('fmb-drawer');
    var debounceTimer = null;
    var abortController = null;
    var MULTI_KEYS = ['account_type', 'regulation', 'platform', 'markets', 'payment', 'features', 'country'];

    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function qsa(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    function primaryForm() {
        return qs('.fmb-filter-form[data-fmb-form]', document.querySelector('aside')) ||
            qs('.fmb-filter-form[data-fmb-form]');
    }

    function collectParams(form) {
        var params = new URLSearchParams();
        var multi = {};

        MULTI_KEYS.forEach(function (k) { multi[k] = []; });

        qsa('[data-fmb-input], [data-fmb-sort]', form).forEach(function (el) {
            var name = el.name;
            if (!name) return;

            if (el.type === 'checkbox') {
                if (el.checked && MULTI_KEYS.indexOf(name) !== -1) {
                    multi[name].push(el.value);
                }
                return;
            }

            if (el.value !== '' && el.value != null) {
                params.set(name, el.value);
            }
        });

        MULTI_KEYS.forEach(function (k) {
            if (multi[k].length) {
                params.set(k, multi[k].join(','));
            }
        });

        // Prefer toolbar sort if present
        var sortSelect = qs('[data-fmb-sort-select]');
        if (sortSelect && sortSelect.value) {
            params.set('sort', sortSelect.value);
        }

        return params;
    }

    function syncFormsFromParams(params) {
        qsa('.fmb-filter-form[data-fmb-form]').forEach(function (form) {
            qsa('[data-fmb-input], [data-fmb-sort]', form).forEach(function (el) {
                var name = el.name;
                if (!name) return;

                if (el.type === 'checkbox') {
                    var list = (params.get(name) || '').split(',').filter(Boolean);
                    el.checked = list.indexOf(el.value) !== -1;
                    return;
                }

                if (MULTI_KEYS.indexOf(name) !== -1) return;
                el.value = params.get(name) || '';
            });
        });

        var sortSelect = qs('[data-fmb-sort-select]');
        if (sortSelect) {
            sortSelect.value = params.get('sort') || 'highest_rated';
        }
    }

    function syncHiddenSort(value) {
        qsa('[data-fmb-sort]').forEach(function (el) {
            el.value = value || 'highest_rated';
        });
    }

    function buildUrl(params) {
        var q = params.toString();
        return endpoint + (q ? '?' + q : '');
    }

    function setLoading(on) {
        if (!resultsEl) return;
        resultsEl.classList.toggle('is-loading', !!on);
    }

    function fetchResults(params, pushState) {
        if (abortController) {
            try { abortController.abort(); } catch (e) {}
        }
        abortController = typeof AbortController !== 'undefined' ? new AbortController() : null;

        var url = buildUrl(params);
        var fetchUrl = url + (url.indexOf('?') === -1 ? '?' : '&') + 'partial=1';

        setLoading(true);

        var opts = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            credentials: 'same-origin'
        };
        if (abortController) opts.signal = abortController.signal;

        fetch(fetchUrl, opts)
            .then(function (res) {
                if (!res.ok) throw new Error('Request failed');
                return res.text();
            })
            .then(function (html) {
                resultsEl.innerHTML = html;
                setLoading(false);
                if (pushState !== false) {
                    history.pushState({ fmb: true }, '', url);
                }
                bindResultsEvents();
                updateMobileBadge(params);
            })
            .catch(function (err) {
                if (err && err.name === 'AbortError') return;
                setLoading(false);
            });
    }

    function updateMobileBadge(params) {
        var btn = document.getElementById('fmb-open-filters');
        if (!btn) return;
        var count = 0;
        params.forEach(function (v, k) {
            if (k === 'sort' || k === 'page' || k === 'partial') return;
            if (!v) return;
            if (MULTI_KEYS.indexOf(k) !== -1) {
                count += v.split(',').filter(Boolean).length;
            } else {
                count += 1;
            }
        });
        var existing = btn.querySelector('span');
        if (existing) existing.remove();
        if (count > 0) {
            var badge = document.createElement('span');
            badge.className = 'bg-blue-600 text-white text-xs rounded-full px-2 py-0.5';
            badge.textContent = String(count);
            btn.appendChild(badge);
        }
    }

    function applyFromForm(debounceMs, sourceForm) {
        var form = sourceForm || primaryForm();
        if (!form) return;
        var params = collectParams(form);

        // Keep both forms in sync
        syncFormsFromParams(params);

        clearTimeout(debounceTimer);
        if (debounceMs) {
            debounceTimer = setTimeout(function () {
                fetchResults(params, true);
            }, debounceMs);
        } else {
            fetchResults(params, true);
        }
    }

    function resetAll() {
        qsa('.fmb-filter-form[data-fmb-form]').forEach(function (form) {
            qsa('[data-fmb-input]', form).forEach(function (el) {
                if (el.type === 'checkbox') {
                    el.checked = false;
                } else if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                    el.value = '';
                } else {
                    el.value = '';
                }
            });
            var sortHidden = qs('[data-fmb-sort]', form);
            if (sortHidden) sortHidden.value = 'highest_rated';
        });
        syncHiddenSort('highest_rated');
        var sortSelect = qs('[data-fmb-sort-select]');
        if (sortSelect) sortSelect.value = 'highest_rated';
        fetchResults(new URLSearchParams(), true);
    }

    function removeChip(key, value) {
        var form = primaryForm();
        if (!form) return;

        if (MULTI_KEYS.indexOf(key) !== -1) {
            qsa('input[type="checkbox"][name="' + key + '"]', form).forEach(function (el) {
                if (el.value === value) el.checked = false;
            });
        } else if (key === 'q') {
            qsa('input[name="q"]').forEach(function (el) { el.value = ''; });
        } else {
            qsa('[name="' + key + '"]').forEach(function (el) {
                if (el.type === 'checkbox') return;
                el.value = '';
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
            });
        }

        applyFromForm(0);
    }

    function bindFormEvents(form) {
        if (!form || form._fmbBound) return;
        form._fmbBound = true;

        form.addEventListener('change', function (e) {
            if (!e.target.matches('[data-fmb-input]')) return;
            applyFromForm(0, form);
        });

        form.addEventListener('input', function (e) {
            if (!e.target.matches('input[type="search"][data-fmb-input], input[type="text"][data-fmb-input]')) return;
            applyFromForm(250, form);
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            applyFromForm(0, form);
        });
    }

    function bindResultsEvents() {
        var sortSelect = qs('[data-fmb-sort-select]');
        if (sortSelect && !sortSelect._fmbBound) {
            sortSelect._fmbBound = true;
            sortSelect.addEventListener('change', function () {
                syncHiddenSort(sortSelect.value);
                applyFromForm(0);
            });
        }

        // Re-bind sort after AJAX replace (clone loses _fmbBound on new node)
        if (sortSelect) {
            sortSelect.onchange = function () {
                syncHiddenSort(sortSelect.value);
                applyFromForm(0);
            };
        }

        qsa('.fmb-chip-remove', resultsEl).forEach(function (btn) {
            btn.addEventListener('click', function () {
                var chip = btn.closest('.fmb-chip');
                if (!chip) return;
                removeChip(chip.getAttribute('data-chip-key'), chip.getAttribute('data-chip-value'));
            });
        });

        qsa('.fmb-reset', resultsEl).forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                resetAll();
            });
        });

        // Intercept pagination links for AJAX
        qsa('.fmb-pagination a', resultsEl).forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                var href = a.getAttribute('href');
                if (!href) return;
                var url = new URL(href, window.location.origin);
                var params = new URLSearchParams(url.search);
                syncFormsFromParams(params);
                fetchResults(params, true);
                window.scrollTo({ top: app.offsetTop - 80, behavior: 'smooth' });
            });
        });
    }

    function openDrawer() {
        if (!drawer) return;
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    }

    function closeDrawer() {
        if (!drawer) return;
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    }

    function init() {
        qsa('.fmb-filter-form[data-fmb-form]').forEach(bindFormEvents);

        document.addEventListener('click', function (e) {
            var resetBtn = e.target.closest('.fmb-reset');
            if (resetBtn) {
                e.preventDefault();
                resetAll();
                closeDrawer();
                return;
            }
        });

        var openBtn = document.getElementById('fmb-open-filters');
        if (openBtn) openBtn.addEventListener('click', openDrawer);
        var closeBtn = document.getElementById('fmb-close-filters');
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        var closeBtn2 = document.getElementById('fmb-close-filters-btn');
        if (closeBtn2) closeBtn2.addEventListener('click', closeDrawer);

        bindResultsEvents();

        // Sync filter forms from URL on first load (e.g. homepage search redirect)
        var initialParams = new URLSearchParams(window.location.search);
        if ([...initialParams.keys()].length) {
            syncFormsFromParams(initialParams);
            updateMobileBadge(initialParams);
        }

        window.addEventListener('popstate', function () {
            var params = new URLSearchParams(window.location.search);
            syncFormsFromParams(params);
            fetchResults(params, false);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
