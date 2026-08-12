(function () {
    'use strict';

    var searchInput = document.getElementById('sbiSearchInput');
    var warningFilters = document.querySelectorAll('[data-sbi-warning-filter]');
    var cards = document.querySelectorAll('[data-sbi-card]');
    var emptyState = document.getElementById('sbiEmptyState');
    var emptyTitle = document.getElementById('sbiEmptyTitle');
    var emptyText = document.getElementById('sbiEmptyText');
    var resultsCount = document.getElementById('sbiResultsCount');
    var clearBtn = document.getElementById('sbiClearFilters');
    var grid = document.getElementById('sbiBrokerGrid');
    var totalCount = resultsCount ? parseInt(resultsCount.getAttribute('data-sbi-total') || '0', 10) : 0;

    function activeWarnings() {
        return Array.from(warningFilters)
            .filter(function (input) {
                return input.checked;
            })
            .map(function (input) {
                return input.value;
            });
    }

    function hasActiveFilters() {
        var query = (searchInput && searchInput.value || '').trim();
        return query !== '' || activeWarnings().length > 0;
    }

    function applyFilters() {
        if (!cards.length) {
            return;
        }

        var query = (searchInput && searchInput.value || '').trim().toLowerCase();
        var warnings = activeWarnings();
        var visible = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-sbi-name') || '').toLowerCase();
            var cardWarnings = (card.getAttribute('data-sbi-warnings') || '')
                .split(',')
                .map(function (value) {
                    return value.trim();
                })
                .filter(Boolean);
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
            if (hasActiveFilters()) {
                resultsCount.textContent = visible + ' of ' + totalCount + ' flagged broker' + (totalCount === 1 ? '' : 's') + ' shown';
            } else {
                resultsCount.textContent = totalCount + ' flagged ' + (totalCount === 1 ? 'broker' : 'brokers');
            }
        }

        if (emptyTitle && emptyText && totalCount > 0) {
            if (visible === 0) {
                emptyTitle.textContent = 'No brokers match your filters';
                emptyText.textContent = 'Try clearing the search or warning type filters.';
            }
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
