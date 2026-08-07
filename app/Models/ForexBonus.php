<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Support\Str;

class ForexBonus extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'title',
        'slug',
        'broker_id',
        'publish_date',
        'author_name',
        'promo_type',
        'description',
        'feature_image',
        'link',
        'participate',
        'how_to_participate',
        'details',
        'general_terms',
        'prize',
        'eligibility_criteria',
        'expiry_date',
        'min_deposit',
        'bonus_amount',
        'bonus_percentage',
        'bonus_type_details',
        'terms_conditions_url',
        'affiliate_link',
        'bonus_category',
        'promotion_status',
        'is_featured',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'written_by_author_id',
        'edited_by_author_id',
        'fact_checked_by_author_id',
        'written_by_admin_id',
        'edited_by_admin_id',
        'fact_checked_by_admin_id',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date' => 'date',
        'min_deposit' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'bonus_percentage' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function writtenByAuthor()
    {
        return $this->belongsTo(Author::class, 'written_by_author_id');
    }

    public function editedByAuthor()
    {
        return $this->belongsTo(Author::class, 'edited_by_author_id');
    }

    public function factCheckedByAuthor()
    {
        return $this->belongsTo(Author::class, 'fact_checked_by_author_id');
    }

    public function writtenByAdmin()
    {
        return $this->belongsTo(Admin::class, 'written_by_admin_id');
    }

    public function editedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'edited_by_admin_id');
    }

    public function factCheckedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'fact_checked_by_admin_id');
    }

    public function displayAuthorName(): string
    {
        return $this->author_name
            ?: (\App\Services\EditorialAssignmentService::primaryWriterName($this) ?? 'Editorial Team');
    }

    public function brokerDisplayName(): ?string
    {
        return $this->broker?->name;
    }

    public function detailRouteName(): ?string
    {
        return match ($this->promo_type) {
            'Forex No Deposit Bonus' => 'no-deposit-bonuses.detail',
            'Forex Deposit Bonus' => 'deposit-bonuses.detail',
            'Forex Live Contest' => 'live-contests.detail',
            'Forex Demo Contest' => 'demo-contests.detail',
            'Forex Cashback Rebate' => 'cashback-rebates.detail',
            'Crypto Bonus Promotion' => 'crypto-bonuses.detail',
            default => null,
        };
    }

    public function detailSlug(): string
    {
        $broker = $this->broker;

        if ($broker) {
            return $broker->listingSlug();
        }

        $name = $this->brokerDisplayName();

        if ($name) {
            return Str::slug($name);
        }

        $legacy = (string) $this->slug;

        if ($legacy !== '') {
            $parts = explode('-', $legacy);

            return Str::slug($parts[0] !== '' ? $parts[0] : $legacy);
        }

        return 'promo-'.$this->id;
    }

    public function detailUrl(): ?string
    {
        $route = $this->detailRouteName();

        return $route ? route($route, $this->detailSlug()) : null;
    }

    public static function promoTypeForDetailRoute(?string $routeName): ?string
    {
        return match ($routeName) {
            'deposit-bonuses.detail' => 'Forex Deposit Bonus',
            'no-deposit-bonuses.detail' => 'Forex No Deposit Bonus',
            'live-contests.detail' => 'Forex Live Contest',
            'demo-contests.detail' => 'Forex Demo Contest',
            'cashback-rebates.detail' => 'Forex Cashback Rebate',
            'crypto-bonuses.detail' => 'Crypto Bonus Promotion',
            default => null,
        };
    }

    public static function findForDetailRoute(string $routeName, string $slug): self
    {
        $promoType = self::promoTypeForDetailRoute($routeName);

        if (! $promoType) {
            abort(404);
        }

        $normalized = Str::slug($slug);

        $bonus = static::query()
            ->with([
                'broker',
                'writtenByAuthor',
                'editedByAuthor',
                'factCheckedByAuthor',
                'writtenByAdmin',
                'editedByAdmin',
                'factCheckedByAdmin',
            ])
            ->where('promo_type', $promoType)
            ->where(function ($query) use ($slug, $normalized) {
                $query->where('slug', $slug)
                    ->orWhere('slug', $normalized)
                    ->orWhereHas('broker', function ($brokerQuery) use ($normalized) {
                        $brokerQuery->where('slug', $normalized)
                            ->orWhereRaw('LOWER(slug) = ?', [strtolower($normalized)]);
                    });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('publish_date')
            ->first();

        if (! $bonus) {
            $bonus = static::query()
                ->with([
                    'broker',
                    'writtenByAuthor',
                    'editedByAuthor',
                    'factCheckedByAuthor',
                    'writtenByAdmin',
                    'editedByAdmin',
                    'factCheckedByAdmin',
                ])
                ->where('promo_type', $promoType)
                ->orderByDesc('is_featured')
                ->orderByDesc('publish_date')
                ->get()
                ->first(fn (self $candidate) => $candidate->detailSlug() === $normalized);
        }

        if (! $bonus) {
            abort(404);
        }

        return $bonus;
    }

    public function cardUrl(): string
    {
        return $this->detailUrl()
            ?? $this->affiliate_link
            ?? $this->link
            ?? route('forex_deposit_bonus');
    }

    public function promoTypeShort(): string
    {
        return match ($this->promo_type) {
            'Forex Deposit Bonus' => 'Deposit bonus',
            'Forex No Deposit Bonus' => 'No deposit',
            'Forex Live Contest' => 'Live contest',
            'Forex Demo Contest' => 'Demo contest',
            'Forex Cashback Rebate' => 'Cashback',
            'Crypto Bonus Promotion' => 'Crypto promo',
            default => 'Promotion',
        };
    }

    public function promoTypeTone(): string
    {
        return match ($this->promo_type) {
            'Forex Deposit Bonus' => 'deposit',
            'Forex No Deposit Bonus' => 'nodeposit',
            'Forex Live Contest' => 'contest',
            'Forex Demo Contest' => 'demo',
            'Forex Cashback Rebate' => 'cashback',
            'Crypto Bonus Promotion' => 'crypto',
            default => 'default',
        };
    }

    public function headlineOffer(): string
    {
        if ($this->bonus_percentage) {
            $pct = rtrim(rtrim(number_format((float) $this->bonus_percentage, 2), '0'), '.');

            return $pct.'% bonus';
        }

        if ($this->bonus_amount) {
            return '$'.number_format((float) $this->bonus_amount, 0).' bonus';
        }

        $prize = trim(strip_tags((string) $this->prize));

        if ($prize !== '') {
            return Str::limit($prize, 56);
        }

        return 'View offer details';
    }

    public function minDepositLabel(): ?string
    {
        if ($this->min_deposit === null) {
            return null;
        }

        $amount = (float) $this->min_deposit;

        if ($amount <= 0) {
            return 'No min. deposit';
        }

        return 'From $'.number_format($amount, 0);
    }

    public function expiryLabel(): ?string
    {
        if (! $this->expiry_date) {
            return null;
        }

        if ($this->expiry_date->isPast()) {
            return 'Expired';
        }

        if ($this->expiry_date->lte(now()->addDays(14))) {
            return 'Ends '.$this->expiry_date->format('M j');
        }

        return 'Until '.$this->expiry_date->format('M j, Y');
    }

    public function isActivePromotion(): bool
    {
        if ($this->promotion_status === 'expired') {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return false;
        }

        return true;
    }

    /** @return array<int, array{label: string, route: string, type: string}> */
    public static function homepageCategoryLinks(): array
    {
        return [
            ['label' => 'Deposit bonuses', 'type' => 'deposit-bonuses', 'route' => 'bonuses.type'],
            ['label' => 'No deposit', 'type' => 'no-deposit-bonuses', 'route' => 'bonuses.type'],
            ['label' => 'Live contests', 'type' => 'live-contests', 'route' => 'bonuses.type'],
            ['label' => 'Demo contests', 'type' => 'demo-contests', 'route' => 'bonuses.type'],
            ['label' => 'Cashback', 'type' => 'cashback-rebates', 'route' => 'bonuses.type'],
            ['label' => 'Crypto promos', 'type' => 'crypto-bonuses', 'route' => 'bonuses.type'],
        ];
    }
}
