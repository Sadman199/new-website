@extends('admin.layout.app')
@include('admin.forex_bonuses._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'View Bonus')

@php
    $thumb = $bonus->imageUrl();
    $liveUrl = $bonus->detailUrl();
    $statusClass = $bonus->promotion_status === 'expired'
        ? 'ab-pill--danger'
        : ($bonus->promotion_status === 'limited-time' ? 'ab-pill--warn' : 'ab-pill--ok');
    $contentBlocks = [
        'Prize / offer' => $bonus->prize,
        'Description' => $bonus->description,
        'How to take part' => $bonus->how_to_participate,
        'Country restrictions' => $bonus->participate,
        'Details' => $bonus->details,
        'General terms' => $bonus->general_terms,
        'Eligibility' => $bonus->eligibility_criteria,
        'Type notes' => $bonus->bonus_type_details,
    ];
@endphp

@section('main_content')
<div class="ab-page ab-page--bonus">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($thumb)
                        <img src="{{ $thumb }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($bonus->title ?: 'B', 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">Bonus overview</p>
                    <h1 class="ab-header__title">{{ $bonus->title }}</h1>
                    <p class="ab-header__sub">{{ $bonus->promoTypeShort() }} · {{ $bonus->broker?->name ?? 'No broker' }}</p>
                    <div class="ab-pills">
                        <span class="ab-pill {{ $statusClass }}">{{ $bonus->promotionStatusLabel() }}</span>
                        @if($bonus->is_featured)
                            <span class="ab-pill ab-pill--warn">Featured</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($liveUrl)
                    <a href="{{ $liveUrl }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">Open live</a>
                @endif
                <a href="{{ route('admin_forex_bonus_edit', $bonus->id) }}" class="ab-btn ab-btn--primary">Edit</a>
                <a href="{{ route('admin_forex_bonus_show') }}" class="ab-btn ab-btn--ghost">All bonuses</a>
            </div>
        </header>

        <dl class="ab-dl">
            <div>
                <dt>Broker</dt>
                <dd>{{ $bonus->broker?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Type</dt>
                <dd>{{ $bonus->promoTypeShort() }}</dd>
            </div>
            <div>
                <dt>Offer</dt>
                <dd>{{ $bonus->headlineOffer() }}</dd>
            </div>
            <div>
                <dt>Min. deposit</dt>
                <dd>{{ $bonus->minDepositLabel() ?? '—' }}</dd>
            </div>
            <div>
                <dt>Max. credit</dt>
                <dd>{{ $bonus->maxCreditLabel() ?? '—' }}</dd>
            </div>
            <div>
                <dt>Eligible</dt>
                <dd>{{ $bonus->eligibleClientsLabel() ?? '—' }}</dd>
            </div>
            <div>
                <dt>Wagering</dt>
                <dd>{{ $bonus->wageringRequirementLabel() ?? '—' }}</dd>
            </div>
            <div>
                <dt>Volume</dt>
                <dd>{{ $bonus->volumeRequirementLabel() ?? '—' }}</dd>
            </div>
            <div>
                <dt>Published</dt>
                <dd>{{ $bonus->publish_date?->format('M j, Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt>Expiry</dt>
                <dd>{{ $bonus->expiryLabel() ?? 'No expiry' }}</dd>
            </div>
            <div>
                <dt>Author</dt>
                <dd>{{ $bonus->displayAuthorName() }}</dd>
            </div>
            <div>
                <dt>Written by</dt>
                <dd>{{ collect($credits)->firstWhere('role', 'written')['name'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Reviewed by</dt>
                <dd>{{ collect($credits)->firstWhere('role', 'edited')['name'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Fact checked by</dt>
                <dd>{{ collect($credits)->firstWhere('role', 'fact_checked')['name'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Offer link</dt>
                <dd>
                    @if($bonus->link)
                        <a href="{{ $bonus->link }}" target="_blank" rel="noopener">{{ $bonus->link }}</a>
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt>Last saved</dt>
                <dd>{{ $bonus->updated_at?->format('M j, Y H:i') ?? '—' }}</dd>
            </div>
        </dl>

        @if($thumb)
            <figure class="ab-hero-media">
                <img src="{{ $thumb }}" alt="{{ $bonus->title }}">
            </figure>
        @endif

        @foreach($contentBlocks as $heading => $html)
            @if(trim(strip_tags((string) $html)) !== '')
                <section class="ab-section">
                    <div class="ab-section__head"><h2>{{ $heading }}</h2></div>
                    <div class="ab-section__body rich-text">{!! $html !!}</div>
                </section>
            @endif
        @endforeach

        @if($bonus->meta_title || $bonus->meta_description || $bonus->meta_keywords)
            <section class="ab-section">
                <div class="ab-section__head"><h2>Search listing</h2></div>
                <div class="ab-section__body">
                    <dl class="ab-dl">
                        <div>
                            <dt>Search title</dt>
                            <dd>{{ $bonus->meta_title ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Keywords</dt>
                            <dd>{{ $bonus->meta_keywords ?: '—' }}</dd>
                        </div>
                    </dl>
                    @if($bonus->meta_description)
                        <p class="mt-3 mb-0">{{ $bonus->meta_description }}</p>
                    @endif
                </div>
            </section>
        @endif

        <div class="ab-save">
            <a href="{{ route('admin_forex_bonus_edit', $bonus->id) }}" class="ab-btn ab-btn--primary">Edit this bonus</a>
            <form action="{{ route('admin_forex_bonus_delete', $bonus->id) }}" method="POST" data-ab-delete data-ab-name="{{ $bonus->title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete bonus">
                @csrf
                @method('DELETE')
                <button class="ab-btn ab-btn--danger" type="submit">Delete bonus</button>
            </form>
        </div>
    </div>
</div>
@endsection
