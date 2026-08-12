document.addEventListener('DOMContentLoaded', function () {
    var stripChangeBtn = document.getElementById('countryStripChangeBtn');
    stripChangeBtn?.addEventListener('click', function () {
        if (window.bcCountryDrawer && typeof window.bcCountryDrawer.open === 'function') {
            window.bcCountryDrawer.open();
        }
    });
});
