(function () {
    'use strict';

    var searchInput = document.getElementById('calcHubSearch');
    var cards = document.querySelectorAll('[data-calc-card]');
    var emptyState = document.getElementById('calcHubEmpty');
    var resultsCount = document.getElementById('calcHubCount');
    var categories = document.querySelectorAll('[data-calc-category]');

    if (!cards.length) {
        return;
    }

    function applyFilters() {
        var query = ((searchInput && searchInput.value) || '').trim().toLowerCase();
        var visible = 0;

        cards.forEach(function (card) {
            var haystack = (card.getAttribute('data-calc-search') || '').toLowerCase();
            var show = !query || haystack.indexOf(query) !== -1;
            card.classList.toggle('is-hidden', !show);
            if (show) {
                visible += 1;
            }
        });

        categories.forEach(function (section) {
            var any = section.querySelector('[data-calc-card]:not(.is-hidden)');
            section.classList.toggle('is-hidden', !any);
        });

        if (emptyState) {
            emptyState.classList.toggle('is-hidden', visible > 0);
        }

        if (resultsCount) {
            resultsCount.textContent = visible + ' ' + (visible === 1 ? 'tool' : 'tools');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
})();
