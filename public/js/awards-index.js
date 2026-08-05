(function () {
    'use strict';

    var searchInput = document.getElementById('awiSearchInput');
    var cards = document.querySelectorAll('[data-awi-card]');
    var emptyState = document.getElementById('awiEmptyState');
    var resultsCount = document.getElementById('awiResultsCount');
    var grid = document.getElementById('awiAwardGrid');

    if (!cards.length) {
        return;
    }

    function applyFilters() {
        var query = (searchInput && searchInput.value || '').trim().toLowerCase();
        var visible = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-awi-name') || '').toLowerCase();
            var show = !query || name.indexOf(query) !== -1;

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
            resultsCount.textContent = visible + ' award categor' + (visible === 1 ? 'y' : 'ies') + ' shown';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    applyFilters();
})();
