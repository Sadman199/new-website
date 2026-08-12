<?php

namespace Database\Seeders;

use App\Models\Broker;
use App\Services\BrokerGuideService;
use App\Services\BrokerGuideTopicService;
use Illuminate\Database\Seeder;

class BrokerGuideSeeder extends Seeder
{
    public function run(): void
    {
        app(BrokerGuideTopicService::class)->seedDefaultsIfEmpty();

        $service = app(BrokerGuideService::class);

        Broker::query()->orderBy('id')->each(function (Broker $broker) use ($service) {
            $service->ensureGuidesForBroker($broker);
        });
    }
}
