@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Prop Firm FAQs')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header">
        <div><p class="ab-header__eyebrow">Prop firms</p><h1 class="ab-header__title">FAQs</h1><p class="ab-header__sub">Questions shown on public firm pages.</p></div>
        <div class="ab-header__actions"><a href="{{ route('admin_prop_firm_faqs_create') }}" class="ab-btn ab-btn--primary"><i class="fas fa-plus" aria-hidden="true"></i> Add FAQ</a></div>
    </header>
    @include('admin.prop-firms._nav', ['active' => 'faqs'])
    <div class="ab-panel">
        <form method="GET" class="ab-filters">
            <div class="ab-field"><label for="ab-q">Search</label><input id="ab-q" class="ab-input" type="search" name="q" value="{{ request('q') }}" placeholder="Question…"></div>
            <div class="ab-field">
                <label for="ab-firm">Firm</label>
                <select id="ab-firm" class="ab-select" name="prop_firm_id">
                    <option value="">All firms</option>
                    @foreach($propFirms as $firm)
                        <option value="{{ $firm->id }}" @selected(request('prop_firm_id') == $firm->id)>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div></div>
            <div class="ab-header__actions">
                <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                @if(request()->filled('q') || request()->filled('prop_firm_id'))
                    <a href="{{ route('admin_prop_firm_faqs_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                @endif
            </div>
        </form>
        @if($faqs->isEmpty())
            <div class="ab-empty"><h3>No FAQs found</h3><p>Add a FAQ, or attach them while editing a firm.</p><a href="{{ route('admin_prop_firm_faqs_create') }}" class="ab-btn ab-btn--primary">Add FAQ</a></div>
        @else
            <div class="ab-table-wrap">
                <table class="ab-table">
                    <thead><tr><th>Question</th><th>Firm</th><th>Order</th><th></th></tr></thead>
                    <tbody>
                        @foreach($faqs as $faq)
                            <tr>
                                <td>
                                    <p class="ab-broker__name">{{ \Illuminate\Support\Str::limit($faq->question, 70) }}</p>
                                    <div class="ab-pills">@if($faq->is_active)<span class="ab-pill ab-pill--ok">Active</span>@else<span class="ab-pill">Inactive</span>@endif</div>
                                </td>
                                <td>{{ $faq->propFirm?->name ?? '—' }}</td>
                                <td>{{ $faq->sort_order }}</td>
                                <td>
                                    <div class="ab-actions">
                                        <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firm_faqs_edit', $faq->id) }}">Edit</a>
                                        <form action="{{ route('admin_prop_firm_faqs_delete', $faq->id) }}" method="POST" data-ab-delete data-ab-name="this FAQ" data-ab-warn="This question will be removed from the firm page." data-ab-confirm="Delete FAQ">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ab-btn ab-btn--danger ab-btn--sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="ab-foot">{{ $faqs->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>
</div></div>
@endsection
