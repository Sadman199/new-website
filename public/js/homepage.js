(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initHeroFilters();
        initSearchForm();
        initFinderDropdowns();
        initPickTabs();
    });

    function initHeroFilters() {
        var toggle = document.getElementById('bcHeroFiltersToggle');
        var panel = document.getElementById('bcHeroFilters');
        if (!toggle || !panel) {
            return;
        }

        var filterInputs = panel.querySelectorAll('[data-bc-dropdown-input]');
        var filterTriggers = panel.querySelectorAll('[data-bc-dropdown-trigger]');

        toggle.addEventListener('click', function () {
            var open = panel.hasAttribute('hidden');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.classList.toggle('is-open', open);

            if (open) {
                panel.removeAttribute('hidden');
            } else {
                panel.setAttribute('hidden', '');
                closeAllDropdowns();
            }

            filterInputs.forEach(function (el) {
                el.disabled = !open;
            });
            filterTriggers.forEach(function (el) {
                el.disabled = !open;
            });
        });
    }

    function initSearchForm() {
        var form = document.getElementById('bcHomeSearchForm');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            var panel = document.getElementById('bcHeroFilters');
            var filtersOpen = panel && !panel.hasAttribute('hidden');
            var submitter = e.submitter;
            var usedFilters = filtersOpen && submitter && submitter.classList.contains('bc-hero__search-btn--filter');

            form.querySelectorAll('input').forEach(function (el) {
                if (!el.name || el.disabled) return;

                if (usedFilters && el.name === 'q') {
                    el.removeAttribute('name');
                    return;
                }

                if (!usedFilters && el.name !== 'q') {
                    el.removeAttribute('name');
                    return;
                }

                if ((el.value || '').trim() === '') {
                    el.removeAttribute('name');
                }
            });
        });
    }

    function initFinderDropdowns() {
        var fields = document.querySelectorAll('[data-bc-dropdown]');
        if (!fields.length) return;

        fields.forEach(function (field) {
            var trigger = field.querySelector('[data-bc-dropdown-trigger]');
            var menu = field.querySelector('[data-bc-dropdown-menu]');
            var hidden = field.querySelector('[data-bc-dropdown-input]');
            var valueEl = field.querySelector('.bc-finder__dropdown-value');
            var options = field.querySelectorAll('[data-bc-dropdown-option]');

            if (!trigger || !menu) return;

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (trigger.disabled) return;

                var willOpen = !field.classList.contains('is-open');
                closeAllDropdowns();

                if (willOpen) {
                    field.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });

            options.forEach(function (option) {
                option.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var value = option.getAttribute('data-value') || '';
                    var label = option.textContent.trim();

                    if (hidden) {
                        hidden.value = value;
                    }
                    if (valueEl) {
                        valueEl.textContent = label;
                    }

                    options.forEach(function (item) {
                        item.classList.toggle('is-selected', item === option);
                        item.setAttribute('aria-selected', item === option ? 'true' : 'false');
                    });

                    field.classList.remove('is-open');
                    trigger.setAttribute('aria-expanded', 'false');
                });
            });
        });

        document.addEventListener('click', closeAllDropdowns);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });
    }

    function closeAllDropdowns() {
        document.querySelectorAll('[data-bc-dropdown].is-open').forEach(function (field) {
            field.classList.remove('is-open');
            var trigger = field.querySelector('[data-bc-dropdown-trigger]');
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function initPickTabs() {
        var tabs = document.querySelectorAll('[data-bc-pick]');
        var panels = document.querySelectorAll('[data-bc-pick-panel]');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var key = tab.getAttribute('data-bc-pick');

                tabs.forEach(function (t) {
                    var active = t === tab;
                    t.classList.toggle('is-active', active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                panels.forEach(function (p) {
                    var active = p.getAttribute('data-bc-pick-panel') === key;
                    p.classList.toggle('is-active', active);
                    if (active) {
                        p.removeAttribute('hidden');
                    } else {
                        p.setAttribute('hidden', '');
                    }
                });
            });
        });
    }

})();
