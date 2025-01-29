@extends('admin.layout.app')

@section('heading', 'Greetings from the BrokersCourt Admin Panel!')

@section('main_content')
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fab fa-atlassian"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total News Categories</h4>
                </div>
                <div class="card-body">
                    {{ $total_category }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fab fa-bandcamp"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Subcategory</h4>
                </div>
                <div class="card-body">
                    {{ $total_subcategory }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Post</h4>
                </div>
                <div class="card-body">
                    {{ $total_news }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
            <i class="fas fa-briefcase"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Broker </h4>
                </div>
                <div class="card-body">
                    {{ $total_broker }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-video"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Upload Video</h4>
                </div>
                <div class="card-body">
                    {{ $total_video }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total FAQ</h4>
                </div>
                <div class="card-body">
                    {{ $total_faq }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
            <i class="fas fa-gift"></i> 
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Bonus</h4>
                </div>
                <div class="card-body">
                    {{ $total_forexBonus }}
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
            <i class="fas fa-comment-dots"></i> 
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total User Review</h4>
                </div>
                <div class="card-body">
                    {{ $total_review }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Subscribers</h4>
                </div>
                <div class="card-body">
                    {{ $total_subscriber }}
                </div>
            </div>
        </div>
    </div>
</div>



@endsection