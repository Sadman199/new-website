   <!-- top ad status area -->
   <section class="py-6 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center">
                <div class="w-full lg:w-10/12">
                    @if($global_top_ad_data->top_ad_status == 'Show')
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
                        <p class="mt-2 text-xs text-gray-500 text-center">Advertisement</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    
