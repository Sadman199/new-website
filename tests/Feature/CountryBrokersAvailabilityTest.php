<?php

namespace Tests\Feature;

use App\Models\Broker;
use App\Services\CountryBrokersService;
use App\Support\BrokerListingFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryBrokersAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CountryBrokersService::flush();
    }

    public function test_count_includes_associated_countries_and_headquarters(): void
    {
        $this->makeBroker('HQ Cyprus', ['country' => 'Cyprus']);
        $this->makeBroker('Tagged Singapore', [
            'country' => 'Cyprus',
            'associated_countries' => ['singapore'],
        ]);
        $this->makeBroker('HQ Singapore', ['country' => 'Singapore']);
        $this->makeBroker('Scam Singapore', [
            'country' => 'Singapore',
            'associated_countries' => ['singapore'],
            'is_scam' => true,
        ]);
        $this->makeBroker('Asia Region Only', [
            'country' => 'Cyprus',
            'regions' => ['asia', 'global'],
        ]);

        $service = app(CountryBrokersService::class);

        $this->assertSame(2, $service->countForCountry('singapore'));
        $this->assertSame(3, $service->countForCountry('cyprus'));
        $this->assertSame(0, $service->countForCountry('thailand'));
        $this->assertSame(4, $service->countForCountry('global'));
    }

    public function test_selector_lists_headquarters_countries_from_the_country_field(): void
    {
        $this->makeBroker('HQ Cyprus', ['country' => 'Cyprus', 'rating' => 4.4]);
        $this->makeBroker('HQ Singapore', ['country' => 'Singapore', 'rating' => 4.3]);
        $this->makeBroker('Usa Alias', ['country' => 'Usa', 'rating' => 4.1]);
        $this->makeBroker('Messy Seychelles', [
            'country' => 'Seychelles (Headquartered)',
            'rating' => 4.0,
        ]);
        $this->makeBroker('Tagged Thailand Only', [
            'country' => 'Cyprus',
            'associated_countries' => ['thailand'],
            'rating' => 3.9,
        ]);
        $this->makeBroker('Prose HQ', [
            'country' => 'IC Markets operates globally, with entities regulated in Australia, Cyprus, and Seychelles.',
            'rating' => 3.8,
        ]);

        $service = app(CountryBrokersService::class);
        $selector = $service->countriesForSelector();

        $this->assertArrayHasKey('global', $selector);
        $this->assertArrayHasKey('cyprus', $selector);
        $this->assertArrayHasKey('singapore', $selector);
        $this->assertArrayHasKey('united-states', $selector);
        $this->assertArrayHasKey('seychelles', $selector);
        $this->assertArrayNotHasKey('thailand', $selector);
        $this->assertSame('Seychelles', $selector['seychelles']['name']);
        $this->assertSame(1, $selector['united-states']['broker_count']);
        $this->assertSame(2, $selector['cyprus']['broker_count']);
        $this->assertSame('HQ Singapore', $service->forCountry('singapore', 6)->first()?->name);
        $this->assertTrue($service->forCountry('thailand', 6)->isEmpty());
        $this->assertSame(route('brokers.best', ['slug' => 'cyprus']), $service->brokersPageUrl('cyprus'));
        $this->assertNull($service->brokersPageUrl('thailand'));
    }

    public function test_for_country_ranks_editor_top_broker_ahead_of_rating(): void
    {
        $this->makeBroker('Higher Rated', [
            'country' => 'Cyprus',
            'rating' => 4.9,
            'top_broker' => 0,
        ]);
        $this->makeBroker('Editor Pick', [
            'country' => 'Cyprus',
            'rating' => 4.2,
            'top_broker' => 8,
        ]);

        $first = app(CountryBrokersService::class)->forCountry('cyprus', 4)->first();

        $this->assertSame('Editor Pick', $first?->name);
    }

    public function test_country_strip_appears_on_relevant_pages_only(): void
    {
        $this->makeBroker('Alpha Cyprus', [
            'country' => 'Cyprus',
            'rating' => 4.8,
            'top_broker' => 9,
            'minimum_deposit' => 0,
        ]);

        $this->withSession(['preferred_country' => 'cyprus'])
            ->get('/find-my-broker')
            ->assertOk()
            ->assertSee('Top brokers in Cyprus', false)
            ->assertSee('Alpha Cyprus', false)
            ->assertSee('bc-country-strip', false);

        $this->withSession(['preferred_country' => 'cyprus'])
            ->get('/broker-reviews')
            ->assertOk()
            ->assertSee('Top brokers in Cyprus', false);

        $this->withSession(['preferred_country' => 'cyprus'])
            ->get('/')
            ->assertOk()
            ->assertDontSee('Top brokers in Cyprus', false);

        $this->withSession(['preferred_country' => 'cyprus'])
            ->get('/blog')
            ->assertOk()
            ->assertDontSee('Top brokers in Cyprus', false);

        $this->withSession(['preferred_country' => 'cyprus'])
            ->get('/login')
            ->assertOk()
            ->assertDontSee('Top brokers in Cyprus', false);
    }

    public function test_country_strip_is_hidden_for_global_preference(): void
    {
        $this->makeBroker('Global Leader', [
            'country' => 'Australia',
            'rating' => 4.7,
            'top_broker' => 10,
        ]);

        $this->withSession(['preferred_country' => 'global'])
            ->get('/find-my-broker')
            ->assertOk()
            ->assertDontSee('bc-country-strip', false);
    }

    /** @param  array<string, mixed>  $overrides */
    private function makeBroker(string $name, array $overrides = []): Broker
    {
        return Broker::create(array_merge([
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'rating' => 4.2,
            'is_scam' => false,
            'associated_countries' => [],
            'regions' => [],
        ], $overrides));
    }
}
