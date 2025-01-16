@extends('front.layout.app')
@section('title', 'BrokersCourt Privacy Policy | Your Data Protection Rights')
@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_privacy">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="hero-content">
                        <h2 class="b_c_h">{{ $page_data->privacy_title }}</h2>
                        <nav class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page_data->privacy_title }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content s_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               <div class="privacy-policy-content">
                    {!! $page_data->privacy_detail !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection