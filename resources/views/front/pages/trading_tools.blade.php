@extends('front.layout.app')

@section('title', 'Trading Tools Dashboard | BrokersCourt')
@section('meta_description', 'Free forex trading tools dashboard: pip, position size, profit/loss, margin, risk, pivot points, Fibonacci and currency converter — calculate results instantly.')

@section('main_content')
@php
    $activeSlug = request('tool', optional($tools->first())->slug ?? 'pip');
@endphp

<div class="py-8 border-b bg-white">
    <div class="container max-w-7xl mx-auto w-full px-4 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Trading <span class="text-yellow-500">Tools</span> Dashboard
                </h1>
                <p class="mt-2 text-gray-600 max-w-2xl">All calculators in one place. Pick a tool tab, enter your values, and see results instantly.</p>
            </div>
            <nav class="text-sm bg-gray-100 rounded-full px-4 py-2 inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900"><i class="fas fa-home mr-2"></i>Home</a>
                <span class="mx-2 text-gray-400"><i class="fas fa-chevron-right text-xs"></i></span>
                <span class="font-medium text-gray-800">Tools</span>
            </nav>
        </div>
    </div>
</div>

<section class="py-10 bg-gray-50" id="toolsDashboard"
    data-calc-url="{{ route('trading.tools.calculate') }}"
    data-rates='@json($rates)'>
    <div class="container max-w-7xl mx-auto px-4">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            {{-- Tool tabs --}}
            <div class="border-b border-gray-200 bg-gray-50 overflow-x-auto">
                <div class="flex min-w-max" role="tablist" id="toolTabs">
                    @foreach($tools as $tool)
                        <button type="button"
                            class="tool-tab flex items-center gap-2 px-4 sm:px-5 py-3.5 text-sm font-semibold border-b-2 whitespace-nowrap transition
                                {{ $tool->slug === $activeSlug ? 'border-yellow-500 text-gray-900 bg-white' : 'border-transparent text-gray-500 hover:text-gray-800 hover:bg-white/70' }}"
                            data-tool="{{ $tool->slug }}"
                            aria-selected="{{ $tool->slug === $activeSlug ? 'true' : 'false' }}">
                            <i class="{{ $tool->icon }} text-yellow-500"></i>
                            <span>{{ $tool->name }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="p-5 sm:p-8">
                @foreach($tools as $tool)
                <div class="tool-panel {{ $tool->slug === $activeSlug ? '' : 'hidden' }}" data-panel="{{ $tool->slug }}">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800">{{ $tool->name }}</h2>
                        <p class="text-gray-500 text-sm mt-1">{{ $tool->short_description }}</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        {{-- Inputs --}}
                        <div class="lg:col-span-3 space-y-4">
                            @include('front.pages.partials.tools.' . $tool->slug, ['pairs' => $pairs, 'currencies' => $currencies])
                            <button type="button" class="calc-btn w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold px-6 py-3 rounded-lg transition" data-calc="{{ $tool->slug }}">
                                <i class="fas fa-bolt mr-2"></i>Calculate
                            </button>
                        </div>

                        {{-- Results --}}
                        <div class="lg:col-span-2">
                            <div class="bg-gray-900 text-white rounded-xl p-5 h-full sticky top-24">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-yellow-400 text-sm uppercase tracking-wide">Results</h3>
                                    <span class="text-xs text-gray-400 tool-status" data-status="{{ $tool->slug }}">Ready</span>
                                </div>
                                <div class="space-y-3 tool-results" data-results="{{ $tool->slug }}">
                                    <p class="text-gray-400 text-sm">Enter values and click Calculate to see results here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <p class="mt-6 text-xs text-gray-500 text-center max-w-3xl mx-auto">
            Tools use standard forex formulas with reference FX rates for planning. They are educational and do not constitute trading advice. Always verify with your broker’s contract specs.
        </p>
    </div>
</section>

<style>
    .td-field label { display:block; font-size:12px; font-weight:600; color:#4b5563; margin-bottom:6px; text-transform:uppercase; letter-spacing:.03em; }
    .td-field input, .td-field select {
        width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px;
        padding:10px 12px; color:#111827; font-size:14px;
    }
    .td-field input:focus, .td-field select:focus { outline:none; border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,.2); }
    .td-grid { display:grid; grid-template-columns:1fr; gap:14px; }
    @media (min-width:640px){ .td-grid-2{ grid-template-columns:1fr 1fr; } }
    .result-row { display:flex; justify-content:space-between; align-items:baseline; gap:12px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,.08); }
    .result-row:last-child { border-bottom:0; }
    .result-label { color:#9ca3af; font-size:13px; }
    .result-value { font-weight:700; font-size:15px; color:#fff; }
    .result-value.pos { color:#34d399; }
    .result-value.neg { color:#f87171; }
    .dir-btn { flex:1; padding:10px; border-radius:8px; border:1px solid #e5e7eb; background:#fff; font-weight:600; color:#6b7280; }
    .dir-btn.active-buy { background:#ecfdf5; border-color:#10b981; color:#047857; }
    .dir-btn.active-sell { background:#fef2f2; border-color:#ef4444; color:#b91c1c; }
</style>

<script>
(function () {
    var root = document.getElementById('toolsDashboard');
    if (!root) return;
    var calcUrl = root.getAttribute('data-calc-url');
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';

    // Tabs
    root.querySelectorAll('.tool-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.getAttribute('data-tool');
            root.querySelectorAll('.tool-tab').forEach(function (b) {
                b.classList.remove('border-yellow-500', 'text-gray-900', 'bg-white');
                b.classList.add('border-transparent', 'text-gray-500');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('border-yellow-500', 'text-gray-900', 'bg-white');
            btn.classList.remove('border-transparent', 'text-gray-500');
            btn.setAttribute('aria-selected', 'true');
            root.querySelectorAll('.tool-panel').forEach(function (p) {
                p.classList.toggle('hidden', p.getAttribute('data-panel') !== slug);
            });
            if (history.replaceState) {
                history.replaceState(null, '', '?tool=' + encodeURIComponent(slug));
            }
        });
    });

    // Buy/Sell toggles
    root.querySelectorAll('[data-dir-group]').forEach(function (group) {
        group.querySelectorAll('.dir-btn').forEach(function (b) {
            b.addEventListener('click', function () {
                group.querySelectorAll('.dir-btn').forEach(function (x) {
                    x.classList.remove('active-buy', 'active-sell');
                });
                var dir = b.getAttribute('data-dir');
                b.classList.add(dir === 'buy' ? 'active-buy' : 'active-sell');
                var hidden = group.querySelector('input[type="hidden"]');
                if (hidden) hidden.value = dir;
            });
        });
    });

    function collect(panel) {
        var data = {};
        panel.querySelectorAll('[data-field]').forEach(function (el) {
            var key = el.getAttribute('data-field');
            data[key] = el.value;
        });
        return data;
    }

    function money(n, ccy) {
        var v = Number(n);
        if (isNaN(v)) return '—';
        return (ccy ? ccy + ' ' : '') + v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 4 });
    }

    function render(slug, result) {
        var box = root.querySelector('.tool-results[data-results="' + slug + '"]');
        var status = root.querySelector('.tool-status[data-status="' + slug + '"]');
        if (!box) return;
        if (status) status.textContent = 'Updated';
        var rows = [];

        function add(label, value, cls) {
            rows.push('<div class="result-row"><span class="result-label">' + label + '</span><span class="result-value ' + (cls || '') + '">' + value + '</span></div>');
        }

        if (slug === 'pip') {
            add('Pip size', result.pip_size);
            add('Pip value', money(result.pip_value, result.account_currency));
            add('Position value', money(result.position_value, result.account_currency));
            add('Price used', result.price);
        } else if (slug === 'position') {
            add('Risk amount', money(result.risk_amount, result.account_currency));
            add('Position size', result.position_size_lots + ' lots');
            add('Pip value / lot', money(result.pip_value_per_lot, result.account_currency));
            add('Stop loss', result.sl_pips + ' pips');
        } else if (slug === 'profit') {
            add('Pips', result.pips);
            add('Pip value', money(result.pip_value, result.account_currency));
            add('Profit / Loss', money(result.profit_loss, result.account_currency), result.is_profit ? 'pos' : 'neg');
        } else if (slug === 'margin') {
            add('Position value', money(result.position_value, result.account_currency));
            add('Required margin', money(result.required_margin, result.account_currency));
            add('Leverage', '1:' + result.leverage);
        } else if (slug === 'risk') {
            add('Balance', money(result.balance));
            add('Risk amount', money(result.risk_amount), 'neg');
            add('Reward amount', money(result.reward_amount), 'pos');
            add('Break-even win rate', result.break_even_winrate + '%');
        } else if (slug === 'pivot') {
            add('Method', result.method);
            add('Pivot (PP)', result.pivot);
            add('R1', result.r1); add('R2', result.r2); add('R3', result.r3);
            add('S1', result.s1); add('S2', result.s2); add('S3', result.s3);
        } else if (slug === 'fibonacci') {
            (result.levels || []).forEach(function (lv) {
                add(lv.label, lv.price);
            });
        } else if (slug === 'converter') {
            add('Rate', '1 ' + result.from + ' = ' + result.rate + ' ' + result.to);
            add('Converted', money(result.converted, result.to), 'pos');
            if (result.note) rows.push('<p class="text-xs text-gray-400 mt-3">' + result.note + '</p>');
        }

        box.innerHTML = rows.join('') || '<p class="text-gray-400 text-sm">No results</p>';
    }

    root.querySelectorAll('.calc-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.getAttribute('data-calc');
            var panel = root.querySelector('.tool-panel[data-panel="' + slug + '"]');
            var status = root.querySelector('.tool-status[data-status="' + slug + '"]');
            if (status) status.textContent = 'Calculating…';
            var payload = collect(panel);
            payload.tool = slug;

            fetch(calcUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
            .then(function (res) {
                if (!res.ok || !res.j.result) {
                    if (status) status.textContent = 'Error';
                    var box = root.querySelector('.tool-results[data-results="' + slug + '"]');
                    if (box) box.innerHTML = '<p class="text-red-400 text-sm">' + (res.j.error || 'Calculation failed') + '</p>';
                    return;
                }
                render(slug, res.j.result);
            })
            .catch(function () {
                if (status) status.textContent = 'Error';
            });
        });
    });
})();
</script>
@endsection
