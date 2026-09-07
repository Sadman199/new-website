(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var heroSearch = document.getElementById('bbhHeroSearchInput');
        var sidebarSearch = document.getElementById('bbhSearchInput');
        var resetTop = document.getElementById('bbhResetFiltersTop');
        var resetBottom = document.getElementById('bbhResetFiltersBottom');
        var allGrid = document.getElementById('bbhAllGrid');
        var popularTrack = document.getElementById('bbhPopularTrack');
        var gridCards = allGrid ? Array.from(allGrid.querySelectorAll('[data-bbh-card]')) : [];
        var popularCards = popularTrack ? Array.from(popularTrack.querySelectorAll('[data-bbh-card]')) : [];
        var emptyState = document.getElementById('bbhEmptyState');
        var resultsMeta = document.getElementById('bbhResultsMeta');
        var filterInputs = Array.from(document.querySelectorAll('[data-bbh-filter]'));
        var groups = document.querySelectorAll('.bbh-filter-group[data-bbh-filter-group]');
        var pagination = document.getElementById('bbhPagination');
        var pageInfo = document.getElementById('bbhPageInfo');
        var pagePrev = document.querySelector('[data-bbh-page-prev]');
        var pageNext = document.querySelector('[data-bbh-page-next]');
        var countryTrigger = document.querySelector('[data-bbh-country-trigger]');
        var typeTabs = Array.from(document.querySelectorAll('[data-bbh-type-tab]'));
        var filtersPanel = document.getElementById('bbhFiltersPanel');
        var filtersToggle = document.getElementById('bbhFiltersToggle');
        var filtersClose = document.getElementById('bbhFiltersClose');
        var filtersBackdrop = document.getElementById('bbhFiltersBackdrop');
        var desktopQuery = window.matchMedia('(min-width: 1024px)');

        var pageSize = 12;
        var currentPage = 1;
        var filteredGridCards = [];
        var activeType = 'all';
        var refreshCarousel = function () {};

        if (!gridCards.length && !popularCards.length) {
            return;
        }

        groups.forEach(function (group) {
            var toggle = group.querySelector('[data-bbh-filter-toggle]');
            if (!toggle) {
                return;
            }
            toggle.addEventListener('click', function () {
                group.classList.toggle('is-open');
            });
        });

        function bindSearchSync(source, target) {
            if (!source || !target) {
                return;
            }
            source.addEventListener('input', function () {
                if (target.value !== source.value) {
                    target.value = source.value;
                }
                applyFilters();
            });
        }

        bindSearchSync(heroSearch, sidebarSearch);
        bindSearchSync(sidebarSearch, heroSearch);

        function normalizeSearch(value) {
            return String(value || '')
                .toLowerCase()
                .replace(/[-_]+/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function searchQuery() {
            var value = (heroSearch && heroSearch.value) || (sidebarSearch && sidebarSearch.value) || '';
            return normalizeSearch(value);
        }

        function selectedFiltersByGroup() {
            var byGroup = {};

            filterInputs.forEach(function (input) {
                if (!input.checked) {
                    return;
                }

                var group = input.getAttribute('data-bbh-filter-group') || 'all';
                if (!byGroup[group]) {
                    byGroup[group] = [];
                }
                byGroup[group].push(input.value);
            });

            return byGroup;
        }

        function cardTags(card) {
            return (card.getAttribute('data-bbh-filters') || '')
                .split(',')
                .map(function (tag) {
                    return tag.trim();
                })
                .filter(Boolean);
        }

        function cardMatches(card, query, filtersByGroup) {
            var type = card.getAttribute('data-bbh-type') || 'category';
            var tags = cardTags(card);
            var groupKeys = Object.keys(filtersByGroup);
            var haystack = normalizeSearch([
                card.getAttribute('data-bbh-title') || '',
                type,
                card.getAttribute('data-bbh-slug') || '',
                tags.join(' ')
            ].join(' '));

            if (activeType !== 'all' && type !== activeType) {
                return false;
            }

            var matchesQuery = !query || haystack.indexOf(query) !== -1;

            if (!matchesQuery) {
                return false;
            }

            if (groupKeys.length === 0) {
                return true;
            }

            return groupKeys.every(function (groupKey) {
                var selected = filtersByGroup[groupKey] || [];
                return selected.some(function (value) {
                    return tags.indexOf(value) !== -1;
                });
            });
        }

        function filterPopularCards(query, filtersByGroup) {
            popularCards.forEach(function (card) {
                card.classList.toggle('is-hidden', !cardMatches(card, query, filtersByGroup));
            });
        }

        function setFiltersOpen(isOpen) {
            if (!filtersPanel || desktopQuery.matches) {
                return;
            }

            filtersPanel.classList.toggle('is-open', isOpen);
            document.body.classList.toggle('bbh-filters-open', isOpen);

            if (filtersBackdrop) {
                filtersBackdrop.classList.toggle('is-hidden', !isOpen);
                filtersBackdrop.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            }

            if (filtersToggle) {
                filtersToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        function renderGridPage() {
            var total = filteredGridCards.length;
            var totalPages = Math.max(1, Math.ceil(total / pageSize));

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            gridCards.forEach(function (card) {
                card.classList.add('is-hidden');
            });

            if (total === 0) {
                if (emptyState) {
                    emptyState.classList.remove('is-hidden');
                }
                if (pagination) {
                    pagination.classList.add('is-hidden');
                }
                if (resultsMeta) {
                    resultsMeta.textContent = '0 lists shown';
                }
                return;
            }

            if (emptyState) {
                emptyState.classList.add('is-hidden');
            }

            var start = (currentPage - 1) * pageSize;
            var end = start + pageSize;

            filteredGridCards.slice(start, end).forEach(function (card) {
                card.classList.remove('is-hidden');
            });

            if (resultsMeta) {
                resultsMeta.textContent = total + ' list' + (total === 1 ? '' : 's') + ' shown';
            }

            if (pagination) {
                pagination.classList.toggle('is-hidden', totalPages <= 1);
            }

            if (pageInfo) {
                pageInfo.textContent = 'Page ' + currentPage + ' of ' + totalPages;
            }

            if (pagePrev) {
                pagePrev.disabled = currentPage <= 1;
            }

            if (pageNext) {
                pageNext.disabled = currentPage >= totalPages;
            }
        }

        function applyFilters() {
            var query = searchQuery();
            var filtersByGroup = selectedFiltersByGroup();

            filteredGridCards = gridCards.filter(function (card) {
                return cardMatches(card, query, filtersByGroup);
            });

            filterPopularCards(query, filtersByGroup);
            currentPage = 1;
            renderGridPage();
            refreshCarousel();
        }

        function resetFilters() {
            if (heroSearch) {
                heroSearch.value = '';
            }
            if (sidebarSearch) {
                sidebarSearch.value = '';
            }
            filterInputs.forEach(function (input) {
                input.checked = false;
            });
            activeType = 'all';
            typeTabs.forEach(function (item) {
                var on = item.getAttribute('data-bbh-type-tab') === 'all';
                item.classList.toggle('is-active', on);
                item.setAttribute('aria-pressed', on ? 'true' : 'false');
            });
            applyFilters();
        }

        filterInputs.forEach(function (input) {
            input.addEventListener('change', applyFilters);
        });

        if (resetTop) {
            resetTop.addEventListener('click', resetFilters);
        }

        if (resetBottom) {
            resetBottom.addEventListener('click', resetFilters);
        }

        typeTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activeType = tab.getAttribute('data-bbh-type-tab') || 'all';
                typeTabs.forEach(function (item) {
                    var on = item === tab;
                    item.classList.toggle('is-active', on);
                    item.setAttribute('aria-pressed', on ? 'true' : 'false');
                });
                applyFilters();
            });
        });

        if (filtersToggle) {
            filtersToggle.addEventListener('click', function () {
                setFiltersOpen(!(filtersPanel && filtersPanel.classList.contains('is-open')));
            });
        }

        if (filtersClose) {
            filtersClose.addEventListener('click', function () {
                setFiltersOpen(false);
            });
        }

        if (filtersBackdrop) {
            filtersBackdrop.addEventListener('click', function () {
                setFiltersOpen(false);
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setFiltersOpen(false);
            }
        });

        if (desktopQuery.addEventListener) {
            desktopQuery.addEventListener('change', function () {
                if (desktopQuery.matches) {
                    setFiltersOpen(false);
                    if (filtersPanel) {
                        filtersPanel.classList.remove('is-open');
                    }
                    document.body.classList.remove('bbh-filters-open');
                    document.body.style.overflow = '';
                }
            });
        }

        if (pagePrev) {
            pagePrev.addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage -= 1;
                    renderGridPage();
                    if (allGrid) {
                        allGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        }

        if (pageNext) {
            pageNext.addEventListener('click', function () {
                var totalPages = Math.max(1, Math.ceil(filteredGridCards.length / pageSize));
                if (currentPage < totalPages) {
                    currentPage += 1;
                    renderGridPage();
                    if (allGrid) {
                        allGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        }

        if (countryTrigger) {
            countryTrigger.addEventListener('click', function () {
                var drawer = document.getElementById('countryDrawer');
                if (drawer) {
                    drawer.classList.add('is-open');
                    document.body.classList.add('bc-drawer-open');
                }
            });
        }

        initCarousel();
        applyFilters();

        function initCarousel() {
            var root = document.querySelector('[data-bbh-carousel]');
            var track = document.getElementById('bbhPopularTrack');
            var controls = root ? root.querySelector('.bbh-carousel__controls') : null;
            var dotsWrap = document.querySelector('[data-bbh-carousel-dots]');
            var prevBtn = document.querySelector('[data-bbh-carousel-prev]');
            var nextBtn = document.querySelector('[data-bbh-carousel-next]');

            if (!track) {
                return;
            }

            var slides = function () {
                return Array.from(track.querySelectorAll('.bbh-card-wrap:not(.is-hidden)'));
            };

            function hasOverflow() {
                return track.scrollWidth > track.clientWidth + 4;
            }

            function isWrappingGrid() {
                return window.getComputedStyle(track).display === 'grid';
            }

            function scrollAmount() {
                var visible = slides();
                return visible[0] ? visible[0].offsetWidth + 16 : track.clientWidth;
            }

            function visiblePerView() {
                var visible = slides();
                if (!visible.length) {
                    return 1;
                }
                var cardWidth = visible[0].getBoundingClientRect().width;
                if (!cardWidth) {
                    return 1;
                }
                return Math.max(1, Math.round((track.clientWidth + 16) / (cardWidth + 16)));
            }

            function pageCount() {
                var perView = visiblePerView();
                var total = slides().length;
                if (total <= perView) {
                    return 0;
                }
                return Math.ceil(total / perView);
            }

            function setControlsVisible(show) {
                if (!controls) {
                    return;
                }
                controls.hidden = !show;
                controls.classList.toggle('is-hidden', !show);
            }

            function syncDots() {
                if (!dotsWrap || dotsWrap.hidden) {
                    return;
                }
                var amount = scrollAmount();
                if (!amount) {
                    return;
                }
                var index = Math.round(track.scrollLeft / amount);
                var dots = dotsWrap.querySelectorAll('.bbh-carousel__dot');
                index = Math.max(0, Math.min(index, dots.length - 1));
                dots.forEach(function (dot, i) {
                    dot.classList.toggle('is-active', i === index);
                });
            }

            function updateControls() {
                var overflow = hasOverflow() && !isWrappingGrid();

                if (!overflow) {
                    setControlsVisible(false);
                    if (dotsWrap) {
                        dotsWrap.innerHTML = '';
                        dotsWrap.hidden = true;
                    }
                    return;
                }

                setControlsVisible(true);

                var maxScroll = track.scrollWidth - track.clientWidth;
                if (prevBtn) {
                    prevBtn.disabled = track.scrollLeft <= 4;
                }
                if (nextBtn) {
                    nextBtn.disabled = track.scrollLeft >= maxScroll - 4;
                }

                if (!dotsWrap) {
                    return;
                }

                var pages = pageCount();
                if (pages < 2 || pages > 5) {
                    dotsWrap.innerHTML = '';
                    dotsWrap.hidden = true;
                    return;
                }

                dotsWrap.hidden = false;
                dotsWrap.innerHTML = '';
                for (var i = 0; i < pages; i++) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'bbh-carousel__dot' + (i === 0 ? ' is-active' : '');
                    dot.setAttribute('aria-label', 'Go to page ' + (i + 1));
                    dot.addEventListener('click', function (index) {
                        return function () {
                            track.scrollTo({ left: index * scrollAmount(), behavior: 'smooth' });
                        };
                    }(i));
                    dotsWrap.appendChild(dot);
                }
                syncDots();
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
                });
            }

            track.addEventListener('scroll', function () {
                if (!hasOverflow() || isWrappingGrid()) {
                    return;
                }
                syncDots();
                var maxScroll = track.scrollWidth - track.clientWidth;
                if (prevBtn) {
                    prevBtn.disabled = track.scrollLeft <= 4;
                }
                if (nextBtn) {
                    nextBtn.disabled = track.scrollLeft >= maxScroll - 4;
                }
            }, { passive: true });

            window.addEventListener('resize', updateControls);
            refreshCarousel = updateControls;
            updateControls();
        }
    });
})();
