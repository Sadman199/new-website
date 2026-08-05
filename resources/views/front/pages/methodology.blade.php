@extends('front.layout.app')

@section('title', 'Our Methodology | BrokersCourt')
@section('meta_description', 'Learn how BrokersCourt evaluates forex brokers using transparent research, data analysis, and expert validation.')

@section('main_content')
<section class="pt-16 sm:pt-24 md:pt-32 lg:pt-40 pb-12 min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-gray-100">Our Methodology</h1>
            <p class="mt-3 max-w-2xl mx-auto text-lg text-gray-300">Transparent, unbiased broker evaluation you can trust.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
                <h2 class="text-xl font-bold mb-3 text-yellow-500">Data Collection</h2>
                <p class="text-gray-300 leading-relaxed">We gather broker data across regulation, fees, platforms, account types, support quality, and real trader feedback.</p>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
                <h2 class="text-xl font-bold mb-3 text-yellow-500">Hands-On Testing</h2>
                <p class="text-gray-300 leading-relaxed">Our team tests account opening, execution, deposits, withdrawals, and platform usability wherever possible.</p>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
                <h2 class="text-xl font-bold mb-3 text-yellow-500">Scoring Framework</h2>
                <p class="text-gray-300 leading-relaxed">Brokers are scored using weighted criteria including safety, costs, trading conditions, tools, and customer experience.</p>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
                <h2 class="text-xl font-bold mb-3 text-yellow-500">Expert Validation</h2>
                <p class="text-gray-300 leading-relaxed">Findings are reviewed by industry experts before publication to ensure accuracy and fairness.</p>
            </div>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('awards.index') }}" class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-black font-semibold rounded-lg transition">
                View Broker Awards
            </a>
        </div>
    </div>
</section>
@endsection
