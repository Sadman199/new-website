'use strict';

$(function () {
    initScrollToTop();

    $(document).on('click', '.faq-question', function () {
        const $answer = $(this).next('.faq-answer');
        const $icon = $(this).find('.rotate-icon');

        $answer.slideToggle(200);
        $icon.toggleClass('rotate-180');
        $('.faq-answer').not($answer).slideUp(200);
        $('.rotate-icon').not($icon).removeClass('rotate-180');
    });

    if ($('.filter-btn').length) {
        $('.filter-btn[data-filter="all"]').addClass('bg-blue-600 text-white').removeClass('bg-white border-gray-300');
        $('.filter-btn').on('click', function () {
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
    }

    const $brokerTabs = $('#broker-tabs');
    if ($brokerTabs.length) {
        const $btns = $brokerTabs.find('.tab-button');
        const $contents = $brokerTabs.find('.tab-content');

        const activateBrokerTab = ($btn) => {
            $btns.removeClass('bg-gray-100 text-yellow-600');
            $btns.find('svg, i, span').removeClass('text-yellow-600');
            $contents.addClass('hidden');
            $btn.addClass('bg-gray-100 text-yellow-600');
            $btn.find('svg, i, span').addClass('text-yellow-600');
            $brokerTabs.find('#' + $btn.data('tab')).removeClass('hidden');
        };

        const $default = $btns.filter('[data-tab="top_rated"]');
        if ($default.length) {
            activateBrokerTab($default.first());
        }
        $btns.on('click', function () { activateBrokerTab($(this)); });
    }

    const $promoTabs = $('#promo-tabs');
    if ($promoTabs.length) {
        const $btns = $promoTabs.find('.tab-button');
        const $contents = $promoTabs.find('.tab-content');

        $btns.on('click', function () {
            const tabId = $(this).data('tab');
            $contents.addClass('hidden');
            $btns.removeClass('bg-yellow-100 text-yellow-600');
            $promoTabs.find('#' + tabId).removeClass('hidden');
            $(this).addClass('bg-yellow-100 text-yellow-600');
        });
    }

    const $changingWord = $('#changing-word');
    if ($changingWord.length) {
        const words = ['Featured', 'Trusted', 'Leading'];
        let i = 0;

        const changeWord = () => {
            $changingWord.removeClass('word-animate');
            $changingWord[0].offsetWidth;
            $changingWord.addClass('word-animate').text(words[i]);
            i = (i + 1) % words.length;
        };

        changeWord();
        setInterval(changeWord, 2000);
    }
});

function initScrollToTop() {
    const btn = document.getElementById('scrollToTopBtn');
    if (!btn) {
        return;
    }

    const toggle = () => {
        btn.classList.toggle('is-visible', window.scrollY > 300);
    };

    window.addEventListener('scroll', toggle, { passive: true });
    toggle();

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
