<?php

namespace Tests\Unit;

use App\Support\SiteJsonLd;
use Tests\TestCase;

class SiteJsonLdTest extends TestCase
{
    public function test_web_page_schema_includes_canonical_title_and_publisher(): void
    {
        $graph = SiteJsonLd::webPage('https://example.com/about', 'About Us | BrokersCourt', 'Independent broker research.');

        $this->assertSame('https://schema.org', $graph['@context']);
        $this->assertSame('WebPage', $graph['@type']);
        $this->assertSame('https://example.com/about#webpage', $graph['@id']);
        $this->assertSame('https://example.com/about', $graph['url']);
        $this->assertSame('About Us | BrokersCourt', $graph['name']);
        $this->assertSame('Independent broker research.', $graph['description']);
        $this->assertSame('en', $graph['inLanguage']);
        $this->assertArrayHasKey('@id', $graph['publisher']);
    }
}
