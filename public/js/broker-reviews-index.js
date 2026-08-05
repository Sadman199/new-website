(function () {
    'use strict';

    var searchInput = document.getElementById('briSearchInput');
    var marketFilters = document.querySelectorAll('[data-bri-market-filter]');
    var cards = document.querySelectorAll('[data-bri-card]');
    var emptyState = document.getElementById('briEmptyState');
    var resultsCount = document.getElementById('briResultsCount');
    var clearBtn = document.getElementById('briClearFilters');

    if (!cards.length) {
        return;
    }

    function activeMarkets() {
        return Array.from(marketFilters)
            .filter(function (input) {
                return input.checked;
            })
            .map(function (input) {
                return input.value;
            });
    }

    function applyFilters() {
        var query = (searchInput && searchInput.value || '').trim().toLowerCase();
        var markets = activeMarkets();
        var visible = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-bri-name') || '').toLowerCase();
            var cardMarkets = (card.getAttribute('data-bri-markets') || '').split(',').filter(Boolean);
            var matchesName = !query || name.indexOf(query) !== -1;
            var matchesMarket = markets.length === 0 || markets.some(function (market) {
                return cardMarkets.indexOf(market) !== -1;
            });
            var show = matchesName && matchesMarket;

            card.classList.toggle('is-hidden', !show);
            if (show) {
                visible += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('is-hidden', visible > 0);
        }

        if (resultsCount) {
            resultsCount.textContent = visible + ' broker' + (visible === 1 ? '' : 's') + ' shown';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    marketFilters.forEach(function (input) {
        input.addEventListener('change', applyFilters);
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
            }
            marketFilters.forEach(function (input) {
                input.checked = false;
            });
            applyFilters();
        });
    }

    applyFilters();
})();
