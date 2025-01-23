(function ($) {

    "use strict";

    $(".scroll-top").hide();
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 300) {
            $(".scroll-top").fadeIn();
        } else {
            $(".scroll-top").fadeOut();
        }
    });
    $(".scroll-top").on("click", function () {
        $("html, body").animate({
            scrollTop: 0,
        }, 700)
    });

    $(document).ready(function () {
        $('.select2').select2({
            theme: "bootstrap"
        });
    });

    new WOW().init();

    $('.video-button').magnificPopup({
        type: 'iframe',
        gallery: {
            enabled: true
        }
    });

    $('.magnific').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    $('.my-news-ticker').AcmeTicker({
        type: 'typewriter',
        direction: 'right',
        speed: 50,
        controls: {
            prev: $('.acme-news-ticker-prev'),
            toggle: $('.acme-news-ticker-pause'),
            next: $('.acme-news-ticker-next')
        }
    });

    $('.related-post-carousel').owlCarousel({
        loop: false,
        autoplay: true,
        autoplayHoverPause: true,
        autoplaySpeed: 1500,
        smartSpeed: 1500,
        margin: 30,
        mouseDrag: true,
        nav: true,
        dots: true,
        navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            }
        }
    });

    $('.video-carousel').owlCarousel({
        loop: true,
        autoplay: false,
        autoplayHoverPause: true,
        autoplaySpeed: 1000,
        smartSpeed: 1000,
        margin: 30,
        mouseDrag: true,
        nav: true,
        dots: true,
        navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 4
            }
        }
    });

})(jQuery);


document.addEventListener('DOMContentLoaded', function () {
    let ratingValue = parseFloat("{{ $broker->rating }}"); 
    if (isNaN(ratingValue)) {
        ratingValue = 0; 
    }

    const stars = document.querySelectorAll('.star');
    function updateStars() {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= ratingValue) {
                star.classList.add('filled'); 
            } else {
                star.classList.remove('filled'); 
            }
        });
    }
    updateStars();
});



$(document).ready(function () {
    const $navbar = $(".navbar");
    $(".modal").on("show.bs.modal", function () {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        if (scrollbarWidth > 0) {
            $navbar.css("margin-right", `${scrollbarWidth}px`);
        }
    });

    $(".modal").on("hidden.bs.modal", function () {
        $navbar.css("margin-right", "");
    });
});


////load more button on review systes//

    $(document).ready(function () {
        $('.testimonial-slider-wrapper .testimonial-slider').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            autoplay: false,
            autoplayTimeout: 5000,
            navText: [
                '<i class="fas fa-chevron-left"></i>',
                '<i class="fas fa-chevron-right"></i>'
            ],
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1024: {
                    items: 3
                }
            }
        });
    });


// js for copmarison broker for homepage 

$(document).ready(function () {
    // Toggle dropdown and icon
    $(".compare-toggle").click(function () {
        var $menu = $(this).next(".compare-menu");
        var $icon = $(this).find(".toggle-icon");
        $(".compare-menu").not($menu).fadeOut(200);
        $menu.fadeToggle(300);
        $icon.toggleClass("fa-chevron-down fa-chevron-up");
    });

    // Select item from dropdown
    $(".compare-menu li").click(function () {
        var $dropdown = $(this).closest(".compare-dropdown");
        var value = $(this).data("value"), text = $(this).text();
        $dropdown.find("input").val(value).end().find(".compare-toggle").text(text).end().find(".compare-menu").fadeOut(200).end().find(".compare-error-message").hide();
    });

    // Close dropdown if clicked outside
    $(document).click(function (e) {
        if (!$(e.target).closest(".compare-dropdown").length) {
            $(".compare-menu").fadeOut(200);
            $(".toggle-icon").removeClass("fa-chevron-up").addClass("fa-chevron-down");
        }
    });

    // Validate form
    $("#compareForm").submit(function (e) {
        var isValid = true;
        $(".compare-dropdown").each(function () {
            var $input = $(this).find("input");
            var $error = $(this).find(".compare-error-message");
            if (!$input.val()) { $error.show(); isValid = false; }
            else $error.hide();
        });
        if (!isValid) e.preventDefault();
    });
});


function handleAdBannersForAllPages(triggerSectionSelector, options = {}) {
    const settings = $.extend(
      {
        addImageLeftSelector: '.add_image_left',
        addImageRightSelector: '.add_image_right',
        offset: 200,
        fadeDuration: 300,
        slideDuration: 500,
      },
      options
    );
  
    const $addImageLeft = $(settings.addImageLeftSelector);
    const $addImageRight = $(settings.addImageRightSelector);
    const $triggerSection = $(triggerSectionSelector);
  
    let lastScrollTop = 0;
    let bannersVisible = false;
  
    // Ensure banners are off-screen when the page loads
    $addImageLeft.css('left', `-${settings.offset}px`);
    $addImageRight.css('right', `-${settings.offset}px`);
  
    // Function to toggle banner visibility on scroll
    $(window).scroll(function () {
      const currentScrollTop = $(this).scrollTop();
  
      if (
        currentScrollTop > lastScrollTop &&
        currentScrollTop > $triggerSection.outerHeight()
      ) {
        // Scrolling down and past the trigger section
        if (!bannersVisible) {
          $addImageLeft
            .stop(true, true)
            .fadeTo(settings.fadeDuration, 1)
            .animate({ left: '0' }, settings.slideDuration);
          $addImageRight
            .stop(true, true)
            .fadeTo(settings.fadeDuration, 1)
            .animate({ right: '0' }, settings.slideDuration);
          bannersVisible = true;
        }
      } else {
        // Scrolling up and trigger section comes back into view
        if (currentScrollTop < $triggerSection.outerHeight()) {
          $addImageLeft
            .stop(true, true)
            .fadeTo(settings.fadeDuration, 0)
            .animate({ left: `-${settings.offset}px` }, settings.slideDuration);
          $addImageRight
            .stop(true, true)
            .fadeTo(settings.fadeDuration, 0)
            .animate({ right: `-${settings.offset}px` }, settings.slideDuration);
          bannersVisible = false;
        }
      }
  
      lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // Prevent negative values
    });
  
    // Trigger scroll behavior immediately on page load
    $(window).trigger('scroll');
  }

  
  $(document).ready(function() {
    // Select all FAQ toggle buttons
    $('[data-toggle]').on('click', function() {
        var selector = $(this).data('toggle');
        var block = $(selector);

        // Close all other FAQ items
        $('.accordion__content').each(function() {
            if ($(this)[0] !== block[0]) {
                $(this).css('max-height', ''); // Collapse all other items
                $(this).prev().removeClass('active'); // Remove active class from headers
            }
        });

        // Toggle the clicked FAQ item's content
        if ($(this).hasClass('active')) {
            block.css('max-height', ''); // Collapse the current item
        } else {
            block.css('max-height', block.prop('scrollHeight') + 'px'); // Expand the current item
        }

        // Toggle the 'active' class for the clicked header
        $(this).toggleClass('active');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const loaderOverlay = document.getElementById("loader-overlay");
    loaderOverlay.style.display = "none"; // Hide the loader once the page is fully loaded
});



$(document).ready(function () {
    // Adjust the speed factor for the parallax effect
    const parallaxSpeed = 0.5;

    $(window).on("scroll", function () {
        let scrollTop = $(this).scrollTop();
        $(".hero").css("background-position", `center calc(50% + ${scrollTop * parallaxSpeed}px)`);
    });
});


$('.broker_layer').on('click', function() {
    const descriptionWrapper = $(this).find('.hover_description_wrapper');
    const isVisible = descriptionWrapper.data('visible') === true;

    // Toggle visibility
    if (isVisible) {
        descriptionWrapper.css('height', '0'); // Hide the description
        descriptionWrapper.data('visible', false);
    } else {
        descriptionWrapper.css('height', 'auto'); // Show the description
        descriptionWrapper.data('visible', true);
    }
});



    $(document).ready(function(){
        $(".non-regulated-slider").owlCarousel({
            loop: true,          
            margin: 15,          
            nav: true,           
            dots: false,         
            responsive: {      
                0: { items: 1 },
                600: { items: 2 },
                1000: { items: 7 }
            }
        });
    });


