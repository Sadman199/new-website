<?php

namespace App\Providers;

use App\Models\Broker;
use App\Models\Review;
use App\Services\FooterIndexService;
use App\Services\GlobalViewDataService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        Paginator::useBootstrap();

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return route('user.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });

        try {
            app(GlobalViewDataService::class)->share();
            View::share('topRatedBrokers', app(GlobalViewDataService::class)->topRatedBrokers());
            View::share('popularReviewBrokers', app(GlobalViewDataService::class)->popularReviews(10));
        } catch (\Throwable) {
            View::share('topRatedBrokers', collect());
            View::share('popularReviewBrokers', collect());
        }

        View::composer(['components.admin.layout', 'admin.panel.*'], function ($view) {
            try {
                $view->with('panelBadges', [
                    'brokers' => Broker::count(),
                    'pending_reviews' => Review::where('status', 0)->count(),
                ]);
            } catch (\Throwable) {
                $view->with('panelBadges', [
                    'brokers' => 0,
                    'pending_reviews' => 0,
                ]);
            }
        });

        View::composer('front.layout.partial.broker-spotlight-dock', function ($view) {
            try {
                $view->with('spotlightBrokers', app(GlobalViewDataService::class)->spotlightBrokers());
            } catch (\Throwable) {
                $view->with('spotlightBrokers', collect());
            }
        });

        View::composer('front.layout.partial.mega-footer', function ($view) {
            try {
                $view->with('footer', app(FooterIndexService::class)->build());
            } catch (\Throwable) {
                $view->with('footer', [
                    'brand' => ['name' => 'BrokersCourt', 'tagline' => '', 'logo' => \App\Support\SiteTheme::logoUrl()],
                    'cta' => [],
                    'top_brokers' => ['links' => [], 'view_all' => null],
                    'comparisons' => [],
                    'regions' => [],
                    'for_users' => [],
                    'contact' => ['address' => '', 'email' => 'info@brokerscourt.com', 'phone' => ''],
                    'legal' => [],
                    'social' => [],
                    'disclaimer' => '',
                    'affiliate' => '',
                ]);
            }
        });

    }
}
