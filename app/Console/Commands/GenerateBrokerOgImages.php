<?php

namespace App\Console\Commands;

use App\Models\Broker;
use App\Services\BrokerOgImageService;
use Illuminate\Console\Command;

class GenerateBrokerOgImages extends Command
{
    protected $signature = 'og:generate-brokers {--limit=0 : Max brokers to generate (0 = all)}';

    protected $description = 'Pre-generate Open Graph share images for broker review pages';

    public function handle(BrokerOgImageService $ogImages): int
    {
        if (! $ogImages->canGenerate()) {
            $this->error('GD extension is required to generate OG images.');

            return self::FAILURE;
        }

        $query = Broker::query()->orderBy('id');
        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $ok = 0;
        $fail = 0;

        $query->each(function (Broker $broker) use ($ogImages, &$ok, &$fail) {
            $path = $ogImages->ensureGenerated($broker);
            if ($path) {
                $ok++;
                $this->line('OK  '.$broker->name);
            } else {
                $fail++;
                $this->warn('FAIL '.$broker->name);
            }
        });

        $this->info("Generated {$ok} images".($fail ? ", {$fail} failed" : ''));

        return $fail ? self::FAILURE : self::SUCCESS;
    }
}
