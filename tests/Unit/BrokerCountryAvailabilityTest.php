<?php

namespace Tests\Unit;

use App\Models\Broker;
use App\Support\BrokerListingFilter;
use Tests\TestCase;

class BrokerCountryAvailabilityTest extends TestCase
{
    public function test_associated_country_slug_matches_without_using_hq(): void
    {
        $broker = $this->broker([
            'country' => 'Cyprus',
            'associated_countries' => ['singapore', 'india'],
        ]);

        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($broker, 'singapore'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($broker, 'india'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($broker, 'cyprus'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($broker, 'thailand'));
    }

    public function test_headquarters_name_and_aliases_match(): void
    {
        $uk = $this->broker(['country' => 'UK', 'associated_countries' => []]);
        $usa = $this->broker(['country' => 'United States', 'associated_countries' => []]);

        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($uk, 'united-kingdom'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($usa, 'united-states'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($uk, 'united-states'));
    }

    public function test_region_tags_do_not_count_as_availability(): void
    {
        $broker = $this->broker([
            'country' => 'Cyprus',
            'associated_countries' => [],
            'regions' => ['asia', 'global'],
        ]);

        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($broker, 'singapore'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($broker, 'india'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($broker, 'cyprus'));
    }

    public function test_scam_brokers_are_excluded_from_country_matches(): void
    {
        $broker = $this->broker([
            'country' => 'Singapore',
            'associated_countries' => ['singapore', 'thailand'],
            'is_scam' => true,
        ]);

        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($broker, 'singapore'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($broker, 'thailand'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($broker, 'global'));
    }

    public function test_global_matches_non_scam_brokers_only(): void
    {
        $live = $this->broker(['country' => 'Cyprus']);
        $scam = $this->broker(['country' => 'Cyprus', 'is_scam' => true]);

        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($live, 'global'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($scam, 'global'));
    }

    public function test_country_name_in_associated_countries_matches_slug(): void
    {
        $broker = $this->broker([
            'country' => 'Cyprus',
            'associated_countries' => ['Singapore', 'United Arab Emirates'],
        ]);

        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($broker, 'singapore'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($broker, 'uae'));
    }

    public function test_messy_headquarters_text_canonicalizes_to_a_country(): void
    {
        $cyprus = $this->broker(['country' => 'Headquartered in Limassol, Cyprus.']);
        $seychelles = $this->broker(['country' => 'Seychelles (Headquartered)']);
        $usa = $this->broker(['country' => 'Usa']);
        $prose = $this->broker([
            'country' => 'IC Markets operates globally, with entities regulated in Australia, Cyprus, and Seychelles.',
        ]);

        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($cyprus, 'cyprus'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($seychelles, 'seychelles'));
        $this->assertTrue(BrokerListingFilter::isAvailableInCountry($usa, 'united-states'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($prose, 'cyprus'));
        $this->assertFalse(BrokerListingFilter::isAvailableInCountry($prose, 'australia'));
    }

    /** @param  array<string, mixed>  $overrides */
    private function broker(array $overrides = []): Broker
    {
        return new Broker(array_merge([
            'name' => 'Test Broker',
            'slug' => 'test-broker',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'is_scam' => false,
            'associated_countries' => [],
            'regions' => [],
        ], $overrides));
    }
}
