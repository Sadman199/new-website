@extends('front.layout.app')

@section('title', 'Contact Us | BrokersCourt')
@section('meta_description', 'Contact BrokersCourt for broker review questions, partnership inquiries, editorial feedback, and scam broker reports.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/contact-index.css') }}?v=1">
@endpush

@section('main_content')
<div class="cti-page">
    <div class="cti-wrap">
        <header class="cti-hero">
            <span class="cti-hero__badge">Get in touch</span>
            <h1 class="cti-hero__title">{{ $page['title'] }}</h1>
            <p class="cti-hero__subtitle">{{ $page['detail'] }}</p>
        </header>

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

        <div class="cti-layout">
            <section class="cti-form-panel" aria-labelledby="ctiFormTitle">
                <h2 class="cti-panel__title" id="ctiFormTitle">Send us a message</h2>
                <p class="cti-panel__lead">Fill in the form below and we will respond as soon as possible.</p>

                <form action="{{ route('contact_form_submit') }}" method="post" class="cti-form" novalidate>
                    @csrf

                    <div class="cti-honeypot" aria-hidden="true">
                        <label for="extra_field">Leave blank</label>
                        <input type="text" name="extra_field" id="extra_field" tabindex="-1" autocomplete="off">
                        <label for="website_url">Website</label>
                        <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        <label for="company">Company</label>
                        <input type="text" name="company" id="company" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="cti-field">
                        <label for="name" class="cti-label">Full name</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="cti-input @error('name') is-invalid @enderror"
                               placeholder="Your name"
                               required>
                        @error('name')
                            <span class="cti-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cti-field">
                        <label for="email" class="cti-label">Email address</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="cti-input @error('email') is-invalid @enderror"
                               placeholder="you@example.com"
                               required>
                        @error('email')
                            <span class="cti-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cti-field">
                        <label for="subject" class="cti-label">Subject</label>
                        <select id="subject" name="subject" class="cti-input @error('subject') is-invalid @enderror">
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
                        <label for="message" class="cti-label">Message</label>
                        <textarea id="message"
                                  name="message"
                                  rows="6"
                                  class="cti-input cti-input--textarea @error('message') is-invalid @enderror"
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

                    <button type="submit" class="cti-submit">Send message</button>
                </form>
            </section>

            <aside class="cti-side" aria-label="Contact details">
                <div class="cti-card">
                    <h2 class="cti-panel__title">Contact details</h2>
                    <ul class="cti-channels">
                        @foreach($channels as $channel)
                            <li class="cti-channel">
                                <span class="cti-channel__label">{{ $channel['label'] }}</span>
                                @if($channel['href'])
                                    <a href="{{ $channel['href'] }}" class="cti-channel__value">{{ $channel['value'] }}</a>
                                @else
                                    <span class="cti-channel__value">{{ $channel['value'] }}</span>
                                @endif
                                <span class="cti-channel__hint">{{ $channel['hint'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="cti-card">
                    <h2 class="cti-panel__title">What we can help with</h2>
                    <ul class="cti-topics">
                        @foreach($topics as $topic)
                            <li class="cti-topic">
                                <h3>{{ $topic['title'] }}</h3>
                                <p>{{ $topic['description'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if(!empty($global_social_item_data) && count($global_social_item_data))
                    <div class="cti-card">
                        <h2 class="cti-panel__title">Follow BrokersCourt</h2>
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
                    <div class="cti-card cti-card--map">
                        <h2 class="cti-panel__title">Location</h2>
                        <div class="cti-map">{!! $page['map'] !!}</div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection
