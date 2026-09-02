(function () {
    'use strict';

    var SELECTOR = 'form textarea';
    var SKIP_IDS = {
        disqus_code: true,
        html_code: true,
        pages: true,
        sections_payload: true
    };
    var instanceSeq = 0;

    var FULL_TOOLBAR = 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | blockquote | forecolor backcolor | removeformat | code fullscreen';
    var COMPACT_TOOLBAR = 'blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | forecolor | removeformat | code';

    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function whenTiny(fn) {
        if (window.tinymce && typeof window.tinymce.init === 'function') {
            fn();
            return;
        }
        window.setTimeout(function () {
            whenTiny(fn);
        }, 40);
    }

    function isLazy(el) {
        return el.hasAttribute('data-admin-editor-lazy');
    }

    function isCompact(el) {
        var mode = (el.getAttribute('data-admin-editor') || '').toLowerCase();
        if (mode === 'full' || el.classList.contains('snote-article') || el.id === 'post_detail') {
            return false;
        }
        if (mode === 'compact' || el.classList.contains('snote-excerpt')) {
            return true;
        }
        if (el.classList.contains('snote')) {
            return false;
        }
        var rows = parseInt(el.getAttribute('rows'), 10) || 3;
        return rows < 8;
    }

    function shouldSkip(el, extra) {
        extra = extra || {};
        if (!el || el.nodeName !== 'TEXTAREA') {
            return true;
        }
        if (el.readOnly) {
            return true;
        }
        if ((el.getAttribute('data-admin-editor') || '').toLowerCase() === 'off') {
            return true;
        }
        if (SKIP_IDS[el.id] || SKIP_IDS[el.name] || el.getAttribute('data-field') === 'rows_csv') {
            return true;
        }
        if (el.disabled && !extra.force) {
            return true;
        }
        if (isLazy(el) && !extra.force) {
            return true;
        }
        if (!extra.force && el.closest('.cms-section-card.is-collapsed, [hidden], [data-ab-tax-desc][hidden]')) {
            return true;
        }
        if (!extra.force && el.closest('.tab-pane') && !el.closest('.tab-pane.active, .tab-pane.show')) {
            return true;
        }
        return false;
    }

    function editorHeight(el) {
        var explicit = parseInt(el.getAttribute('data-editor-height'), 10);
        if (explicit) {
            return explicit;
        }
        if (el.classList.contains('snote-article') || el.id === 'post_detail') {
            return 420;
        }
        if (isCompact(el)) {
            return 180;
        }
        var rows = parseInt(el.getAttribute('rows'), 10) || 6;
        return Math.max(180, Math.min(420, rows * 28));
    }

    function ensureId(el) {
        if (el.id) {
            return el.id;
        }
        instanceSeq += 1;
        el.id = 'admin-rte-' + instanceSeq;
        return el.id;
    }

    function instanceFor(el) {
        if (!window.tinymce || !el || !el.id) {
            return null;
        }
        return window.tinymce.get(el.id);
    }

    function uploadImage(blobInfo) {
        var cfg = window.bcAdminEditor || {};

        return new Promise(function (resolve, reject) {
            if (!cfg.uploadUrl) {
                reject('Image upload is not available on this page.');
                return;
            }

            var data = new FormData();
            data.append('file', blobInfo.blob(), blobInfo.filename());
            if (cfg.csrf) {
                data.append('_token', cfg.csrf);
            }

            fetch(cfg.uploadUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': cfg.csrf || ''
                },
                body: data,
                credentials: 'same-origin'
            }).then(function (response) {
                return response.json().then(function (body) {
                    return { ok: response.ok, body: body };
                }).catch(function () {
                    return { ok: false, body: {} };
                });
            }).then(function (result) {
                if (result.ok && result.body && result.body.url) {
                    resolve(result.body.url);
                    return;
                }
                reject((result.body && (result.body.message || (result.body.errors && result.body.errors.file && result.body.errors.file[0]))) || 'Image upload failed.');
            }).catch(function () {
                reject('Image upload failed.');
            });
        });
    }

    function optionsFor(el) {
        var compact = isCompact(el);

        return {
            target: el,
            base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.8.4',
            suffix: '.min',
            menubar: compact ? false : 'edit insert format table',
            plugins: 'lists link image table code fullscreen',
            toolbar: compact ? COMPACT_TOOLBAR : FULL_TOOLBAR,
            toolbar_mode: 'sliding',
            height: editorHeight(el),
            min_height: compact ? 160 : 200,
            branding: false,
            promotion: false,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            paste_data_images: true,
            automatic_uploads: true,
            images_upload_handler: uploadImage,
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote; Preformatted=pre',
            content_style: 'body { font-family: Plus Jakarta Sans, system-ui, sans-serif; font-size: 14px; line-height: 1.65; color: #1c1e24; } img, table { max-width: 100%; } table { border-collapse: collapse; width: 100%; } td, th { border: 1px solid #d9e2e9; padding: 0.4rem 0.55rem; } blockquote { border-left: 3px solid #e8822a; margin: 0.75rem 0; padding: 0.35rem 0.85rem; color: #5c6370; }',
            setup: function (editor) {
                editor.on('change keyup undo redo', function () {
                    editor.save();
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    if (el.id === 'post_detail' && typeof window.bcUpdateReadingTime === 'function') {
                        window.bcUpdateReadingTime();
                    }
                });
            }
        };
    }

    function mount(el, extra) {
        extra = extra || {};
        if (shouldSkip(el, extra)) {
            return;
        }

        ensureId(el);

        whenTiny(function () {
            if (instanceFor(el)) {
                return;
            }
            window.tinymce.init(optionsFor(el));
        });
    }

    function destroy(el) {
        var inst = instanceFor(el);
        if (inst) {
            inst.save();
            inst.remove();
        }
    }

    function sync(el) {
        var inst = instanceFor(el);
        if (inst) {
            inst.save();
        }
    }

    function query(root) {
        root = root || document;
        if (!root.querySelectorAll) {
            return [];
        }
        return Array.prototype.slice.call(root.querySelectorAll(SELECTOR));
    }

    function mountAll(root, extra) {
        query(root).forEach(function (el) {
            mount(el, extra);
        });
    }

    function destroyAll(root) {
        query(root).forEach(destroy);
    }

    function syncAll(root) {
        if (window.tinymce && typeof window.tinymce.triggerSave === 'function') {
            window.tinymce.triggerSave();
            return;
        }
        query(root).forEach(sync);
    }

    function getHtml(el) {
        if (!el) {
            return '';
        }
        var inst = instanceFor(el);
        if (inst) {
            return inst.getContent();
        }
        return el.value || '';
    }

    function resizeInstances(root) {
        query(root).forEach(function (el) {
            var inst = instanceFor(el);
            if (inst && typeof inst.dispatch === 'function') {
                inst.dispatch('ResizeEditor');
            }
        });
    }

    window.AdminEditor = {
        mount: mount,
        mountAll: mountAll,
        destroy: destroy,
        destroyAll: destroyAll,
        sync: sync,
        syncAll: syncAll,
        getHtml: getHtml
    };

    ready(function () {
        whenTiny(function () {
            mountAll(document);

            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function () {
                    syncAll(form);
                });
            });

            document.addEventListener('shown.bs.tab', function (event) {
                var href = event.target && event.target.getAttribute('href');
                var pane = href ? document.querySelector(href) : null;
                if (!pane) {
                    return;
                }
                mountAll(pane);
                window.setTimeout(function () {
                    resizeInstances(pane);
                }, 30);
            });
        });
    });
})();
