(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initRegulationTabs();

        var grid = document.getElementById('tbkBrokerGrid');
        var search = document.getElementById('tbkSearch');
        var chips = Array.from(document.querySelectorAll('[data-tbk-filter]'));
        var sortButtons = Array.from(document.querySelectorAll('[data-tbk-sort]'));
        var resultsMeta = document.getElementById('tbkResultsMeta');
        var emptyState = document.getElementById('tbkEmptyState');
        var loadMoreBtn = document.getElementById('tbkLoadMore');
        var loadMoreLabel = document.getElementById('tbkLoadMoreLabel');

        if (!grid) {
            return;
        }

        var cards = Array.from(grid.querySelectorAll('[data-tbk-card]'));
        var activeFilters = new Set();
        var activeSort = 'overall';
        var displayLimit = 12;
        var pageSize = 12;

        function cardTags(card) {
            return (card.getAttribute('data-tbk-tags') || '')
                .split(',')
                .map(function (tag) {
                    return tag.trim();
                })
                .filter(Boolean);
        }

        function sortValue(card, sortKey) {
            if (sortKey === 'low-cost') {
                return parseFloat(card.getAttribute('data-tbk-sort-low-cost') || '0');
            }

            return parseFloat(card.getAttribute('data-tbk-sort-rating') || '0');
        }

        function resetDisplayLimit() {
            displayLimit = pageSize;
        }

        function updateLoadMore(visibleCount) {
            if (!loadMoreBtn) {
                return;
            }

            var remaining = Math.max(visibleCount - displayLimit, 0);
            loadMoreBtn.classList.toggle('is-hidden', remaining === 0);

            if (loadMoreLabel) {
                loadMoreLabel.textContent = remaining > 0
                    ? 'Load more brokers (' + remaining + ' remaining)'
                    : 'Load more brokers';
            }
        }

        function apply() {
            var query = (search && search.value ? search.value : '').trim().toLowerCase();
            var visible = cards.filter(function (card) {
                var name = (card.getAttribute('data-tbk-name') || '').toLowerCase();
                var matchesQuery = !query || name.indexOf(query) !== -1;
                var tags = cardTags(card);
                var matchesFilters = activeFilters.size === 0 || Array.from(activeFilters).every(function (filter) {
                    return tags.indexOf(filter) !== -1;
                });

                return matchesQuery && matchesFilters;
            });

            visible.sort(function (a, b) {
                return sortValue(b, activeSort) - sortValue(a, activeSort);
            });

            cards.forEach(function (card) {
                card.classList.add('is-hidden');
            });

            visible.forEach(function (card, index) {
                var isShown = index < displayLimit;
                card.classList.toggle('is-hidden', !isShown);

                if (isShown) {
                    grid.appendChild(card);
                }

                var rank = card.querySelector('[data-tbk-rank]');
                if (rank) {
                    rank.textContent = '#' + (index + 1);
                }

                var compareCard = card.querySelector('[data-tbk-card-inner]');
                if (compareCard) {
                    compareCard.classList.toggle('is-top', index === 0);
                }
            });

            if (resultsMeta) {
                var shown = Math.min(visible.length, displayLimit);
                resultsMeta.textContent = shown + ' of ' + visible.length + ' broker' + (visible.length === 1 ? '' : 's') + ' shown';
            }

            if (emptyState) {
                emptyState.classList.toggle('is-hidden', visible.length > 0);
            }

            updateLoadMore(visible.length);
        }

        if (search) {
            search.addEventListener('input', function () {
                resetDisplayLimit();
                apply();
            });
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                var key = chip.getAttribute('data-tbk-filter');
                if (!key) {
                    return;
                }

                if (activeFilters.has(key)) {
                    activeFilters.delete(key);
                    chip.classList.remove('is-active');
                    chip.setAttribute('aria-pressed', 'false');
                } else {
                    activeFilters.add(key);
                    chip.classList.add('is-active');
                    chip.setAttribute('aria-pressed', 'true');
                }

                resetDisplayLimit();
                apply();
            });
        });

        sortButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                activeSort = button.getAttribute('data-tbk-sort') || 'overall';
                sortButtons.forEach(function (item) {
                    item.classList.toggle('is-active', item === button);
                });
                resetDisplayLimit();
                apply();
            });
        });

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function () {
                displayLimit += pageSize;
                apply();
            });
        }

        apply();
    });

    function initRegulationTabs() {
        var tabs = Array.from(document.querySelectorAll('[data-tbk-reg-tab]'));
        var panels = Array.from(document.querySelectorAll('[data-tbk-reg-panel]'));
        var scroller = document.getElementById('tbkRegTabsScroll');
        var prevBtn = document.getElementById('tbkRegPrev');
        var nextBtn = document.getElementById('tbkRegNext');

        if (!tabs.length || !panels.length) {
            return;
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var key = tab.getAttribute('data-tbk-reg-tab');

                tabs.forEach(function (t) {
                    var active = t === tab;
                    t.classList.toggle('is-active', active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                panels.forEach(function (p) {
                    var active = p.getAttribute('data-tbk-reg-panel') === key;
                    p.classList.toggle('is-active', active);
                    if (active) {
                        p.removeAttribute('hidden');
                    } else {
                        p.setAttribute('hidden', '');
                    }
                });

                if (scroller && typeof tab.scrollIntoView === 'function') {
                    tab.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            });
        });

        if (scroller && (prevBtn || nextBtn)) {
            var scrollAmount = function () {
                return Math.max(scroller.clientWidth * 0.6, 160);
            };

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    scroller.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    scroller.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
                });
            }
        }
    }
})();
