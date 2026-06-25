<style>
#changing-word {
  display: inline-block;
  text-align: center;
  min-width: 130px; /* adjust if needed */
}

/* Smooth Slide Animation */
@keyframes slideFade {
  0% { opacity: 0; transform: translateY(10px); }
  50% { opacity: 1; transform: translateY(0px); }
  100% { opacity: 0; transform: translateY(-10px); }
}

.word-animate {
  animation: slideFade 2s ease-in-out forwards;
}
</style>

<section class="bg-gradient-to-b from-gray-900 via-gray-800 to-white overflow-hidden relative pt-20 py-12 px-4 sm:px-6 lg:px-8">


<div class="relative w-72">

    <input
        type="text"
        id="broker-search"
        placeholder="Search broker..."
        autocomplete="off"
        class="w-full px-4 py-2 border rounded-lg"
    >

    <div id="search-results"
        class="absolute w-full bg-white border rounded-lg shadow-lg mt-1 hidden z-50 max-h-80 overflow-y-auto">
    </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("broker-search");
    const resultBox = document.getElementById("search-results");

    let debounceTimer;
    let selectedIndex = -1;
    let results = [];

    // Highlight text
    function highlight(text, query){
        const reg = new RegExp(`(${query})`, "gi");
        return text.replace(reg, `<strong>$1</strong>`);
    }

    input.addEventListener("keyup", function(e){

        // keyboard navigation
        if(e.key === "ArrowDown"){
            selectedIndex++;
            updateSelection();
            return;
        }

        if(e.key === "ArrowUp"){
            selectedIndex--;
            updateSelection();
            return;
        }

        if(e.key === "Enter"){
            if(results[selectedIndex]){
                window.location.href =
                    `/broker/${results[selectedIndex].slug}`;
            }
            return;
        }

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {

            let query = input.value.trim();

            if(query.length < 2){
                resultBox.classList.add("hidden");
                return;
            }

            fetch(`/broker-live-search?query=${query}`)
            .then(res => res.json())
            .then(data => {

                results = data;
                selectedIndex = -1;

                resultBox.innerHTML = "";

                if(data.length === 0){
                    resultBox.innerHTML =
                        `<div class="p-3 text-gray-500">No broker found</div>`;
                }else{

                    data.forEach((broker,index)=>{

                        resultBox.innerHTML += `
                        <a href="/broker/${broker.slug}"
                           class="search-item flex items-center gap-3 p-3 hover:bg-gray-100">

                            <img src="${broker.logo_url}"
                                class="w-8 h-8 object-contain bg-white rounded">

                            <span>
                                ${highlight(broker.name, query)}
                            </span>

                        </a>`;
                    });
                }

                resultBox.classList.remove("hidden");

            });

        }, 300); // debounce delay

    });

    function updateSelection(){
        const items = document.querySelectorAll(".search-item");

        items.forEach(i=>i.classList.remove("bg-gray-200"));

        if(selectedIndex >= items.length)
            selectedIndex = 0;

        if(selectedIndex < 0)
            selectedIndex = items.length - 1;

        if(items[selectedIndex]){
            items[selectedIndex].classList.add("bg-gray-200");
        }
    }

    // close when clicking outside
    document.addEventListener("click",(e)=>{
        if(!input.contains(e.target) &&
           !resultBox.contains(e.target)){
            resultBox.classList.add("hidden");
        }
    });

});
</script>
    
    <div class="mb-12">
        <!-- Popular News Slider -->
            <div class="mt-16">
                <div class="owl-carousel popular-news-carousel owl-theme">
                    @foreach($popularNewsData as $item)
                        @php
                            $userData = $item->author_id == 0
                                ? \App\Models\Admin::find($item->admin_id)
                                : $item->author;
                            $updatedDate = $item->updated_at->format('M j, Y');
                        @endphp
                        <x-news-card :item="$item" :userData="$userData" :updatedDate="$updatedDate" />
                    @endforeach
                </div>
            </div>

    </div>

   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3 
                    flex items-center justify-center gap-2">
                <span id="changing-word" class="text-yellow-500 inline-block">Featured</span>
                Brokers
            </h2>
        </div>

        <!-- Top Brokers with Side Banners -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Banner -->
            <div class="hidden lg:flex lg:w-1/6 justify-center">
                <div class="w-40 sticky top-4"> 
                    <x-banner-ad 
                        ad-image="{{ $home_ad_data->above_footer_ad ?? '' }}" 
                        ad-url="{{ $home_ad_data->above_footer_ad_url ?? '' }}" 
                        ad-status="{{ $home_ad_data->above_footer_ad_status ?? 'Hide' }}" 
                    />
                </div>
            </div>

            <!-- Brokers Grid -->
            <div class="w-full lg:w-4/6">
                @if ($top_brokers->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($top_brokers as $broker)
                            <x-hero-broker-card :broker="$broker" />
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-800/40 border border-gray-700 rounded-xl p-8 text-center">
                        <i class="fas fa-exclamation-circle text-yellow-400 text-2xl mb-3"></i>
                        <h3 class="text-white font-semibold mb-2">No Brokers Available</h3>
                        <p class="text-gray-400 text-sm">Please check back later for updated broker listings</p>
                    </div>
                @endif

                <!-- Top Ad Section under broker cards -->
                @if($global_top_ad_data->top_ad_status == 'Show')
                <div class="mt-8">
                    <div class="flex justify-center items-center">
                        <div class="w-full lg:w-10/12">
                            <div class="relative group">
                                <!-- Badge -->
                                <div class="absolute -top-3 -right-3 z-10">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg">
                                        Sponsored
                                    </span>
                                </div>
                                
                                <!-- Ad Container -->
                                <div class="relative overflow-hidden rounded-xl shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:ring-2 group-hover:ring-blue-500/20">
                                    @if($global_top_ad_data->top_ad_url == '')
                                        <img src="{{ asset('uploads/'.$global_top_ad_data->top_ad) }}" alt="Advertisement" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-[1.02]">
                                    @else
                                        <a href="{{ $global_top_ad_data->top_ad_url }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ asset('uploads/'.$global_top_ad_data->top_ad) }}" alt="Advertisement" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-[1.02]">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition-all duration-300"></div>
                                        </a>
                                    @endif
                                </div>
                                <!-- Disclaimer text -->
                                <div class="text-center pt-8">
                                        <a href="{{ route('broker.comparison') }}"
                                               class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg rounded-full">
                                               Compare
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Banner -->
            <div class="hidden lg:flex lg:w-1/6 justify-center">
                <div class="w-40 sticky top-4">
                    <x-banner-ad 
                        ad-image="{{ $home_ad_data->above_search_ad ?? '' }}" 
                        ad-url="{{ $home_ad_data->above_search_ad_url ?? '' }}" 
                        ad-status="{{ $home_ad_data->above_search_ad_status ?? 'Hide' }}" 
                    />
                </div>
            </div>
        </div>
    </div>
</div>

    
</section>





