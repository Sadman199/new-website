@extends('admin.layout.app')

@section('heading', 'BrokersCourt Admin Dashboard')

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="welcome-box shadow-sm">
                <div class="welcome-text">
                    <h2><strong>Welcome back, Admin!</strong></h2>
                    <p>Here's what's happening with your platform today.</p>
                </div>
                <div class="quick-stats">
                    <div class="stat-item">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ $total_news }} New Posts</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span>{{ $total_subscriber }} Subscribers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row summary-cards">
        <!-- Content Management -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-summary shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-newspaper text-primary mr-2"></i>Content</h5>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <div class="icon-circle bg-primary-light">
                            <i class="fas fa-layer-group text-primary"></i>
                        </div>
                        <div class="summary-text">
                            <h6>Categories</h6>
                            <p>{{ $total_category }}</p>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="icon-circle bg-success-light">
                            <i class="fas fa-tags text-success"></i>
                        </div>
                        <div class="summary-text">
                            <h6>Subcategories</h6>
                            <p>{{ $total_subcategory }}</p>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="icon-circle bg-info-light">
                            <i class="fas fa-file-alt text-info"></i>
                        </div>
                        <div class="summary-text">
                            <h6>News Posts</h6>
                            <p>{{ $total_news }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Broker Data -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-summary shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-briefcase text-warning mr-2"></i>Brokers</h5>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <div class="icon-circle bg-warning-light">
                            <i class="fas fa-briefcase text-warning"></i>
                        </div>
                        <div class="summary-text">
                            <h6>Total Brokers</h6>
                            <p>{{ $total_broker }}</p>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="icon-circle bg-danger-light">
                            <i class="fas fa-gift text-danger"></i>
                        </div>
                        <div class="summary-text">
                            <h6>Forex Bonuses</h6>
                            <p>{{ $total_forexBonus }}</p>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="icon-circle bg-secondary-light">
                            <i class="fas fa-comment-dots text-secondary"></i>
                        </div>
                        <div class="summary-text">
                            <h6>User Reviews</h6>
                            <p>{{ $total_review }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Engagement -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-summary shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-pie text-success mr-2"></i>Engagement</h5>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <div class="icon-circle bg-info-light">
                            <i class="fas fa-video text-info"></i>
                        </div>
                        <div class="summary-text">
                            <h6>Videos</h6>
                            <p>{{ $total_video }}</p>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="icon-circle bg-purple-light">
                            <i class="fas fa-question-circle text-purple"></i>
                        </div>
                        <div class="summary-text">
                            <h6>FAQs</h6>
                            <p>{{ $total_faq }}</p>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="icon-circle bg-teal-light">
                            <i class="fas fa-users text-teal"></i>
                        </div>
                        <div class="summary-text">
                            <h6>Subscribers</h6>
                            <p>{{ $total_subscriber }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Platform Analytics</h5>
                </div>
                <div class="card-body">
                    <div class="chart-placeholder" style="height: 300px;">
                        <!-- This would be replaced with an actual chart (Chart.js, etc.) -->
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="fas fa-chart-bar fa-3x mr-2"></i>
                            <span>Analytics Chart Will Appear Here</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-bolt mr-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-plus-circle text-primary"></i>
                        <span>Add New Post</span>
                    </a>
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-user-plus text-success"></i>
                        <span>Add Broker</span>
                    </a>
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-video text-info"></i>
                        <span>Upload Video</span>
                    </a>
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-gift text-warning"></i>
                        <span>Create Bonus</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .welcome-box {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .welcome-text h2 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .welcome-text p {
        opacity: 0.8;
        margin-bottom: 0;
    }
    
    .quick-stats {
        display: flex;
    }
    
    .stat-item {
        background: rgba(255,255,255,0.15);
        border-radius: 30px;
        padding: 8px 20px;
        margin-left: 15px;
        display: flex;
        align-items: center;
    }
    
    .stat-item i {
        margin-right: 8px;
    }
    
    .card-summary {
        border-radius: 10px;
        height: 100%;
    }
    
    .card-summary .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        border-radius: 10px 10px 0 0 !important;
    }
    
    .summary-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .summary-item:last-child {
        border-bottom: none;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .bg-primary-light {
        background-color: rgba(13,110,253,0.1);
    }
    
    .bg-success-light {
        background-color: rgba(25,135,84,0.1);
    }
    
    .bg-info-light {
        background-color: rgba(13,202,240,0.1);
    }
    
    .bg-warning-light {
        background-color: rgba(255,193,7,0.1);
    }
    
    .bg-danger-light {
        background-color: rgba(220,53,69,0.1);
    }
    
    .bg-purple-light {
        background-color: rgba(111,66,193,0.1);
    }
    
    .bg-teal-light {
        background-color: rgba(32,201,151,0.1);
    }
    
    .bg-secondary-light {
        background-color: rgba(108,117,125,0.1);
    }
    
    .summary-text h6 {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 2px;
    }
    
    .summary-text p {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 0;
    }
    
    .quick-action-btn {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 8px;
        background: #f8f9fa;
        margin-bottom: 10px;
        color: #495057;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .quick-action-btn:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .quick-action-btn i {
        font-size: 20px;
        margin-right: 10px;
        width: 24px;
        text-align: center;
    }
    
    .chart-placeholder {
        background: #f8f9fa;
        border-radius: 8px;
    }
</style>
@endsection