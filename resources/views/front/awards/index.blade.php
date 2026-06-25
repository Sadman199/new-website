@extends('front.layout.app')
@section('title', 'BrokersCourt | Awards & Account Types')
@section('main_content')
@php
use Illuminate\Support\Str;
@endphp

<section class="min-h-screen py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-black overflow-hidden relative pt-20 py-12 px-4 sm:px-6 lg:px-8 mt-12">
  <div class="max-w-6xl mx-auto">
    <div class="flex flex-col items-center text-center">
      <!-- Award Badge -->
      <div class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-400 to-amber-600 text-amber-900 px-6 py-2 rounded-full shadow-lg mb-8">
        <span class="text-2xl">🏆</span>
        <div class="font-bold tracking-wide text-white">Best Brokers 2025</div>
      </div>
      
      <h1 class="text-3.5xl md:text-5xl font-bold text-white mb-5 max-w-2.5xl leading-snug">
          Awards 2025: <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 bg-clip-text text-transparent">
             Leading Forex Brokers of the Year
          </span>
      </h1>


      
      <!-- Subtitle -->
     <p class="font-semibold text-gray-300 mb-12 max-w-2xl leading-relaxed">
          Discover the top performers in the forex industry for 2025. Our prestigious awards recognize excellence, innovation, and outstanding service in forex brokerage.
      </p>

      
     <!-- Trust Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl w-full">

            <!-- Regulated Brokers -->
            <div class="bg-gray-800/60 rounded-xl p-5 border border-gray-700 hover:border-blue-400 
                        transition duration-300 flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 
                            flex items-center justify-center mb-3 shadow-md">
                    <i class="fa fa-check-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold text-base">Regulated Brokers</h3>
            </div>

            <!-- Verified Awards -->
            <div class="bg-gray-800/60 rounded-xl p-5 border border-gray-700 hover:border-emerald-400 
                        transition duration-300 flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 
                            flex items-center justify-center mb-3 shadow-md">
                    <i class="fa fa-check-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold text-base">Verified Awards</h3>
            </div>

            <!-- Updated Quarterly -->
            <div class="bg-gray-800/60 rounded-xl p-5 border border-gray-700 hover:border-rose-400 
                        transition duration-300 flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-rose-500 to-pink-600 
                            flex items-center justify-center mb-3 shadow-md">
                    <i class="fa fa-check-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold text-base">Updated Quarterly</h3>
            </div>

        </div>
  
    </div>
  </div>

    <div class="pt-20">
      <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-white drop-shadow-lg">
              Explore Broker Awards
          </h2>
          <p class="text-gray-400 mt-3 max-w-2xl mx-auto">
              Discover top-rated brokers recognized for excellence in performance, trust, execution, and overall service. 
              Choose an award category to view brokers that truly stand out.
          </p>
      </div>

     

      <!-- Awards Grid -->
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           @foreach ($awardColumns as $column)
                @foreach ($column as $award)
                   <a href="{{ route('brokers.byAward', ['award' => $award['slug']]) }}" 
                    class="group relative block p-6 rounded-2xl bg-gray-800/30 backdrop-blur-md border border-white/20 shadow-lg 
                            transition-all duration-300 hover:shadow-xl hover:border-{{ $award['color'] }}-400/40">
            
                      <!-- Award Content Wrapper -->
                      <div class="relative flex items-center space-x-4">
            
                          <!-- Left wing icon (grayscale) -->
                          <img src="{{ asset('public/award_2.png') }}" alt="" 
                               class="h-16 mr-2 filter grayscale opacity-80 group-hover:opacity-100 transition-opacity">
            
                          <!-- Main Content -->
                          <div class="flex-1 min-w-0 text-center">
                              <h3 class="text-lg font-bold text-white group-hover:text-{{ $award['color'] }}-300 
                                        transition-colors duration-300 mb-2">
                                  {{ $award['name'] }}
                              </h3>
                              <p class="text-gray-300 text-sm leading-relaxed group-hover:text-gray-200 
                                        transition-colors duration-300">
                                  {{ $award['description'] }}
                              </p>
            
                              <!-- Interactive Button -->
                              <div class="mt-4 flex items-center justify-center text-{{ $award['color'] }}-400 group-hover:text-{{ $award['color'] }}-300 
                                          transition-colors duration-300">
                                  <span class="text-sm font-medium">Explore Brokers</span>
                                  <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" 
                                      fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                  </svg>
                              </div>
                          </div>
            
                          <!-- Right wing icon (grayscale) -->
                          <img src="{{ asset('public/award_1.png') }}" alt="" 
                               class="h-16 ml-2 filter grayscale opacity-80 group-hover:opacity-100 transition-opacity">
            
                      </div>
            
                      <!-- Decorative Ping -->
                      <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                          <div class="w-2 h-2 bg-{{ $award['color'] }}-400 rounded-full animate-ping"></div>
                      </div>
                  </a>
                @endforeach
            @endforeach

      </div>
    </div>
</div>
</section>
<section class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-3 text-slate-600 mb-6">
                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                <span class="text-sm font-medium tracking-wider uppercase">Methodology 2025</span>
                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
            </div>
            <h1 class="text-5xl font-light text-gray-700 mb-6 tracking-tight">
                Excellence in <span class="font-semibold">Broker Selection</span>
            </h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                A rigorous, data-driven approach to identifying brokers that deliver exceptional trading experiences through innovation, reliability, and client-centric services.
            </p>
        </div>

        <!-- Advanced Grid Layout -->
        <div class="grid grid-cols-12 gap-8 mb-20">
            <!-- Main Content Area -->
            <div class="col-span-12 lg:col-span-12 space-y-8">
                <!-- Evaluation Framework -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-8 flex items-center gap-4">
                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-white text-sm"></i>
                        </div>
                        Evaluation Framework
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Regulation Card -->
                        <div class="group p-6 rounded-xl border border-slate-200 hover:border-blue-300 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-shield-alt text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Regulation & Security</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Comprehensive verification of regulatory compliance, fund protection measures, and security infrastructure.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Trading Conditions -->
                        <div class="group p-6 rounded-xl border border-slate-200 hover:border-green-300 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                    <i class="fas fa-chart-line text-green-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Trading Conditions</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        In-depth analysis of execution quality, pricing transparency, and cost efficiency across market conditions.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Platform Technology -->
                        <div class="group p-6 rounded-xl border border-slate-200 hover:border-purple-300 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                                    <i class="fas fa-desktop text-purple-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Platform & Technology</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Assessment of platform stability, technological innovation, and user experience across devices.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Client Experience -->
                        <div class="group p-6 rounded-xl border border-slate-200 hover:border-amber-300 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                                    <i class="fas fa-users text-amber-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-yellow-300 mb-2">Client Experience</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Evaluation of support quality, educational resources, and overall client satisfaction metrics.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Process Timeline -->
         <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Left Section - Header and Description -->
                <div class="bg-gray-900 p-10 text-white flex flex-col justify-center">
                    <div class="mb-6">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-6 backdrop-blur-sm">
                            <i class="fas fa-cog text-white text-xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold mb-4">Four-Phase Evaluation</h2>
                        <p class="text-blue-100 leading-relaxed">
                            Our rigorous evaluation process ensures comprehensive assessment of brokers through multiple stages of analysis, testing, and validation.
                        </p>
                    </div>
                    
                    <div class="mt-8 bg-white/10 p-6 rounded-xl backdrop-blur-sm">
                        <h3 class="font-semibold text-lg mb-3">Methodology Highlights</h3>
                        <ul class="space-y-2 text-blue-100">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-2"></i>
                                <span>150+ brokers initially screened</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-2"></i>
                                <span>6M+ simulated trades analyzed</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-2"></i>
                                <span>50+ weighted metrics evaluated</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-2"></i>
                                <span>2,500+ client reviews incorporated</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Right Section - Process Steps -->
                <div class="p-10">
                    <div class="relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-gray-200 to-gray-100 hidden md:block"></div>
                        
                        <div class="space-y-10">
                            <!-- Phase 1 -->
                            <div class="flex gap-6 group relative">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-b from-gray-900 via-gray-800 rounded-2xl flex items-center justify-center relative z-10 transform group-hover:scale-110 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                    <span class="text-white font-bold text-lg">01</span>
                                    <div class="absolute -inset-1 bg-blue-500/20 rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                </div>
                                <div class="flex-1 pb-2">
                                    <div class="flex items-center mb-2">
                                        <h3 class="font-bold text-gray-700 text-xl">Data Intelligence</h3>
                                        <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Collection</span>
                                    </div>
                                  <p class="text-gray-700 text-sm font-medium">
                                        Comprehensive data collection from regulatory databases, broker documentation, and market data feeds. Initial screening of 150+ brokers.
                                    </p>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Regulatory Data</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Documentation</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Market Feeds</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Phase 2 -->
                            <div class="flex gap-6 group relative">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-b from-gray-900 via-gray-800 rounded-2xl flex items-center justify-center relative z-10 transform group-hover:scale-110 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                    <span class="text-white font-bold text-lg">02</span>
                                    <div class="absolute -inset-1 bg-green-500/20 rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                </div>
                                <div class="flex-1 pb-2">
                                    <div class="flex items-center mb-2">
                                        <h3 class="font-bold text-gray-700 text-xl">Live Environment Testing</h3>
                                        <span class="ml-3 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Testing</span>
                                    </div>
                                    <p class="text-gray-700 text-sm font-medium">
                                        Real-world testing of execution speeds, platform performance, and support responsiveness across 6M+ simulated trades.
                                    </p>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Execution Speed</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Platform Performance</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Support Testing</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Phase 3 -->
                            <div class="flex gap-6 group relative">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-b from-gray-900 via-gray-800 rounded-2xl flex items-center justify-center relative z-10 transform group-hover:scale-110 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                    <span class="text-white font-bold text-lg">03</span>
                                    <div class="absolute -inset-1 bg-purple-500/20 rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                </div>
                                <div class="flex-1 pb-2">
                                    <div class="flex items-center mb-2">
                                        <h3 class="font-bold text-gray-700 text-xl">Analytical Scoring</h3>
                                        <span class="ml-3 bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Analysis</span>
                                    </div>
                                    <p class="text-gray-700 text-sm font-medium">
                                        Weighted scoring across 50+ metrics, incorporating quantitative data and qualitative assessments from 2,500+ client reviews.
                                    </p>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">50+ Metrics</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Weighted Scoring</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Client Reviews</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Phase 4 -->
                            <div class="flex gap-6 group relative">
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-b from-gray-900 via-gray-800 rounded-2xl flex items-center justify-center relative z-10 transform group-hover:scale-110 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                    <span class="text-white font-bold text-lg">04</span>
                                    <div class="absolute -inset-1 bg-yellow-500/20 rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="font-bold text-gray-700 text-xl">Expert Validation</h3>
                                        <span class="ml-3 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Validation</span>
                                    </div>
                                    <p class="text-gray-700 text-sm font-medium">
                                        Final review by industry experts, validation of findings, and assignment to 12 specialized award categories.
                                    </p>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Industry Experts</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Validation</span>
                                        <span class="text-xs font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full">Award Categories</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom CTA -->
        <div class="text-center pt-12">
            <div class="inline-flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-1 h-1 bg-slate-400 rounded-full"></div>
                <span class="text-sm font-medium">Transparent & Unbiased</span>
                <div class="w-1 h-1 bg-slate-400 rounded-full"></div>
            </div>
            <p class="text-slate-600 max-w-2xl mx-auto">
                Our methodology ensures every award reflects genuine excellence, backed by comprehensive data analysis and real-world testing.
            </p>
        </div>
    </div>
</section>

@endsection