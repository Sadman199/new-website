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
        var resultsBox = input.parentElement.querySelector('[data-bc-tools-results]');
        if (!resultsBox) {
            return;
        }

        var debounceTimer = null;
        var selectedIndex = -1;

        function highlight(text, query) {
            var escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return text.replace(new RegExp('(' + escaped + ')', 'gi'), '<strong>$1</strong>');
        }

        function hideResults() {
            resultsBox.classList.remove('is-visible');
            resultsBox.innerHTML = '';
            selectedIndex = -1;
        }

        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(function () {
                var query = input.value.trim();

                if (query.length < 2) {
                    hideResults();
                    return;
                }

                fetch('/broker-live-search?query=' + encodeURIComponent(query))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        resultsBox.innerHTML = '';
                        selectedIndex = -1;

                        if (!data.length) {
                            resultsBox.innerHTML = '<div class="bc-tools-sheet__empty">No brokers found</div>';
                        } else {
                            data.forEach(function (broker) {
                                var link = document.createElement('a');
                                link.href = '/broker-reviews/' + broker.slug;
                                link.className = 'bc-tools-sheet__result';
                                link.innerHTML =
                                    '<img src="' + broker.logo_url + '" alt="">' +
                                    '<span>' + highlight(broker.name, query) + '</span>';
                                resultsBox.appendChild(link);
                            });
                        }

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
    }

    var searchInput = sheet.querySelector('[data-bc-tools-search]');
    if (searchInput) {
        initSearch(searchInput);
    }
})();
