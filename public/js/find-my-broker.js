(function () {
    'use strict';

    var app = document.getElementById('fmb-app');
    if (!app) return;

    var endpoint = app.getAttribute('data-endpoint') || '/find-my-broker';
    var resultsEl = document.getElementById('fmb-results');
    var heroMatchEl = document.getElementById('fmb-hero-match');
    var drawer = document.getElementById('fmb-drawer');
    var debounceTimer = null;
    var abortController = null;
    var MULTI_KEYS = ['account_type', 'regulation', 'platform', 'markets', 'payment', 'features', 'country'];
    var SAVED_KEY = 'savedBrokers';
    var compareBase = app.getAttribute('data-compare-base') || '/brokers/compare';

    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function qsa(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    function primaryForm() {
        return qs('.fmb-filters--desktop .fmb-filter-form[data-fmb-form]') ||
            qs('.fmb-filter-form[data-fmb-form]');
    }

    function savedBrokers() {
        try {
            return JSON.parse(localStorage.getItem(SAVED_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function setSavedBrokers(list) {
        localStorage.setItem(SAVED_KEY, JSON.stringify(list));
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

    function formatCount(n) {
        return Number(n || 0).toLocaleString();
    }

    function updateCountsFromHtml() {
        var countEl = qs('#fmb-count', resultsEl);
        if (countEl && heroMatchEl) {
            heroMatchEl.textContent = countEl.textContent.trim();
        }
    }

    function bindSaveButtons() {
        var saved = savedBrokers().map(String);

        qsa('[data-fmb-save]', resultsEl).forEach(function (btn) {
            var card = btn.closest('[data-fmb-card]');
            if (!card) return;

            var id = String(card.getAttribute('data-broker-id'));
            updateSaveButton(btn, saved.indexOf(id) !== -1);

            btn.onclick = function () {
                var list = savedBrokers().map(String);
                var isSaved = list.indexOf(id) !== -1;
                list = isSaved ? list.filter(function (i) { return i !== id; }) : list.concat(id);
                setSavedBrokers(list);
                updateSaveButton(btn, !isSaved);

                if (window.bcSyncSavedBroker) {
                    window.bcSyncSavedBroker(id, !isSaved);
                }
            };
        });
    }

    function updateSaveButton(btn, isSaved) {
        btn.classList.toggle('is-saved', isSaved);
        btn.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
        btn.innerHTML = isSaved
            ? '<svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>Saved'
            : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>Save';
    }

    function selectedCompareSlugs() {
        if (!resultsEl) {
            return [];
        }
        return qsa('[data-fmb-compare]:checked', resultsEl).map(function (el) {
            return el.value;
        }).filter(Boolean);
    }

    function updateCompareBar() {
        var bar = document.getElementById('fmb-compare-bar');
        var goBtn = document.getElementById('fmb-compare-go');
        var countEl = document.getElementById('fmb-compare-count');
        if (!bar || !goBtn) {
            return;
        }

        var slugs = selectedCompareSlugs();
        bar.classList.toggle('is-hidden', slugs.length === 0);

        if (countEl) {
            countEl.textContent = String(slugs.length);
        }

        if (slugs.length === 2) {
            slugs = slugs.slice().sort();
            goBtn.href = compareBase.replace(/\/$/, '') + '/' + slugs[0] + '-vs-' + slugs[1];
            goBtn.classList.remove('is-disabled');
            goBtn.setAttribute('aria-disabled', 'false');
        } else {
            goBtn.href = '#';
            goBtn.classList.add('is-disabled');
            goBtn.setAttribute('aria-disabled', 'true');
        }

        qsa('[data-fmb-compare]:not(:checked)', resultsEl).forEach(function (el) {
            el.disabled = slugs.length >= 2;
        });
    }

    function bindCompareInputs() {
        qsa('[data-fmb-compare]', resultsEl).forEach(function (input) {
            input.onchange = function () {
                var slugs = selectedCompareSlugs();
                if (slugs.length > 2) {
                    input.checked = false;
                    return;
                }
                updateCompareBar();
            };
        });
        updateCompareBar();
    }

    function autoSelectQuizMatches() {
        var params = new URLSearchParams(window.location.search);
        if (params.get('from') !== 'quiz') {
            return;
        }

        var match = (params.get('match') || '').split(',').map(function (slug) {
            return slug.trim();
        }).filter(Boolean).slice(0, 2);

        if (!match.length) {
            match = qsa('[data-fmb-card]', resultsEl).slice(0, 2).map(function (card) {
                return card.getAttribute('data-broker-slug');
            }).filter(Boolean);
        }

        match.forEach(function (slug) {
            var input = qs('[data-fmb-compare][value="' + slug + '"]', resultsEl);
            if (input) {
                input.checked = true;
            }
        });
        updateCompareBar();
    }

    function copyShareLink(button) {
        var url = window.location.href.split('#')[0];

        if (button && !button.dataset.fmbCopyHtml) {
            button.dataset.fmbCopyHtml = button.innerHTML;
        }

        function done(ok) {
            if (!button) {
                return;
            }
            if (ok) {
                button.classList.add('is-copied');
                button.textContent = 'Link copied!';
                window.setTimeout(function () {
                    button.classList.remove('is-copied');
                    button.innerHTML = button.dataset.fmbCopyHtml || 'Copy link';
                }, 2000);
            }
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function () { done(true); }).catch(function () { done(false); });
            return;
        }

        var tmp = document.createElement('textarea');
        tmp.value = url;
        document.body.appendChild(tmp);
        tmp.select();
        try {
            done(document.execCommand('copy'));
        } catch (e) {
            done(false);
        }
        document.body.removeChild(tmp);
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
                bindSaveButtons();
                bindCompareInputs();
                updateCountsFromHtml();
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

        var existing = btn.querySelector('.fmb-mobile-badge');
        if (existing) existing.remove();

        if (count > 0) {
            var badge = document.createElement('span');
            badge.className = 'fmb-mobile-badge';
            badge.textContent = String(count);
            btn.appendChild(badge);
        }
    }

    function applyFromForm(debounceMs, sourceForm) {
        var form = sourceForm || primaryForm();
        if (!form) return;
        var params = collectParams(form);

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

        var copyBtn = qs('[data-fmb-copy-link]', resultsEl);
        if (copyBtn) {
            copyBtn.onclick = function () { copyShareLink(copyBtn); };
        }
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
            if (e.target.closest('.fmb-reset')) {
                e.preventDefault();
                resetAll();
                closeDrawer();
            }
        });

        var openBtn = document.getElementById('fmb-open-filters');
        if (openBtn) openBtn.addEventListener('click', openDrawer);
        var closeBtn = document.getElementById('fmb-close-filters');
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        var closeBtn2 = document.getElementById('fmb-close-filters-btn');
        if (closeBtn2) closeBtn2.addEventListener('click', closeDrawer);

        bindResultsEvents();
        bindSaveButtons();
        bindCompareInputs();
        autoSelectQuizMatches();

        var clearCompare = document.getElementById('fmb-compare-clear');
        if (clearCompare) {
            clearCompare.addEventListener('click', function () {
                qsa('[data-fmb-compare]', resultsEl).forEach(function (el) {
                    el.checked = false;
                    el.disabled = false;
                });
                updateCompareBar();
            });
        }

        var copyBtn = qs('[data-fmb-copy-link]', resultsEl);
        if (copyBtn) {
            copyBtn.addEventListener('click', function () { copyShareLink(copyBtn); });
        }

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
