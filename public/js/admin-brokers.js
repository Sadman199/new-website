(function () {
    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function whenEditor(fn) {
        if (window.AdminEditor) {
            fn();
            return;
        }

        window.setTimeout(function () {
            whenEditor(fn);
        }, 40);
    }

    ready(function () {
        var slug = document.getElementById('slug');
        var source = document.getElementById('name')
            || document.getElementById('account_type')
            || document.getElementById('post_title')
            || document.getElementById('sub_category_name')
            || document.getElementById('category_name')
            || document.getElementById('title');
        if (source && slug && slug.dataset.autogen !== 'off' && !slug.value) {
            source.addEventListener('input', function () {
                if (slug.dataset.touched === '1') {
                    return;
                }
                slug.value = source.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
            slug.addEventListener('input', function () {
                slug.dataset.touched = '1';
            });
        }

        var firstInvalid = document.querySelector('.ab-page .is-invalid, .ab-page .ab-error');
        if (firstInvalid) {
            var section = firstInvalid.closest('.ab-section');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        var links = Array.prototype.slice.call(document.querySelectorAll('[data-ab-nav]'));
        var sections = links.map(function (link) {
            return document.querySelector(link.getAttribute('href'));
        }).filter(Boolean);

        if (links.length && sections.length && 'IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    links.forEach(function (link) {
                        link.classList.toggle('is-active', link.getAttribute('href') === '#' + entry.target.id);
                    });
                });
            }, { rootMargin: '-30% 0px -55% 0px', threshold: 0.1 });

            sections.forEach(function (section) {
                observer.observe(section);
            });
        }

        whenEditor(function () {
            document.querySelectorAll('[data-ab-tax-toggle]').forEach(function (checkbox) {
                var targetId = checkbox.getAttribute('data-ab-tax-target');
                var textarea = targetId ? document.getElementById(targetId) : null;
                var panel = textarea ? textarea.closest('[data-ab-tax-desc]') : null;

                if (!panel || !textarea) {
                    return;
                }

                var sync = function () {
                    var on = checkbox.checked;

                    panel.hidden = !on;

                    if (on) {
                        textarea.disabled = false;
                        window.AdminEditor.mount(textarea, { force: true });
                        return;
                    }

                    window.AdminEditor.destroy(textarea);
                    textarea.disabled = true;
                };

                checkbox.addEventListener('change', sync);
                sync();
            });

            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function () {
                    window.AdminEditor.syncAll(form);
                });
            });
        });

        var checkAll = document.getElementById('ab-check-all');
        if (checkAll) {
            checkAll.addEventListener('change', function () {
                var on = checkAll.checked;
                document.querySelectorAll('.ab-row-check').forEach(function (cb) {
                    cb.checked = on;
                });
            });
        }

        document.querySelectorAll('[data-ab-delete]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var name = form.getAttribute('data-ab-name') || 'this item';
                var warn = form.getAttribute('data-ab-warn') || 'This cannot be undone.';
                var confirmLabel = form.getAttribute('data-ab-confirm') || 'Delete';
                var go = function () {
                    form.submit();
                };

                if (window.Swal && typeof window.Swal.fire === 'function') {
                    window.Swal.fire({
                        icon: 'warning',
                        title: 'Delete ' + name + '?',
                        text: warn,
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: confirmLabel
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            go();
                        }
                    });
                    return;
                }

                if (window.confirm('Delete ' + name + '? ' + warn)) {
                    go();
                }
            });
        });
    });
})();
