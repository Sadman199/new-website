@extends('front.layout.app')

@section('main_content')
<div class="page-top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>{{ __('Brokers List') }}</h2> <!-- Page title -->
                <nav class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Brokers List') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($brokers->isEmpty())
                    <p>{{ __('No brokers available.') }}</p>
                @else
                    <ul class="broker-list">
                        @foreach($brokers as $broker)
                            <li class="broker-item">
                                <h3><a href="{{ route('broker_detail', $broker->id) }}">{{ $broker->name }}</a></h3>
                                <p>{{ Str::limit($broker->description, 150) }}</p>
                                <a href="{{ route('broker_detail', $broker->id) }}" class="btn btn-primary">{{ __('View Details') }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
