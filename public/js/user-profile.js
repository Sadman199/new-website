(function () {
    'use strict';

    document.querySelectorAll('[data-ua-unsave]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var row = form.closest('.ua-saved-item');
            var brokerId = row ? row.getAttribute('data-broker-id') : null;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            var token = csrf ? csrf.getAttribute('content') : '';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: '_method=DELETE&_token=' + encodeURIComponent(token),
                credentials: 'same-origin',
            })
                .then(function (res) {
                    if (!res.ok) {
                        throw new Error('Remove failed');
                    }
                    return res.json();
                })
                .then(function (data) {
                    if (row) {
                        row.remove();
                    }
                    if (data.broker_ids) {
                        localStorage.setItem('savedBrokers', JSON.stringify(data.broker_ids.map(String)));
                    } else if (brokerId) {
                        localStorage.setItem('savedBrokers', JSON.stringify(
                            readLocal().filter(function (id) { return id !== String(brokerId); })
                        ));
                    }

                    var grid = document.querySelector('[data-ua-saved-grid]');
                    if (grid && !grid.querySelector('.ua-saved-item')) {
                        window.location.reload();
                    }
                })
                .catch(function () {
                    form.submit();
                });
        });
    });

    function readLocal() {
        try {
            return JSON.parse(localStorage.getItem('savedBrokers') || '[]').map(String);
        } catch (e) {
            return [];
        }
    }

    function scrollToTarget(id) {
        if (!id) {
            return;
        }
        var el = document.getElementById(id);
        if (!el) {
            return;
        }
        window.setTimeout(function () {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 80);
    }

    var root = document.querySelector('.ua-root[data-ua-scroll]');
    if (root) {
        scrollToTarget(root.getAttribute('data-ua-scroll'));
    } else if (window.location.hash) {
        scrollToTarget(window.location.hash.replace(/^#/, ''));
    }
})();
