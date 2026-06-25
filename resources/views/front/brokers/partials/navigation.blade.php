<!-- =====================================================
        BROKER STICKY SCROLL NAVIGATION (NO CONFLICT)
=====================================================-->

<nav id="broker-scroll-nav"
    class="sticky top-16 z-50 bg-white border-b border-gray-200 shadow-sm h-16 flex items-center">

    <div class="relative max-w-7xl mx-auto w-full px-4 flex items-center justify-center">

        <!-- LEFT ARROW -->
        <button id="broker-scroll-left"
            class="absolute left-0 bg-white shadow border rounded-full p-2 hidden md:flex">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- SCROLL AREA -->
        <div id="broker-scroll-container" class="overflow-hidden w-full">

            <div class="flex flex-nowrap gap-3 min-w-max px-8 py-2">

                <a href="#gettingstarted" class="broker-scroll-link">Getting Started</a>
                <a href="#key-stats" class="broker-scroll-link">Key Stats</a>
                <a href="#brokeroverview" class="broker-scroll-link">Broker Overview</a>
                <a href="#insightsfeatures" class="broker-scroll-link">Insights & Features</a>
                <a href="#account-structures" class="broker-scroll-link">Account Structures</a>
                <a href="#faqs" class="broker-scroll-link">FAQs</a>
                <a href="#accounttypes" class="broker-scroll-link">Account Types</a>
                <a href="#featuresconditions" class="broker-scroll-link">Feature & Conditions</a>
                <a href="#voices" class="broker-scroll-link">Voices</a>
                <a href="#compare" class="broker-scroll-link">Compare</a>

            </div>

        </div>

        <!-- RIGHT ARROW -->
        <button id="broker-scroll-right"
            class="absolute right-0 bg-white shadow border rounded-full p-2 hidden md:flex">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>
</nav>



<!-- =====================================================
                        STYLE
=====================================================-->
<style>

#broker-scroll-nav{
    background:#fff;
}

.broker-scroll-link{
    color:#4b5563;
    font-weight:500;
    font-size:14px;
    text-transform:uppercase;
    padding:8px 16px;
    border-radius:6px;
    border-bottom:2px solid transparent;
    white-space:nowrap;
    transition:all .25s ease;
}

.broker-scroll-link:hover{
    color: rgb(217 119 6);
    background:rgb(255 251 235);
    border-bottom-color: rgb(217 119 6);
}

.broker-scroll-link.active{
    background: rgb(255 251 235);
    color:rgb(217 119 6);
    font-weight:600;
    border-bottom-color: rgb(217 119 6);
}

</style>



<!-- =====================================================
                        SCRIPT
=====================================================-->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function () {

    const navLinks = $('.broker-scroll-link');
    const scrollContainer = $('#broker-scroll-container');
    const scrollLeftBtn = $('#broker-scroll-left');
    const scrollRightBtn = $('#broker-scroll-right');

    const scrollAmount = 250;



    /* ---------------- ARROW VISIBILITY ---------------- */

    function updateArrows(){

        const left = scrollContainer.scrollLeft();
        const max =
            scrollContainer[0].scrollWidth - scrollContainer.outerWidth();

        scrollLeftBtn.toggle(left > 10);
        scrollRightBtn.toggle(left < max - 10);
    }

    scrollLeftBtn.on('click', function(){
        scrollContainer.animate({scrollLeft:'-='+scrollAmount},300);
    });

    scrollRightBtn.on('click', function(){
        scrollContainer.animate({scrollLeft:'+='+scrollAmount},300);
    });

    scrollContainer.on('scroll',updateArrows);
    $(window).on('resize',updateArrows);

    updateArrows();



    /* ---------------- ACTIVE LINK ---------------- */

    function setActive(id){

        navLinks.removeClass('active');

        const activeLink =
            $('.broker-scroll-link[href="#'+id+'"]');

        activeLink.addClass('active');

        const left = activeLink.position().left;
        const width = activeLink.outerWidth();
        const containerWidth = scrollContainer.width();
        const currentScroll = scrollContainer.scrollLeft();

        const target =
            left + currentScroll - containerWidth/2 + width/2;

        scrollContainer.stop().animate({
            scrollLeft:target
        },300);
    }



    /* ---------------- SCROLL SPY ---------------- */

    const sections=[];

    navLinks.each(function(){

        const id=$(this).attr('href').substring(1);
        const el=document.getElementById(id);

        if(el) sections.push(el);

    });

    $(window).on('scroll',function(){

        let current="";
        const scrollPos=$(window).scrollTop()+180;

        sections.forEach(function(section){

            if(scrollPos >= $(section).offset().top){
                current=section.id;
            }

        });

        if(current){
            setActive(current);
        }

    });



    /* ---------------- CLICK SCROLL ---------------- */

    navLinks.on('click',function(e){

        e.preventDefault();

        const id=$(this).attr('href').substring(1);
        const target=$('#'+id);

        $('html,body').animate({
            scrollTop:target.offset().top-110
        },600);

        setActive(id);

    });

});
</script>