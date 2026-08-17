(function () {
    'use strict';

    var config = window.BROKER_BATTLE || {};
    var brokers = Array.isArray(config.brokers) ? config.brokers : [];
    var form = document.getElementById('bcBattleRestartForm');
    var select1 = document.getElementById('bcBattleBroker1');
    var select2 = document.getElementById('bcBattleBroker2');
    var errorEl = document.getElementById('bcBattleRestartError');
    var copyBtn = document.getElementById('bcBattleCopyLink');
    var copyLabel = document.getElementById('bcBattleCopyLabel');
    var shareUrl = String(config.shareUrl || window.location.href);
    var shareTitle = String(config.shareTitle || document.title);

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function battleUrl(slug1, slug2) {
        var slugs = [slug1, slug2].sort();
        var base = String(config.battleBase || '/broker-battle').replace(/\/$/, '');
        return base + '/' + slugs[0] + '-vs-' + slugs[1];
    }

    function populateSelect(select, preferredSlug, excludeSlug) {
        if (!select) {
            return;
        }

        var html = '<option value="">Choose a broker</option>';
        brokers.forEach(function (broker) {
            if (!broker || !broker.slug || broker.slug === excludeSlug) {
                return;
            }

            html += '<option value="' + escapeHtml(broker.slug) + '"'
                + (broker.slug === preferredSlug ? ' selected' : '')
                + '>' + escapeHtml(broker.name) + '</option>';
        });
        select.innerHTML = html;
    }

    function refreshOptions() {
        var left = select1 ? select1.value : '';
        var right = select2 ? select2.value : '';
        populateSelect(select1, left, right);
        populateSelect(select2, right, left);
    }

    function showError(message) {
        if (!errorEl) {
            return;
        }
        if (!message) {
            errorEl.textContent = '';
            errorEl.classList.add('bc-compare-hidden');
            return;
        }
        errorEl.textContent = message;
        errorEl.classList.remove('bc-compare-hidden');
    }

    function wireShareLinks() {
        var encodedUrl = encodeURIComponent(shareUrl);
        var encodedTitle = encodeURIComponent(shareTitle);
        var facebook = document.getElementById('bcBattleShareFacebook');
        var x = document.getElementById('bcBattleShareX');
        var telegram = document.getElementById('bcBattleShareTelegram');

        if (facebook) {
            facebook.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodedUrl;
        }
        if (x) {
            x.href = 'https://twitter.com/intent/tweet?url=' + encodedUrl + '&text=' + encodedTitle;
        }
        if (telegram) {
            telegram.href = 'https://t.me/share/url?url=' + encodedUrl + '&text=' + encodedTitle;
        }
    }

    function copyShareLink() {
        var done = function () {
            if (copyLabel) {
                copyLabel.textContent = 'Copied!';
                window.setTimeout(function () {
                    copyLabel.textContent = 'Copy link';
                }, 1800);
            }
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(shareUrl).then(done).catch(function () {
                fallbackCopy(done);
            });
            return;
        }

        fallbackCopy(done);
    }

    function fallbackCopy(done) {
        var input = document.createElement('input');
        input.value = shareUrl;
        document.body.appendChild(input);
        input.select();
        try {
            document.execCommand('copy');
            done();
        } catch (e) {}
        document.body.removeChild(input);
    }

    if (select1 && select2) {
        populateSelect(select1, config.leftSlug || '', config.rightSlug || '');
        populateSelect(select2, config.rightSlug || '', config.leftSlug || '');

        select1.addEventListener('change', function () {
            showError('');
            refreshOptions();
        });
        select2.addEventListener('change', function () {
            showError('');
            refreshOptions();
        });
    }

    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var left = select1 ? select1.value : '';
            var right = select2 ? select2.value : '';

            if (!left || !right) {
                showError('Select two brokers to start a battle.');
                return;
            }

            if (left === right) {
                showError('Choose two different brokers.');
                return;
            }

            window.location.href = battleUrl(left, right);
        });
    }

    if (copyBtn) {
        copyBtn.addEventListener('click', copyShareLink);
    }

    wireShareLinks();
})();
