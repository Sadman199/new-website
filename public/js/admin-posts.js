(function () {
    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function wordCountFromHtml(html) {
        var text = String(html || '')
            .replace(/<[^>]+>/g, ' ')
            .replace(/&nbsp;/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
        if (!text) {
            return 0;
        }
        return text.split(' ').length;
    }

    function updateReadingTime() {
        var preview = document.getElementById('reading-time-preview');
        if (!preview) {
            return;
        }
        var html = '';
        var field = document.getElementById('post_detail');
        if (window.AdminEditor && field) {
            html = window.AdminEditor.getHtml(field);
        } else {
            html = field ? field.value : '';
        }
        var minutes = Math.max(1, Math.ceil(wordCountFromHtml(html) / 200));
        preview.textContent = minutes + ' min';
    }

    function bindCounters() {
        document.querySelectorAll('[data-counter]').forEach(function (field) {
            var limit = parseInt(field.getAttribute('data-counter'), 10) || 0;
            var output = document.querySelector('[data-counter-for="' + field.id + '"]');
            if (!output || !limit) {
                return;
            }
            var render = function () {
                var length = (field.value || '').length;
                output.textContent = length + ' / ' + limit + ' recommended';
                output.classList.toggle('is-over', length > limit);
            };
            field.addEventListener('input', render);
            render();
        });
    }

    function bindPreviews() {
        document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
            var img = document.getElementById(input.getAttribute('data-preview'));
            if (!img) {
                return;
            }
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) {
                    return;
                }
                img.src = URL.createObjectURL(file);
                img.classList.remove('is-empty');
            });
        });
    }

    function bindScheduleToggle() {
        var status = document.querySelector('[data-status-toggle]');
        var scheduled = document.querySelector('[data-scheduled-field]');
        if (!status || !scheduled) {
            return;
        }
        var sync = function () {
            scheduled.style.display = status.value === 'scheduled' ? '' : 'none';
        };
        status.addEventListener('change', sync);
        sync();
    }

    function initEditors() {
        if (!window.AdminEditor) {
            return;
        }
        var article = document.getElementById('post_detail');
        if (article) {
            window.AdminEditor.mount(article, { force: true });
        }
    }

    function initSelects() {
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) {
            return;
        }

        var $ = window.jQuery;

        $('#broker_ids, #related_post_ids, #related_category_ids, #sub_category_id').each(function () {
            var $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.select2({
                width: '100%',
                dropdownParent: $(document.body),
                closeOnSelect: !$el.prop('multiple'),
                placeholder: $el.data('placeholder') || '',
                allowClear: !$el.prop('required')
            });
        });

        var $type = $('#content_type');
        if ($type.length) {
            if ($type.hasClass('select2-hidden-accessible')) {
                $type.select2('destroy');
            }
            $type.select2({
                width: '100%',
                tags: true,
                dropdownParent: $(document.body),
                placeholder: $type.data('placeholder') || 'Select or type a new type',
                createTag: function (params) {
                    var term = $.trim(params.term || '');
                    if (term === '') {
                        return null;
                    }
                    return { id: term, text: term, newTag: true };
                }
            });
        }
    }

    function initTags() {
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.tagsinput) {
            return;
        }

        var $el = window.jQuery('#tags');
        if (!$el.length || $el.data('tagsinput')) {
            return;
        }

        $el.tagsinput({
            trimValue: true,
            confirmKeys: [13, 188]
        });

        $el.on('itemAdded', function () {
            var input = $el.tagsinput('input');
            if (input) {
                input.value = '';
            }
        });
    }

    ready(function () {
        bindCounters();
        bindPreviews();
        bindScheduleToggle();
        initEditors();
        initSelects();
        initTags();
        updateReadingTime();
        window.bcUpdateReadingTime = updateReadingTime;
        window.setInterval(updateReadingTime, 4000);
    });
})();
