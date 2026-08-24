<?php

namespace Tests\Feature;

use App\Models\HomeAdvertisement;
use App\Services\PageContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageAdSectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_hides_home_ads_when_status_is_not_show(): void
    {
        HomeAdvertisement::unguarded(function () {
            HomeAdvertisement::query()->create([
                'above_search_ad' => 'search-banner.jpg',
                'above_search_ad_url' => 'https://example.com/search',
                'above_search_ad_status' => 'Hide',
                'above_footer_ad' => 'footer-banner.jpg',
                'above_footer_ad_url' => 'https://example.com/footer',
                'above_footer_ad_status' => 'Hide',
            ]);
        });

        PageContextService::flush();

        $this->get('/')
            ->assertOk()
            ->assertDontSee('bc-home-ad', false)
            ->assertDontSee('search-banner.jpg');
    }

    public function test_homepage_renders_active_home_ads_from_admin(): void
    {
        HomeAdvertisement::unguarded(function () {
            HomeAdvertisement::query()->create([
                'above_search_ad' => 'search-banner.jpg',
                'above_search_ad_url' => 'https://example.com/search',
                'above_search_ad_status' => 'Show',
                'above_footer_ad' => 'footer-banner.jpg',
                'above_footer_ad_url' => 'https://example.com/footer',
                'above_footer_ad_status' => 'Show',
            ]);
        });

        PageContextService::flush();

        $this->get('/')
            ->assertOk()
            ->assertSee('bc-home-ad--search', false)
            ->assertSee('bc-home-ad--footer', false)
            ->assertSee('uploads/search-banner.jpg')
            ->assertSee('uploads/footer-banner.jpg')
            ->assertSee('https://example.com/search')
            ->assertSee('https://example.com/footer')
            ->assertSee('Sponsored');
    }
}
