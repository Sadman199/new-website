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
        var groups = document.querySelectorAll('[data-bbh-filter-group]');
        var pagination = document.getElementById('bbhPagination');
        var pageInfo = document.getElementById('bbhPageInfo');
        var pagePrev = document.querySelector('[data-bbh-page-prev]');
        var pageNext = document.querySelector('[data-bbh-page-next]');
        var countryTrigger = document.querySelector('[data-bbh-country-trigger]');

        var pageSize = 12;
        var currentPage = 1;
        var filteredGridCards = [];

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

        function searchQuery() {
            var value = (heroSearch && heroSearch.value) || (sidebarSearch && sidebarSearch.value) || '';
            return value.trim().toLowerCase();
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
            var title = (card.getAttribute('data-bbh-title') || '').toLowerCase();
            var desc = (card.getAttribute('data-bbh-desc') || '').toLowerCase();
            var tags = cardTags(card);
            var groupKeys = Object.keys(filtersByGroup);

            var matchesQuery = !query || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1;

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
            var hasActiveFilters = query.length > 0 || Object.keys(filtersByGroup).length > 0;

            popularCards.forEach(function (card) {
                var isSpotlight = card.getAttribute('data-bbh-spotlight') === 'true';

                if (isSpotlight && !hasActiveFilters) {
                    card.classList.remove('is-hidden');
                    return;
                }

                if (isSpotlight && hasActiveFilters) {
                    card.classList.add('is-hidden');
                    return;
                }

                card.classList.toggle('is-hidden', !cardMatches(card, query, filtersByGroup));
            });
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
            var track = document.getElementById('bbhPopularTrack');
            var dotsWrap = document.querySelector('[data-bbh-carousel-dots]');
            var prevBtn = document.querySelector('[data-bbh-carousel-prev]');
            var nextBtn = document.querySelector('[data-bbh-carousel-next]');

            if (!track) {
                return;
            }

            var slides = function () {
                return Array.from(track.querySelectorAll('.bbh-card-wrap:not(.is-hidden)'));
            };

            function scrollAmount() {
                var visible = slides();
                return visible[0] ? visible[0].offsetWidth + 16 : track.clientWidth;
            }

            function updateControls() {
                var visible = slides();
                var maxScroll = track.scrollWidth - track.clientWidth;
                var pageCount = Math.max(1, visible.length);

                if (prevBtn) {
                    prevBtn.disabled = track.scrollLeft <= 4;
                }
                if (nextBtn) {
                    nextBtn.disabled = track.scrollLeft >= maxScroll - 4;
                }

                if (!dotsWrap) {
                    return;
                }

                dotsWrap.innerHTML = '';
                for (var i = 0; i < pageCount; i++) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'bbh-carousel__dot' + (i === 0 ? ' is-active' : '');
                    dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                    dot.addEventListener('click', function (index) {
                        return function () {
                            var target = slides()[index];
                            if (target) {
                                track.scrollTo({ left: target.offsetLeft, behavior: 'smooth' });
                            }
                        };
                    }(i));
                    dotsWrap.appendChild(dot);
                }
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
                if (!dotsWrap) {
                    return;
                }
                var visible = slides();
                if (!visible.length) {
                    return;
                }
                var index = 0;
                var minDiff = Infinity;
                visible.forEach(function (slide, i) {
                    var diff = Math.abs(slide.offsetLeft - track.scrollLeft);
                    if (diff < minDiff) {
                        minDiff = diff;
                        index = i;
                    }
                });
                dotsWrap.querySelectorAll('.bbh-carousel__dot').forEach(function (dot, i) {
                    dot.classList.toggle('is-active', i === index);
                });
                if (prevBtn) {
                    prevBtn.disabled = track.scrollLeft <= 4;
                }
                if (nextBtn) {
                    nextBtn.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
                }
            }, { passive: true });

            window.addEventListener('resize', updateControls);
            updateControls();
        }
    });
})();
