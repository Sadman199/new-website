@php
    use App\Support\BrokerReviewPresenter;
    $brokerSections = BrokerReviewPresenter::brokerSections($broker);
@endphp

@foreach($brokerSections as $section)
    @include('front.brokers.partials.review-data-section', ['section' => $section])
@endforeach

@include('front.brokers.partials.account-types', ['broker' => $broker, 'account_options' => $account_options])
