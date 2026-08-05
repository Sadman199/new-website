(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initSearchTabs();
        initSearchForm();
        initPickTabs();
    });

    function initSearchTabs() {
        var form = document.getElementById('bcHomeSearchForm');
        if (!form) return;

        var tabs = form.querySelectorAll('[data-bc-tab]');
        var panels = form.querySelectorAll('[data-bc-panel]');
        var nameInput = form.querySelector('input[name="q"]');
        var filterFields = form.querySelectorAll('[data-bc-panel="filter"] select');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var mode = tab.getAttribute('data-bc-tab');
                tabs.forEach(function (t) {
                    var active = t === tab;
                    t.classList.toggle('is-active', active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                panels.forEach(function (p) {
                    p.classList.toggle('is-hidden', p.getAttribute('data-bc-panel') !== mode);
                });

                var isName = mode === 'name';
                if (nameInput) {
                    nameInput.disabled = !isName;
                }
                filterFields.forEach(function (el) {
                    el.disabled = isName;
                });
            });
        });

        if (nameInput) {
            nameInput.disabled = false;
        }
        filterFields.forEach(function (el) {
            el.disabled = true;
        });
    }

    function initSearchForm() {
        var form = document.getElementById('bcHomeSearchForm');
        if (!form) return;

        form.addEventListener('submit', function () {
            var namePanel = form.querySelector('[data-bc-panel="name"]');
            var isName = namePanel && !namePanel.classList.contains('is-hidden');

            form.querySelectorAll('input, select').forEach(function (el) {
                if (!el.name || el.disabled) return;
                if (isName && el.name !== 'q') {
                    el.removeAttribute('name');
                    return;
                }
                if (!isName && el.name === 'q') {
                    el.removeAttribute('name');
                    return;
                }
                if ((el.value || '').trim() === '') {
                    el.removeAttribute('name');
                }
            });
        });
    }

    function initPickTabs() {
        var tabs = document.querySelectorAll('[data-bc-pick]');
        var panels = document.querySelectorAll('[data-bc-pick-panel]');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var key = tab.getAttribute('data-bc-pick');
                tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
                panels.forEach(function (p) {
                    p.classList.toggle('is-active', p.getAttribute('data-bc-pick-panel') === key);
                });
            });
        });
    }
})();
