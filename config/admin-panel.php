<?php

return [

    'name' => 'BrokersCourt',
    'tagline' => 'Admin Panel',

    /*
    |--------------------------------------------------------------------------
    | Sidebar navigation (route names must exist in routes/admin-panel.php)
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        [
            'label' => 'Overview',
            'items' => [
                ['route' => 'admin.panel.dashboard', 'icon' => 'fas fa-th-large', 'label' => 'Dashboard'],
            ],
        ],
        [
            'label' => 'Brokers',
            'items' => [
                ['route' => 'admin.panel.brokers.index', 'icon' => 'fas fa-building', 'label' => 'All Brokers', 'badge' => 'brokers'],
                ['route' => 'admin.panel.brokers.edit', 'icon' => 'fas fa-edit', 'label' => 'Broker Details', 'params' => ['broker' => 'latest'], 'hidden' => true],
                ['route' => 'admin.panel.account-options.index', 'icon' => 'fas fa-layer-group', 'label' => 'Account Options'],
                ['route' => 'admin.panel.scam-brokers.index', 'icon' => 'fas fa-exclamation-triangle', 'label' => 'Scam Brokers'],
                ['route' => 'admin.panel.broker-reports.index', 'icon' => 'fas fa-shield-alt', 'label' => 'Safety Reports'],
            ],
        ],
        [
            'label' => 'Frontend Features',
            'items' => [
                ['route' => 'admin.panel.comparison.index', 'icon' => 'fas fa-columns', 'label' => 'Compare', 'suffix' => '(app)'],
                ['route' => 'admin.panel.find-my-broker.index', 'icon' => 'fas fa-search', 'label' => 'Find My Broker', 'suffix' => '(app)'],
            ],
        ],
        [
            'label' => 'Community',
            'items' => [
                ['route' => 'admin.panel.reviews.index', 'icon' => 'fas fa-star', 'label' => 'Reviews', 'badge' => 'pending_reviews'],
                ['route' => 'admin.panel.promotions.index', 'icon' => 'fas fa-gift', 'label' => 'Bonuses'],
                ['route' => 'admin.panel.users.index', 'icon' => 'fas fa-users', 'label' => 'Users'],
            ],
        ],
        [
            'label' => 'Content',
            'items' => [
                ['route' => 'admin.panel.blog.index', 'icon' => 'fas fa-newspaper', 'label' => 'Blog & Articles'],
                ['route' => 'admin.panel.categories.index', 'icon' => 'fas fa-folder', 'label' => 'Categories'],
                ['route' => 'admin.panel.tags.index', 'icon' => 'fas fa-tags', 'label' => 'Tags'],
                ['route' => 'admin.panel.faqs.index', 'icon' => 'fas fa-question-circle', 'label' => 'Broker FAQs'],
                ['route' => 'admin.panel.authors.index', 'icon' => 'fas fa-user-edit', 'label' => 'Authors'],
                ['route' => 'admin.panel.live-channels.index', 'icon' => 'fas fa-broadcast-tower', 'label' => 'Live Channels'],
                ['route' => 'admin.panel.online-polls.index', 'icon' => 'fas fa-poll', 'label' => 'Online Polls'],
            ],
        ],
        [
            'label' => 'Marketing',
            'items' => [
                ['route' => 'admin.panel.advertisements.index', 'icon' => 'fas fa-ad', 'label' => 'Advertisements'],
                ['route' => 'admin.panel.trading-tools.index', 'icon' => 'fas fa-calculator', 'label' => 'Trading Tools'],
            ],
        ],
        [
            'label' => 'System',
            'items' => [
                ['route' => 'admin.panel.admins.index', 'icon' => 'fas fa-user-shield', 'label' => 'Admin Users'],
                ['route' => 'admin.panel.activity-logs.index', 'icon' => 'fas fa-history', 'label' => 'Activity Logs'],
                ['route' => 'admin.panel.settings.index', 'icon' => 'fas fa-cog', 'label' => 'Settings'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy CRUD routes (existing admin) — used for create/edit actions
    |--------------------------------------------------------------------------
    */
    'legacy_routes' => [
        'broker_create' => 'admin_broker_create',
        'broker_edit' => 'admin_broker_edit',
        'broker_store' => 'admin_broker_store',
        'broker_update' => 'admin_broker_update',
        'review_pending' => 'reviews.pending',
        'settings_update' => 'admin_setting_update',
    ],

];
