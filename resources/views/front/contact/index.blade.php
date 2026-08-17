@extends('front.layout.app')

@section('title', 'Contact Us | BrokersCourt')
@section('meta_description', 'Contact BrokersCourt for broker review questions, partnership inquiries, editorial feedback, and scam broker reports.')
@section('canonical', route('contact'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/contact-index.css') }}?v=6">
@endpush

@section('main_content')
<div class="cti-page">
    <header class="cti-hero">
        <div class="cti-hero__bg" aria-hidden="true"></div>
        <div class="container cti-wrap">
            <nav class="cti-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Contact</span>
            </nav>

            <p class="cti-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                </svg>
                Get in touch
            </p>
            <h1 class="cti-hero__title">Contact <span class="cti-hero__accent">BrokersCourt</span></h1>
            @if($page['detail'])
                <p class="cti-hero__subtitle">{!! strip_tags($page['detail'], '<p><br><strong><em>') !!}</p>
            @endif
        </div>
    </header>

    <div class="cti-body">
        <div class="container cti-wrap">
            @if(session('success'))
                <div class="cti-alert cti-alert--success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="cti-alert cti-alert--error" role="alert">
                    <ul class="cti-alert__list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="cti-notice">
                <strong>Editorial inbox:</strong> we read every message. For urgent scam reports, include broker name, website, and any evidence you can share.
                <a href="{{ route('broker.scam_checker') }}">Check a broker first</a>.
            </div>

            <div class="cti-channels">
                @foreach($channels as $channel)
                    @if($channel['href'])
                        <a href="{{ $channel['href'] }}" class="cti-channel-card">
                    @else
                        <div class="cti-channel-card">
                    @endif
                        <span class="cti-channel-card__icon" aria-hidden="true">
                            @if($channel['key'] === 'email')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            @elseif($channel['key'] === 'phone')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            @elseif($channel['key'] === 'office')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            @endif
                        </span>
                        <span class="cti-channel-card__body">
                            <strong class="cti-channel-card__label">{{ $channel['label'] }}</strong>
                            <span class="cti-channel-card__value">{{ $channel['value'] }}</span>
                            <span class="cti-channel-card__hint">{{ $channel['hint'] }}</span>
                        </span>
                        @if($channel['href'])
                            <span class="cti-channel-card__arrow" aria-hidden="true">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                            </span>
                        @endif
                    @if($channel['href'])
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="cti-layout">
                <div class="cti-panel">
                    <div class="cti-panel__head">
                        <h2 class="cti-panel__title" id="ctiFormTitle">Send us a message</h2>
                        <p class="cti-panel__desc">Fill in the form below. We typically respond within one business day.</p>
                    </div>

                    <form action="{{ route('contact_form_submit') }}" method="post" class="cti-form" novalidate aria-labelledby="ctiFormTitle">
                        @csrf

                        <div class="cti-honeypot" aria-hidden="true">
                            <label for="extra_field">Leave blank</label>
                            <input type="text" name="extra_field" id="extra_field" tabindex="-1" autocomplete="off">
                            <label for="website_url">Website</label>
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                            <label for="company">Company</label>
                            <input type="text" name="company" id="company" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="cti-form__grid">
                            <div class="cti-field">
                                <label for="name">Full name</label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="@error('name') is-invalid @enderror"
                                       placeholder="Your name"
                                       required>
                                @error('name')
                                    <span class="cti-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="cti-field">
                                <label for="email">Email address</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="@error('email') is-invalid @enderror"
                                       placeholder="you@example.com"
                                       required>
                                @error('email')
                                    <span class="cti-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="cti-field">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject" class="@error('subject') is-invalid @enderror">
                                <option value="General inquiry" @selected(old('subject') === 'General inquiry')>General inquiry</option>
                                <option value="Broker review question" @selected(old('subject') === 'Broker review question')>Broker review question</option>
                                <option value="Partnership / media" @selected(old('subject') === 'Partnership / media')>Partnership / media</option>
                                <option value="Report a scam broker" @selected(old('subject') === 'Report a scam broker')>Report a scam broker</option>
                                <option value="Website feedback" @selected(old('subject') === 'Website feedback')>Website feedback</option>
                            </select>
                            @error('subject')
                                <span class="cti-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="cti-field">
                            <label for="message">Message</label>
                            <textarea id="message"
                                      name="message"
                                      rows="6"
                                      class="@error('message') is-invalid @enderror"
                                      placeholder="Tell us how we can help..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="cti-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="cti-field cti-field--checkbox">
                            <input type="checkbox" id="terms" name="terms" value="1" @checked(old('terms')) required>
                            <label for="terms">
                                I agree to the <a href="{{ route('terms') }}">terms and conditions</a> and
                                <a href="{{ route('privacy') }}">privacy policy</a>.
                            </label>
                        </div>

                        <button type="submit" class="cti-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-10.5M3.75 13.5H9m-5.25 0V8"/>
                            </svg>
                            Send message
                        </button>
                    </form>
                </div>

                <aside class="cti-sidebar" aria-label="Contact sidebar">
                    <p class="cti-sidebar__title">Quick links</p>
                    <nav class="cti-sidebar__nav">
                        @foreach($quick_links as $link)
                            <a href="{{ route($link['route']) }}" class="cti-sidebar__link">
                                <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                                <span>
                                    <strong>{{ $link['label'] }}</strong>
                                    <small>{{ $link['desc'] }}</small>
                                </span>
                            </a>
                        @endforeach
                    </nav>

                    @if(!empty($global_social_item_data) && count($global_social_item_data))
                        <div class="cti-sidebar__social">
                            <p class="cti-sidebar__title">Follow us</p>
                            <div class="cti-social">
                                @foreach($global_social_item_data as $item)
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $item->name }}">
                                        <i class="{{ $item->icon }}" aria-hidden="true"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($page['map']))
                        <div class="cti-sidebar__map">
                            <p class="cti-sidebar__title">Location</p>
                            <div class="cti-map">{!! $page['map'] !!}</div>
                        </div>
                    @endif
                </aside>
            </div>

            <p class="cti-disclaimer">
                BrokersCourt is an independent comparison and review site. Messages are handled by our editorial team —
                we do not provide personalised trading advice. For account-specific issues, contact your broker directly.
            </p>
        </div>
    </div>
</div>
@endsection
