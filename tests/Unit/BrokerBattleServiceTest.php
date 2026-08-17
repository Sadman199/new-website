<?php

namespace Tests\Unit;

use App\Services\BrokerBattleService;
use App\Services\BrokerComparisonService;
use Mockery;
use Tests\TestCase;

class BrokerBattleServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_overall_score_averages_available_evidence_only(): void
    {
        $service = $this->makeService();
        $method = new \ReflectionMethod($service, 'overallScore');
        $method->setAccessible(true);

        $score = $method->invoke($service, [
            'rating' => 4.0,
            'safety' => ['overall_score' => 80],
            'trust_score' => 90,
            'category_scores' => [
                'fees' => 7.0,
            ],
        ]);

        $this->assertSame(4, $score['evidence_count']);
        $this->assertSame(8.0, $score['value']);
        $this->assertSame('8.0/10', $score['display']);
    }

    public function test_missing_evidence_does_not_invent_score(): void
    {
        $service = $this->makeService();
        $method = new \ReflectionMethod($service, 'overallScore');
        $method->setAccessible(true);

        $score = $method->invoke($service, [
            'rating' => null,
            'safety' => [],
            'trust_score' => '—',
            'category_scores' => [],
        ]);

        $this->assertNull($score['value']);
        $this->assertSame('—', $score['display']);
        $this->assertSame(0, $score['evidence_count']);
    }

    public function test_ranked_row_keeps_existing_winner(): void
    {
        $service = $this->makeService();
        $method = new \ReflectionMethod($service, 'roundFromRow');
        $method->setAccessible(true);

        $round = $method->invoke($service, [
            'label' => 'Minimum deposit',
            'left' => '$10',
            'right' => '$100',
            'winner' => 'broker1',
        ]);

        $this->assertSame('broker1', $round['outcome']);
        $this->assertSame('Win', $round['winner_label']);
    }

    public function test_equal_values_become_tie(): void
    {
        $service = $this->makeService();
        $method = new \ReflectionMethod($service, 'roundFromRow');
        $method->setAccessible(true);

        $round = $method->invoke($service, [
            'label' => 'Average spreads',
            'left' => '0.6 pips',
            'right' => '0.6 pips',
            'winner' => null,
        ]);

        $this->assertSame('tie', $round['outcome']);
        $this->assertSame('Tie', $round['winner_label']);
    }

    public function test_unrankable_text_is_insufficient(): void
    {
        $service = $this->makeService();
        $method = new \ReflectionMethod($service, 'roundFromRow');
        $method->setAccessible(true);

        $round = $method->invoke($service, [
            'label' => 'Average spreads',
            'left' => '0.6 pips',
            'right' => '0.8 pips',
            'winner' => null,
        ]);

        $this->assertSame('insufficient', $round['outcome']);
        $this->assertSame('Not enough data', $round['winner_label']);
    }

    public function test_missing_values_are_insufficient(): void
    {
        $service = $this->makeService();
        $method = new \ReflectionMethod($service, 'roundFromRow');
        $method->setAccessible(true);

        $round = $method->invoke($service, [
            'label' => 'Commission',
            'left' => '—',
            'right' => '$2/lot',
            'winner' => null,
        ]);

        $this->assertSame('insufficient', $round['outcome']);
    }

    public function test_category_wins_decide_battle_winner(): void
    {
        $service = $this->makeService();

        $battle = $service->buildBattle(
            new \App\Models\Broker(['name' => 'XM', 'slug' => 'xm']),
            new \App\Models\Broker(['name' => 'Vantage', 'slug' => 'vantage']),
            [
                'broker1' => [
                    'name' => 'XM',
                    'slug' => 'xm',
                    'rating' => 4.5,
                    'trust_score' => 88,
                    'safety' => ['overall_score' => 90],
                    'category_scores' => ['fees' => 8.5],
                ],
                'broker2' => [
                    'name' => 'Vantage',
                    'slug' => 'vantage',
                    'rating' => 4.2,
                    'trust_score' => 80,
                    'safety' => ['overall_score' => 82],
                    'category_scores' => ['fees' => 7.5],
                ],
                'sections' => [
                    [
                        'id' => 'overview',
                        'label' => 'Overview',
                        'rows' => [
                            ['label' => 'Overall rating', 'left' => '4.5/5', 'right' => '4.2/5', 'winner' => 'broker1'],
                            ['label' => 'Safety score', 'left' => '90/100', 'right' => '82/100', 'winner' => 'broker1'],
                            ['label' => 'Trust score', 'left' => '88', 'right' => '80', 'winner' => 'broker1'],
                            ['label' => 'Average spreads', 'left' => '0.6 pips', 'right' => '0.8 pips', 'winner' => null],
                        ],
                    ],
                ],
                'promotions' => [
                    'broker1' => null,
                    'broker2' => null,
                ],
            ]
        );

        $this->assertSame(3, $battle['wins']['broker1']);
        $this->assertSame(0, $battle['wins']['broker2']);
        $this->assertSame(1, $battle['wins']['insufficient']);
        $this->assertSame('broker1', $battle['winner']['broker']);
        $this->assertSame('XM', $battle['winner']['name']);
        $this->assertStringContainsString('wins 3 categories', $battle['winner']['reason']);
    }

    public function test_tied_categories_can_fall_back_to_score_evidence(): void
    {
        $service = $this->makeService();

        $battle = $service->buildBattle(
            new \App\Models\Broker(['name' => 'XM', 'slug' => 'xm']),
            new \App\Models\Broker(['name' => 'Vantage', 'slug' => 'vantage']),
            [
                'broker1' => [
                    'name' => 'XM',
                    'slug' => 'xm',
                    'rating' => 4.8,
                    'trust_score' => 90,
                    'safety' => ['overall_score' => 92],
                    'category_scores' => ['fees' => 9.0],
                ],
                'broker2' => [
                    'name' => 'Vantage',
                    'slug' => 'vantage',
                    'rating' => 4.0,
                    'trust_score' => 70,
                    'safety' => ['overall_score' => 70],
                    'category_scores' => ['fees' => 6.0],
                ],
                'sections' => [
                    [
                        'id' => 'overview',
                        'label' => 'Overview',
                        'rows' => [
                            ['label' => 'Overall rating', 'left' => '4.5/5', 'right' => '4.5/5', 'winner' => null],
                            ['label' => 'Trust score', 'left' => '80', 'right' => '80', 'winner' => null],
                        ],
                    ],
                ],
                'promotions' => [
                    'broker1' => null,
                    'broker2' => null,
                ],
            ]
        );

        $this->assertSame(0, $battle['wins']['broker1']);
        $this->assertSame(0, $battle['wins']['broker2']);
        $this->assertSame(2, $battle['wins']['ties']);
        $this->assertSame('broker1', $battle['winner']['broker']);
        $this->assertStringContainsString('evidence-based battle score', $battle['winner']['reason']);
    }

    public function test_no_winner_when_everything_is_tied_and_scores_match(): void
    {
        $service = $this->makeService();

        $battle = $service->buildBattle(
            new \App\Models\Broker(['name' => 'XM', 'slug' => 'xm']),
            new \App\Models\Broker(['name' => 'Vantage', 'slug' => 'vantage']),
            [
                'broker1' => [
                    'name' => 'XM',
                    'slug' => 'xm',
                    'rating' => 4.0,
                    'trust_score' => 80,
                    'safety' => ['overall_score' => 80],
                    'category_scores' => [],
                ],
                'broker2' => [
                    'name' => 'Vantage',
                    'slug' => 'vantage',
                    'rating' => 4.0,
                    'trust_score' => 80,
                    'safety' => ['overall_score' => 80],
                    'category_scores' => [],
                ],
                'sections' => [
                    [
                        'id' => 'overview',
                        'label' => 'Overview',
                        'rows' => [
                            ['label' => 'Overall rating', 'left' => '4.0/5', 'right' => '4.0/5', 'winner' => null],
                        ],
                    ],
                ],
                'promotions' => [
                    'broker1' => null,
                    'broker2' => null,
                ],
            ]
        );

        $this->assertNull($battle['winner']);
        $this->assertSame(1, $battle['wins']['ties']);
    }

    private function makeService(): BrokerBattleService
    {
        return new BrokerBattleService(Mockery::mock(BrokerComparisonService::class));
    }
}
