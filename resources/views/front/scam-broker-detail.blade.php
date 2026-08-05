@extends('front.layout.app')

@section('title', 'Is '.$broker->name.' a Scam? '.date('Y').' Warning & Review | BrokersCourt')
@section('meta_description', 'Why '.$broker->name.' has been flagged as a scam / high-risk broker: the reported issues, warning signs, and what to do if you have deposited funds.')

@push('head')
<link rel="canonical" href="{{ url('/scam-brokers/'.$broker->scam_slug) }}">
@endpush

@section('main_content')

<!-- Warning Hero -->
<section class="relative overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-rose-600 text-white mt-16">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 20%, #fff 1px, transparent 1px); background-size: 22px 22px;"></div>
    <div class="relative container px-4 mx-auto max-w-7xl py-12">
        <nav class="text-sm mb-6 inline-flex items-center bg-white/15 backdrop-blur rounded-full px-4 py-2">
            <a href="{{ route('home') }}" class="flex items-center text-white/90 hover:text-white transition"><i class="fas fa-home mr-2"></i> Home</a>
            <span class="mx-2 text-white/60"><i class="fas fa-chevron-right text-xs"></i></span>
            <a href="{{ route('scam_brokers') }}" class="text-white/90 hover:text-white transition">Scam Brokers</a>
            <span class="mx-2 text-white/60"><i class="fas fa-chevron-right text-xs"></i></span>
            <span class="font-medium">{{ $broker->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-shrink-0 w-20 h-20 rounded-2xl bg-white flex items-center justify-center overflow-hidden border border-white/25">
                @if($broker->logo)
                    <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} logo" class="w-14 h-14 object-contain grayscale">
                @else
                    <i class="fas fa-building text-gray-300 text-3xl"></i>
                @endif
            </div>
            <div>
                <div class="inline-flex items-center gap-2 bg-white/15 rounded-full px-3 py-1 text-xs font-semibold mb-3">
                    <i class="fas fa-exclamation-triangle"></i> Scam / High-Risk Warning
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Is {{ $broker->name }} a scam?</h1>
                <p class="mt-2 text-white/90 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                    <span><i class="far fa-calendar mr-1"></i>
                        @if($broker->scam_reported_date) Reported {{ \Carbon\Carbon::parse($broker->scam_reported_date)->format('M d, Y') }} @else Under review @endif
                    </span>
                    @if($broker->country)<span><i class="fas fa-map-marker-alt mr-1"></i>{{ $broker->country }}</span>@endif
                    @if($broker->rating)<span><i class="fas fa-star mr-1"></i>{{ rtrim(rtrim(number_format($broker->rating,1),'0'),'.') }}/5 rating</span>@endif
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Why flagged -->
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                <div class="bg-red-600 text-white px-6 py-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <h2 class="font-bold">Why {{ $broker->name }} is flagged</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">
                        {{ $broker->scam_reason ?: 'This broker has been flagged as high-risk. We strongly advise extreme caution before depositing any funds until the concerns below are resolved.' }}
                    </p>
                </div>
            </div>

            <!-- What we found -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">What we found</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        // Normalise values that may be stored as JSON arrays or HTML fragments.
                        $clean = function ($v) {
                            if (is_null($v) || $v === '') return null;
                            $s = is_string($v) ? $v : (string) $v;
                            $decoded = json_decode($s, true);
                            if (is_array($decoded)) {
                                $s = implode(', ', array_filter(array_map('strval', $decoded)));
                            }
                            $s = trim(strip_tags(html_entity_decode($s, ENT_QUOTES)));
                            return $s === '' ? null : $s;
                        };
                        $facts = [
                            ['fas fa-landmark', 'Regulation', $clean($broker->regulation)],
                            ['fas fa-globe', 'Country', $clean($broker->country)],
                            ['fas fa-coins', 'Minimum deposit', $broker->minimum_deposit ? '$'.rtrim(rtrim(number_format($broker->minimum_deposit,2),'0'),'.') : null],
                            ['fas fa-chart-line', 'Leverage', $clean($broker->leverage)],
                            ['fas fa-desktop', 'Platforms', $clean($broker->platforms)],
                            ['fas fa-certificate', 'Regulatory licenses', $clean($broker->regulatory_licenses)],
                        ];
                    @endphp
                    @foreach($facts as [$icon, $label, $value])
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="bg-red-50 text-red-600 p-2 rounded-lg"><i class="{{ $icon }}"></i></div>
                            <div class="min-w-0">
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold">{{ $label }}</p>
                                <p class="text-sm text-gray-700 break-words">{{ \Illuminate\Support\Str::limit($value ?: 'Not verified / undisclosed', 120) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-4"><i class="fas fa-info-circle mr-1"></i> Details are drawn from the broker's own claims and public records; unverified claims are a red flag in themselves.</p>
            </div>

            <!-- Warning signs -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Warning signs to watch for</h2>
                <ul class="space-y-3">
                    @foreach([
                        'No verifiable licence from a top-tier regulator (FCA, ASIC, CySEC, FSCA).',
                        'Withdrawal requests delayed, refused, or hit with surprise "fees" and "taxes".',
                        'Aggressive account managers pushing bigger deposits or "guaranteed" profits.',
                        'Bonus terms that lock your funds until an unrealistic trading volume is met.',
                        'Cloned or look-alike website impersonating a legitimate, regulated firm.',
                    ] as $sign)
                    <li class="flex items-start gap-3">
                        <i class="fas fa-times-circle text-red-500 mt-0.5"></i>
                        <span class="text-sm text-gray-600">{{ $sign }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- What to do -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">If you've already deposited with {{ $broker->name }}</h2>
                <ol class="space-y-4">
                    @foreach([
                        ['Stop depositing immediately', 'Do not send any more money, and ignore requests for "release fees" or "tax" to unlock a withdrawal.'],
                        ['Document everything', 'Save chats, emails, transaction IDs and screenshots of your account balance.'],
                        ['Request a withdrawal in writing', 'Formally request your funds and keep a record of the response (or lack of one).'],
                        ['Contact your bank / card provider', 'Ask about a chargeback or dispute, especially for card and wire deposits.'],
                        ['Report to the regulator', 'File a complaint with the relevant financial authority and warn other traders.'],
                    ] as $i => $step)
                    <li class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 text-red-700 font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $step[0] }}</p>
                            <p class="text-sm text-gray-500">{{ $step[1] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <div class="bg-red-600 text-white rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-lg flex items-center gap-2"><i class="fas fa-bullhorn"></i> Been scammed by {{ $broker->name }}?</h3>
                <p class="text-white/90 text-sm mt-2">Share your experience so we can warn other traders and keep this listing accurate.</p>
                <a href="{{ route('contact.us') }}" class="mt-4 inline-flex items-center gap-2 bg-white text-red-700 font-semibold px-4 py-2 rounded-full hover:bg-red-50 transition text-sm">
                    <i class="fas fa-flag"></i> Report this broker
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-3"><i class="fas fa-shield-alt text-green-600 text-xl"></i></div>
                <h3 class="font-bold text-gray-800">Trade with a regulated broker</h3>
                <p class="text-sm text-gray-500 mt-1">Protect your capital — choose a broker with verified tier-1 regulation.</p>
                <a href="{{ route('regulated_brokers') }}" class="mt-4 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-full transition text-sm">
                    <i class="fas fa-check-circle"></i> See trusted brokers
                </a>
            </div>

            @if($relatedScam->count())
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Other flagged brokers</h3>
                <div class="space-y-3">
                    @foreach($relatedScam as $rb)
                    <a href="{{ route('scam_broker_detail', ['slug' => $rb->scam_slug]) }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-lg border border-gray-100 bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($rb->logo)<img src="{{ asset($rb->logo) }}" alt="{{ $rb->name }}" class="w-7 h-7 object-contain grayscale">@else<i class="fas fa-building text-gray-300"></i>@endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-red-600 truncate">{{ $rb->name }}</p>
                            <span class="text-xs text-red-500"><i class="fas fa-exclamation-triangle"></i> Scam warning</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>

    <div class="max-w-7xl mx-auto mt-8">
        <a href="{{ route('scam_brokers') }}" class="inline-flex items-center gap-2 text-red-600 hover:text-red-800 font-semibold text-sm">
            <i class="fas fa-arrow-left"></i> Back to the full scam broker list
        </a>
    </div>
</section>
@endsection
