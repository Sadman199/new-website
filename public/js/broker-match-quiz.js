(function () {
    'use strict';

    var root = document.getElementById('bcMatchQuiz');
    if (!root) {
        return;
    }

    var config = {};
    try {
        config = JSON.parse(root.getAttribute('data-config') || '{}');
    } catch (e) {
        return;
    }

    var steps = config.steps || [];
    var options = config.options || {};
    var endpoint = root.getAttribute('data-endpoint');
    var csrf = root.getAttribute('data-csrf');
    var brokerCount = parseInt(root.getAttribute('data-broker-count') || '0', 10);

    var wizardEl = document.getElementById('bcMatchWizard');
    var resultsEl = document.getElementById('bcMatchResults');
    var loadingEl = document.getElementById('bcMatchLoading');
    var navEl = document.getElementById('bcMatchNav');
    var backBtn = document.getElementById('bcMatchBack');
    var nextBtn = document.getElementById('bcMatchNext');
    var skipBtn = document.getElementById('bcMatchSkip');
    var progressFill = document.getElementById('bcMatchProgressFill');
    var progressLabel = document.getElementById('bcMatchProgressLabel');
    var resultsGrid = document.getElementById('bcMatchResultsGrid');
    var resultsSummary = document.getElementById('bcMatchResultsSummary');
    var resultsMeta = document.getElementById('bcMatchResultsMeta');
    var seeAllLink = document.getElementById('bcMatchSeeAll');
    var restartBtn = document.getElementById('bcMatchRestart');
    var copyLinkBtn = document.getElementById('bcMatchCopyLink');
    var compareTopBtn = document.getElementById('bcMatchCompareTop');
    var savedBanner = document.getElementById('bcMatchSavedBanner');
    var loadSavedBtn = document.getElementById('bcMatchLoadSaved');
    var dismissSavedBtn = document.getElementById('bcMatchDismissSaved');
    var RESULTS_KEY = 'brokerMatchResults';
    var profileTitle = document.getElementById('bcMatchProfileTitle');
    var profileTags = document.getElementById('bcMatchProfileTags');
    var loadingTitle = document.getElementById('bcMatchLoadingTitle');
    var loadingSub = document.getElementById('bcMatchLoadingSub');

    var currentStep = 0;
    var answers = {};
    var loadingMessages = [
        { title: 'Scanning broker database…', sub: 'Checking ' + (brokerCount || '100') + '+ regulated brokers' },
        { title: 'Scoring safety & regulation…', sub: 'Tier, licences & investor protection' },
        { title: 'Matching trading costs…', sub: 'Spreads, commissions & fee profiles' },
        { title: 'Ranking your top fits…', sub: 'Personalising results for your profile' },
    ];
    var loadingTimer = null;

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text == null ? '' : String(text);
        return div.innerHTML;
    }

    function stepField(step) {
        return step.field || step.id;
    }

    function currentValue(step) {
        var field = stepField(step);
        return step.type === 'multi' ? (answers[field] || []) : (answers[field] || '');
    }

    function isStepValid(step) {
        var value = currentValue(step);
        if (step.type === 'multi') {
            if (step.optional && value.length === 0) {
                return true;
            }
            return value.length >= (step.min != null ? step.min : 1);
        }
        return value !== '';
    }

    function labelFor(field, value) {
        var list = options[field] || [];
        for (var i = 0; i < list.length; i++) {
            if (list[i].value === value) {
                return list[i].label;
            }
        }
        return value;
    }

    function renderOptionIcon(opt) {
        if (opt.flag_url) {
            return '<img class="bc-match__option-flag" src="' + escapeHtml(opt.flag_url) + '" alt="" width="22" height="16" loading="lazy" decoding="async">';
        }
        if (opt.icon) {
            return '<span class="bc-match__option-icon" aria-hidden="true">' + opt.icon + '</span>';
        }
        return '';
    }

    function updateProfilePreview() {
        var tags = [];
        if (answers.country) {
            tags.push(labelFor('country', answers.country));
        }
        (answers.markets || []).slice(0, 3).forEach(function (m) {
            tags.push(labelFor('markets', m));
        });

        if (profileTitle) {
            if (!answers.experience) {
                profileTitle.textContent = 'Your profile';
            } else {
                profileTitle.textContent = labelFor('experience', answers.experience);
            }
        }

        if (profileTags) {
            if (!tags.length) {
                profileTags.innerHTML = '<li class="bc-match__profile-empty">Selections appear here</li>';
            } else {
                profileTags.innerHTML = tags.map(function (t) {
                    return '<li>' + escapeHtml(t) + '</li>';
                }).join('');
            }
        }
    }

    function updateProgress() {
        var pct = steps.length ? ((currentStep + 1) / steps.length) * 100 : 0;
        if (progressFill) {
            progressFill.style.width = pct + '%';
        }
        if (progressLabel) {
            progressLabel.textContent = (currentStep + 1) + ' / ' + steps.length;
        }
        updateProfilePreview();
    }

    function renderStep() {
        var step = steps[currentStep];
        if (!step || !wizardEl) {
            return;
        }

        var field = stepField(step);
        var stepOptions = options[field] || [];
        var selected = currentValue(step);
        var gridClass = 'bc-match__options';

        if (field === 'deposit') {
            gridClass += ' bc-match__options--tiles';
        } else if (field === 'markets' || field === 'extras') {
            gridClass += ' bc-match__options--grid bc-match__options--markets';
        }

        var html = '<div class="bc-match__step">' +
            (step.icon ? '<span class="bc-match__step-icon" aria-hidden="true">' + step.icon + '</span>' : '') +
            '<h3 class="bc-match__step-title">' + escapeHtml(step.title) + '</h3>' +
            '<p class="bc-match__step-subtitle">' + escapeHtml(step.subtitle || '') + '</p>';

        if (step.searchable) {
            html += '<input type="search" class="bc-match__search" id="bcMatchCountrySearch" placeholder="Search country or region…" autocomplete="off">';
        }

        html += '<div class="' + gridClass + '" id="bcMatchOptions">';

        stepOptions.forEach(function (opt) {
            var isSelected = step.type === 'multi'
                ? selected.indexOf(opt.value) !== -1
                : selected === opt.value;
            var tileClass = field === 'deposit' ? ' bc-match__option--tile' : '';

            html += '<button type="button" class="bc-match__option' + (isSelected ? ' is-selected' : '') + tileClass + '" data-value="' + escapeHtml(opt.value) + '">';

            html += renderOptionIcon(opt);

            html += '<span class="bc-match__option-body">' +
                '<span class="bc-match__option-label">' + escapeHtml(opt.label) + '</span>';

            if (opt.hint) {
                html += '<span class="bc-match__option-hint">' + escapeHtml(opt.hint) + '</span>';
            }
            if (opt.desc) {
                html += '<span class="bc-match__option-desc">' + escapeHtml(opt.desc) + '</span>';
            }

            html += '</span>';

            if (step.type === 'multi') {
                html += '<span class="bc-match__option-check" aria-hidden="true"></span>';
            }

            html += '</button>';
        });

        html += '</div></div>';
        wizardEl.innerHTML = html;

        var optionsEl = document.getElementById('bcMatchOptions');
        if (optionsEl) {
            optionsEl.querySelectorAll('.bc-match__option').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var value = btn.getAttribute('data-value');
                    if (step.type === 'multi') {
                        var list = (answers[field] || []).slice();
                        var idx = list.indexOf(value);
                        if (idx === -1) {
                            list.push(value);
                        } else {
                            list.splice(idx, 1);
                        }
                        answers[field] = list;
                    } else {
                        answers[field] = value;
                    }
                    renderStep();
                    updateNav();
                });
            });
        }

        var searchEl = document.getElementById('bcMatchCountrySearch');
        if (searchEl && optionsEl) {
            searchEl.addEventListener('input', function () {
                var q = searchEl.value.trim().toLowerCase();
                optionsEl.querySelectorAll('.bc-match__option').forEach(function (btn) {
                    var text = btn.textContent.toLowerCase();
                    btn.style.display = !q || text.indexOf(q) !== -1 ? '' : 'none';
                });
            });
        }

        updateProgress();
        updateNav();
    }

    function updateNav() {
        var step = steps[currentStep];
        if (backBtn) {
            backBtn.disabled = currentStep === 0;
        }
        if (skipBtn) {
            skipBtn.classList.toggle('is-hidden', !(step && step.optional));
        }
        if (nextBtn) {
            var isLast = currentStep >= steps.length - 1;
            nextBtn.innerHTML = isLast
                ? 'Reveal my matches <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.59L7.03 9.28a.75.75 0 0 0-1.06 1.06l3.22 3.22a.75.75 0 0 0 1.06 0l3.22-3.22a.75.75 0 1 0-1.06-1.06l-2.22 2.22V6.75Z" clip-rule="evenodd"/></svg>'
                : 'Continue <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>';
            nextBtn.disabled = !isStepValid(step);
        }
    }

    function goNext() {
        var step = steps[currentStep];
        if (!isStepValid(step)) {
            return;
        }
        if (currentStep >= steps.length - 1) {
            fetchRecommendations();
            return;
        }
        currentStep += 1;
        renderStep();
    }

    function showLoading(on) {
        if (loadingEl) {
            loadingEl.classList.toggle('is-hidden', !on);
        }
        if (wizardEl) {
            wizardEl.classList.toggle('is-hidden', on);
        }
        if (navEl) {
            navEl.classList.toggle('is-hidden', on);
        }

        if (on) {
            var i = 0;
            loadingTimer = window.setInterval(function () {
                var msg = loadingMessages[i % loadingMessages.length];
                if (loadingTitle) {
                    loadingTitle.textContent = msg.title;
                }
                if (loadingSub) {
                    loadingSub.textContent = msg.sub;
                }
                i += 1;
            }, 900);
        } else if (loadingTimer) {
            window.clearInterval(loadingTimer);
            loadingTimer = null;
        }
    }

    function showResults(on) {
        if (resultsEl) {
            resultsEl.classList.toggle('is-hidden', !on);
        }
        if (wizardEl) {
            wizardEl.classList.toggle('is-hidden', on);
        }
        if (navEl) {
            navEl.classList.toggle('is-hidden', on);
        }
    }

    function scoreRing(percent) {
        var circumference = 2 * Math.PI * 16;
        var offset = circumference - (circumference * percent / 100);
        return '<div class="bc-match__result-score">' +
            '<svg viewBox="0 0 36 36" aria-hidden="true">' +
            '<circle class="bc-match__ring-bg" cx="18" cy="18" r="16"/>' +
            '<circle class="bc-match__ring-fill" cx="18" cy="18" r="16" stroke-dasharray="' + circumference + '" stroke-dashoffset="' + circumference + '" data-target="' + offset + '"/>' +
            '</svg><strong>' + percent + '%</strong></div>';
    }

    function renderBreakdown(breakdown, labels) {
        if (!breakdown) {
            return '';
        }
        labels = labels || {};
        return '<div class="bc-match__breakdown">' + Object.keys(breakdown).map(function (key) {
            var val = breakdown[key];
            return '<div class="bc-match__breakdown-row">' +
                '<span>' + escapeHtml(labels[key] || key) + '</span>' +
                '<div class="bc-match__breakdown-bar"><i style="width:' + val + '%"></i></div>' +
                '<span>' + val + '</span></div>';
        }).join('') + '</div>';
    }

    function animateRings() {
        document.querySelectorAll('.bc-match__ring-fill[data-target]').forEach(function (circle) {
            window.requestAnimationFrame(function () {
                circle.style.strokeDashoffset = circle.getAttribute('data-target');
            });
        });
    }

    function renderReasons(reasons) {
        if (!reasons || !reasons.length) {
            return '';
        }
        return '<ul class="bc-match__reasons">' + reasons.map(function (r) {
            return '<li>' + escapeHtml(r) + '</li>';
        }).join('') + '</ul>';
    }

    function renderPerformance(metrics) {
        if (!metrics || !metrics.length) {
            return '';
        }
        return '<div class="bc-match__perf">' + metrics.map(function (m) {
            return '<span class="bc-match__perf-chip"><strong>' + escapeHtml(m.display || '') + '</strong> ' + escapeHtml(m.label || '') + '</span>';
        }).join('') + '</div>';
    }

    function renderResults(data, options) {
        options = options || {};
        if (!resultsGrid) {
            return;
        }

        if (resultsSummary) {
            resultsSummary.textContent = data.summary || '';
        }
        if (resultsMeta && data.meta) {
            resultsMeta.textContent = 'Evaluated ' + (data.meta.evaluated || brokerCount) + ' brokers · Ranked by 5 fit dimensions';
        }
        if (seeAllLink) {
            seeAllLink.href = data.match_url || '#';
        }
        if (compareTopBtn) {
            if (data.compare_url) {
                compareTopBtn.href = data.compare_url;
                compareTopBtn.classList.remove('is-hidden');
            } else {
                compareTopBtn.classList.add('is-hidden');
            }
        }

        if (options.persist !== false) {
            saveResults(data);
        }

        if (profileTitle && data.profile) {
            profileTitle.textContent = data.profile.title || 'Your profile';
        }
        if (profileTags && data.profile && data.profile.tags) {
            profileTags.innerHTML = data.profile.tags.map(function (t) {
                return '<li>' + escapeHtml(t) + '</li>';
            }).join('');
        }

        var labels = (data.meta && data.meta.dimension_labels) || {};

        resultsGrid.innerHTML = (data.brokers || []).map(function (broker) {
            var featured = broker.is_best_match ? ' is-featured' : '';
            var logo = broker.logo
                ? '<img src="' + escapeHtml(broker.logo) + '" alt="">'
                : escapeHtml((broker.name || '?').charAt(0));
            var ring = scoreRing(broker.match_percent || 0);

            if (broker.is_best_match) {
                return '<article class="bc-match__result' + featured + '">' +
                    '<div class="bc-match__result-hero">' +
                    '<div class="bc-match__result-logo">' + logo + '</div>' +
                    '<div class="bc-match__result-hero-body">' +
                    '<span class="bc-match__result-badge">Best match</span>' +
                    '<a href="' + escapeHtml(broker.review_url || '#') + '" class="bc-match__result-name">' + escapeHtml(broker.name) + '</a>' +
                    '<p class="bc-match__result-fit">' + escapeHtml(broker.profile_fit || '') + '</p>' +
                    '</div>' + ring + '</div>' +
                    '<p class="bc-match__result-meta">' + escapeHtml(broker.regulation_summary || '') + ' · Min ' + escapeHtml(broker.minimum_deposit || '—') +
                    (broker.rating ? ' · ★ ' + broker.rating : '') + '</p>' +
                    renderPerformance(broker.performance) +
                    renderReasons(broker.match_reasons) +
                    renderBreakdown(broker.match_breakdown, labels) +
                    '<div class="bc-match__result-actions">' +
                    (broker.visit_url ? '<a href="' + escapeHtml(broker.visit_url) + '" class="bc-match__result-link bc-match__result-link--primary" target="_blank" rel="noopener noreferrer nofollow">Visit broker</a>' : '') +
                    '<a href="' + escapeHtml(broker.review_url || '#') + '" class="bc-match__result-link bc-match__result-link--ghost">Full review</a>' +
                    '</div></article>';
            }

            return '<article class="bc-match__result' + featured + '">' +
                '<div class="bc-match__result-logo">' + logo + '</div>' +
                '<div class="bc-match__result-body">' +
                '<a href="' + escapeHtml(broker.review_url || '#') + '" class="bc-match__result-name">' + escapeHtml(broker.name) + '</a>' +
                '<p class="bc-match__result-fit">' + escapeHtml(broker.profile_fit || '') + '</p>' +
                '<p class="bc-match__result-meta">' + escapeHtml(broker.regulation_summary || '') + ' · Min ' + escapeHtml(broker.minimum_deposit || '—') +
                (broker.rating ? ' · ★ ' + broker.rating : '') + '</p>' +
                renderReasons(broker.match_reasons) +
                '<div class="bc-match__result-actions">' +
                (broker.visit_url ? '<a href="' + escapeHtml(broker.visit_url) + '" class="bc-match__result-link bc-match__result-link--primary" target="_blank" rel="noopener noreferrer nofollow">Visit</a>' : '') +
                '<a href="' + escapeHtml(broker.review_url || '#') + '" class="bc-match__result-link bc-match__result-link--ghost">Review</a>' +
                '</div></div>' +
                ring +
                '</article>';
        }).join('');

        window.setTimeout(animateRings, 80);
    }

    function saveResults(data) {
        try {
            localStorage.setItem(RESULTS_KEY, JSON.stringify({
                saved_at: Date.now(),
                answers: answers,
                payload: {
                    brokers: data.brokers || [],
                    profile: data.profile || {},
                    match_url: data.match_url || '',
                    compare_url: data.compare_url || '',
                    summary: data.summary || '',
                    meta: data.meta || {},
                },
            }));
        } catch (e) {
            // Ignore storage failures.
        }
    }

    function loadSavedResults() {
        try {
            var raw = localStorage.getItem(RESULTS_KEY);
            if (!raw) {
                return null;
            }
            var parsed = JSON.parse(raw);
            if (!parsed || !parsed.payload) {
                return null;
            }
            if (Date.now() - (parsed.saved_at || 0) > 7 * 24 * 60 * 60 * 1000) {
                localStorage.removeItem(RESULTS_KEY);
                return null;
            }
            return parsed;
        } catch (e) {
            return null;
        }
    }

    function copyMatchLink(url, button) {
        if (!url) {
            return;
        }
        var label = button ? button.textContent : '';

        function restored() {
            if (!button) {
                return;
            }
            button.textContent = 'Link copied!';
            window.setTimeout(function () {
                button.textContent = label;
            }, 2000);
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(restored).catch(function () {});
        }
    }

    function showSavedBannerIfNeeded() {
        if (!savedBanner || loadSavedResults() === null) {
            return;
        }
        savedBanner.classList.remove('is-hidden');
    }

    function resultsListUrl(data) {
        var url = (data && data.match_url) || '/find-my-broker';
        var slugs = ((data && data.brokers) || []).map(function (broker) {
            return broker.slug;
        }).filter(Boolean).slice(0, 3);
        var joiner = url.indexOf('?') === -1 ? '?' : '&';

        if (url.indexOf('from=') === -1) {
            url += joiner + 'from=quiz';
            joiner = '&';
        }
        if (slugs.length && url.indexOf('match=') === -1) {
            url += joiner + 'match=' + encodeURIComponent(slugs.join(','));
        }

        return url;
    }

    function openMatchResults(data, persist) {
        if (persist !== false) {
            saveResults(data);
        }
        lastMatchUrl = resultsListUrl(data);
        window.location.assign(lastMatchUrl);
    }

    function fetchRecommendations() {
        showLoading(true);

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(answers),
        })
            .then(function (response) {
                if (!response.ok) {
                    return response.json().then(function (err) {
                        throw new Error(err.message || 'Request failed');
                    });
                }
                return response.json();
            })
            .then(function (data) {
                if (loadingTitle) {
                    loadingTitle.textContent = 'Opening your matching brokers…';
                }
                openMatchResults(data);
            })
            .catch(function () {
                showLoading(false);
                if (wizardEl) {
                    wizardEl.classList.remove('is-hidden');
                    wizardEl.innerHTML = '<p class="bc-match__step-subtitle">We could not load recommendations. Please check your connection and try again.</p>';
                }
                if (navEl) {
                    navEl.classList.remove('is-hidden');
                }
            });
    }

    function resetQuiz() {
        answers = {};
        currentStep = 0;
        lastMatchUrl = '';
        try {
            localStorage.removeItem(RESULTS_KEY);
        } catch (e) {
            // Ignore storage failures.
        }
        if (savedBanner) {
            savedBanner.classList.add('is-hidden');
        }
        showResults(false);
        renderStep();
    }

    var lastMatchUrl = '';

    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', function () {
            copyMatchLink(lastMatchUrl || (seeAllLink && seeAllLink.href) || '', copyLinkBtn);
        });
    }

    if (loadSavedBtn) {
        loadSavedBtn.addEventListener('click', function () {
            var saved = loadSavedResults();
            if (!saved) {
                return;
            }
            answers = saved.answers || {};
            if (savedBanner) {
                savedBanner.classList.add('is-hidden');
            }
            openMatchResults(saved.payload, false);
        });
    }

    if (dismissSavedBtn && savedBanner) {
        dismissSavedBtn.addEventListener('click', function () {
            savedBanner.classList.add('is-hidden');
        });
    }

    if (backBtn) {
        backBtn.addEventListener('click', function () {
            if (currentStep > 0) {
                currentStep -= 1;
                renderStep();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', goNext);
    }

    if (skipBtn) {
        skipBtn.addEventListener('click', function () {
            var step = steps[currentStep];
            if (step && step.optional) {
                answers[stepField(step)] = step.type === 'multi' ? [] : '';
                goNext();
            }
        });
    }

    if (restartBtn) {
        restartBtn.addEventListener('click', resetQuiz);
    }

    updateProfilePreview();
    renderStep();
    showSavedBannerIfNeeded();
})();
