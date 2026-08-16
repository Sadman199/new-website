<?php

namespace Tests\Unit;

use App\Support\BrokerRating;
use PHPUnit\Framework\TestCase;

class BrokerRatingTest extends TestCase
{
    public function test_five_point_rating_maps_to_proportional_fill(): void
    {
        $this->assertSame(4.2, BrokerRating::outOfFive(4.2));
        $this->assertSame(84.0, BrokerRating::percent(4.2));
    }

    public function test_legacy_ten_point_rating_is_normalized(): void
    {
        $this->assertSame(4.4, BrokerRating::outOfFive(8.8));
        $this->assertSame(88.0, BrokerRating::percent(8.8));
    }

    public function test_rating_values_are_clamped(): void
    {
        $this->assertSame(0.0, BrokerRating::outOfFive(-1));
        $this->assertSame(5.0, BrokerRating::outOfFive(12));
        $this->assertSame(100.0, BrokerRating::percent(12));
    }
}
