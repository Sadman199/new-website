
@extends('front.layout.app')
@section('title', $broker->meta_title ?? $broker->title)
@section('meta_description', $broker->meta_description ?? Str::limit(strip_tags($broker->description), 150))
@section('meta_keywords', $broker->meta_keywords)

@section('main_content')
    <!-- Hero Section -->
    @include('front.brokers.partials.hero', ['broker' => $broker])

    <!-- Sticky Navigation -->
    @include('front.brokers.partials.navigation')

    <!-- Main Article Content -->
    <article class="container px-4 max-w-7xl mx-auto w-full">
        <!-- Header with Logo and Action Buttons -->
        @include('front.brokers.partials.header', ['broker' => $broker])

        <!-- Key Stats and Pros/Cons -->
        @include('front.brokers.partials.key-stats', ['broker' => $broker])


         <!-- Broker key info -->
        @include('front.brokers.partials.key_info', ['broker' => $broker])

        <!-- Combined Table (Overview, Trading Capabilities, Account & Services) -->
        @include('front.brokers.partials.tables', ['broker' => $broker])

        <!-- Broker Insights -->
        @include('front.brokers.partials.broker-insights', ['broker' => $broker])


        <!-- Account Structures -->
        @include('front.brokers.partials.account-structures', ['account_options' => $account_options, 'broker' => $broker])

        <!-- Offers, Instruments, Risk Tools, Special Conditions -->
        @include('front.brokers.partials.account-details', ['account_options' => $account_options, 'broker' => $broker])

        <!-- FAQs -->
        @include('front.brokers.partials.faqs', ['faqs' => $faqs])
    </article>

    <!-- Reviews Section -->
    @include('front.brokers.partials.reviews', ['broker' => $broker, 'approved_reviews' => $approved_reviews])

    <!-- Comparison Section -->
    @include('front.brokers.partials.compare', ['broker' => $broker, 'compare_brokers' => $compare_brokers])
@endsection

@section('scripts')
    <script src="{{ asset('js/broker-review.js') }}" defer></script>
@endsection