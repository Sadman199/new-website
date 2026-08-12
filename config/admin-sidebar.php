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
                [
                    'label' => 'Prop Firms',
                    'icon' => 'fas fa-chart-line',
                    'match' => 'admin/prop-firms*',
                    'children' => [
                        ['route' => 'admin_prop_firms_dashboard', 'label' => 'Dashboard', 'match' => 'admin/prop-firms/dashboard'],
                        ['route' => 'admin_prop_firms_show', 'label' => 'All Firms', 'match' => ['admin/prop-firms/show', 'admin/prop-firms/edit/*']],
                        ['route' => 'admin_prop_firms_create', 'label' => 'Add New', 'match' => 'admin/prop-firms/create'],
                        ['route' => 'admin_prop_firm_categories_show', 'label' => 'Categories', 'match' => 'admin/prop-firms/categories*'],
                        ['route' => 'admin_prop_firm_programs_show', 'label' => 'Programs', 'match' => 'admin/prop-firms/programs*'],
                        ['route' => 'admin_prop_firm_reviews_show', 'label' => 'Reviews', 'match' => 'admin/prop-firms/reviews*'],
                        ['route' => 'admin_prop_firm_faqs_show', 'label' => 'FAQs', 'match' => 'admin/prop-firms/faqs*'],
                        ['route' => 'admin_prop_firm_attributes_show', 'label' => 'Attributes', 'match' => 'admin/prop-firms/attributes*'],
                        ['route' => 'admin_prop_firm_settings_edit', 'label' => 'Settings', 'match' => 'admin/prop-firms/settings*'],
                    ],
                ],
                ['route' => 'reviews.pending', 'icon' => 'fas fa-comments', 'label' => 'User Reviews', 'match' => 'admin/reviews*', 'badge' => 'pending_reviews'],
            ],
        ],
        [
            'label' => 'Content',
            'items' => [
                [
                    'label' => 'News',
                    'icon' => 'far fa-newspaper',
                    'match' => ['admin/category/*', 'admin/sub-category/*', 'admin/post/*'],
                    'children' => [
                        ['route' => 'admin_category_show', 'label' => 'Categories', 'match' => 'admin/category/*'],
                        ['route' => 'admin_sub_category_show', 'label' => 'Subcategories', 'match' => 'admin/sub-category/*'],
                        ['route' => 'admin_post_show', 'label' => 'Posts', 'match' => 'admin/post/*'],
                    ],
                ],
                ['route' => 'admin_cms_pages_index', 'icon' => 'fas fa-layer-group', 'label' => 'CMS Pages', 'match' => 'admin/cms-pages*'],
                ['route' => 'admin_faq_show', 'icon' => 'fas fa-question-circle', 'label' => 'FAQ Section', 'match' => 'admin/faq/*'],
                ['route' => 'admin_author_show', 'icon' => 'fas fa-user-edit', 'label' => 'Authors', 'match' => 'admin/author/*'],
            ],
        ],
        [
            'label' => 'Marketing',
            'items' => [
                ['route' => 'admin_forex_bonus_show', 'icon' => 'fas fa-gift', 'label' => 'Forex Bonuses', 'match' => 'admin/forex-bonus/*'],
                [
                    'label' => 'Advertisements',
                    'icon' => 'fas fa-ad',
                    'match' => ['admin/top-advertisement', 'admin/home-advertisement', 'admin/sidebar-advertisement-*', 'admin/ads*'],
                    'children' => [
                        ['route' => 'admin_top_ad_show', 'label' => 'Top Ad', 'match' => 'admin/top-advertisement'],
                        ['route' => 'admin_home_ad_show', 'label' => 'Home Ad', 'match' => 'admin/home-advertisement'],
                        ['route' => 'admin_sidebar_ad_show', 'label' => 'Sidebar Ad', 'match' => 'admin/sidebar-advertisement-*'],
                        ['route' => 'admin_ads_index', 'label' => 'Popup Ads', 'match' => 'admin/ads*'],
                    ],
                ],
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
