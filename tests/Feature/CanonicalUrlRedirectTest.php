<?php

namespace Tests\Feature;

use App\Models\Broker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanonicalUrlRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_us_redirects_to_about(): void
    {
        $this->get('/about-us')
            ->assertStatus(301)
            ->assertRedirect(route('about'));
    }

    public function test_country_listing_redirects_to_best_brokers_guide(): void
    {
        $this->get('/country/united-kingdom')
            ->assertStatus(301)
            ->assertRedirect(route('brokers.best', ['slug' => 'united-kingdom']));
    }

    public function test_country_display_name_redirects_to_best_brokers_guide(): void
    {
        $this->get('/country/United%20Kingdom')
            ->assertStatus(301)
            ->assertRedirect(route('brokers.best', ['slug' => 'united-kingdom']));
    }

    public function test_unknown_country_listing_returns_not_found(): void
    {
        $this->get('/country/not-a-real-country')->assertNotFound();
    }

    public function test_global_country_listing_redirects_to_reviews_index(): void
    {
        $this->get('/country/global')
            ->assertStatus(301)
            ->assertRedirect(route('broker.reviews.index'));
    }

    public function test_legacy_brokers_index_redirects_to_find_my_broker(): void
    {
        $this->get('/brokers')
            ->assertStatus(301)
            ->assertRedirect(route('find_my_broker'));
    }

    public function test_legacy_brokers_filter_redirects_to_find_my_broker(): void
    {
        $this->get('/brokers/filter')
            ->assertStatus(301)
            ->assertRedirect(route('find_my_broker'));
    }

    public function test_short_review_slug_redirects_to_canonical_review_url(): void
    {
        Broker::create([
            'name' => 'Canonical Broker',
            'slug' => 'canonical-broker',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
        ]);

        $this->get('/broker-reviews/canonical-broker')
            ->assertStatus(301)
            ->assertRedirect(route('broker_detail', ['slug' => 'canonical-broker-review']));
    }
}
