@extends('front.layout.app')

@section('title', 'Scam Broker List '.date('Y').' | Flagged & Blacklisted Forex Brokers | BrokersCourt')
@section('meta_description', 'Verified list of scam and blacklisted forex brokers. Check flagged brokers, the reasons they were reported, and protect yourself before you deposit.')

@push('head')
<link rel="canonical" href="{{ url('/scam-brokers') }}">
@endpush

@section('main_content')

<!-- Warning Hero -->
<section class="relative overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-rose-600 text-white mt-16">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 20%, #fff 1px, transparent 1px); background-size: 22px 22px;"></div>
    <div class="relative container px-4 mx-auto max-w-7xl py-14">
        <nav class="text-sm mb-6 inline-flex items-center bg-white/15 backdrop-blur rounded-full px-4 py-2">
            <a href="{{ route('home') }}" class="flex items-center text-white/90 hover:text-white transition">
                <i class="fas fa-home mr-2"></i> Home
            </a>
            <span class="mx-2 text-white/60"><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="font-medium">Scam Brokers</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center border border-white/25">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                    Scam Broker List
                </h1>
                <p class="mt-3 text-white/90 max-w-2xl leading-relaxed">
                    Brokers flagged for fraudulent behaviour, missing regulation, blocked withdrawals or
                    regulator warnings. Always verify a broker here before you deposit a single dollar.
                </p>
                <div class="mt-4 inline-flex items-center gap-2 bg-white/15 rounded-full px-4 py-1.5 text-sm font-semibold">
                    <i class="fas fa-ban"></i> {{ $scamCount }} flagged {{ \Illuminate\Support\Str::plural('broker', $scamCount) }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Safety notice -->
<section class="bg-red-50 border-b border-red-100">
    <div class="container px-4 mx-auto max-w-7xl py-4">
        <div class="flex items-start gap-3 text-sm text-red-800">
            <i class="fas fa-shield-alt mt-0.5"></i>
            <p>
                <strong>How we flag brokers:</strong> we rely on public regulator warnings, verified user complaints,
                and evidence of unregulated activity. If you believe a listing is inaccurate,
                <a href="{{ route('contact.us') }}" class="underline font-semibold">contact us</a>.
            </p>
        </div>
    </div>
</section>

<section class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-[40vh]">
    <div class="max-w-7xl mx-auto">

        <!-- Search -->
        <form method="GET" action="{{ route('scam_brokers') }}" class="mb-8 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold text-gray-800">Flagged &amp; Blacklisted Brokers</h2>
            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search a broker name..."
                    class="w-full pl-11 pr-4 py-2.5 rounded-full border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none text-sm">
            </div>
        </form>

        @if($scamBrokers->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($scamBrokers as $broker)
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                <div class="flex items-center gap-3 p-5 border-b border-gray-100">
                    <div class="w-12 h-12 rounded-xl border border-gray-100 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($broker->logo)
                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} logo" class="w-9 h-9 object-contain grayscale">
                        @else
                            <i class="fas fa-building text-gray-300 text-xl"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 truncate">{{ $broker->name }}</h3>
                        <span class="inline-flex items-center gap-1 mt-0.5 bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                            <i class="fas fa-exclamation-triangle"></i> Scam Warning
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <p class="text-sm text-gray-600 leading-relaxed flex-1">
                        {{ \Illuminate\Support\Str::limit($broker->scam_reason ?: 'This broker has been flagged as high-risk. Trade with extreme caution.', 160) }}
                    </p>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                        <span>
                            <i class="far fa-calendar mr-1"></i>
                            @if($broker->scam_reported_date)
                                Reported {{ \Carbon\Carbon::parse($broker->scam_reported_date)->format('M d, Y') }}
                            @else
                                Under review
                            @endif
                        </span>
                        @if($broker->slug)
                        <a href="{{ route('scam_broker_detail', ['slug' => $broker->scam_slug]) }}" class="text-red-600 hover:text-red-800 font-semibold">
                            Read details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $scamBrokers->links() }}
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-200 py-16 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-green-50 flex items-center justify-center mb-4">
                <i class="fas fa-shield-alt text-2xl text-green-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">No scam brokers found</h3>
            <p class="text-gray-500 mt-1 text-sm">
                @if($search)
                    No flagged broker matches "<strong>{{ $search }}</strong>".
                    <a href="{{ route('scam_brokers') }}" class="text-red-600 font-medium">Clear search</a>
                @else
                    There are currently no brokers flagged as scam.
                @endif
            </p>
        </div>
        @endif

        <!-- Tips -->
        <div class="mt-14 bg-white rounded-2xl border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">How to spot a scam broker</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-3">
                    <div class="bg-red-50 p-2 rounded-lg text-red-600"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <h3 class="font-semibold text-gray-800">No real regulation</h3>
                        <p class="text-sm text-gray-500 mt-1">Missing or fake licences from FCA, ASIC, CySEC and similar bodies.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="bg-red-50 p-2 rounded-lg text-red-600"><i class="fas fa-hand-holding-usd"></i></div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Withdrawal problems</h3>
                        <p class="text-sm text-gray-500 mt-1">Delays, endless verification, or surprise fees when you cash out.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="bg-red-50 p-2 rounded-lg text-red-600"><i class="fas fa-bullhorn"></i></div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Guaranteed profits</h3>
                        <p class="text-sm text-gray-500 mt-1">Aggressive sales calls and promises of risk-free returns.</p>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <a href="{{ route('regulated_brokers') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-full transition">
                    <i class="fas fa-shield-alt"></i> See regulated &amp; trusted brokers
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
