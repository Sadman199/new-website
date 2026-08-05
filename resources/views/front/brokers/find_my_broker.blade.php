@extends('front.layout.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)

@push('head')
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
@endpush

@section('main_content')

<style>
    .fmb-page { background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); min-height: 70vh; }
    .fmb-panel {
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.9);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(15,23,42,0.06);
    }
    .fmb-group-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 10px;
    }
    .fmb-check {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        border-radius: 8px;
        font-size: 13px;
        color: #334155;
        cursor: pointer;
        transition: background .15s;
    }
    .fmb-check:hover { background: #eff6ff; }
    .fmb-check input { accent-color: #2563eb; }
    .fmb-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 500;
        color: #1d4ed8;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
    }
    .fmb-chip button {
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
        color: #64748b;
        cursor: pointer;
    }
    .fmb-chip button:hover { color: #1e293b; }
    .fmb-results.is-loading { opacity: 0.45; pointer-events: none; }
    .fmb-drawer {
        position: fixed;
        inset: 0;
        z-index: 1100;
        display: none;
    }
    .fmb-drawer.is-open { display: block; }
    .fmb-drawer-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15,23,42,0.45);
    }
    .fmb-drawer-panel {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: min(92vw, 360px);
        overflow-y: auto;
        background: #fff;
        padding: 16px;
        box-shadow: 12px 0 40px rgba(15,23,42,0.18);
    }
    @media (min-width: 1024px) {
        .fmb-drawer { display: none !important; }
    }
</style>

<div class="fmb-page">
    <div class="border-b border-gray-200/80 bg-white/70 backdrop-blur">
        <div class="container max-w-7xl mx-auto w-full px-4 mt-20 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Find My <span style="color: rgba(245, 158, 11, 1);">Broker</span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Filter by deposit, regulation, platform, markets, and more — instantly.</p>
                </div>
                <nav class="text-sm bg-gray-100 rounded-full px-4 py-2 inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="font-medium text-gray-800">Find My Broker</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container max-w-7xl mx-auto w-full px-4 py-8" id="fmb-app" data-endpoint="{{ route('find_my_broker') }}">
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Desktop filters --}}
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="fmb-panel p-4 sticky top-24 max-h-[calc(100vh-7rem)] overflow-y-auto">
                    @include('front.brokers.partials.find_my_broker_filters', ['idPrefix' => 'desk'])
                </div>
            </aside>

            {{-- Results --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-3 mb-4 lg:hidden">
                    <button type="button" id="fmb-open-filters" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-sm font-semibold text-gray-800 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 12h12M10 20h4"/></svg>
                        Filters
                        @if(count($activeChips))
                            <span class="bg-blue-600 text-white text-xs rounded-full px-2 py-0.5">{{ count($activeChips) }}</span>
                        @endif
                    </button>
                </div>

                <div id="fmb-results" class="fmb-results">
                    @include('front.brokers.partials.find_my_broker_results')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile filter drawer --}}
<div class="fmb-drawer" id="fmb-drawer" aria-hidden="true">
    <div class="fmb-drawer-backdrop" id="fmb-close-filters"></div>
    <div class="fmb-drawer-panel">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Filters</h2>
            <button type="button" class="p-2 text-gray-500" id="fmb-close-filters-btn" aria-label="Close filters">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @include('front.brokers.partials.find_my_broker_filters', ['idPrefix' => 'mob'])
    </div>
</div>

<script src="{{ asset('js/find-my-broker.js') }}?v={{ @filemtime(public_path('js/find-my-broker.js')) ?: time() }}" defer></script>
@endsection
