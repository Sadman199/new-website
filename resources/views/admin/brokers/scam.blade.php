@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Scam brokers')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Risk</p>
                <h1 class="ab-header__title">Scam flagged brokers</h1>
                <p class="ab-header__sub">Listings marked high-risk from the broker profile.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_show') }}" class="ab-btn ab-btn--ghost">All brokers</a>
            </div>
        </header>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_broker_scam') }}" class="ab-filters" style="grid-template-columns: minmax(0,1fr) auto;">
                <div class="ab-field">
                    <label for="ab-q">Search</label>
                    <input id="ab-q" class="ab-input" type="search" name="q" value="{{ request('q') }}" placeholder="Name or slug…">
                </div>
                <button type="submit" class="ab-btn ab-btn--primary">Search</button>
            </form>

            @if($brokers->isEmpty())
                <div class="ab-empty">
                    <h3>No brokers are flagged as scam</h3>
                    <p>Turn on the scam flag from a broker’s regulation section to add one here.</p>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Broker</th>
                                <th>Warning</th>
                                <th>Reported</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brokers as $broker)
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                @if($broker->mediaUrl())
                                                    <img src="{{ $broker->mediaUrl() }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                                @endif
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $broker->name }}</p>
                                                <p class="ab-broker__meta">{{ $broker->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($broker->scam_reason, 140) ?: '—' }}</td>
                                    <td>{{ optional($broker->scam_reported_date)->format('M j, Y') ?: '—' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_broker_view', $broker->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_broker_edit', $broker->id) }}">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="ab-foot">{{ $brokers->links('pagination::bootstrap-4') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
