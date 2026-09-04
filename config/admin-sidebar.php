<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin sidebar navigation
    |--------------------------------------------------------------------------
    */
    'sections' => [
        [
            'label' => 'Overview',
            'items' => [
                ['route' => 'admin_home', 'icon' => 'fas fa-home', 'label' => 'Dashboard', 'match' => 'admin/home'],
                ['route' => 'admin_setting', 'icon' => 'fas fa-cog', 'label' => 'Settings', 'match' => 'admin/setting'],
            ],
        ],
        [
            'label' => 'Brokers',
            'items' => [
                ['route' => 'admin_broker_show', 'icon' => 'fas fa-briefcase', 'label' => 'All Brokers', 'match' => 'admin/broker*', 'match_exclude' => 'admin/broker/scam'],
                ['route' => 'admin_broker_scam', 'icon' => 'fas fa-exclamation-triangle', 'label' => 'Scam Brokers', 'match' => 'admin/broker/scam'],
                ['route' => 'admin_account_options_all', 'icon' => 'fas fa-layer-group', 'label' => 'Account Options', 'match' => 'admin/account-options*'],
                ['route' => 'admin_broker_guide_topics_index', 'icon' => 'fas fa-book-open', 'label' => 'Guide Topics', 'match' => 'admin/broker-guide-topics*'],
                ['route' => 'admin_broker_alternatives_show', 'icon' => 'fas fa-exchange-alt', 'label' => 'Broker Alternatives', 'match' => 'admin/broker-alternatives*'],
                ['route' => 'admin_prop_firms_dashboard', 'icon' => 'fas fa-chart-line', 'label' => 'Prop Firms', 'match' => 'admin/prop-firms*'],
                ['route' => 'reviews.pending', 'icon' => 'fas fa-comments', 'label' => 'User Reviews', 'match' => 'admin/reviews*', 'badge' => 'pending_reviews'],
            ],
        ],
        [
            'label' => 'Content',
            'items' => [
                ['route' => 'admin_post_show', 'icon' => 'far fa-newspaper', 'label' => 'Blog', 'match' => ['admin/post*', 'admin/category*', 'admin/sub-category*']],
                ['route' => 'admin_cms_pages_index', 'icon' => 'fas fa-layer-group', 'label' => 'CMS Pages', 'match' => 'admin/cms-pages*'],
                ['route' => 'admin_faq_show', 'icon' => 'fas fa-question-circle', 'label' => 'FAQ Section', 'match' => 'admin/faq/*'],
                ['route' => 'admin_author_show', 'icon' => 'fas fa-user-edit', 'label' => 'Authors', 'match' => 'admin/author/*'],
            ],
        ],
        [
            'label' => 'Marketing',
            'items' => [
                ['route' => 'admin_forex_bonus_show', 'icon' => 'fas fa-gift', 'label' => 'Forex Bonuses', 'match' => 'admin/forex-bonus/*'],
                ['route' => 'admin_banners_index', 'icon' => 'fas fa-image', 'label' => 'Banners', 'match' => 'admin/banners*'],
                ['route' => 'admin_ads_index', 'icon' => 'fas fa-ad', 'label' => 'Advertisements', 'match' => ['admin/top-advertisement*', 'admin/home-advertisement*', 'admin/sidebar-advertisement*', 'admin/ads*']],
                ['route' => 'admin_trading_tools_index', 'icon' => 'fas fa-calculator', 'label' => 'Trading Tools', 'match' => 'admin/trading-tools*'],
            ],
        ],
        [
            'label' => 'Community',
            'items' => [
                ['route' => 'admin_users_index', 'icon' => 'fas fa-users', 'label' => 'Users', 'match' => 'admin/users*'],
                ['route' => 'admin_contact_inquiries.index', 'icon' => 'fas fa-envelope', 'label' => 'Contact Inquiries', 'match' => 'admin/contact-inquiries*', 'badge' => 'contact_new'],
            ],
        ],
    ],

];
