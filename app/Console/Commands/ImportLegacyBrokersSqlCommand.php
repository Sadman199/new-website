<?php

namespace App\Console\Commands;

use App\Models\Broker;
use App\Services\BrokerGuideService;
use App\Services\CountryBrokersService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportLegacyBrokersSqlCommand extends Command
{
    protected $signature = 'brokers:import-legacy
        {path? : Path to legacy_brokers.json (default: storage/app/imports/legacy_brokers.json)}
        {--fresh : Truncate brokers (and dependent account_options) before import}
        {--force : Skip confirmation prompts}
        {--skip-guides : Do not seed draft broker guides}';

    protected $description = 'Import brokers from a parsed legacy SQL dump JSON into the current schema (admin-editable).';

    /** @var array<string, array<string, mixed>> */
    private array $enrichment = [
        'exness' => [
            'year_founded' => 2008,
            'markets' => ['forex', 'crypto', 'indices', 'commodities', 'stocks'],
            'commission' => 'From $0 (Standard) / Raw spread accounts available',
            'fee_level' => 'low',
            'withdrawal_fee' => 'Usually free (method-dependent)',
            'demo_duration' => 'Unlimited',
            'regulatory_tier' => 2,
        ],
        'ic-markets' => [
            'year_founded' => 2007,
            'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'],
            'commission' => 'From $3.50 per side (Raw)',
            'fee_level' => 'low',
            'withdrawal_fee' => 'Free for most methods',
            'demo_duration' => 'Unlimited',
            'regulatory_tier' => 1,
        ],
        'xm' => [
            'year_founded' => 2009,
            'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'],
            'commission' => 'Included in spread (Standard)',
            'fee_level' => 'medium',
            'demo_duration' => '30 days (typical)',
            'regulatory_tier' => 1,
        ],
        'fbs' => [
            'year_founded' => 2009,
            'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'],
            'fee_level' => 'medium',
            'regulatory_tier' => 2,
        ],
        'fpmarkets' => [
            'year_founded' => 2005,
            'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'],
            'fee_level' => 'low',
            'regulatory_tier' => 1,
        ],
        'fxpro' => [
            'year_founded' => 2006,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'medium',
            'regulatory_tier' => 1,
        ],
        'fxtm' => [
            'year_founded' => 2011,
            'markets' => ['forex', 'indices', 'commodities', 'stocks'],
            'fee_level' => 'medium',
            'regulatory_tier' => 2,
        ],
        'vtmarkets' => [
            'year_founded' => 2015,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'low',
            'regulatory_tier' => 2,
        ],
        'oneroyal' => [
            'year_founded' => 2019,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'low',
            'regulatory_tier' => 2,
        ],
        'pu-prime' => [
            'year_founded' => 2015,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'low',
            'regulatory_tier' => 2,
        ],
        'hantec-markets' => [
            'year_founded' => 1995,
            'markets' => ['forex', 'indices', 'commodities'],
            'fee_level' => 'medium',
            'regulatory_tier' => 1,
        ],
        'go-markets' => [
            'year_founded' => 2006,
            'markets' => ['forex', 'indices', 'commodities', 'stocks'],
            'fee_level' => 'low',
            'regulatory_tier' => 1,
        ],
        'multibank-fx' => [
            'year_founded' => 2005,
            'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'],
            'fee_level' => 'medium',
            'regulatory_tier' => 2,
        ],
        'pepperstone' => [
            'year_founded' => 2007,
            'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'],
            'fee_level' => 'low',
            'regulatory_tier' => 1,
        ],
        'etoro' => [
            'year_founded' => 2007,
            'markets' => ['forex', 'stocks', 'crypto', 'indices', 'commodities'],
            'fee_level' => 'medium',
            'regulatory_tier' => 1,
        ],
        'axi' => [
            'year_founded' => 2007,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'low',
            'regulatory_tier' => 1,
        ],
        'eightcap' => [
            'year_founded' => 2009,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'low',
            'regulatory_tier' => 1,
        ],
        'tickmill' => [
            'year_founded' => 2014,
            'markets' => ['forex', 'indices', 'commodities'],
            'fee_level' => 'low',
            'regulatory_tier' => 1,
        ],
        'vantage' => [
            'year_founded' => 2009,
            'markets' => ['forex', 'indices', 'commodities', 'crypto'],
            'fee_level' => 'low',
            'regulatory_tier' => 2,
        ],
        'roboforex' => [
            'year_founded' => 2009,
            'markets' => ['forex', 'indices', 'commodities', 'stocks', 'crypto'],
            'fee_level' => 'medium',
            'regulatory_tier' => 2,
        ],
    ];

    public function handle(): int
    {
        $path = $this->argument('path')
            ?: storage_path('app/imports/legacy_brokers.json');

        if (! is_file($path)) {
            $this->error("JSON not found: {$path}");
            $this->line('Run: python docs/extract_legacy_brokers_sql.py');

            return self::FAILURE;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows) || $rows === []) {
            $this->error('Import JSON is empty or invalid.');

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            if (! $this->option('force') && ! $this->confirm('This will delete existing brokers and account_options. Continue?', false)) {
                return self::SUCCESS;
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            if (DB::getSchemaBuilder()->hasTable('broker_guide_sections')) {
                DB::table('broker_guide_sections')->truncate();
            }
            if (DB::getSchemaBuilder()->hasTable('broker_guides')) {
                DB::table('broker_guides')->truncate();
            }
            if (DB::getSchemaBuilder()->hasTable('account_options')) {
                DB::table('account_options')->truncate();
            }
            DB::table('brokers')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->warn('Truncated brokers (+ related).');
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $missingFieldsLog = [];
        $usedSlugs = [];

        DB::transaction(function () use ($rows, &$created, &$updated, &$skipped, &$missingFieldsLog, &$usedSlugs) {
            foreach ($rows as $row) {
                $legacyId = ! empty($row['id']) ? (int) $row['id'] : null;
                $baseSlug = Str::slug((string) ($row['slug'] ?? $row['name'] ?? ''));
                if ($baseSlug === '') {
                    $skipped++;
                    continue;
                }

                $slug = $baseSlug;
                if (isset($usedSlugs[$slug]) || Broker::where('slug', $slug)->when($legacyId, fn ($q) => $q->where('id', '!=', $legacyId))->exists()) {
                    $slug = $baseSlug.'-'.($legacyId ?: Str::lower(Str::random(4)));
                }
                $usedSlugs[$slug] = true;

                $payload = $this->mapRow($row, $slug);
                $broker = $legacyId
                    ? Broker::disableCache()->find($legacyId)
                    : null;

                if ($broker) {
                    $broker->fill($payload);
                    foreach (['logo', 'banner_image_1', 'banner_image_2'] as $img) {
                        if (array_key_exists($img, $payload)) {
                            $broker->{$img} = $payload[$img];
                        }
                    }
                    $broker->save();
                    $updated++;
                } else {
                    $broker = new Broker();
                    if ($legacyId && ! Broker::where('id', $legacyId)->exists()) {
                        $broker->id = $legacyId;
                    }
                    $broker->fill($payload);
                    foreach (['logo', 'banner_image_1', 'banner_image_2'] as $img) {
                        if (array_key_exists($img, $payload)) {
                            $broker->{$img} = $payload[$img];
                        }
                    }
                    $broker->save();
                    $created++;
                }

                if (! $this->option('skip-guides')) {
                    try {
                        app(BrokerGuideService::class)->ensureGuidesForBroker($broker);
                    } catch (\Throwable $e) {
                        $this->warn("Guides skipped for {$slug}: ".$e->getMessage());
                    }
                }

                $missing = $this->missingAdminFields($broker);
                if ($missing !== []) {
                    $missingFieldsLog[$slug] = $missing;
                }
            }
        });

        CountryBrokersService::flush();

        $this->info("Import done. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}");
        $this->line('All imported fields are editable in Admin → Brokers.');

        $reportPath = storage_path('app/imports/legacy_brokers_missing_fields.json');
        file_put_contents(
            $reportPath,
            json_encode($missingFieldsLog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        $this->line("Missing-field checklist written to: {$reportPath}");
        $this->comment('Fill empty fields (description, verdict, markets, etc.) per broker in admin edit.');

        return self::SUCCESS;
    }

    /** @param  array<string, mixed>  $row */
    private function mapRow(array $row, string $slug): array
    {
        $jsonFields = [
            'regulation',
            'platforms',
            'account_types',
            'associated_countries',
            'markets',
            'broker_categories',
            'regions',
            'category_scores',
        ];

        $payload = [
            'slug' => $slug,
            'name' => (string) ($row['name'] ?? $slug),
            'title' => $row['title'] ?? null,
            'rating' => $this->toDecimal($row['rating'] ?? null),
            'url' => (string) ($row['url'] ?? '#'),
            'logo' => $row['logo'] ?? null,
            'banner_image_1' => $row['banner_image_1'] ?? null,
            'banner_image_2' => $row['banner_image_2'] ?? null,
            'short_description' => $row['short_description'] ?? null,
            'visit_site' => $row['visit_site'] ?? null,
            'open_live' => $row['open_live'] ?? null,
            'open_demo' => $row['open_demo'] ?? null,
            'pros' => $row['pros'] ?? null,
            'cons' => $row['cons'] ?? null,
            'languages' => $this->asText($row['languages'] ?? null),
            'pricing' => $this->asText($row['pricing'] ?? null),
            'deposit_methods' => $this->asText($row['deposit_methods'] ?? null),
            'withdrawal_method' => $this->asText($row['withdrawal_method'] ?? null),
            'country' => (string) ($row['country'] ?? 'Unknown'),
            'regulated_jurisdictions' => $row['regulated_jurisdictions'] ?? null,
            'regulatory_licenses' => $row['regulatory_licenses'] ?? null,
            'minimum_deposit' => $this->toDecimal($row['minimum_deposit'] ?? null),
            'spreads' => $this->asText($row['spreads'] ?? null),
            'leverage' => $this->asText($row['leverage'] ?? null),
            'payment_methods' => $row['payment_methods'] ?? null,
            'customer_support' => $row['customer_support'] ?? null,
            'educational_resources' => $row['educational_resources'] ?? null,
            'research_tools' => $row['research_tools'] ?? null,
            'mobile_trading' => $row['mobile_trading'] ?? null,
            'social_trading' => $row['social_trading'] ?? null,
            // Old dump stores free-text HTML notes in capitalization; DB is decimal — skip non-numeric.
            'capitalization' => $this->toDecimal($row['capitalization'] ?? null),
            'insurance' => $row['insurance'] ?? null,
            'segregation_of_funds' => (bool) ($row['segregation_of_funds'] ?? false),
            'web_trader' => $row['web_trader'] ?? null,
            'charting_tools' => $row['charting_tools'] ?? null,
            'account_managers' => (bool) ($row['account_managers'] ?? false),
            'news_and_analysis' => $row['news_and_analysis'] ?? null,
            'top_feature' => $row['top_feature'] ?? null,
            'featured_broker' => (bool) ($row['featured_broker'] ?? false),
            'top_broker' => isset($row['top_broker']) ? (int) $row['top_broker'] : null,
            'is_scam' => (bool) ($row['is_scam'] ?? false),
            'scam_reason' => $row['scam_reason'] ?? null,
            'scam_reported_date' => $row['scam_reported_date'] ?? null,
            'meta_title' => $row['meta_title'] ?? null,
            'meta_keyword' => $row['meta_keyword'] ?? null,
            'meta_description' => $row['meta_description'] ?? null,
            'economic_calendar' => (bool) ($row['economic_calendar'] ?? false),
            'vps_hosting' => (bool) ($row['vps_hosting'] ?? false),
            'demo_account_available' => filled($row['open_demo'] ?? null) || filled($row['demo_link'] ?? null),
            'demo_link' => $row['open_demo'] ?? ($row['demo_link'] ?? null),
            'investor_protection' => false,
            'negative_balance_protection' => false,
        ];

        foreach ($jsonFields as $field) {
            if (! array_key_exists($field, $row) && $field !== 'markets') {
                continue;
            }
            $payload[$field] = $this->decodeJsonArray($row[$field] ?? null);
        }

        // Derive trust score from rating when missing in legacy dump.
        if ($payload['rating'] !== null) {
            $payload['trust_score'] = (int) min(99, max(1, round(((float) $payload['rating']) * 20)));
        }

        // Known-broker enrichment (still fully editable in admin).
        $extra = $this->enrichment[$slug] ?? [];
        foreach ($extra as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (! array_key_exists($key, $payload) || blank($payload[$key]) || $payload[$key] === [] || $payload[$key] === false) {
                $payload[$key] = $value;
            }
        }

        // Default markets if still empty.
        if (empty($payload['markets'])) {
            $payload['markets'] = ['forex'];
        }

        return $payload;
    }

    private function decodeJsonArray(mixed $value): ?array
    {
        $items = \App\Support\JsonList::normalize($value);

        return $items !== [] ? $items : null;
    }

    private function toDecimal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return round((float) $value, 2);
        }
        if (is_string($value)) {
            $clean = preg_replace('/[^0-9.\-]/', '', str_replace(',', '', strip_tags($value)));
            if ($clean !== null && $clean !== '' && is_numeric($clean)) {
                return round((float) $clean, 2);
            }
        }

        return null;
    }

    private function asText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        if (is_array($value)) {
            return \App\Support\JsonList::toPlainText($value);
        }

        return \App\Support\RichText::toPlainText((string) $value);
    }

    private function asVarcharString(mixed $value, int $maxLength = 255): ?string
    {
        $string = $this->asText($value);
        if ($string === null) {
            return null;
        }
        if (Str::length($string) <= $maxLength) {
            return $string;
        }

        return Str::limit($string, $maxLength, '');
    }

    /** @return list<string> */
    private function missingAdminFields(Broker $broker): array
    {
        $checks = [
            'description',
            'verdict',
            'year_founded',
            'commission',
            'fee_level',
            'withdrawal_fee',
            'demo_duration',
            'markets',
            'instrument_count',
            'category_scores',
            'broker_categories',
            'regions',
            'regulatory_tier',
            'investor_protection',
            'negative_balance_protection',
        ];

        $missing = [];
        foreach ($checks as $field) {
            $val = $broker->{$field};
            if ($val === null || $val === '' || $val === [] || $val === false) {
                $missing[] = $field;
            }
        }

        return $missing;
    }
}
