(function () {
    'use strict';

    var config = window.BROKER_COMPARE || {};
    var brokers = config.brokers || [];
    var tabGroups = config.tabGroups || {};
    var slots = [null, null, null];
    var activeTab = Object.keys(tabGroups)[0] || 'overall';
    var openSlotIndex = null;
    var skipNextPrompt = false;
    var matrixFilter = 'all';

    var HIGHER_KEYS = ['rating', 'rating_display', 'trust_score', 'review_count', 'instrument_count'];
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
        els.battleLink = $('bcBattleModeLink');
        els.winners = $('bcCompareWinners');
        els.profiles = $('bcCompareProfiles');
        els.toolbar = $('bcCompareToolbar');
        els.diffCount = $('bcCompareDiffCount');
        els.filterButtons = document.querySelectorAll('[data-compare-filter]');
        els.main = $('bcCompareMain');
        els.shell = $('bcCompareShell');
        els.arena = $('compare-tool');

        bindTabs();
        bindPickers();
        bindSuggestions();
        bindActions();
        bindFilters();
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

    function bindFilters() {
        if (!els.filterButtons) {
            return;
        }
        els.filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                matrixFilter = btn.getAttribute('data-compare-filter') || 'all';
                els.filterButtons.forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });
                var selected = selectedBrokers();
                if (selected.length >= 2) {
                    renderMatrix(selected);
                }
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

    function brokerSubline(b) {
        var parts = [];
        if (b.rating !== null && b.rating !== undefined) {
            parts.push(numberFormat(b.rating, 1) + '/5');
        }
        if (b.regulatory_tier && b.regulatory_tier !== '—') {
            parts.push(b.regulatory_tier);
        }
        if (b.minimum_deposit && b.minimum_deposit !== '—') {
            parts.push(b.minimum_deposit + ' min');
        }
        return parts.join(' · ');
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
        }).slice(0, 24);

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
                '<span class="bc-compare-slot__result-body">' +
                '<span class="bc-compare-slot__result-name">' + escapeHtml(b.name) + '</span>' +
                '<span class="bc-compare-slot__result-meta">' + escapeHtml(brokerSubline(b) || 'Broker') + '</span>' +
                '</span>' +
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
        renderProfiles(selected);
        renderSuggestions(selected);
        syncUrl(selected);

        if (els.main) {
            els.main.classList.toggle('is-comparing', selected.length >= 2);
        }
        if (els.shell) {
            els.shell.classList.toggle('is-comparing', selected.length >= 2);
        }
        if (els.arena) {
            els.arena.classList.toggle('is-filled', selected.length > 0);
            els.arena.classList.toggle('is-ready', selected.length >= 2);
        }

        if (els.toolbar) {
            els.toolbar.classList.toggle('bc-compare-hidden', selected.length < 2);
        }

        if (selected.length >= 2) {
            els.matrixWrap.classList.remove('bc-compare-hidden');
            renderMatrix(selected);
        } else {
            els.matrixWrap.classList.add('bc-compare-hidden');
            els.matrixWrap.innerHTML = '';
            if (els.diffCount) {
                els.diffCount.textContent = '';
            }
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
            var subEl = selectedWrap ? selectedWrap.querySelector('.bc-compare-slot__sub') : null;

            slotEl.classList.toggle('is-needed', index === neededIndex);
            slotEl.classList.toggle('has-broker', !!broker);

            if (broker) {
                inner.classList.add('has-broker');
                placeholder.classList.add('bc-compare-hidden');
                selectedWrap.classList.remove('bc-compare-hidden');
                selectedWrap.querySelector('.bc-compare-slot__name').textContent = broker.name;
                if (subEl) {
                    subEl.textContent = brokerSubline(broker);
                }
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
            els.hint.textContent = 'Pick at least 2 brokers to unlock the comparison matrix.';
        } else if (selected.length === 1) {
            els.hint.textContent = 'Add a second broker to compare side by side.';
        } else if (selected.length === 2) {
            els.hint.textContent = 'Open the full comparison, enter battle mode, or add a third broker.';
        } else {
            els.hint.textContent = 'Comparing 3 brokers. Clear a slot to swap one out.';
        }
    }

    function renderPairLink(selected) {
        if (els.pairLink) {
            if (selected.length === 2) {
                els.pairLink.href = pairUrl(selected[0].slug, selected[1].slug);
                els.pairLink.classList.remove('bc-compare-hidden');
            } else {
                els.pairLink.classList.add('bc-compare-hidden');
                els.pairLink.href = '#';
            }
        }

        if (els.battleLink) {
            if (selected.length === 2) {
                els.battleLink.href = battleUrl(selected[0].slug, selected[1].slug);
                els.battleLink.classList.remove('bc-compare-hidden');
            } else {
                els.battleLink.classList.add('bc-compare-hidden');
                els.battleLink.href = '#';
            }
        }
    }

    function pairUrl(slug1, slug2) {
        var slugs = [slug1, slug2].sort();
        var base = String(config.pairBase || '/brokers/compare').replace(/\/$/, '');
        return base + '/' + slugs[0] + '-vs-' + slugs[1];
    }

    function battleUrl(slug1, slug2) {
        var slugs = [slug1, slug2].sort();
        var base = String(config.battleBase || '/broker-battle').replace(/\/$/, '');
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
            chips.push(winnerChip('Highest rating', rating, rating.rating_display || numberFormat(rating.rating, 1) + '/5'));
        }
        var deposit = winnerBroker(selected, 'minimum_deposit_raw', 'lower');
        if (deposit) {
            chips.push(winnerChip('Lowest min deposit', deposit, deposit.minimum_deposit));
        }
        var trust = winnerBroker(selected, 'trust_score', 'higher');
        if (trust) {
            chips.push(winnerChip('Best trust score', trust, String(trust.trust_score)));
        }
        var regulation = winnerBroker(selected, 'regulatory_tier', 'tier');
        if (!regulation) {
            regulation = winnerBroker(selected, 'broker_type', 'type');
        }
        if (regulation) {
            chips.push(winnerChip('Strongest regulation', regulation, regulation.regulatory_tier !== '—' ? regulation.regulatory_tier : regulation.broker_type));
        }
        var instruments = winnerBroker(selected, 'instrument_count', 'higher');
        if (instruments) {
            chips.push(winnerChip('Most instruments', instruments, instruments.instrument_count + '+'));
        }
        var leverage = winnerBroker(selected, 'leverage', 'leverage');
        if (leverage) {
            chips.push(winnerChip('Highest leverage', leverage, leverage.leverage));
        }

        if (!chips.length) {
            els.winners.classList.add('bc-compare-hidden');
            els.winners.innerHTML = '';
            return;
        }

        els.winners.classList.remove('bc-compare-hidden');
        els.winners.innerHTML = '<p class="bc-compare-winners__label">Quick winners</p><div class="bc-compare-winners__grid">' + chips.join('') + '</div>';
    }

    function winnerChip(label, broker, value) {
        return (
            '<span class="bc-compare-winner">' +
            '<span class="bc-compare-winner__label">' + escapeHtml(label) + '</span>' +
            '<span class="bc-compare-winner__name">' + escapeHtml(broker.name) + '</span>' +
            (value ? '<span class="bc-compare-winner__value">' + escapeHtml(String(value)) + '</span>' : '') +
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

    function renderProfiles(selected) {
        if (!els.profiles) {
            return;
        }
        if (selected.length < 2) {
            els.profiles.classList.add('bc-compare-hidden');
            els.profiles.innerHTML = '';
            return;
        }

        els.profiles.classList.remove('bc-compare-hidden');
        els.profiles.innerHTML = selected.map(function (b) {
            var logo = b.logo
                ? '<img src="' + escapeAttr(b.logo) + '" alt="" loading="lazy" decoding="async">'
                : '<span>' + escapeHtml(b.name.charAt(0)) + '</span>';
            var tags = (b.regulation_list || []).slice(0, 3).map(function (tag) {
                return '<span class="bc-compare-profile__tag">' + escapeHtml(tag) + '</span>';
            }).join('');
            var summary = b.short_description || b.top_feature || '';
            if (summary && summary.length > 140) {
                summary = summary.slice(0, 137) + '…';
            }
            return (
                '<article class="bc-compare-profile">' +
                '<div class="bc-compare-profile__head">' +
                '<div class="bc-compare-profile__logo">' + logo + '</div>' +
                '<div>' +
                '<h3 class="bc-compare-profile__name">' + escapeHtml(b.name) + '</h3>' +
                '<p class="bc-compare-profile__score">' + escapeHtml(b.rating_display || '—') +
                (b.broker_type ? ' · ' + escapeHtml(b.broker_type) : '') + '</p>' +
                '</div></div>' +
                (summary ? '<p class="bc-compare-profile__summary">' + escapeHtml(summary) + '</p>' : '') +
                '<dl class="bc-compare-profile__facts">' +
                '<div><dt>Min. deposit</dt><dd>' + escapeHtml(b.minimum_deposit || '—') + '</dd></div>' +
                '<div><dt>Spreads</dt><dd>' + escapeHtml(truncate(b.spreads || '—', 36)) + '</dd></div>' +
                '<div><dt>Leverage</dt><dd>' + escapeHtml(truncate(b.leverage || '—', 28)) + '</dd></div>' +
                '<div><dt>Fee level</dt><dd>' + escapeHtml(b.fee_level || '—') + '</dd></div>' +
                '</dl>' +
                (tags ? '<div class="bc-compare-profile__tags">' + tags + '</div>' : '') +
                '<div class="bc-compare-profile__actions">' +
                (b.review_url ? '<a href="' + escapeAttr(b.review_url) + '" class="bc-compare-btn bc-compare-btn--ghost bc-compare-btn--sm">Review</a>' : '') +
                (b.visit_url ? '<a href="' + escapeAttr(b.visit_url) + '" class="bc-compare-btn bc-compare-btn--primary bc-compare-btn--sm" target="_blank" rel="noopener nofollow">Visit</a>' : '') +
                '</div></article>'
            );
        }).join('');
    }

    function truncate(str, max) {
        str = String(str);
        return str.length > max ? str.slice(0, max - 1) + '…' : str;
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
        if (!group) {
            return;
        }
        if (els.sidebarHead) {
            els.sidebarHead.textContent = group.label;
        }
        if (els.sidebarRows) {
            els.sidebarRows.innerHTML = group.rows.map(function (row) {
                return '<li class="bc-compare-sidebar__row">' + escapeHtml(row.label) + '</li>';
            }).join('');
        }
    }

    function renderMatrix(selected) {
        var rows = dedupeRows(currentRows());
        var diffCount = 0;

        var headHtml = '<th class="compare-table__cell bc-compare-matrix__metric" scope="col">Metric</th>' + selected.map(function (b) {
            var logo = b.logo
                ? '<img src="' + escapeAttr(b.logo) + '" alt="" loading="lazy" decoding="async">'
                : '<span>' + escapeHtml(b.name.charAt(0)) + '</span>';
            var score = b.rating !== null && b.rating !== undefined ? numberFormat(b.rating, 1) : '—';
            return (
                '<th class="compare-table__cell compare-table__broker" scope="col"><div class="bc-compare-matrix__broker-head">' +
                '<div class="bc-compare-matrix__broker-logo">' + logo + '</div>' +
                '<div class="bc-compare-matrix__broker-name">' +
                (b.review_url
                    ? '<a href="' + escapeAttr(b.review_url) + '">' + escapeHtml(b.name) + '</a>'
                    : escapeHtml(b.name)) +
                '</div>' +
                '<div class="bc-compare-matrix__broker-score">' + score + '/5</div>' +
                '</div></th>'
            );
        }).join('');

        var bodyHtml = rows.map(function (row) {
            var values = selected.map(function (b) {
                return formatValue(row.key, b[row.key]);
            });
            var bestIndex = findBestIndex(row.key, selected, values);
            var allSame = values.length > 1 && values.every(function (val) {
                return val === values[0];
            });
            if (!allSame) {
                diffCount += 1;
            }
            if (matrixFilter === 'diff' && allSame) {
                return '';
            }
            var cells = values.map(function (val, i) {
                var cls = cellClass(row.key, val, i === bestIndex, allSame);
                return '<td class="compare-table__cell ' + cls + '" data-broker="' + escapeAttr(selected[i].name) + '">' + escapeHtml(String(val)) + '</td>';
            }).join('');
            return '<tr class="compare-table__row ' + (allSame ? 'is-same' : 'is-diff') + '">' +
                '<th class="compare-table__cell bc-compare-matrix__metric" scope="row">' + escapeHtml(row.label) + '</th>' +
                cells + '</tr>';
        }).join('');

        if (els.diffCount) {
            els.diffCount.textContent = diffCount + ' difference' + (diffCount === 1 ? '' : 's');
        }

        if (!bodyHtml && matrixFilter === 'diff') {
            bodyHtml = '<tr><td class="compare-table__cell" colspan="' + (selected.length + 1) + '">No differences in this category.</td></tr>';
        }

        els.matrixWrap.innerHTML =
            '<div class="compare-table-wrap">' +
            '<table class="compare-table bc-compare-matrix">' +
            '<thead class="compare-table__header"><tr class="compare-table__row">' + headHtml + '</tr></thead>' +
            '<tbody>' + bodyHtml + '</tbody>' +
            '</table></div>';
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
        if (key === 'instrument_count') {
            return String(val) + '+';
        }
        if (key === 'review_count') {
            return String(val);
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
            var raw;
            if (key === 'minimum_deposit') {
                raw = b.minimum_deposit_raw;
            } else if (key === 'rating_display') {
                raw = b.rating;
            } else {
                raw = b[key];
            }
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
        if (key === 'instrument_count' || key === 'review_count' || key === 'year_founded' || key === 'trust_score') {
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
