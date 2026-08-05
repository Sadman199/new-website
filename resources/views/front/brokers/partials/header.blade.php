<div class="w-full max-w-7xl mx-auto mt-12" id="gettingstarted">
    <div class="bg-gradient-to-br from-gray-900 to-gray-700 rounded-2xl shadow-xl border border-gray-800 overflow-hidden">
        <!-- Header with dark gradient background -->
        <div class="bg-gradient-to-r from-gray-950 to-gray-700 py-4 px-6 border-b border-gray-800">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <h1 class="text-2xl font-bold text-gray-200">{{ $broker->name ?? 'Forex Broker' }}</h1>
              @php
                    $isRegulated = $broker->isRegulated();
                    $rating = $broker->rating;
                    $marketsLabel = $broker->marketList()
                        ? implode(', ', array_map('ucfirst', $broker->marketList()))
                        : null;
                @endphp
                
                <div class="flex items-center gap-5">
                 <!-- Regulation Badge -->
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold shadow-md border border-opacity-30 text-sm transition-all duration-300
                        {{ $isRegulated ? 'bg-emerald-950/60 text-gray-100 border-emerald-500/50' : 'bg-amber-950/60 text-amber-300 border-amber-500/50' }}">
                        @if($isRegulated)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Trusted & Safe
                        @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4h2v2h-2v-2zm0-6h2v4h-2V8z" clip-rule="evenodd"/>
                            </svg>
                            Risk Alert
                        @endif
                    </span>
                    
                  <!-- Numeric Score -->
                    <section class="flex items-center">
                        <div class="relative w-14 h-8">
                            <span 
                                class="absolute bottom-0 left-0 h-3 rounded-lg bg-gradient-to-r from-cyan-400 to-emerald-400 transition-all duration-700"
                                style="width: {{ $rating * 10 }}%">
                            </span>
                            <span class="absolute bottom-0 left-0 text-gray-100 font-bold text-2xl z-10">
                                {{ number_format($rating, 2) }}
                            </span>
                        </div>
                        <span class="text-gray-500 text-sm font-semibold">Score</span>
                    </section>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left Column -->
                <div class="lg:col-span-2 flex flex-col space-y-6">
                    <!-- Top Banner -->
                    <div class="w-full bg-gradient-to-r from-gray-900 to-slate-900 rounded-xl overflow-hidden border border-gray-800 shadow-lg relative">
                        @if(!empty($broker->banner_image_1))
                            <a href="{{ $broker->url }}" target="_blank" rel="noopener noreferrer" class="block">
                                <img 
                                    src="{{ asset($broker->banner_image_1) }}" 
                                    alt="{{ $broker->name }} Banner" 
                                    class="w-full h-24 object-cover"
                                    loading="lazy">
                            </a>
                        @else
                            <div class="bg-gray-800 rounded-md flex items-center justify-center h-24">
                                <span class="text-gray-500 text-sm">Advertisement Banner</span>
                            </div>
                        @endif
                    </div>

                    <!-- Logo and Info Section -->
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        <div class="relative">
                            <div class="w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl border border-gray-700 flex items-center justify-center shadow-lg relative overflow-hidden">
                                @if($broker->logo)
                                    <img src="{{ asset($broker->logo) }}" alt="Logo" class="object-contain w-full h-full p-3" loading="lazy" />
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-600 to-cyan-600 rounded-lg flex items-center justify-center">
                                        <span class="text-xl font-bold text-white">FX</span>
                                    </div>
                                @endif
                                <div class="absolute top-0 right-0 w-8 h-8 bg-gradient-to-br from-emerald-500/50 to-cyan-500/50 rounded-bl-lg"></div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="mb-4">
                                <h2 class="text-xl font-bold text-gray-200 mb-1">Your Gateway to Global Trading</h2>
                                <p class="text-gray-400 text-sm">Trade Forex, Stocks, Indices and Commodities with competitive spreads</p>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                                <div class="text-center p-3 bg-gray-800/50 rounded-lg border border-gray-400">
                                    <div class="text-sm font-bold text-gray-100">
                                        {{ $broker->leverage ?? '—' }}
                                    </div>
                                    <div class="text-xs text-gray-100">Leverage</div>
                                </div>
                            
                                <div class="text-center p-3 bg-gray-800/50 rounded-lg border border-gray-400">
                                    <div class="text-sm font-bold text-gray-100">
                                        {{ optional($broker->accountOptions->first())->spread_value ?? '—' }}
                                    </div>
                                    <div class="text-sm font-bold text-gray-100">Spreads</div>
                                </div>
                            
                                <div class="text-center p-3 bg-gray-800/50 rounded-lg border border-gray-400">
                                    <div class="text-sm font-bold text-gray-100">
                                       {{ $marketsLabel ?? ($broker->instrument_count ? $broker->instrument_count . ' instruments' : '—') }}
                                    </div>
                                    <div class="text-xs text-gray-100">Markets</div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <a href="{{ $broker->demo_link ?? '#' }}" class="flex-1 min-w-[180px] px-5 py-3 bg-gray-800 border border-gray-700 rounded-xl text-sm font-semibold text-gray-200 hover:bg-gray-700 hover:border-emerald-500/50 transition-all duration-300 flex items-center justify-center shadow-sm group">
                                    <svg class="w-5 h-5 mr-2 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Try Demo Account
                                </a>
                                <a href="{{ $broker->open_live }}" class="flex-1 min-w-[180px] px-6 py-3 bg-gradient-to-r from-emerald-600 to-cyan-600 rounded-xl text-sm font-semibold text-white hover:from-emerald-700 hover:to-cyan-700 transition-all shadow-sm flex items-center justify-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    Open Live Account
                                </a>
                            </div>

                            @if(!empty($editorialCredits))
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach($editorialCredits as $credit)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-800/60 text-gray-200 border border-gray-600">
                                        <span class="font-semibold mr-1">{{ $credit['label'] }}:</span> {{ $credit['name'] }}
                                    </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Banner -->
                <div class="flex justify-center lg:justify-end items-start">
                    <div class="w-full max-w-[301px] h-[251px] bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-lg relative">
                        @if(!empty($broker->banner_image_2))
                            <a href="{{ $broker->url }}" target="_blank" rel="noopener noreferrer" class="block w-full h-full">
                                <img 
                                    src="{{ asset($broker->banner_image_2) }}" 
                                    alt="{{ $broker->name ?? 'Advertisement' }} Banner"
                                    class="w-full h-full object-cover"
                                    loading="lazy">
                            </a>
                        @else
                            <div class="bg-gray-800 flex items-center justify-center w-full h-full">
                                <span class="text-gray-500 text-sm">Advertisement Banner</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>