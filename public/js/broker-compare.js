(function () {
    'use strict';

    var config = window.BROKER_COMPARE || {};
    var brokers = config.brokers || [];
    var tabGroups = config.tabGroups || {};
    var slots = [null, null, null];
    var activeTab = 'overall';
    var openSlotIndex = null;
    var skipNextPrompt = false;

    var HIGHER_KEYS = ['rating', 'trust_score', 'review_count', 'instrument_count'];
    var LOWER_KEYS = ['minimum_deposit', 'year_founded'];
    var TIER_KEYS = ['regulatory_tier'];
    var LEVERAGE_KEYS = ['leverage'];
    var TYPE_KEYS = ['broker_type'];

    var els = {};

    function $(id) {
        return document.getElementById(id);
    }

    function brokerBySlug(slug) {
        return brokers.find(function (b) {
            return b.slug === slug;
        }) || null;
    }

    function usedSlugs(excludeIndex) {
        return slots
            .map(function (s, i) {
                return i === excludeIndex ? null : (s ? s.slug : null);
            })
            .filter(Boolean);
    }

    function init() {
        if (!$('bcCompareMatrixWrap')) {
            return;
        }

        els.pickers = document.querySelectorAll('[data-compare-slot]');
        els.matrixWrap = $('bcCompareMatrixWrap');
        els.suggestions = $('bcCompareSuggestions');
        els.suggestionsTitle = $('bcCompareSuggestionsTitle');
        els.sidebarHead = $('bcCompareSidebarHead');
        els.sidebarRows = $('bcCompareSidebarRows');
        els.tabButtons = document.querySelectorAll('[data-compare-tab]');
        els.clearBtn = $('bcCompareClearBtn');
        els.hint = $('bcCompareHint');
        els.pairLink = $('bcComparePairLink');
        els.winners = $('bcCompareWinners');
        els.main = $('bcCompareMain');

        bindTabs();
        bindPickers();
        bindSuggestions();
        bindActions();
        skipNextPrompt = true;
        prefillFromQuery();
        renderAll();
        skipNextPrompt = false;

        document.addEventListener('click', function (e) {
            if (!e.target.closest('[data-compare-slot]')) {
                closeAllSlots();
            }
        });
    }

    function prefillFromQuery() {
        try {
            var params = new URLSearchParams(window.location.search);
            var raw = params.get('brokers') || '';
            if (!raw) {
                return;
            }

            raw.split(',').map(function (s) {
                return s.trim();
            }).filter(Boolean).slice(0, 3).forEach(function (slug, i) {
                var broker = brokerBySlug(slug);
                if (broker) {
                    slots[i] = broker;
                }
            });
        } catch (e) {}
    }

    function bindTabs() {
        els.tabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeTab = btn.getAttribute('data-compare-tab');
                els.tabButtons.forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                    b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
                });
                renderAll();
            });
        });
    }

    function bindPickers() {
        els.pickers.forEach(function (slotEl) {
            var index = parseInt(slotEl.getAttribute('data-compare-slot'), 10);
            var inner = slotEl.querySelector('.bc-compare-slot__inner');
            var clearBtn = slotEl.querySelector('.bc-compare-slot__clear');
            var searchInput = slotEl.querySelector('.bc-compare-slot__search-input');

            inner.addEventListener('click', function (e) {
                if (e.target.closest('.bc-compare-slot__clear')) {
                    return;
                }
                toggleSlot(index);
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    slots[index] = null;
                    closeAllSlots();
                    renderAll();
                    maybePromptNextSlot();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    renderSearchResults(index, searchInput.value.trim());
                });

                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeAllSlots();
                    }
                });
            }
        });
    }

    function bindSuggestions() {
        document.querySelectorAll('[data-suggest-slug]').forEach(function (card) {
            card.addEventListener('click', function () {
                var slug = card.getAttribute('data-suggest-slug');
                var broker = brokerBySlug(slug);
                if (!broker) {
                    return;
                }
                var emptyIndex = slots.findIndex(function (s) {
                    return s === null;
                });
                if (emptyIndex === -1) {
                    emptyIndex = 0;
                }
                slots[emptyIndex] = broker;
                closeAllSlots();
                renderAll();
                maybePromptNextSlot();
            });
        });
    }

    function bindActions() {
        if (els.clearBtn) {
            els.clearBtn.addEventListener('click', function () {
                slots = [null, null, null];
                closeAllSlots();
                renderAll();
            });
        }
    }

    function toggleSlot(index) {
        if (openSlotIndex === index) {
            closeAllSlots();
            return;
        }
        closeAllSlots();
        openSlotIndex = index;
        var slotEl = els.pickers[index];
        slotEl.classList.add('is-open');
        var input = slotEl.querySelector('.bc-compare-slot__search-input');
        if (input) {
            input.value = '';
            renderSearchResults(index, '');
            setTimeout(function () {
                input.focus();
            }, 50);
        }
    }

    function closeAllSlots() {
        openSlotIndex = null;
        if (!els.pickers) {
            return;
        }
        els.pickers.forEach(function (slotEl) {
            slotEl.classList.remove('is-open');
        });
    }

    function maybePromptNextSlot() {
        if (skipNextPrompt) {
            return;
        }
        if (selectedBrokers().length !== 1) {
            return;
        }
        var emptyIndex = slots.findIndex(function (s) {
            return s === null;
        });
        if (emptyIndex !== -1 && emptyIndex < 2) {
            toggleSlot(emptyIndex);
        }
    }

    function renderSearchResults(index, query) {
        var slotEl = els.pickers[index];
        var resultsEl = slotEl.querySelector('.bc-compare-slot__results');
        var taken = usedSlugs(index);
        var q = query.toLowerCase();
        var filtered = brokers.filter(function (b) {
            if (q && b.name.toLowerCase().indexOf(q) === -1) {
                return false;
            }
            return true;
        }).slice(0, 20);

        if (!filtered.length) {
            resultsEl.innerHTML = '<div class="bc-compare-slot__empty">No brokers found</div>';
            return;
        }

        resultsEl.innerHTML = filtered.map(function (b) {
            var disabled = taken.indexOf(b.slug) !== -1;
            var logo = b.logo
                ? '<img src="' + escapeAttr(b.logo) + '" alt="" loading="lazy" decoding="async">'
                : '<span>' + escapeHtml(b.name.charAt(0)) + '</span>';
            return (
                '<button type="button" class="bc-compare-slot__result' + (disabled ? ' is-disabled' : '') + '" ' +
                'data-pick-slug="' + escapeAttr(b.slug) + '" data-slot-index="' + index + '"' +
                (disabled ? ' disabled' : '') + '>' +
                '<span class="bc-compare-slot__logo">' + logo + '</span>' +
                '<span>' + escapeHtml(b.name) + '</span>' +
                '</button>'
            );
        }).join('');

        resultsEl.querySelectorAll('[data-pick-slug]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (btn.disabled) {
                    return;
                }
                var slug = btn.getAttribute('data-pick-slug');
                var broker = brokerBySlug(slug);
                if (broker) {
                    slots[index] = broker;
                    closeAllSlots();
                    renderAll();
                    maybePromptNextSlot();
                }
            });
        });
    }

    function selectedBrokers() {
        return slots.filter(Boolean);
    }

    function currentRows() {
        var group = tabGroups[activeTab];
        return group ? group.rows : [];
    }

    function renderAll() {
        var selected = selectedBrokers();
        renderPickers(selected);
        renderSidebar();
        renderHint(selected);
        renderPairLink(selected);
        renderWinners(selected);
        renderSuggestions(selected);
        syncUrl(selected);

        if (els.main) {
            els.main.classList.toggle('is-comparing', selected.length >= 2);
        }

        if (selected.length >= 2) {
            els.matrixWrap.classList.remove('bc-compare-hidden');
            renderMatrix(selected);
        } else {
            els.matrixWrap.classList.add('bc-compare-hidden');
        }
    }

    function renderPickers(selected) {
        var neededIndex = -1;
        if (selected.length < 2) {
            neededIndex = slots.findIndex(function (s) {
                return s === null;
            });
        }

        els.pickers.forEach(function (slotEl, index) {
            var broker = slots[index];
            var inner = slotEl.querySelector('.bc-compare-slot__inner');
            var placeholder = slotEl.querySelector('.bc-compare-slot__placeholder');
            var selectedWrap = slotEl.querySelector('.bc-compare-slot__selected');

            slotEl.classList.toggle('is-needed', index === neededIndex);

            if (broker) {
                inner.classList.add('has-broker');
                placeholder.classList.add('bc-compare-hidden');
                selectedWrap.classList.remove('bc-compare-hidden');
                selectedWrap.querySelector('.bc-compare-slot__name').textContent = broker.name;
                var logoEl = selectedWrap.querySelector('.bc-compare-slot__logo');
                logoEl.innerHTML = broker.logo
                    ? '<img src="' + escapeAttr(broker.logo) + '" alt="" loading="lazy" decoding="async">'
                    : '<span>' + escapeHtml(broker.name.charAt(0)) + '</span>';
            } else {
                inner.classList.remove('has-broker');
                placeholder.classList.remove('bc-compare-hidden');
                selectedWrap.classList.add('bc-compare-hidden');
            }
        });
    }

    function renderHint(selected) {
        if (!els.hint) {
            return;
        }
        if (selected.length === 0) {
            els.hint.textContent = 'Pick at least 2 brokers to see the comparison table below.';
        } else if (selected.length === 1) {
            els.hint.textContent = 'Add a second broker to compare side by side.';
        } else if (selected.length === 2) {
            els.hint.textContent = 'Open the full comparison page, or add a third broker.';
        } else {
            els.hint.textContent = 'Comparing 3 brokers. Clear a slot to swap one out.';
        }
    }

    function renderPairLink(selected) {
        if (!els.pairLink) {
            return;
        }
        if (selected.length === 2) {
            els.pairLink.href = pairUrl(selected[0].slug, selected[1].slug);
            els.pairLink.classList.remove('bc-compare-hidden');
        } else {
            els.pairLink.classList.add('bc-compare-hidden');
            els.pairLink.href = '#';
        }
    }

    function pairUrl(slug1, slug2) {
        var slugs = [slug1, slug2].sort();
        var base = String(config.pairBase || '/brokers/compare').replace(/\/$/, '');
        return base + '/' + slugs[0] + '-vs-' + slugs[1];
    }

    function syncUrl(selected) {
        if (!window.history || !window.history.replaceState) {
            return;
        }
        if (window.location.pathname.indexOf('/brokers/compare/') !== -1) {
            return;
        }
        var slugs = selected.map(function (b) {
            return b.slug;
        });
        var url = window.location.pathname;
        if (slugs.length) {
            url += '?brokers=' + encodeURIComponent(slugs.join(','));
        }
        if (url !== window.location.pathname + window.location.search) {
            window.history.replaceState({}, '', url);
        }
    }

    function renderWinners(selected) {
        if (!els.winners) {
            return;
        }
        if (selected.length < 2) {
            els.winners.classList.add('bc-compare-hidden');
            els.winners.innerHTML = '';
            return;
        }

        var chips = [];
        var rating = winnerBroker(selected, 'rating', 'higher');
        if (rating) {
            chips.push(winnerChip('Highest rating', rating));
        }
        var deposit = winnerBroker(selected, 'minimum_deposit_raw', 'lower');
        if (deposit) {
            chips.push(winnerChip('Lowest min deposit', deposit));
        }
        var regulation = winnerBroker(selected, 'regulatory_tier', 'tier');
        if (!regulation) {
            regulation = winnerBroker(selected, 'broker_type', 'type');
        }
        if (regulation) {
            chips.push(winnerChip('Strongest regulation', regulation));
        }

        if (!chips.length) {
            els.winners.classList.add('bc-compare-hidden');
            els.winners.innerHTML = '';
            return;
        }

        els.winners.classList.remove('bc-compare-hidden');
        els.winners.innerHTML = chips.join('');
    }

    function winnerChip(label, broker) {
        return (
            '<span class="bc-compare-winner">' +
            '<span class="bc-compare-winner__label">' + escapeHtml(label) + '</span>' +
            '<span class="bc-compare-winner__name">' + escapeHtml(broker.name) + '</span>' +
            '</span>'
        );
    }

    function winnerBroker(selected, key, mode) {
        var scored = selected.map(function (b, i) {
            return { broker: b, index: i, num: comparableNumber(key, b[key], mode) };
        }).filter(function (x) {
            return x.num !== null;
        });
        if (scored.length < 2) {
            return null;
        }
        scored.sort(function (a, b) {
            return mode === 'lower' || mode === 'tier' ? a.num - b.num : b.num - a.num;
        });
        if (scored[0].num === scored[1].num) {
            return null;
        }
        return scored[0].broker;
    }

    function renderSuggestions(selected) {
        if (!els.suggestions) {
            return;
        }
        var taken = selected.map(function (b) {
            return b.slug;
        });
        document.querySelectorAll('[data-suggest-slug]').forEach(function (card) {
            var used = taken.indexOf(card.getAttribute('data-suggest-slug')) !== -1;
            card.classList.toggle('is-used', used);
            card.disabled = used;
        });

        if (selected.length >= 2) {
            els.suggestions.classList.add('bc-compare-hidden');
            return;
        }

        els.suggestions.classList.remove('bc-compare-hidden');
        if (els.suggestionsTitle) {
            els.suggestionsTitle.textContent = selected.length === 1
                ? 'Add a second broker'
                : 'Suggested brokers';
        }
    }

    function renderSidebar() {
        var group = tabGroups[activeTab];
        if (!group || !els.sidebarHead || !els.sidebarRows) {
            return;
        }
        els.sidebarHead.textContent = group.label;
        els.sidebarRows.innerHTML = group.rows.map(function (row) {
            return '<li class="bc-compare-sidebar__row">' + escapeHtml(row.label) + '</li>';
        }).join('');
    }

    function renderMatrix(selected) {
        var rows = currentRows();
        var uniqueRows = dedupeRows(rows);

        var headHtml = '<th class="bc-compare-matrix__metric" scope="col">Metric</th>' + selected.map(function (b) {
            var logo = b.logo
                ? '<img src="' + escapeAttr(b.logo) + '" alt="" loading="lazy" decoding="async">'
                : '<span>' + escapeHtml(b.name.charAt(0)) + '</span>';
            var score = b.rating !== null ? numberFormat(b.rating, 1) : '—';
            return (
                '<th><div class="bc-compare-matrix__broker-head">' +
                '<div class="bc-compare-matrix__broker-logo">' + logo + '</div>' +
                '<div class="bc-compare-matrix__broker-name">' + escapeHtml(b.name) + '</div>' +
                '<div class="bc-compare-matrix__broker-score">' + score + '</div>' +
                '</div></th>'
            );
        }).join('');

        var bodyHtml = uniqueRows.map(function (row) {
            var values = selected.map(function (b) {
                return formatValue(row.key, b[row.key]);
            });
            var bestIndex = findBestIndex(row.key, selected, values);
            var allSame = values.length > 1 && values.every(function (val) {
                return val === values[0];
            });
            var cells = values.map(function (val, i) {
                var cls = cellClass(row.key, val, i === bestIndex, allSame);
                return '<td class="' + cls + '" data-broker="' + escapeAttr(selected[i].name) + '">' + escapeHtml(String(val)) + '</td>';
            }).join('');
            return '<tr class="' + (allSame ? 'is-same' : 'is-diff') + '">' +
                '<th class="bc-compare-matrix__metric" scope="row">' + escapeHtml(row.label) + '</th>' +
                cells + '</tr>';
        }).join('');

        els.matrixWrap.innerHTML =
            '<table class="bc-compare-matrix">' +
            '<thead><tr>' + headHtml + '</tr></thead>' +
            '<tbody>' + bodyHtml + '</tbody>' +
            '</table>';
    }

    function dedupeRows(rows) {
        var seen = {};
        return rows.filter(function (row) {
            var id = row.key + '|' + row.label;
            if (seen[id]) {
                return false;
            }
            seen[id] = true;
            return true;
        });
    }

    function formatValue(key, val) {
        if (val === null || val === undefined || val === '') {
            return '—';
        }
        return val;
    }

    function cellClass(key, val, isBest, allSame) {
        if (val === 'Yes') {
            return 'bc-val--bool-yes';
        }
        if (val === 'No') {
            return 'bc-val--bool-no';
        }
        if (allSame) {
            return 'bc-val--same';
        }
        if (isBest && isComparable(key)) {
            return 'bc-val--best';
        }
        if (isComparable(key)) {
            return 'bc-val--diff';
        }
        return '';
    }

    function isComparable(key) {
        return HIGHER_KEYS.indexOf(key) !== -1
            || LOWER_KEYS.indexOf(key) !== -1
            || TIER_KEYS.indexOf(key) !== -1
            || LEVERAGE_KEYS.indexOf(key) !== -1
            || TYPE_KEYS.indexOf(key) !== -1;
    }

    function compareMode(key) {
        if (LOWER_KEYS.indexOf(key) !== -1) {
            return 'lower';
        }
        if (TIER_KEYS.indexOf(key) !== -1) {
            return 'tier';
        }
        if (LEVERAGE_KEYS.indexOf(key) !== -1) {
            return 'leverage';
        }
        if (TYPE_KEYS.indexOf(key) !== -1) {
            return 'type';
        }
        return 'higher';
    }

    function findBestIndex(key, selected, values) {
        if (!isComparable(key)) {
            return -1;
        }
        var mode = compareMode(key);
        var numeric = selected.map(function (b, i) {
            var raw = key === 'minimum_deposit' ? b.minimum_deposit_raw : b[key];
            return { index: i, num: comparableNumber(key, raw !== undefined && raw !== null ? raw : values[i], mode) };
        }).filter(function (x) {
            return x.num !== null;
        });
        if (numeric.length < 2) {
            return -1;
        }
        numeric.sort(function (a, b) {
            return mode === 'lower' || mode === 'tier' ? a.num - b.num : b.num - a.num;
        });
        if (numeric[0].num === numeric[1].num) {
            return -1;
        }
        return numeric[0].index;
    }

    function comparableNumber(key, val, mode) {
        if (val === '—' || val === null || val === undefined || val === '') {
            return null;
        }
        mode = mode || compareMode(key);
        if (mode === 'type') {
            var type = String(val).toLowerCase();
            if (type === 'regulated') {
                return 2;
            }
            if (type === 'unregulated') {
                return 1;
            }
            return null;
        }
        if (mode === 'tier') {
            var tier = String(val).replace(/[^0-9]/g, '');
            return tier ? parseInt(tier, 10) : null;
        }
        if (mode === 'leverage') {
            var lev = String(val).match(/1\s*:\s*([0-9,.]+)/i);
            if (lev) {
                return parseFloat(lev[1].replace(/,/g, ''));
            }
            var n = parseFloat(String(val).replace(/[^0-9.]/g, ''));
            return isNaN(n) ? null : n;
        }
        if (key === 'instrument_count' || key === 'review_count' || key === 'year_founded') {
            var whole = String(val).replace(/[^0-9]/g, '');
            return whole ? parseInt(whole, 10) : null;
        }
        var num = parseFloat(String(val).replace(/[^0-9.]/g, ''));
        return isNaN(num) ? null : num;
    }

    function numberFormat(n, d) {
        return parseFloat(n).toFixed(d);
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function escapeAttr(str) {
        return escapeHtml(str).replace(/'/g, '&#39;');
    }

    function initResultPage() {
        var shareBtn = document.getElementById('bcCompareShare');
        if (shareBtn) {
            shareBtn.addEventListener('click', function () {
                var url = shareBtn.getAttribute('data-share-url') || window.location.href.split('#')[0];

                function restored() {
                    shareBtn.textContent = 'Link copied!';
                    window.setTimeout(function () {
                        shareBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Copy comparison link';
                    }, 2000);
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(restored).catch(function () {});
                }
            });
        }

        var tocLinks = document.querySelectorAll('[data-result-toc]');
        var sections = document.querySelectorAll('[data-result-section]');

        if (!tocLinks.length || !sections.length) {
            return;
        }

        tocLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                var target = document.getElementById('bc-result-' + link.getAttribute('data-result-toc'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        if (!('IntersectionObserver' in window)) {
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var id = entry.target.getAttribute('data-result-section');
                tocLinks.forEach(function (link) {
                    link.classList.toggle('is-active', link.getAttribute('data-result-toc') === id);
                });
            });
        }, {
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0
        });

        sections.forEach(function (section) {
            observer.observe(section);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
            if (document.querySelector('.bc-result-page')) {
                initResultPage();
            }
        });
    } else {
        init();
        if (document.querySelector('.bc-result-page')) {
            initResultPage();
        }
    }
})();
