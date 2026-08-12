(function () {
    'use strict';

    var dock = document.getElementById('bcSpotlightDock');
    if (!dock) {
        return;
    }

    var sourceBrokers = [];
    try {
        sourceBrokers = JSON.parse(dock.getAttribute('data-brokers') || '[]');
    } catch (error) {
        sourceBrokers = [];
    }

    if (!sourceBrokers.length) {
        dock.remove();
        return;
    }

    var autoplayMs = parseInt(dock.getAttribute('data-autoplay') || '5000', 10);
    var refreshMs = parseInt(dock.getAttribute('data-refresh') || '45000', 10);
    var peek = document.getElementById('bcSpotlightPeek');
    var panel = document.getElementById('bcSpotlightPanel');
    var track = dock.querySelector('[data-spotlight-track]');
    var dotsTarget = dock.querySelector('[data-spotlight-dots]');
    var reviewLink = dock.querySelector('[data-spotlight-review]');
    var visitLink = dock.querySelector('[data-spotlight-visit]');
    var logoPeek = dock.querySelector('[data-spotlight-logo]');
    var namePeek = dock.querySelector('[data-spotlight-name]');
    var metaPeek = dock.querySelector('[data-spotlight-meta]');
    var ratingPeek = dock.querySelector('[data-spotlight-rating]');

    var brokers = [];
    var index = 0;
    var autoplayTimer = null;
    var refreshTimer = null;
    var paused = false;

    function shuffle(list) {
        var copy = list.slice();
        for (var i = copy.length - 1; i > 0; i -= 1) {
            var j = Math.floor(Math.random() * (i + 1));
            var temp = copy[i];
            copy[i] = copy[j];
            copy[j] = temp;
        }
        return copy;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text == null ? '' : String(text);
        return div.innerHTML;
    }

    function featuresHtml(features) {
        if (!features || !features.length) {
            return '<span class="bc-spotlight-dock__chip"><i class="fas fa-info-circle" aria-hidden="true"></i>No feature data yet</span>';
        }

        return features.map(function (feature) {
            return '<span class="bc-spotlight-dock__chip"><i class="fas ' + escapeHtml(feature.icon) + '" aria-hidden="true"></i>' + escapeHtml(feature.label) + '</span>';
        }).join('');
    }

    function slideHtml(broker, slideIndex) {
        var rating = broker.rating !== null && broker.rating !== undefined
            ? '<span class="bc-spotlight-dock__score">' + broker.rating + '/5</span>'
            : '';
        var tagline = broker.top_feature
            ? '<p class="bc-spotlight-dock__tagline">' + escapeHtml(broker.top_feature) + '</p>'
            : '';

        return '<li class="bc-spotlight-dock__slide" data-slide-index="' + slideIndex + '" role="group" aria-roledescription="slide" aria-label="' + escapeHtml(broker.name) + '">' +
            '<header class="bc-spotlight-dock__head">' +
                '<div class="bc-spotlight-dock__brand">' +
                    '<span class="bc-spotlight-dock__logo"><img src="' + escapeHtml(broker.logo) + '" alt="' + escapeHtml(broker.name) + ' logo"></span>' +
                    '<div>' +
                        '<p class="bc-spotlight-dock__eyebrow">Broker spotlight</p>' +
                        '<h2 class="bc-spotlight-dock__name">' + escapeHtml(broker.name) + '</h2>' +
                        tagline +
                    '</div>' +
                '</div>' +
                '<div class="bc-spotlight-dock__head-actions">' + rating + '</div>' +
            '</header>' +
            '<div class="bc-spotlight-dock__features">' + featuresHtml(broker.features) + '</div>' +
        '</li>';
    }

    function rebuildTrack(startIndex) {
        if (!track) {
            return;
        }

        track.style.transition = 'none';
        track.innerHTML = brokers.map(function (broker, i) {
            return slideHtml(broker, i);
        }).join('');

        index = Math.max(0, Math.min(startIndex || 0, brokers.length - 1));
        track.offsetHeight;
        updateTrack(false);
        renderDots();
        syncPeek();
        syncActions();
    }

    function refreshRandom() {
        brokers = shuffle(sourceBrokers);
        var nextIndex = brokers.length > 1 ? Math.floor(Math.random() * brokers.length) : 0;

        if (track) {
            track.classList.add('is-refreshing');
            window.setTimeout(function () {
                rebuildTrack(nextIndex);
                track.classList.remove('is-refreshing');
            }, 220);
        } else {
            rebuildTrack(nextIndex);
        }
    }

    function updateTrack(animate) {
        if (!track) {
            return;
        }

        track.style.transition = animate === false ? 'none' : 'transform 0.55s cubic-bezier(0.4, 0, 0.2, 1)';
        track.style.transform = 'translate3d(-' + (index * 100) + '%, 0, 0)';
        renderDots();
        syncPeek();
        syncActions();
    }

    function syncPeek() {
        var broker = brokers[index];
        if (!broker) {
            return;
        }

        if (logoPeek) {
            logoPeek.innerHTML = '<img src="' + escapeHtml(broker.logo) + '" alt="' + escapeHtml(broker.name) + ' logo">';
        }

        if (namePeek) {
            namePeek.textContent = broker.name;
        }

        if (metaPeek) {
            var metaParts = [];
            if (broker.country) {
                metaParts.push(broker.country);
            }
            if (broker.feature_count) {
                metaParts.push(broker.feature_count + ' features');
            }
            metaPeek.textContent = metaParts.join(' · ') || 'Featured broker';
        }

        if (ratingPeek) {
            if (broker.rating === null || broker.rating === undefined) {
                ratingPeek.hidden = true;
                ratingPeek.textContent = '';
            } else {
                ratingPeek.hidden = false;
                ratingPeek.textContent = broker.rating + '/5';
            }
        }
    }

    function syncActions() {
        var broker = brokers[index];
        if (!broker) {
            return;
        }

        if (reviewLink) {
            reviewLink.href = broker.review_url;
        }

        if (visitLink) {
            if (broker.visit_url) {
                visitLink.href = broker.visit_url;
                visitLink.classList.remove('is-hidden');
            } else {
                visitLink.classList.add('is-hidden');
            }
        }
    }

    function renderDots() {
        if (!dotsTarget) {
            return;
        }

        dotsTarget.innerHTML = '';

        brokers.forEach(function (_, dotIndex) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'bc-spotlight-dock__dot' + (dotIndex === index ? ' is-active' : '');
            dot.setAttribute('aria-label', 'Show broker ' + (dotIndex + 1));
            dot.addEventListener('click', function () {
                goTo(dotIndex);
                restartAutoplay();
            });
            dotsTarget.appendChild(dot);
        });
    }

    function goTo(nextIndex, animate) {
        if (!brokers.length) {
            return;
        }

        index = (nextIndex + brokers.length) % brokers.length;
        updateTrack(animate !== false);
    }

    function next() {
        goTo(index + 1);
    }

    function prev() {
        goTo(index - 1);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            window.clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function startAutoplay() {
        stopAutoplay();
        if (paused || brokers.length < 2) {
            return;
        }

        autoplayTimer = window.setInterval(function () {
            if (!paused && !dock.classList.contains('is-hidden')) {
                next();
            }
        }, autoplayMs);
    }

    function restartAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    function stopRefresh() {
        if (refreshTimer) {
            window.clearInterval(refreshTimer);
            refreshTimer = null;
        }
    }

    function startRefresh() {
        stopRefresh();
        refreshTimer = window.setInterval(function () {
            if (!paused && !dock.classList.contains('is-hidden')) {
                refreshRandom();
            }
        }, refreshMs);
    }

    function expand() {
        dock.classList.add('is-expanded');
        panel.hidden = false;
        peek.setAttribute('aria-expanded', 'true');
        restartAutoplay();
    }

    function collapse() {
        dock.classList.remove('is-expanded');
        peek.setAttribute('aria-expanded', 'false');
        window.setTimeout(function () {
            if (!dock.classList.contains('is-expanded')) {
                panel.hidden = true;
            }
        }, 420);
    }

    function hideDock() {
        dock.classList.add('is-hidden');
        stopAutoplay();
    }

    function showDock() {
        dock.classList.remove('is-hidden');
        restartAutoplay();
    }

    peek.addEventListener('click', expand);

    dock.querySelectorAll('[data-spotlight-collapse]').forEach(function (button) {
        button.addEventListener('click', collapse);
    });

    dock.querySelector('[data-spotlight-prev]').addEventListener('click', function () {
        prev();
        restartAutoplay();
    });

    dock.querySelector('[data-spotlight-next]').addEventListener('click', function () {
        next();
        restartAutoplay();
    });

    dock.addEventListener('mouseenter', function () {
        paused = true;
        stopAutoplay();
    });

    dock.addEventListener('mouseleave', function () {
        paused = false;
        startAutoplay();
    });

    dock.addEventListener('focusin', function () {
        paused = true;
        stopAutoplay();
    });

    dock.addEventListener('focusout', function () {
        if (!dock.contains(document.activeElement)) {
            paused = false;
            startAutoplay();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && dock.classList.contains('is-expanded')) {
            collapse();
        }
    });

    document.addEventListener('click', function (event) {
        if (!dock.classList.contains('is-expanded')) {
            return;
        }

        if (!dock.contains(event.target)) {
            collapse();
        }
    });

    window.addEventListener('bc:country-drawer-open', function () {
        collapse();
        hideDock();
    });

    document.addEventListener('bc:quick-access-open', function () {
        collapse();
        hideDock();
    });

    document.addEventListener('bc:quick-access-close', showDock);

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            stopAutoplay();
        } else {
            startAutoplay();
        }
    });

    brokers = shuffle(sourceBrokers);
    rebuildTrack(0);
    startAutoplay();
    startRefresh();
})();
