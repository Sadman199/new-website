<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrokerComparisonRedirectTest extends TestCase
{
    use RefreshDatabase;
    public function test_legacy_comparison_path_redirects_to_compare_tool(): void
    {
        $this->get('/brokers/comparison')
            ->assertStatus(301)
            ->assertRedirect(route('broker.comparison'));
    }
}
