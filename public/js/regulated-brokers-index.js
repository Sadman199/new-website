(function () {
    'use strict';

    var searchInput = document.getElementById('rbiSearchInput');
    var regulatorFilters = document.querySelectorAll('[data-rbi-regulator-filter]');
    var tierFilters = document.querySelectorAll('[data-rbi-tier-filter]');
    var cards = document.querySelectorAll('[data-rbi-card]');
    var emptyState = document.getElementById('rbiEmptyState');
    var resultsCount = document.getElementById('rbiResultsCount');
    var grid = document.getElementById('rbiBrokerGrid');
    var clearBtn = document.getElementById('rbiClearFilters');

    if (!cards.length) {
        return;
    }

    function activeValues(inputs) {
        return Array.from(inputs)
            .filter(function (input) {
                return input.checked;
            })
            .map(function (input) {
                return input.value;
            });
    }

    function applyFilters() {
        var query = (searchInput && searchInput.value || '').trim().toLowerCase();
        var regulators = activeValues(regulatorFilters);
        var tiers = activeValues(tierFilters);
        var visible = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-rbi-name') || '').toLowerCase();
            var cardRegulators = (card.getAttribute('data-rbi-regulators') || '').split(',').filter(Boolean);
            var tier = card.getAttribute('data-rbi-tier') || '';
            var matchesName = !query || name.indexOf(query) !== -1;
            var matchesRegulator = regulators.length === 0 || regulators.some(function (regulator) {
                return cardRegulators.indexOf(regulator) !== -1;
            });
            var matchesTier = tiers.length === 0 || tiers.indexOf(tier) !== -1;
            var show = matchesName && matchesRegulator && matchesTier;

            card.classList.toggle('is-hidden', !show);
            if (show) {
                visible += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('is-hidden', visible > 0);
        }

        if (grid) {
            grid.classList.toggle('is-hidden', visible === 0);
        }

        if (resultsCount) {
            resultsCount.textContent = visible + ' regulated broker' + (visible === 1 ? '' : 's') + ' shown';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    regulatorFilters.forEach(function (input) {
        input.addEventListener('change', applyFilters);
    });

    tierFilters.forEach(function (input) {
        input.addEventListener('change', applyFilters);
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
            }
            regulatorFilters.forEach(function (input) {
                input.checked = false;
            });
            tierFilters.forEach(function (input) {
                input.checked = false;
            });
            applyFilters();
        });
    }

    applyFilters();
})();
