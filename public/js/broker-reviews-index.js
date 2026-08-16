(function () {
    'use strict';

    var searchInput = document.getElementById('briSearchInput');
    var marketFilters = document.querySelectorAll('[data-bri-market-filter]');
    var cards = document.querySelectorAll('[data-bri-card]');
    var emptyState = document.getElementById('briEmptyState');
    var resultsCount = document.getElementById('briResultsCount');
    var clearBtn = document.getElementById('briClearFilters');
    var loadMoreBtn = document.getElementById('briLoadMore');
    var loadMoreWrap = document.getElementById('briLoadMoreWrap');
    var filtersPanel = document.getElementById('briFiltersPanel');
    var filtersToggle = document.getElementById('briFiltersToggle');
    var filtersClose = document.getElementById('briFiltersClose');
    var filtersBackdrop = document.getElementById('briFiltersBackdrop');
    var desktopQuery = window.matchMedia('(min-width: 1024px)');
    var pageSize = 9;
    var visibleLimit = pageSize;

    function activeMarkets() {
        return Array.from(marketFilters)
            .filter(function (input) {
                return input.checked;
            })
            .map(function (input) {
                return input.value;
            });
    }

    function applyFilters(resetLimit) {
        if (!cards.length) {
            return;
        }

        if (resetLimit) {
            visibleLimit = pageSize;
        }

        var query = (searchInput && searchInput.value || '').trim().toLowerCase();
        var markets = activeMarkets();
        var matched = 0;
        var shown = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-bri-name') || '').toLowerCase();
            var cardMarkets = (card.getAttribute('data-bri-markets') || '').split(',').filter(Boolean);
            var matchesName = !query || name.indexOf(query) !== -1;
            var matchesMarket = markets.length === 0 || markets.some(function (market) {
                return cardMarkets.indexOf(market) !== -1;
            });
            var matches = matchesName && matchesMarket;
            var show = matches && matched < visibleLimit;

            card.classList.toggle('is-hidden', !show);
            if (matches) {
                matched += 1;
            }
            if (show) {
                shown += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('is-hidden', matched > 0);
        }

        if (resultsCount) {
            resultsCount.textContent = matched > shown
                ? 'Showing ' + shown + ' of ' + matched + ' brokers'
                : matched + ' broker' + (matched === 1 ? '' : 's') + ' shown';
        }

        if (loadMoreWrap) {
            loadMoreWrap.classList.toggle('is-hidden', shown >= matched);
        }
    }

    function setFiltersOpen(isOpen) {
        if (!filtersPanel || desktopQuery.matches) {
            return;
        }

        filtersPanel.classList.toggle('is-open', isOpen);

        if (filtersBackdrop) {
            filtersBackdrop.classList.toggle('is-hidden', !isOpen);
        }

        if (filtersToggle) {
            filtersToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    function closeFilters() {
        setFiltersOpen(false);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            applyFilters(true);
        });
    }

    marketFilters.forEach(function (input) {
        input.addEventListener('change', function () {
            applyFilters(true);
        });
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
            }
            marketFilters.forEach(function (input) {
                input.checked = false;
            });
            applyFilters(true);
        });
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            visibleLimit += pageSize;
            applyFilters(false);
        });
    }

    if (filtersToggle) {
        filtersToggle.addEventListener('click', function () {
            var isOpen = filtersPanel && filtersPanel.classList.contains('is-open');
            setFiltersOpen(!isOpen);
        });
    }

    if (filtersClose) {
        filtersClose.addEventListener('click', closeFilters);
    }

    if (filtersBackdrop) {
        filtersBackdrop.addEventListener('click', closeFilters);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeFilters();
        }
    });

    desktopQuery.addEventListener('change', function () {
        closeFilters();
    });

    applyFilters(false);

    function metricScrollStep(track) {
        var metric = track.querySelector('.bri-perf-metric');
        if (!metric) {
            return 150;
        }

        var styles = window.getComputedStyle(track);
        var gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;

        return metric.getBoundingClientRect().width + gap;
    }

    function updatePerfNav(slider) {
        var track = slider.querySelector('[data-bri-perf-track]');
        var prev = slider.querySelector('[data-bri-perf-prev]');
        var next = slider.querySelector('[data-bri-perf-next]');

        if (!track || !prev || !next) {
            return;
        }

        var maxScroll = track.scrollWidth - track.clientWidth;
        var slop = 4;

        prev.disabled = track.scrollLeft <= slop;
        next.disabled = maxScroll <= slop || track.scrollLeft >= maxScroll - slop;
    }

    document.querySelectorAll('[data-bri-perf]').forEach(function (slider) {
        var track = slider.querySelector('[data-bri-perf-track]');
        var prev = slider.querySelector('[data-bri-perf-prev]');
        var next = slider.querySelector('[data-bri-perf-next]');

        if (!track) {
            return;
        }

        function scrollBy(delta) {
            track.scrollBy({ left: delta, behavior: 'smooth' });
        }

        if (prev) {
            prev.addEventListener('click', function () {
                scrollBy(-metricScrollStep(track));
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                scrollBy(metricScrollStep(track));
            });
        }

        track.addEventListener('scroll', function () {
            updatePerfNav(slider);
        }, { passive: true });

        track.addEventListener('keydown', function (event) {
            var step = metricScrollStep(track);

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                scrollBy(-step);
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                scrollBy(step);
            }
        });

        updatePerfNav(slider);

        if (typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(function () {
                updatePerfNav(slider);
            }).observe(track);
        }
    });

    document.querySelectorAll('[data-bri-regions-slider]').forEach(function (slider) {
        var track = slider.querySelector('[data-bri-regions-track]');
        var prev = slider.querySelector('[data-bri-regions-prev]');
        var next = slider.querySelector('[data-bri-regions-next]');

        if (!track || !prev || !next) {
            return;
        }

        function regionScrollStep() {
            var card = track.querySelector('.bri-region-card');
            if (!card) {
                return track.clientWidth;
            }

            var styles = window.getComputedStyle(track);
            var gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;
            var cardsPerStep = window.matchMedia('(min-width: 1001px)').matches ? 2 : 1;

            return (card.getBoundingClientRect().width + gap) * cardsPerStep;
        }

        function updateRegionNav() {
            var maxScroll = track.scrollWidth - track.clientWidth;
            var slop = 4;

            prev.disabled = track.scrollLeft <= slop;
            next.disabled = maxScroll <= slop || track.scrollLeft >= maxScroll - slop;
        }

        function scrollRegions(direction) {
            track.scrollBy({
                left: direction * regionScrollStep(),
                behavior: 'smooth'
            });
        }

        prev.addEventListener('click', function () {
            scrollRegions(-1);
        });

        next.addEventListener('click', function () {
            scrollRegions(1);
        });

        track.addEventListener('scroll', updateRegionNav, { passive: true });
        track.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                event.preventDefault();
                scrollRegions(event.key === 'ArrowLeft' ? -1 : 1);
            }
        });

        updateRegionNav();

        if (typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(updateRegionNav).observe(track);
        }
    });
})();
