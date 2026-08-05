(function () {
    'use strict';

    var tabsNav = document.getElementById('bliTabs');
    if (!tabsNav) {
        return;
    }

    var scrollContainer = tabsNav.querySelector('.bli-tabs__scroll');
    var activeTab = tabsNav.querySelector('.bli-tab.is-active');

    if (activeTab && scrollContainer) {
        var left = activeTab.offsetLeft - (scrollContainer.clientWidth / 2) + (activeTab.offsetWidth / 2);
        scrollContainer.scrollLeft = Math.max(0, left);
    }

    tabsNav.addEventListener('keydown', function (event) {
        var tabs = Array.prototype.slice.call(tabsNav.querySelectorAll('.bli-tab'));
        var currentIndex = tabs.findIndex(function (tab) {
            return tab.classList.contains('is-active');
        });

        if (currentIndex === -1) {
            return;
        }

        var nextIndex = currentIndex;

        if (event.key === 'ArrowRight') {
            nextIndex = Math.min(tabs.length - 1, currentIndex + 1);
        } else if (event.key === 'ArrowLeft') {
            nextIndex = Math.max(0, currentIndex - 1);
        } else {
            return;
        }

        event.preventDefault();
        tabs[nextIndex].focus();
        tabs[nextIndex].click();
    });
})();
