


$(document).ready(function () {
    let ratingValue = parseFloat("{{ $broker->rating }}");
    if (isNaN(ratingValue)) {
        ratingValue = 0;
    }

    $('.star').each(function () {
        const starValue = parseInt($(this).data('value'));
        if (starValue <= ratingValue) {
            $(this).addClass('filled');
        } else {
            $(this).removeClass('filled');
        }
    });
});


// bottom to top js
function initScrollToTop() {
    const $scrollBtn = $('#scrollToTopBtn');
    const $scrollProgress = $('#scrollProgress');
    const $scrollPercentage = $('#scrollPercentage');
    const r = 45;
    const circumference = 2 * Math.PI * r;
    $(window).on('scroll', function () {
        const scrollTop = $(window).scrollTop();
        const scrollHeight = $(document).height();
        const windowHeight = $(window).height();
        const scrollable = scrollHeight - windowHeight;
        const progress = Math.min(scrollTop / scrollable, 1);
        const offset = circumference - (progress * circumference);
        const percent = Math.round(progress * 100);

        $scrollProgress.css('stroke-dashoffset', offset);
        $scrollPercentage.text(percent + '%');

        // Change button color dynamically
        if (percent > 70) {
            $scrollBtn
                .removeClass('bg-yellow-500 hover:bg-yellow-600')
                .addClass('bg-green-500 hover:bg-green-600');
            $scrollProgress.attr('stroke', '#ffffff');
        } else {
            $scrollBtn
                .removeClass('bg-green-500 hover:bg-green-600')
                .addClass('bg-yellow-500 hover:bg-yellow-600');
            $scrollProgress.attr('stroke', 'currentColor');
        }

        // Toggle visibility
        if (scrollTop > 300) {
            $scrollBtn.css({ opacity: 1, pointerEvents: 'auto' });
        } else {
            $scrollBtn.css({ opacity: 0, pointerEvents: 'none' });
        }
    });
    // Scroll to top on click
    $scrollBtn.on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 600);
    });
    }

    // Re-initialize on load and optionally after every page load (AJAX or Livewire/Turbo visit)
    $(document).ready(function () {
        initScrollToTop();
    });

 $(document).ready(function () {
        $('.faq-question').on('click', function () {
            const $answer = $(this).next('.faq-answer');
            const $icon = $(this).find('.rotate-icon');

            // Toggle the selected answer
            $answer.slideToggle(200);
            $icon.toggleClass('rotate-180');

            // Optionally close others
            $('.faq-answer').not($answer).slideUp(200);
            $('.rotate-icon').not($icon).removeClass('rotate-180');
        });
    });
    
    // FAQ for broker details

        $(document).ready(function() {
        $('.filter-btn[data-filter="all"]').addClass('bg-blue-600 text-white').removeClass('bg-white border-gray-300');
        $('.filter-btn').click(function() {
            const filter = $(this).data('filter');
            $('.filter-btn').removeClass('bg-blue-600 text-white').addClass('bg-white border-gray-300');
            $(this).removeClass('bg-white border-gray-300').addClass('bg-blue-600 text-white');
                            if (filter === 'all') {
                $('.resource-card').fadeIn(200);
            } else {
                $('.resource-card').hide();
                $(`.resource-card[data-type="${filter}"]`).fadeIn(200);
            }
        });
    });


// bonus section tab

$(document).ready(function() {
    const $promoTabs = $('#promo-tabs');
    const $tabButtons = $promoTabs.find('.tab-button');
    const $tabContents = $promoTabs.find('.tab-content');

    $tabButtons.on('click', function() {
        const tabId = $(this).data('tab');
        $tabContents.addClass('hidden');
        $tabButtons.removeClass('bg-yellow-100 text-yellow-600');
        $promoTabs.find('#' + tabId).removeClass('hidden');
        $(this).addClass('bg-yellow-100 text-yellow-600');
    });
});

// Top Regulated Rated No Regulation Monthly Top Demo Accounts Low Deposit Tab

$(function () {
    // Broker Tabs
    const $brokerTabs = $('#broker-tabs');
    if ($brokerTabs.length) {
        const $btns = $brokerTabs.find('.tab-button');
        const $contents = $brokerTabs.find('.tab-content');

        const activate = $btn => {
            $btns.removeClass('bg-gray-100 text-yellow-600');
            $btns.find('svg, i, span').removeClass('text-yellow-600');
            $contents.addClass('hidden');

            $btn.addClass('bg-gray-100 text-yellow-600');
            $btn.find('svg, i, span').addClass('text-yellow-600');
            $brokerTabs.find('#' + $btn.data('tab')).removeClass('hidden');
        };

        activate($btns.filter('[data-tab="top_rated"]'));
        $btns.on('click', function () { activate($(this)); });
    }

    // Promo Tabs
    const $promoTabs = $('#promo-tabs');
    if ($promoTabs.length) {
        const $btns = $promoTabs.find('.tab-button');
        const $contents = $promoTabs.find('.tab-content');

        $btns.on('click', function () {
            $btns.removeClass('bg-yellow-100 text-yellow-600');
            $contents.addClass('hidden');

            $(this).addClass('bg-yellow-100 text-yellow-600');
            $promoTabs.find('#' + $(this).data('tab')).removeClass('hidden');
        });
    }
});



$(document).ready(function(){
    $('.popular-news-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        smartSpeed: 800,
        responsive: {
            0: { items: 1 },
            640: { items: 3 },
            1024: { items: 6 }
        }
    });
});

$(document).ready(function() {
    var words = ["Featured", "Trusted", "Leading"];
    var i = 0;
    var $el = $("#changing-word");

    function changeWord() {
        $el.removeClass("word-animate"); // reset animation

        // Trigger reflow for animation restart
        $el[0].offsetWidth;

        $el.addClass("word-animate");
        $el.text(words[i]);
        i = (i + 1) % words.length;
    }

    changeWord(); // initial call
    setInterval(changeWord, 2000);
});






