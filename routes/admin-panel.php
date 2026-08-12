<?php

use App\Http\Controllers\Admin\Panel\PanelAuthorsController;
use App\Http\Controllers\Admin\Panel\PanelBrokerReportsController;
use App\Http\Controllers\Admin\Panel\PanelDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin:admin'])
    ->prefix('admin/panel')
    ->name('admin.panel.')
    ->group(function () {
        Route::get('/', [PanelDashboardController::class, 'index'])->name('dashboard');

        Route::get('/brokers', fn () => redirect()->route('admin_broker_show'))->name('brokers.index');
        Route::get('/brokers/create', fn () => redirect()->route('admin_broker_create'))->name('brokers.create');
        Route::post('/brokers', fn () => redirect()->route('admin_broker_create'))->name('brokers.store');
        Route::get('/brokers/{broker}/edit', fn ($broker) => redirect()->route('admin_broker_edit', $broker))->name('brokers.edit');
        Route::put('/brokers/{broker}', fn ($broker) => redirect()->route('admin_broker_edit', $broker))->name('brokers.update');
        Route::delete('/brokers/{broker}', fn () => redirect()->route('admin_broker_show'))->name('brokers.destroy');

        // Placeholder routes referenced by sidebar config (render stub pages)
        Route::view('/account-options', 'admin.panel.pages.account-options.index', [
            'title' => 'Account Options',
            'pageTitle' => 'Account Options',
        ])->name('account-options.index');

        Route::view('/scam-brokers', 'admin.panel.pages.scam-brokers.index', [
            'title' => 'Scam Brokers',
            'pageTitle' => 'Scam Brokers',
        ])->name('scam-brokers.index');

        Route::get('/broker-reports', [PanelBrokerReportsController::class, 'index'])->name('broker-reports.index');
        Route::put('/broker-reports/{report}', [PanelBrokerReportsController::class, 'update'])->name('broker-reports.update');

        Route::view('/comparison', 'admin.panel.pages.comparison.index', [
            'title' => 'Compare',
            'pageTitle' => 'Compare',
        ])->name('comparison.index');

        Route::view('/find-my-broker', 'admin.panel.pages.find-my-broker.index', [
            'title' => 'Find My Broker',
            'pageTitle' => 'Find My Broker',
        ])->name('find-my-broker.index');

        Route::view('/reviews', 'admin.panel.pages.reviews.index', [
            'title' => 'Reviews',
            'pageTitle' => 'Reviews',
        ])->name('reviews.index');

        Route::get('/promotions', fn () => redirect()->route('admin_forex_bonus_show'))->name('promotions.index');

        Route::view('/users', 'admin.panel.pages.users.index', [
            'title' => 'Users',
            'pageTitle' => 'Users',
        ])->name('users.index');

        Route::view('/blog', 'admin.panel.pages.blog.index', [
            'title' => 'Blog',
            'pageTitle' => 'Blog',
        ])->name('blog.index');

        Route::view('/categories', 'admin.panel.pages.categories.index', [
            'title' => 'Categories',
            'pageTitle' => 'Categories',
        ])->name('categories.index');

        Route::view('/tags', 'admin.panel.pages.tags.index', [
            'title' => 'Tags',
            'pageTitle' => 'Tags',
        ])->name('tags.index');

        Route::view('/faqs', 'admin.panel.pages.faqs.index', [
            'title' => 'FAQs',
            'pageTitle' => 'FAQs',
        ])->name('faqs.index');

        Route::get('/authors', [PanelAuthorsController::class, 'index'])->name('authors.index');

        Route::view('/live-channels', 'admin.panel.pages.live-channels.index', [
            'title' => 'Live Channels',
            'pageTitle' => 'Live Channels',
        ])->name('live-channels.index');

        Route::view('/online-polls', 'admin.panel.pages.online-polls.index', [
            'title' => 'Online Polls',
            'pageTitle' => 'Online Polls',
        ])->name('online-polls.index');

        Route::view('/advertisements', 'admin.panel.pages.advertisements.index', [
            'title' => 'Advertisements',
            'pageTitle' => 'Advertisements',
        ])->name('advertisements.index');

        Route::view('/trading-tools', 'admin.panel.pages.trading-tools.index', [
            'title' => 'Trading Tools',
            'pageTitle' => 'Trading Tools',
        ])->name('trading-tools.index');

        Route::view('/admins', 'admin.panel.pages.admins.index', [
            'title' => 'Admin Users',
            'pageTitle' => 'Admin Users',
        ])->name('admins.index');

        Route::view('/activity-logs', 'admin.panel.pages.activity-logs.index', [
            'title' => 'Activity Logs',
            'pageTitle' => 'Activity Logs',
        ])->name('activity-logs.index');

        Route::view('/settings', 'admin.panel.pages.settings.index', [
            'title' => 'Settings',
            'pageTitle' => 'Settings',
        ])->name('settings.index');
    });
