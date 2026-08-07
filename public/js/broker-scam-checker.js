(function ($) {
    'use strict';

    var app = document.getElementById('bscApp');
    if (!app) {
        return;
    }

    var searchUrl = app.dataset.searchUrl;
    var compareUrl = app.dataset.compareUrl;
    var reportUrl = app.dataset.reportUrl;
    var openReport = app.dataset.openReport === '1';
    var currentSlug = app.dataset.currentSlug || '';
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrf ? csrf.getAttribute('content') : '';

    var $input = $('#bscSearchInput');
    var $dropdown = $('#bscSearchDropdown');
    var searchTimer = null;

    function animateScoreRing() {
        $('.bsc-score-ring[data-score]').each(function () {
            var $ring = $(this);
            var score = parseInt($ring.data('score'), 10) || 0;
            var $fill = $ring.find('.bsc-ring-fill');
            var circumference = 326.7;
            var offset = circumference - (circumference * score / 100);
            $fill.css('stroke-dashoffset', offset);

            var $value = $ring.find('#bscScoreValue');
            if ($value.length) {
                var current = 0;
                var step = Math.max(1, Math.round(score / 40));
                var interval = setInterval(function () {
                    current += step;
                    if (current >= score) {
                        current = score;
                        clearInterval(interval);
                    }
                    $value.text(current);
                }, 20);
            }
        });
    }

    function animateMeters() {
        $('[data-meter]').each(function () {
            var value = parseInt($(this).data('meter'), 10) || 0;
            $(this).css('width', value + '%');
        });
    }

    function renderDropdown(items) {
        if (!items.length) {
            $dropdown.addClass('d-none').empty();
            return;
        }

        var html = items.map(function (item) {
            return '<button type="button" class="bsc-search-item" data-slug="' + item.slug + '" data-url="' + item.url + '">' +
                '<img src="' + item.logo_url + '" alt="">' +
                '<span><strong>' + item.name + '</strong><br><small class="bsc-muted">' + item.risk_label + ' · ' + item.overall_score + '/100</small></span>' +
                '</button>';
        }).join('');

        $dropdown.html(html).removeClass('d-none');
    }

    $input.on('input', function () {
        var q = $.trim($input.val());
        clearTimeout(searchTimer);

        if (q.length < 2) {
            $dropdown.addClass('d-none').empty();
            return;
        }

        searchTimer = setTimeout(function () {
            $.getJSON(searchUrl, { q: q })
                .done(function (response) {
                    renderDropdown(response.results || []);
                });
        }, 250);
    });

    $dropdown.on('click', '.bsc-search-item', function () {
        window.location.href = $(this).data('url');
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.bsc-search__wrap').length) {
            $dropdown.addClass('d-none');
        }
    });

    $('[data-bsc-example]').on('click', function () {
        $input.val($(this).data('bscExample'));
        $('#bscSearchForm').trigger('submit');
    });

    $('#bscCompareToggle').on('click', function () {
        if (currentSlug) {
            $('#bscCompare1').val($('.bsc-broker-ident__name').first().text().trim());
        }
        var modal = new bootstrap.Modal(document.getElementById('bscCompareModal'));
        modal.show();
    });

    $('#bscRunCompare').on('click', function () {
        var brokers = ['#bscCompare1', '#bscCompare2', '#bscCompare3']
            .map(function (selector) {
                return $.trim($(selector).val());
            })
            .filter(function (value) {
                return value.length > 0;
            });

        if (brokers.length < 2) {
            alert('Please enter at least two broker names.');
            return;
        }

        $.ajax({
            url: compareUrl,
            method: 'POST',
            data: JSON.stringify({ brokers: brokers }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).done(function (response) {
            renderCompareTable(response.brokers || []);
        }).fail(function (xhr) {
            var message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Comparison failed. Please check broker names.';
            $('#bscCompareResults').html('<p class="text-danger">' + message + '</p>');
        });
    });

    function renderCompareTable(brokers) {
        if (!brokers.length) {
            $('#bscCompareResults').html('<p class="bsc-muted">No results.</p>');
            return;
        }

        var headers = brokers.map(function (b) {
            return '<th>' + b.broker.name + '<br><small>' + b.overall_score + '/100</small></th>';
        }).join('');

        function row(label, getter) {
            return '<tr><th>' + label + '</th>' + brokers.map(function (b) {
                return '<td>' + getter(b) + '</td>';
            }).join('') + '</tr>';
        }

        var html = '<div class="table-responsive"><table class="bsc-compare-table">' +
            '<thead><tr><th>Metric</th>' + headers + '</tr></thead><tbody>' +
            row('Risk level', function (b) { return b.risk_icon + ' ' + b.risk_label; }) +
            row('Regulation tier', function (b) { return b.regulation.tier; }) +
            row('Trust label', function (b) { return b.trust.label; }) +
            row('Founded', function (b) { return b.company.founded; }) +
            row('Country', function (b) { return b.company.country; }) +
            row('Investor protection', function (b) {
                return b.protection.items[0] && b.protection.items[0].active ? 'Yes' : 'No';
            }) +
            row('Negative balance protection', function (b) {
                return b.protection.items[2] && b.protection.items[2].active ? 'Yes' : 'No';
            }) +
            '</tbody></table></div>';

        $('#bscCompareResults').html(html);
    }

    function clearReportErrors() {
        $('#bscReportForm .bsc-field-error').addClass('d-none').text('');
        $('#bscReportForm .is-invalid').removeClass('is-invalid');
        $('#bscReportAlert').addClass('d-none').removeClass('bsc-form-alert--success bsc-form-alert--error').text('');
    }

    function showReportFieldError(field, message) {
        var $input = $('#bscReportForm [name="' + field + '"]');
        $input.addClass('is-invalid');
        $('#bscReportForm [data-error-for="' + field + '"]').removeClass('d-none').text(message);
    }

    function showReportAlert(type, message) {
        $('#bscReportAlert')
            .removeClass('d-none bsc-form-alert--success bsc-form-alert--error')
            .addClass(type === 'success' ? 'bsc-form-alert--success' : 'bsc-form-alert--error')
            .text(message);
    }

    function validateReportForm() {
        clearReportErrors();
        var valid = true;
        var issueType = $.trim($('#bscIssueType').val());
        var message = $.trim($('#bscReportMessage').val());

        if (!issueType) {
            showReportFieldError('issue_type', 'Please select an issue type.');
            valid = false;
        }

        if (!message) {
            showReportFieldError('message', 'Please describe the issue.');
            valid = false;
        } else if (message.length < 20) {
            showReportFieldError('message', 'Your message must be at least 20 characters.');
            valid = false;
        } else if (message.length > 5000) {
            showReportFieldError('message', 'Your message may not exceed 5000 characters.');
            valid = false;
        }

        return valid;
    }

    function setReportSubmitting(isSubmitting) {
        var $btn = $('#bscReportSubmit');
        $btn.prop('disabled', isSubmitting);
        $btn.find('.bsc-submit-label').toggleClass('d-none', isSubmitting);
        $btn.find('.bsc-submit-spinner').toggleClass('d-none', !isSubmitting);
    }

    $('#bscReportMessage').on('input', function () {
        var length = $(this).val().length;
        $('#bscMessageCount').text(length);
    }).trigger('input');

    $('#bscReportForm').on('submit', function (event) {
        event.preventDefault();

        if (!reportUrl || !validateReportForm()) {
            return;
        }

        setReportSubmitting(true);
        clearReportErrors();

        $.ajax({
            url: reportUrl,
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).done(function (response) {
            showReportAlert('success', response.message || 'Report submitted successfully.');
            $('#bscReportForm')[0].reset();
            $('#bscMessageCount').text('0');
            $('#bscIssueType').prop('selectedIndex', 0);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Report submitted',
                    text: response.message,
                    confirmButtonColor: '#007AAD'
                });
            }

            setTimeout(function () {
                var modalEl = document.getElementById('bscReportModal');
                if (modalEl) {
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                }
            }, 1200);
        }).fail(function (xhr) {
            if (xhr.status === 401) {
                showReportAlert('error', 'Your session has expired. Please log in again.');
                return;
            }

            if (xhr.status === 422) {
                var payload = xhr.responseJSON || {};
                if (payload.errors) {
                    Object.keys(payload.errors).forEach(function (field) {
                        var messages = payload.errors[field];
                        if (messages && messages.length) {
                            showReportFieldError(field, messages[0]);
                        }
                    });
                }
                showReportAlert('error', payload.message || 'Please fix the errors below and try again.');
                return;
            }

            showReportAlert('error', 'Something went wrong. Please try again in a moment.');
        }).always(function () {
            setReportSubmitting(false);
        });
    });

    if (openReport && document.getElementById('bscReportModal')) {
        var reportModal = new bootstrap.Modal(document.getElementById('bscReportModal'));
        reportModal.show();
    }

    animateScoreRing();
    animateMeters();
})(jQuery);
