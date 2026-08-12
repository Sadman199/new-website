(function () {
    'use strict';

    var sheet = document.getElementById('bcToolsSheet');
    var trigger = document.getElementById('bcToolsTrigger');

    if (!sheet || !trigger) {
        return;
    }

    var isOpen = false;

    function openSheet() {
        if (isOpen) {
            return;
        }

        sheet.classList.add('is-open');
        sheet.setAttribute('aria-hidden', 'false');
        trigger.setAttribute('aria-expanded', 'true');
        trigger.classList.add('is-hidden');
        isOpen = true;
        document.dispatchEvent(new CustomEvent('bc:quick-access-open'));

        var input = sheet.querySelector('[data-bc-tools-search]');
        if (input) {
            window.setTimeout(function () {
                input.focus();
            }, 360);
        }
    }

    function closeSheet() {
        if (!isOpen) {
            return;
        }

        sheet.classList.remove('is-open');
        sheet.setAttribute('aria-hidden', 'true');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.classList.remove('is-hidden');
        isOpen = false;
        document.dispatchEvent(new CustomEvent('bc:quick-access-close'));
    }

    trigger.addEventListener('click', function () {
        if (isOpen) {
            closeSheet();
        } else {
            openSheet();
        }
    });

    sheet.querySelectorAll('[data-bc-tools-close]').forEach(function (button) {
        button.addEventListener('click', closeSheet);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && isOpen) {
            closeSheet();
        }
    });

    window.addEventListener('bc:country-drawer-open', closeSheet);

    function initSearch(input) {
        var form = input.closest('form');
        var resultsBox = form ? form.querySelector('[data-bc-tools-results]') : null;
        if (!resultsBox) {
            return;
        }

        var debounceTimer = null;
        var selectedIndex = -1;
        var resultsUrl = null;

        function highlight(text, query) {
            var escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return text.replace(new RegExp('(' + escaped + ')', 'gi'), '<strong>$1</strong>');
        }

        function hideResults() {
            resultsBox.hidden = true;
            resultsBox.classList.remove('is-visible');
            resultsBox.innerHTML = '';
            selectedIndex = -1;
            resultsUrl = null;
        }

        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(function () {
                var query = input.value.trim();

                if (query.length < 2) {
                    hideResults();
                    return;
                }

                fetch('/search/suggest?q=' + encodeURIComponent(query))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        resultsBox.innerHTML = '';
                        selectedIndex = -1;
                        resultsUrl = data.results_url || ('/search?q=' + encodeURIComponent(query));
                        var items = Array.isArray(data.results) ? data.results : [];

                        if (!items.length) {
                            resultsBox.innerHTML = '<div class="bc-tools-sheet__empty">No matches yet — press Enter for full results.</div>';
                        } else {
                            items.forEach(function (item) {
                                var link = document.createElement('a');
                                link.href = item.url;
                                link.className = 'bc-tools-sheet__result';
                                var imageHtml = item.image
                                    ? '<img src="' + item.image + '" alt="">'
                                    : '<span class="bc-tools-sheet__result-fallback" aria-hidden="true">↗</span>';
                                link.innerHTML =
                                    imageHtml +
                                    '<span class="bc-tools-sheet__result-copy">' +
                                        '<small>' + (item.type_label || 'Result') + '</small>' +
                                        '<span>' + highlight(item.title || '', query) + '</span>' +
                                    '</span>';
                                resultsBox.appendChild(link);
                            });

                            var viewAll = document.createElement('a');
                            viewAll.href = resultsUrl;
                            viewAll.className = 'bc-tools-sheet__view-all';
                            viewAll.textContent = 'View all ' + (data.total || items.length) + ' results';
                            resultsBox.appendChild(viewAll);
                        }

                        resultsBox.hidden = false;
                        resultsBox.classList.add('is-visible');
                    })
                    .catch(function () {
                        hideResults();
                    });
            }, 260);
        });

        input.addEventListener('keydown', function (event) {
            var items = resultsBox.querySelectorAll('.bc-tools-sheet__result');

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
            } else if (event.key === 'Enter' && items[selectedIndex]) {
                event.preventDefault();
                window.location.href = items[selectedIndex].href;
                return;
            } else if (event.key === 'Escape') {
                hideResults();
                return;
            } else {
                return;
            }

            items.forEach(function (item, index) {
                item.classList.toggle('is-active', index === selectedIndex);
            });
        });

        if (form) {
            form.addEventListener('submit', function () {
                closeSheet();
            });
        }
    }

    var searchInput = sheet.querySelector('[data-bc-tools-search]');
    if (searchInput) {
        initSearch(searchInput);
    }
})();
