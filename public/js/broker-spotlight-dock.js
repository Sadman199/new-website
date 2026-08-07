(function () {
    'use strict';

    var dock = document.getElementById('bcSpotlightDock');
    if (!dock) {
        return;
    }

    var brokers = [];
    try {
        brokers = JSON.parse(dock.getAttribute('data-brokers') || '[]');
    } catch (error) {
        brokers = [];
    }

    if (!brokers.length) {
        dock.remove();
        return;
    }

    var peek = document.getElementById('bcSpotlightPeek');
    var panel = document.getElementById('bcSpotlightPanel');
    var index = 0;

    var logoTargets = dock.querySelectorAll('[data-spotlight-logo]');
    var nameTargets = dock.querySelectorAll('[data-spotlight-name]');
    var metaTarget = dock.querySelector('[data-spotlight-meta]');
    var ratingTargets = dock.querySelectorAll('[data-spotlight-rating]');
    var taglineTarget = dock.querySelector('[data-spotlight-tagline]');
    var featuresTarget = dock.querySelector('[data-spotlight-features]');
    var dotsTarget = dock.querySelector('[data-spotlight-dots]');
    var reviewLink = dock.querySelector('[data-spotlight-review]');
    var visitLink = dock.querySelector('[data-spotlight-visit]');

    function setLogo(target, url, name) {
        target.innerHTML = '<img src="' + url + '" alt="' + name + ' logo">';
    }

    function setRating(targets, rating) {
        targets.forEach(function (target) {
            if (rating === null || rating === undefined) {
                target.hidden = true;
                target.textContent = '';
                return;
            }

            target.hidden = false;
            target.textContent = rating + '/5';
        });
    }

    function renderFeatures(features) {
        if (!featuresTarget) {
            return;
        }

        featuresTarget.innerHTML = '';

        if (!features || !features.length) {
            featuresTarget.innerHTML = '<span class="bc-spotlight-dock__chip"><i class="fas fa-info-circle" aria-hidden="true"></i>No feature data yet</span>';
            return;
        }

        features.forEach(function (feature) {
            var chip = document.createElement('span');
            chip.className = 'bc-spotlight-dock__chip';
            chip.innerHTML = '<i class="fas ' + feature.icon + '" aria-hidden="true"></i>' + feature.label;
            featuresTarget.appendChild(chip);
        });
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
                showBroker(dotIndex);
            });
            dotsTarget.appendChild(dot);
        });
    }

    function showBroker(nextIndex) {
        index = (nextIndex + brokers.length) % brokers.length;
        var broker = brokers[index];

        logoTargets.forEach(function (target) {
            setLogo(target, broker.logo, broker.name);
        });

        nameTargets.forEach(function (target) {
            target.textContent = broker.name;
        });

        if (metaTarget) {
            var metaParts = [];
            if (broker.country) {
                metaParts.push(broker.country);
            }
            if (broker.feature_count) {
                metaParts.push(broker.feature_count + ' features');
            }
            metaTarget.textContent = metaParts.join(' · ') || 'Featured broker';
        }

        setRating(Array.prototype.slice.call(ratingTargets), broker.rating);

        if (taglineTarget) {
            if (broker.top_feature) {
                taglineTarget.hidden = false;
                taglineTarget.textContent = broker.top_feature;
            } else {
                taglineTarget.hidden = true;
                taglineTarget.textContent = '';
            }
        }

        renderFeatures(broker.features);

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

        renderDots();
    }

    function expand() {
        dock.classList.add('is-expanded');
        panel.hidden = false;
        peek.setAttribute('aria-expanded', 'true');
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
    }

    function showDock() {
        dock.classList.remove('is-hidden');
    }

    peek.addEventListener('click', expand);

    dock.querySelectorAll('[data-spotlight-collapse]').forEach(function (button) {
        button.addEventListener('click', collapse);
    });

    dock.querySelector('[data-spotlight-prev]').addEventListener('click', function () {
        showBroker(index - 1);
    });

    dock.querySelector('[data-spotlight-next]').addEventListener('click', function () {
        showBroker(index + 1);
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

    showBroker(0);
})();
