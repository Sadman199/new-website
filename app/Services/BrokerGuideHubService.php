<?php

namespace App\Services;

use App\Models\Broker;
use Illuminate\Support\Facades\DB;

class BrokerGuideHubService
{
    public function titleFor(Broker $broker): string
    {
        $template = $this->titleTemplate();

        return str_replace(':broker', $broker->name, $template);
    }

    public function titleTemplate(): string
    {
        return $this->setting('hub_title')
            ?? config('broker-guides.hub.title', 'Getting started with :broker');
    }

    public function description(): string
    {
        return $this->setting('hub_description')
            ?? config('broker-guides.hub.description', '');
    }

    /** @param array<string, string|null> $values */
    public function saveSettings(array $values): void
    {
        DB::transaction(function () use ($values) {
            foreach ($values as $key => $value) {
                DB::table('broker_guide_settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        });
    }

    private function setting(string $key): ?string
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('broker_guide_settings')) {
            return null;
        }

        $value = DB::table('broker_guide_settings')->where('key', $key)->value('value');

        return filled($value) ? (string) $value : null;
    }
}
