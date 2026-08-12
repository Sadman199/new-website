document.addEventListener('DOMContentLoaded', function () {
    var drawer = document.getElementById('countryDrawer');
    var backdrop = document.getElementById('countryDrawerBackdrop');
    var closeBtn = document.getElementById('countryDrawerClose');
    var openBtn = document.getElementById('countrySelectorBtn');
    var navFlag = document.getElementById('countryNavFlag');
    var navLabel = document.getElementById('countryNavLabel');
    var searchInput = document.getElementById('countryDrawerSearch');
    var emptyState = document.getElementById('countryDrawerEmpty');
    var selected = drawer ? drawer.querySelector('.bc-country-option.is-selected') : null;
    var toast = document.getElementById('countrySuccessToast');
    var countryForms = drawer ? drawer.querySelectorAll('.bc-country-option-form') : [];
    var recommendedUrl = drawer ? drawer.dataset.recommendedUrl : null;
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (toast) {
        setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 4500);
    }

    function setBodyScroll(lock) {
        document.body.classList.toggle('overflow-hidden', lock);
    }

    function nearestFlagWidth(width) {
        var sizes = [20, 40, 80, 160, 320, 640];
        var closest = 40;
        var smallest = Infinity;
        sizes.forEach(function (size) {
            var diff = Math.abs(size - width);
            if (diff < smallest) {
                smallest = diff;
                closest = size;
            }
        });
        return closest;
    }

    function flagMarkup(code, width) {
        width = nearestFlagWidth(width || 20);
        var height = Math.max(14, Math.round(width * 0.75));
        if (code) {
            var base = 'https://flagcdn.com/w' + width + '/' + code + '.png';
            var retina = 'https://flagcdn.com/w' + nearestFlagWidth(width * 2) + '/' + code + '.png';
            return '<img src="' + base + '" srcset="' + retina + ' 2x" alt="" class="bc-flag-img" width="' + width + '" height="' + height + '" loading="lazy" decoding="async" aria-hidden="true">';
        }
        return '<svg class="bc-flag-globe" width="' + width + '" height="' + width + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="1.75"/><path stroke-width="1.75" d="M3 12h18M12 3c2.5 2.8 3.8 6.2 3.8 9s-1.3 6.2-3.8 9M12 3c-2.5 2.8-3.8 6.2-3.8 9s1.3 6.2 3.8 9"/></svg>';
    }

    function updateNavPreview(btn) {
        if (!btn) {
            return;
        }
        if (navFlag) {
            navFlag.innerHTML = flagMarkup(btn.dataset.code || '', 20);
        }
        if (navLabel) {
            navLabel.textContent = btn.dataset.shortcode || (btn.dataset.code ? btn.dataset.code.toUpperCase() : 'GL');
        }
        if (openBtn) {
            openBtn.setAttribute('aria-label', 'Select country: ' + (btn.dataset.name || 'Global'));
            openBtn.setAttribute('title', btn.dataset.name || 'Global');
        }
    }

    function markSelectedCountry(btn) {
        drawer?.querySelectorAll('.bc-country-option').forEach(function (option) {
            var isMatch = option === btn;
            option.classList.toggle('is-selected', isMatch);
            option.setAttribute('aria-selected', isMatch ? 'true' : 'false');
        });
    }

    function filterCountries(query) {
        var normalized = (query || '').trim().toLowerCase();
        var visible = 0;

        countryForms.forEach(function (form) {
            var btn = form.querySelector('.bc-country-option');
            if (!btn) {
                return;
            }
            var name = (btn.dataset.name || '').toLowerCase();
            var code = (btn.dataset.shortcode || '').toLowerCase();
            var match = !normalized || name.indexOf(normalized) !== -1 || code.indexOf(normalized) !== -1;
            form.classList.toggle('is-hidden', !match);
            if (match) {
                visible += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('is-hidden', visible > 0);
        }
    }

    function refreshRecommendedSection() {
        if (!recommendedUrl) {
            return Promise.resolve();
        }

        return fetch(recommendedUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'text/html',
            },
        })
            .then(function (response) {
                return response.text();
            })
            .then(function (html) {
                var container = document.querySelector('.bc-trust-board__primary');
                var current = document.getElementById('bcTrustRecommendedZone');
                if (!container) {
                    return;
                }

                if (current) {
                    if (html.trim()) {
                        current.outerHTML = html;
                    } else {
                        current.remove();
                    }
                    return;
                }

                if (html.trim()) {
                    container.insertAdjacentHTML('afterbegin', html);
                }
            })
            .catch(function () {
                /* Non-home pages may not have the recommended zone */
            });
    }

    function switchCountry(form, btn) {
        var body = new FormData(form);

        return fetch(form.action, {
            method: 'POST',
            body: body,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Country switch failed');
                }
                return response.json();
            })
            .then(function () {
                markSelectedCountry(btn);
                updateNavPreview(btn);
                return refreshRecommendedSection();
            })
            .then(function () {
                closeDrawer();
                showToast('Country updated! Recommended brokers refreshed for ' + (btn.dataset.name || 'your region') + '.');
            })
            .catch(function () {
                form.submit();
            });
    }

    function showToast(message) {
        var node = document.getElementById('countrySuccessToast');
        if (!node) {
            node = document.createElement('div');
            node.id = 'countrySuccessToast';
            node.className = 'bc-country-toast';
            node.setAttribute('role', 'status');
            node.setAttribute('aria-live', 'polite');
            document.body.appendChild(node);
        }
        node.textContent = message;
        node.classList.add('is-visible');
        setTimeout(function () {
            node.classList.remove('is-visible');
        }, 4500);
    }

    function openDrawer() {
        if (!drawer) {
            return;
        }
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        setBodyScroll(true);
        openBtn?.classList.add('is-active');
        openBtn?.setAttribute('aria-expanded', 'true');
        window.dispatchEvent(new CustomEvent('bc:country-drawer-open'));
        if (searchInput) {
            setTimeout(function () {
                searchInput.focus({ preventScroll: true });
            }, 120);
        }
    }

    function closeDrawer() {
        if (!drawer) {
            return;
        }
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        setBodyScroll(false);
        openBtn?.classList.remove('is-active');
        openBtn?.setAttribute('aria-expanded', 'false');
        if (searchInput) {
            searchInput.value = '';
            filterCountries('');
        }
    }

    openBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (drawer?.classList.contains('is-open')) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    closeBtn?.addEventListener('click', closeDrawer);
    backdrop?.addEventListener('click', closeDrawer);

    searchInput?.addEventListener('input', function () {
        filterCountries(searchInput.value);
    });

    countryForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var btn = form.querySelector('.bc-country-option');
            if (!btn) {
                return;
            }
            e.preventDefault();
            switchCountry(form, btn);
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && drawer?.classList.contains('is-open')) {
            closeDrawer();
        }
    });

    updateNavPreview(selected);

    window.bcCountryDrawer = { open: openDrawer, close: closeDrawer };
});
