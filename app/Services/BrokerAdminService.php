<?php

namespace App\Services;

use App\Models\Broker;
use App\Services\Admin\PublicUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\EditorialAssignmentService;

class BrokerAdminService
{
    public function __construct(protected PublicUploadService $uploads)
    {
    }
    /** @var string[] */
    protected array $booleanFields = [
        'demo_account_available',
        'featured_broker',
        'vps_hosting',
        'economic_calendar',
        'account_managers',
        'investor_protection',
        'segregation_of_funds',
        'negative_balance_protection',
        'is_scam',
    ];

    /** @var string[] */
    protected array $jsonArrayFields = [
        'regulation',
        'platforms',
        'account_types',
        'broker_categories',
        'regions',
        'associated_countries',
        'markets',
    ];

    /** @var string[] */
    protected array $fillableScalars = [
        'name', 'slug', 'title', 'url', 'short_description', 'country', 'year_founded',
        'languages', 'visit_site', 'open_live', 'open_demo', 'demo_link', 'demo_duration',
        'top_feature', 'top_broker', 'meta_title', 'meta_keyword', 'meta_description',
        'minimum_deposit', 'spreads', 'leverage', 'pricing', 'commission', 'fee_level',
        'withdrawal_fee', 'instrument_count', 'deposit_methods', 'withdrawal_method',
        'payment_methods', 'mobile_trading', 'social_trading', 'web_trader', 'charting_tools',
        'customer_support', 'educational_resources', 'research_tools', 'news_and_analysis',
        'rating', 'trust_score', 'regulatory_tier', 'regulated_jurisdictions',
        'regulatory_licenses', 'capitalization', 'insurance', 'pros', 'cons', 'verdict',
        'scam_reason', 'scam_reported_date',
    ];

    public function save(Broker $broker, Request $request): Broker
    {
        $broker->fill($request->only($this->fillableScalars));

        foreach ($this->booleanFields as $field) {
            $broker->{$field} = $request->boolean($field);
        }

        foreach ($this->jsonArrayFields as $field) {
            $broker->{$field} = $this->normalizeArrayInput($request->input($field, []));
        }

        $broker->category_scores = $this->normalizeCategoryScores($request->input('category_scores', []));

        if (empty($broker->slug)) {
            $broker->slug = Str::slug($request->input('name', $broker->name));
        }

        $this->handleUpload($request, 'logo', 'uploads/logos', 'logo_', $broker, 'logo');
        $this->handleUpload($request, 'banner_image_1', 'uploads', 'banner1_', $broker, 'banner_image_1');
        $this->handleUpload($request, 'banner_image_2', 'uploads', 'banner2_', $broker, 'banner_image_2');

        EditorialAssignmentService::applyFromRequest($broker, $request);

        $broker->save();

        CountryBrokersService::flush();

        return $broker;
    }

    public function delete(Broker $broker): void
    {
        foreach (['logo', 'banner_image_1', 'banner_image_2'] as $field) {
            $this->deletePublicFile($broker->{$field});
        }

        $broker->delete();

        CountryBrokersService::flush();
    }

    protected function normalizeArrayInput(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), fn ($item) => $item !== ''));
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter($decoded, fn ($item) => $item !== '' && $item !== null));
            }

            return array_values(array_filter(array_map('trim', explode(',', $value)), fn ($item) => $item !== ''));
        }

        return [];
    }

    protected function normalizeCategoryScores(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        $scores = [];
        foreach ($value as $key => $score) {
            if ($score === null || $score === '') {
                continue;
            }
            $scores[$key] = round((float) $score, 1);
        }

        return $scores ?: null;
    }

    protected function handleUpload(
        Request $request,
        string $inputName,
        string $directory,
        string $prefix,
        Broker $broker,
        string $column
    ): void {
        if (! $request->hasFile($inputName)) {
            return;
        }

        $path = $this->uploads->replaceFromRequest(
            $request,
            $inputName,
            $broker->{$column},
            $directory,
            $prefix
        );

        if ($path === null) {
            return;
        }

        if (trim($directory, '/') === 'uploads' && ! str_contains($path, '/')) {
            $path = 'uploads/' . $path;
        }

        $broker->{$column} = $path;
    }

    protected function deletePublicFile(?string $relativePath): void
    {
        $this->uploads->delete($relativePath);
    }

    public static function marketOptions(): array
    {
        return [
            'forex' => 'Forex',
            'stocks' => 'Stocks',
            'crypto' => 'Crypto',
            'indices' => 'Indices',
            'commodities' => 'Commodities',
            'etfs' => 'ETFs',
            'bonds' => 'Bonds',
        ];
    }

    public static function platformOptions(): array
    {
        return [
            'MetaTrader 4' => 'MetaTrader 4',
            'MetaTrader 5' => 'MetaTrader 5',
            'cTrader' => 'cTrader',
            'TradingView' => 'TradingView',
            'WebTrader' => 'WebTrader',
            'Proprietary' => 'Proprietary Platform',
        ];
    }

    public static function regulationOptions(): array
    {
        return [
            'FCA' => 'FCA (UK)',
            'ASIC' => 'ASIC (Australia)',
            'CySEC' => 'CySEC (Cyprus)',
            'FSCA' => 'FSCA (South Africa)',
            'NFA/CFTC' => 'NFA/CFTC (US)',
            'BaFin' => 'BaFin (Germany)',
            'MAS' => 'MAS (Singapore)',
            'FINMA' => 'FINMA (Switzerland)',
            'FSC' => 'FSC (Mauritius)',
            'FSA' => 'FSA (Seychelles)',
        ];
    }

    public static function categoryScoreKeys(): array
    {
        return [
            'fees' => 'Fees & Costs',
            'safety' => 'Safety & Regulation',
            'platforms' => 'Trading Platforms',
            'deposit_withdrawal' => 'Deposit & Withdrawal',
            'customer_support' => 'Customer Support',
            'education' => 'Education',
            'research' => 'Research',
            'account_opening' => 'Account Opening',
            'products' => 'Products & Markets',
        ];
    }

    public static function brokerCategoryOptions(): array
    {
        return \App\Support\BrokerTaxonomy::categories();
    }

    public static function regionOptions(): array
    {
        return \App\Support\BrokerTaxonomy::regions();
    }

    public static function countryListingOptions(): array
    {
        return [
            'united-kingdom' => 'United Kingdom',
            'india' => 'India',
            'bangladesh' => 'Bangladesh',
            'singapore' => 'Singapore',
            'malaysia' => 'Malaysia',
            'south-africa' => 'South Africa',
            'nigeria' => 'Nigeria',
        ];
    }

    /** @return string[] */
    public static function selectableCountrySlugs(): array
    {
        return array_merge(['global'], array_keys(self::countryListingOptions()));
    }

    /**
     * Admin Country Listings + flags for nav, drawer, and homepage picker.
     *
     * @return array<string, array{name: string, flag: string, code: ?string, shortcode: string}>
     */
    public static function listedCountriesWithFlags(): array
    {
        $taxonomy = \App\Support\BrokerTaxonomy::countriesWithFlags();
        $listed = [];

        foreach (self::countryListingOptions() as $slug => $label) {
            $meta = $taxonomy[$slug] ?? ['name' => $label, 'flag' => '🌍', 'code' => null];
            $listed[$slug] = [
                'name' => $meta['name'] ?? $label,
                'flag' => $meta['flag'] ?? '🌍',
                'code' => $meta['code'] ?? null,
                'shortcode' => \App\Support\BrokerTaxonomy::countryShortcode($slug, $meta['code'] ?? null),
            ];
        }

        return $listed;
    }

    /**
     * Admin region options + flags for nav and mega menus.
     *
     * @return array<string, array{name: string, flag: string, code: ?string, shortcode: string}>
     */
    public static function listedRegionsWithFlags(): array
    {
        $taxonomy = \App\Support\BrokerTaxonomy::regionsWithFlags();
        $listed = [];

        foreach (self::regionOptions() as $slug => $label) {
            $meta = $taxonomy[$slug] ?? ['name' => $label, 'flag' => '🌍', 'code' => null];
            $listed[$slug] = [
                'name' => $meta['name'] ?? $label,
                'flag' => $meta['flag'] ?? '🌍',
                'code' => $meta['code'] ?? null,
                'shortcode' => \App\Support\BrokerTaxonomy::countryShortcode($slug, $meta['code'] ?? null),
            ];
        }

        return $listed;
    }

    /**
     * Global + admin Country Listings for the country selector UI.
     *
     * @return array<string, array{name: string, flag: string, code: ?string, shortcode: string}>
     */
    public static function countriesForSelector(): array
    {
        $global = \App\Support\BrokerTaxonomy::countriesWithFlags()['global'];

        return [
            'global' => [
                'name' => $global['name'],
                'flag' => $global['flag'],
                'code' => $global['code'],
                'shortcode' => 'GL',
            ],
        ] + self::listedCountriesWithFlags();
    }
}
