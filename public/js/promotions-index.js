(function () {
    'use strict';

    var loadMoreWrap = document.getElementById('bpr-load-more');
    var loadMoreBtn = document.getElementById('bpr-load-more-btn');
    var grid = document.getElementById('bpr-grid');
    var loadedCountEl = document.getElementById('bpr-loaded-count');
    var totalCountEl = document.getElementById('bpr-total-count');
    var showingCountEl = document.getElementById('bpr-showing-count');
    var sortSelect = document.getElementById('bpr-sort-select');
    var featuredToggle = document.getElementById('bpr-featured-toggle');
    var filterBar = document.querySelector('.bpr-filters__bar');

    function navigateTo(url) {
        if (url) {
            window.location.href = url;
        }
    }

    function appendSortParam(baseUrl, sortValue) {
        try {
            var url = new URL(baseUrl, window.location.origin);
            if (sortValue && sortValue !== 'featured') {
                url.searchParams.set('sort', sortValue);
            } else {
                url.searchParams.delete('sort');
            }
            return url.pathname + url.search;
        } catch (error) {
            return baseUrl;
        }
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            navigateTo(appendSortParam(sortSelect.getAttribute('data-base-url'), sortSelect.value));
        });
    }

    if (featuredToggle) {
        featuredToggle.addEventListener('change', function () {
            navigateTo(featuredToggle.checked
                ? featuredToggle.getAttribute('data-featured-url')
                : featuredToggle.getAttribute('data-base-url'));
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
    }

    document.querySelectorAll('.bpr-faq__question').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.closest('.bpr-faq__item');
            var answer = item ? item.querySelector('.bpr-faq__answer') : null;
            var isOpen = item && item.classList.contains('is-open');

            document.querySelectorAll('.bpr-faq__item.is-open').forEach(function (openItem) {
                openItem.classList.remove('is-open');
                var openButton = openItem.querySelector('.bpr-faq__question');
                var openAnswer = openItem.querySelector('.bpr-faq__answer');
                if (openButton) {
                    openButton.setAttribute('aria-expanded', 'false');
                }
                if (openAnswer) {
                    openAnswer.hidden = true;
                }
            });

            if (!isOpen && item && answer) {
                item.classList.add('is-open');
                button.setAttribute('aria-expanded', 'true');
                answer.hidden = false;
            }
        });
    });

})();
