(function () {
    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function hasSelect2() {
        return window.jQuery && window.jQuery.fn && window.jQuery.fn.select2;
    }

    function destroySelect2(el) {
        if (!hasSelect2() || !el) {
            return;
        }
        var $el = window.jQuery(el);
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }
    }

    function initSelect2(el, extra) {
        if (!hasSelect2() || !el) {
            return;
        }
        destroySelect2(el);
        var $el = window.jQuery(el);
        $el.select2(Object.assign({
            width: '100%',
            dropdownParent: window.jQuery(document.body),
            placeholder: $el.data('placeholder') || '',
            allowClear: !el.required,
            closeOnSelect: !el.multiple
        }, extra || {}));
    }

    ready(function () {
        var targeting = document.getElementById('targeting');
        var brokersWrap = document.querySelector('[data-banner-brokers]');
        var multiWrap = document.querySelector('[data-banner-multi-brokers]');
        var pageWrap = document.querySelector('[data-banner-page]');
        var brokerSelect = document.getElementById('broker_id');
        var pageSelect = document.getElementById('page_path');
        var formatInputs = document.querySelectorAll('input[name="creative_format"]');
        var imagesSection = document.querySelector('[data-banner-images]');
        var htmlSection = document.querySelector('[data-banner-html]');
        var htmlField = document.getElementById('html_content');
        var preview = document.querySelector('[data-banner-html-preview]');
        var starterBtn = document.querySelector('[data-banner-html-starter]');
        var refreshBtn = document.querySelector('[data-banner-html-refresh]');
        var starterSource = document.querySelector('[data-banner-html-starter-source]');

        function selectedFormat() {
            var checked = document.querySelector('input[name="creative_format"]:checked');
            return checked ? checked.value : 'image';
        }

        function syncFormat() {
            var isHtml = selectedFormat() === 'html';
            if (imagesSection) {
                imagesSection.hidden = isHtml;
            }
            if (htmlSection) {
                htmlSection.hidden = !isHtml;
            }
            document.querySelectorAll('.ab-banner-choice').forEach(function (choice) {
                var input = choice.querySelector('input[name="creative_format"]');
                choice.classList.toggle('is-selected', !!(input && input.checked));
            });
            document.querySelectorAll('#desktop_image, #mobile_image').forEach(function (input) {
                input.disabled = isHtml;
                input.required = false;
            });
            if (htmlField) {
                htmlField.required = isHtml;
            }
            if (isHtml) {
                updatePreview();
            }
        }

        function syncTargeting() {
            var value = targeting ? targeting.value : 'website_wide';
            var needsPage = value === 'specific_page';
            var isMultiple = value === 'multiple_brokers';

            if (brokersWrap) {
                brokersWrap.hidden = false;
            }
            if (multiWrap) {
                multiWrap.hidden = !isMultiple;
            }
            if (pageWrap) {
                pageWrap.hidden = !needsPage;
            }
            if (brokerSelect) {
                brokerSelect.required = value === 'specific_broker';
                brokerSelect.disabled = false;
            }
            if (pageSelect) {
                pageSelect.required = needsPage;
            }
        }

        function updatePreview() {
            if (!preview || !htmlField) {
                return;
            }
            preview.srcdoc = htmlField.value || '<p style="padding:16px;color:#6b7280;font-family:sans-serif;">Preview will appear here.</p>';
        }

        formatInputs.forEach(function (input) {
            input.addEventListener('change', syncFormat);
        });

        if (targeting) {
            targeting.addEventListener('change', syncTargeting);
        }

        if (htmlField) {
            htmlField.addEventListener('input', updatePreview);
            htmlField.addEventListener('keydown', function (event) {
                if (event.key !== 'Tab') {
                    return;
                }
                event.preventDefault();
                var start = htmlField.selectionStart;
                var end = htmlField.selectionEnd;
                htmlField.value = htmlField.value.slice(0, start) + '  ' + htmlField.value.slice(end);
                htmlField.selectionStart = htmlField.selectionEnd = start + 2;
                updatePreview();
            });
        }

        if (starterBtn && htmlField && starterSource) {
            starterBtn.addEventListener('click', function () {
                if (htmlField.value && !window.confirm('Replace the current HTML with the starter layout?')) {
                    return;
                }
                htmlField.value = starterSource.value;
                updatePreview();
            });
        }

        if (refreshBtn) {
            refreshBtn.addEventListener('click', updatePreview);
        }

        document.querySelectorAll('.ab-banner-form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var button = form.querySelector('[data-banner-submit]');
                if (!button) {
                    return;
                }
                button.disabled = true;
                button.classList.add('is-loading');
                button.setAttribute('aria-busy', 'true');
                var icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-spinner fa-spin';
                }
            });
        });

        initSelect2(brokerSelect, { allowClear: true });
        initSelect2(document.getElementById('broker_ids'));
        initSelect2(pageSelect, { tags: true, allowClear: true });
        syncTargeting();
        syncFormat();
    });
})();
