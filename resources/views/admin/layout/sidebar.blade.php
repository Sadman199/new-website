<div class="main-sidebar">
    <aside id="sidebar-wrapper">
     <div class="admin-brand">
                <div class="brand-container">
                        <a href="{{ route('admin_home') }}" class="brand-link">
                        <span class="brand-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 5h16v14H4V5zm2 2v10h12V7H6zm5 2h2v6h-2V9zm-3 2h2v4H8v-4zm6 0h2v4h-2v-4z"/>
                                </svg>
                        </span>
                        <span class="brand-name">Dashboard</span>
                        <span class="brand-badge">PRO</span>
                        </a>
                </div>
        </div>


        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin_home') }}"></a>
        </div>

        <ul class="sidebar-menu">

            <li class="{{ Request::is('admin/home') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_home') }}"><i class="fas fa-home"></i> <span>Home</span></a></li>

            <li class="{{ Request::is('admin/setting') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_setting') }}"><i class="fas fa-cog"></i> <span>Setting</span></a></li>

            <li class="{{ Request::is('admin/author/*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_author_show') }}"><i class="fas fa-user-edit"></i> <span>Author
                        List</span></a></li>


                        <li class="{{ Request::is('admin/forex-bonus/show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_forex_bonus_show') }}">
                    <i class="fas fa-share-alt"></i> <span>Forex Bonus</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/broker*') && ! Request::is('admin/broker/scam') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_broker_show') }}">
                    <i class="fas fa-briefcase"></i> <span>Broker</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ Request::is('admin/prop-firms*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-line"></i><span>Prop Firms</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/prop-firms/dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firms_dashboard') }}"><i class="fas fa-angle-right"></i> Dashboard</a></li>
                    <li class="{{ Request::is('admin/prop-firms/show') || Request::is('admin/prop-firms/edit/*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firms_show') }}"><i class="fas fa-angle-right"></i> All Prop Firms</a></li>
                    <li class="{{ Request::is('admin/prop-firms/create') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firms_create') }}"><i class="fas fa-angle-right"></i> Add New</a></li>
                    <li class="{{ Request::is('admin/prop-firms/categories*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firm_categories_show') }}"><i class="fas fa-angle-right"></i> Categories</a></li>
                    <li class="{{ Request::is('admin/prop-firms/programs*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firm_programs_show') }}"><i class="fas fa-angle-right"></i> Programs</a></li>
                    <li class="{{ Request::is('admin/prop-firms/reviews*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firm_reviews_show') }}"><i class="fas fa-angle-right"></i> Reviews</a></li>
                    <li class="{{ Request::is('admin/prop-firms/faqs*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firm_faqs_show') }}"><i class="fas fa-angle-right"></i> FAQs</a></li>
                    <li class="{{ Request::is('admin/prop-firms/attributes*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firm_attributes_show') }}"><i class="fas fa-angle-right"></i> Attributes</a></li>
                    <li class="{{ Request::is('admin/prop-firms/settings*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin_prop_firm_settings_edit') }}"><i class="fas fa-angle-right"></i> Settings</a></li>
                </ul>
            </li>

            <li class="{{ Request::is('admin/account-options*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_account_options_all') }}">
                    <i class="fas fa-layer-group"></i> <span>Account Options</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/broker/scam') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_broker_scam') }}">
                    <i class="fas fa-exclamation-triangle"></i> <span>Scam Brokers</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/reviews*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reviews.pending') }}">
                        <i class="fas fa-comments"></i> <span>User Reviews</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_users_index') }}">
                        <i class="fas fa-users"></i> <span>Users</span>
                </a>
            </li>         

            <li class="{{ Request::is('admin/trading-tools*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_trading_tools_index') }}">
                    <i class="fas fa-calculator"></i> <span>Trading Tools</span>
                </a>
            </li>

            <li
                class="nav-item dropdown {{ Request::is('admin/top-advertisement')||Request::is('admin/home-advertisement')||Request::is('admin/sidebar-advertisement-*')||Request::is('admin/ads*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-ad"></i><span>Advertisements</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/top-advertisement') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_top_ad_show') }}"><i class="fas fa-angle-right"></i> Top
                            Advertisement</a></li>
                    <li class="{{ Request::is('admin/home-advertisement') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_home_ad_show') }}"><i class="fas fa-angle-right"></i> Home
                            Advertisement</a></li>
                    <li class="{{ Request::is('admin/sidebar-advertisement-*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_sidebar_ad_show') }}"><i class="fas fa-angle-right"></i> Sidebar
                            Advertisement</a></li>
                    <li class="{{ Request::is('admin/ads*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_ads_index') }}"><i class="fas fa-angle-right"></i> Popup Ads</a></li>
                </ul>
            </li>

            <li
                class="nav-item dropdown {{ Request::is('admin/category/*')||Request::is('admin/sub-category/*')||Request::is('admin/post/*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-newspaper"></i><span>News</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/category/*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_category_show') }}"><i class="fas fa-angle-right"></i> Categories</a>
                    </li>
                    <li class="{{ Request::is('admin/sub-category/*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_sub_category_show') }}"><i class="fas fa-angle-right"></i>
                            SubCategories</a></li>
                    <li class="{{ Request::is('admin/post/*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_post_show') }}"><i class="fas fa-angle-right"></i> Posts</a></li>
                </ul>
            </li>

            <li class="{{ Request::is('admin/photo/*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_photo_show') }}"><i class="fas fa-camera"></i> <span>Photo Gallery</span></a>
            </li>

            <li class="{{ Request::is('admin/video/*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_video_show') }}"><i class="fas fa-video"></i> <span>Video Gallery</span></a>
            </li>

            <li class="nav-item dropdown {{ Request::is('admin/page/*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-copy"></i><span>Pages</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/page/about') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_about') }}"><i class="fas fa-angle-right"></i> About</a></li>
                    <li class="{{ Request::is('admin/page/faq') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_faq') }}"><i class="fas fa-angle-right"></i> FAQ</a></li>
                    <li class="{{ Request::is('admin/page/contact') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_contact') }}"><i class="fas fa-angle-right"></i> Contact</a></li>
                    <li class="{{ Request::is('admin/page/login') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_login') }}"><i class="fas fa-angle-right"></i> Login</a></li>
                    <li class="{{ Request::is('admin/page/terms') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_terms') }}"><i class="fas fa-angle-right"></i> Terms and
                            Conditions</a></li>
                    <li class="{{ Request::is('admin/page/privacy') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_privacy') }}"><i class="fas fa-angle-right"></i> Privacy
                            Policy</a></li>
                    <li class="{{ Request::is('admin/page/disclaimer') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_page_disclaimer') }}"><i class="fas fa-angle-right"></i>
                            Disclaimer</a></li>
                </ul>
            </li>

            <li class="{{ Request::is('admin/faq/*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_faq_show') }}"><i class="fas fa-question-circle"></i> <span>FAQ
                        Section</span></a></li>

            <li class="{{ Request::is('admin/contact-inquiries*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_contact_inquiries.index') }}">
                    <i class="fas fa-envelope"></i>
                    <span>Contact Inquiries</span>
                    @php($newInquiryCount = \App\Models\ContactInquiry::where('status', 'new')->count())
                    @if($newInquiryCount > 0)
                        <span class="badge badge-warning ml-1">{{ $newInquiryCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item dropdown {{ Request::is('admin/subscriber/*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Subscribers</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/subscriber/all') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_subscribers') }}"><i class="fas fa-angle-right"></i> All
                            Subscribers</a></li>
                    <li class="{{ Request::is('admin/subscriber/send-email') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin_subscriber_send_email') }}"><i class="fas fa-angle-right"></i> Send
                            Email to All</a></li>
                </ul>
            </li>

            <li class="{{ Request::is('admin/social-item/*') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin_social_item_show') }}"><i class="fas fa-share-alt"></i> <span>Social
                        Items</span></a></li>

        </ul>
    </aside>
</div>