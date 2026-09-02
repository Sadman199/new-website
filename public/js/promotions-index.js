(function () {
    'use strict';

    var loadMoreWrap = document.getElementById('bpr-load-more');
    var loadMoreBtn = document.getElementById('bpr-load-more-btn');
    var grid = document.getElementById('bpr-grid');
    var loadedCountEl = document.getElementById('bpr-loaded-count');
    var totalCountFooterEl = document.getElementById('bpr-total-count-footer');
    var showingCountEl = document.getElementById('bpr-showing-count');
    var totalCountEl = document.getElementById('bpr-total-count');
    var filterBar = document.getElementById('bpr-toolbar');
    var searchInput = document.getElementById('bpr-search-input');
    var filterForm = document.getElementById('bpr-filter-form');

    if (searchInput && filterForm) {
        filterForm.addEventListener('submit', function (event) {
            if (!searchInput.value.trim()) {
                searchInput.removeAttribute('name');
            }
        });
    }

    if (filterBar && 'IntersectionObserver' in window) {
        var sentinel = document.createElement('div');
        sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none;';
        filterBar.parentElement.insertBefore(sentinel, filterBar);
        var pinObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                filterBar.classList.toggle('is-pinned', !entry.isIntersecting);
            });
        }, { threshold: 1, rootMargin: '-1px 0px 0px 0px' });
        pinObserver.observe(sentinel);
    }

    if (loadMoreBtn && grid) {
        loadMoreBtn.addEventListener('click', function () {
            var endpoint = loadMoreBtn.getAttribute('data-endpoint');
            var type = loadMoreBtn.getAttribute('data-type');
            var offset = parseInt(loadMoreBtn.getAttribute('data-offset'), 10) || 0;
            var sort = loadMoreBtn.getAttribute('data-sort') || 'featured';
            var featured = loadMoreBtn.getAttribute('data-featured') === '1';
            var search = loadMoreBtn.getAttribute('data-search') || '';
            var broker = loadMoreBtn.getAttribute('data-broker') || '';
            var category = loadMoreBtn.getAttribute('data-category') || '';
            var status = loadMoreBtn.getAttribute('data-status') || '';
            var maxMinDeposit = loadMoreBtn.getAttribute('data-max-min-deposit') || '';

            if (!endpoint) {
                return;
            }

            loadMoreBtn.classList.add('is-loading');
            loadMoreBtn.textContent = 'Loading…';

            var params = new URLSearchParams({
                partial: '1',
                type: type,
                offset: String(offset)
            });

            if (sort && sort !== 'featured') {
                params.set('sort', sort);
            }
            if (featured) {
                params.set('featured', '1');
            }
            if (search.trim()) {
                params.set('q', search.trim());
            }
            if (broker) {
                params.set('broker', broker);
            }
            if (category) {
                params.set('category', category);
            }
            if (status) {
                params.set('status', status);
            }
            if (maxMinDeposit) {
                params.set('max_min_deposit', maxMinDeposit);
            }

            fetch(endpoint + '?' + params.toString(), {
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

                    temp.querySelectorAll('.bpr-offer').forEach(function (row) {
                        grid.appendChild(row);
                    });

                    var meta = temp.querySelector('[data-loaded-count]');
                    if (meta) {
                        var loaded = meta.getAttribute('data-loaded-count');
                        if (loadedCountEl) {
                            loadedCountEl.textContent = loaded;
                        }
                        if (showingCountEl) {
                            showingCountEl.textContent = loaded;
                        }
                        if (totalCountEl) {
                            totalCountEl.textContent = meta.getAttribute('data-total-count');
                        }
                        if (totalCountFooterEl) {
                            totalCountFooterEl.textContent = meta.getAttribute('data-total-count');
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
    }
})();
