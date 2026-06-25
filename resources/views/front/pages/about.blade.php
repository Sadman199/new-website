@extends('front.layout.app')

@section('main_content')
<!-- Main Content -->
<section class="pt-16 sm:pt-24 md:pt-32 lg:pt-40 pb-12 min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight sm:text-4xl text-gray-400">
                About Us
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-lg text-gray-300">Last Updated: May 19, 2025</p>
        </div>

        <!-- Our Mission -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6 mb-8">
            <div class="flex items-center mb-6 space-x-4">
                <!-- Icon only, no background -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h2 class="text-2xl font-bold">Our Mission</h2>
            </div>
            <p class="text-gray-300 leading-relaxed">
                At BrokersCourt, we're dedicated to empowering traders worldwide by providing clear, unbiased, and comprehensive reviews of forex brokers. Our mission is to equip traders—whether beginners or professionals—with trusted insights, helping them navigate the complexities of the forex market with confidence.
            </p>
        </div>

        <!-- Who We Are -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6 mb-8">
            <div class="flex items-center mb-6 space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-2xl font-bold">Who We Are</h2>
            </div>
            <p class="text-gray-300 leading-relaxed mb-4">
                BrokersCourt was founded by a passionate team of forex traders, financial analysts, and market strategists who understand the importance of reliable information in making smart trading decisions. We believe transparency and accuracy are key to helping traders succeed.
            </p>
            <p class="text-gray-300 leading-relaxed">
                Our experts combine years of industry experience with deep market knowledge to provide detailed evaluations of brokers, focusing on their reliability, trading platforms, fees, and customer service. We are committed to maintaining an independent voice in the forex community.
            </p>
        </div>

        <!-- What We Do -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6 mb-8">
            <div class="flex items-center mb-6 space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h2 class="text-2xl font-bold">What We Do</h2>
            </div>
            <p class="text-gray-300 leading-relaxed mb-6">
                We rigorously evaluate forex brokers against a wide range of criteria to ensure our users have access to trustworthy and up-to-date information. Our analysis covers:
            </p>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-yellow-500 font-medium mb-2">Regulation & Security</h3>
                    <p class="text-gray-300 text-sm">We verify brokers’ licensing and compliance with financial authorities, alongside advanced security protocols to safeguard client funds and data privacy.</p>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-yellow-400 font-medium mb-2">Trading Conditions</h3>
                    <p class="text-gray-300 text-sm">Detailed scrutiny of spreads, commissions, leverage options, slippage, and execution speeds to find the best trading environment.</p>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-yellow-400 font-medium mb-2">Platforms & Tools</h3>
                    <p class="text-gray-300 text-sm">Assessment of user-friendly trading platforms, charting capabilities, mobile apps, and the availability of automated trading tools.</p>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-yellow-400 font-medium mb-2">Customer Support</h3>
                    <p class="text-gray-300 text-sm">We test broker support responsiveness, multilingual service availability, and helpfulness across multiple contact methods.</p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6 mb-8">
            <div class="flex items-center mb-6 space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <h2 class="text-2xl font-bold">Why Choose BrokersCourt</h2>
            </div>
            <div class="space-y-6">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mt-1 mr-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-white">Unbiased Reviews</h3>
                        <p class="text-gray-300">We uphold total independence and never accept payments that could influence our ratings, ensuring our reviews remain honest and trustworthy.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mt-1 mr-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-white">Comprehensive Analysis</h3>
                        <p class="text-gray-300">Our 50+ point evaluation framework covers every essential aspect of trading with a broker, from fees to platform usability and customer service quality.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mt-1 mr-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-white">Real Trading Tests</h3>
                        <p class="text-gray-300">We conduct live account testing under actual market conditions to verify broker claims, execution quality, and service reliability.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mt-1 mr-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-white">Updated Regularly</h3>
                        <p class="text-gray-300">The forex market is dynamic, so we continuously review and update our content to reflect the latest broker changes and market trends.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
@endsection