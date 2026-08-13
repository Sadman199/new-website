<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontLayoutArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_find_my_broker_renders(): void
    {
        $this->get('/find-my-broker')->assertOk();
    }

    public function test_compare_page_renders(): void
    {
        $this->get('/brokers/compare')->assertOk();
    }

    public function test_search_page_renders(): void
    {
        $this->get('/search')->assertOk();
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_privacy_page_renders(): void
    {
        $this->get('/privacy-policy')->assertOk();
    }

    public function test_terms_page_renders(): void
    {
        $this->get('/terms-and-conditions')->assertOk();
    }

    public function test_disclaimer_page_renders(): void
    {
        $this->get('/disclaimer')->assertOk();
    }

    public function test_methodology_page_renders(): void
    {
        $this->get('/our-methodology')->assertOk();
    }

    public function test_navbar_uses_is_hidden_not_tailwind_hidden(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('is-hidden', $html);
        $this->assertStringNotContainsString('id="mobileMenu" class="lg:hidden hidden', $html);
        $this->assertStringNotContainsString("classList.add('hidden')", $html);
    }

    public function test_broker_reviews_index_renders(): void
    {
        $this->get('/broker-reviews')->assertOk();
    }
}
