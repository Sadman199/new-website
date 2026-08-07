(function () {
    'use strict';

    var loadMoreWrap = document.getElementById('bpr-load-more');
    var loadMoreBtn = document.getElementById('bpr-load-more-btn');
    var grid = document.getElementById('bpr-grid');
    var loadedCountEl = document.getElementById('bpr-loaded-count');
    var totalCountEl = document.getElementById('bpr-total-count');
    var showingCountEl = document.getElementById('bpr-showing-count');

    if (!loadMoreBtn || !grid) {
        return;
    }

    loadMoreBtn.addEventListener('click', function () {
        var endpoint = loadMoreBtn.getAttribute('data-endpoint');
        var type = loadMoreBtn.getAttribute('data-type');
        var offset = parseInt(loadMoreBtn.getAttribute('data-offset'), 10) || 0;

        if (!endpoint) {
            return;
        }

        loadMoreBtn.classList.add('is-loading');
        loadMoreBtn.textContent = 'Loading…';

        var url = endpoint + '?partial=1&type=' + encodeURIComponent(type) + '&offset=' + encodeURIComponent(String(offset));

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            credentials: 'same-origin'
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Request failed');
                }
                return res.text();
            })
            .then(function (html) {
                var temp = document.createElement('div');
                temp.innerHTML = html.trim();

                temp.querySelectorAll('.bpr-card').forEach(function (card) {
                    grid.appendChild(card);
                });

                var meta = temp.querySelector('[data-loaded-count]');
                if (meta) {
                    if (loadedCountEl) {
                        loadedCountEl.textContent = meta.getAttribute('data-loaded-count');
                    }
                    if (showingCountEl) {
                        showingCountEl.textContent = meta.getAttribute('data-loaded-count');
                    }
                    if (totalCountEl) {
                        totalCountEl.textContent = meta.getAttribute('data-total-count');
                    }
                    loadMoreBtn.setAttribute('data-offset', meta.getAttribute('data-next-offset'));

                    if (meta.getAttribute('data-has-more') !== '1' && loadMoreWrap) {
                        loadMoreWrap.classList.add('is-hidden');
                    }
                }

                loadMoreBtn.classList.remove('is-loading');
                loadMoreBtn.textContent = 'Load more promotions';
            })
            .catch(function () {
                loadMoreBtn.classList.remove('is-loading');
                loadMoreBtn.textContent = 'Load more promotions';
            });
    });
})();
