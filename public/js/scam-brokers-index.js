(function () {
    'use strict';

    var searchInput = document.getElementById('sbiSearchInput');
    var warningFilters = document.querySelectorAll('[data-sbi-warning-filter]');
    var cards = document.querySelectorAll('[data-sbi-card]');
    var emptyState = document.getElementById('sbiEmptyState');
    var resultsCount = document.getElementById('sbiResultsCount');
    var clearBtn = document.getElementById('sbiClearFilters');
    var grid = document.getElementById('sbiBrokerGrid');

    if (!cards.length) {
        if (resultsCount) {
            resultsCount.textContent = '0 brokers flagged';
        }
        return;
    }

    function activeWarnings() {
        return Array.from(warningFilters)
            .filter(function (input) {
                return input.checked;
            })
            .map(function (input) {
                return input.value;
            });
    }

    function applyFilters() {
        var query = (searchInput && searchInput.value || '').trim().toLowerCase();
        var warnings = activeWarnings();
        var visible = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-sbi-name') || '').toLowerCase();
            var cardWarnings = (card.getAttribute('data-sbi-warnings') || '').split(',').filter(Boolean);
            var matchesName = !query || name.indexOf(query) !== -1;
            var matchesWarning = warnings.length === 0 || warnings.some(function (warning) {
                return cardWarnings.indexOf(warning) !== -1;
            });
            var show = matchesName && matchesWarning;

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
            resultsCount.textContent = visible + ' flagged broker' + (visible === 1 ? '' : 's') + ' shown';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    warningFilters.forEach(function (input) {
        input.addEventListener('change', applyFilters);
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
            }
            warningFilters.forEach(function (input) {
                input.checked = false;
            });
            applyFilters();
        });
    }

    applyFilters();
})();
