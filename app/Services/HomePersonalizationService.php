<?php

namespace App\Services;

use App\Models\Broker;
use App\Models\ForexBonus;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomePersonalizationService
{
    public function __construct(
        private readonly PromotionsIndexService $promotionsIndex,
        private readonly UserSavedBrokerService $savedBrokers,
        private readonly CountryBrokersService $countryBrokers,
        private readonly BrokerReviewsIndexService $reviewsIndex,
    ) {}

    /** @return array<string, mixed> */
    public function build(?User $user, string $countrySlug): array
    {
        $catalog = $this->promotionsIndex->activePromotionsCatalog();
        $allPromos = $catalog->flatten(1)->filter(fn (ForexBonus $bonus) => $bonus->isActivePromotion());

        $featured = $allPromos->firstWhere('is_featured', true) ?? $allPromos->first();

        $promotionCards = $allPromos
            ->take(6)
            ->map(fn (ForexBonus $bonus) => $this->promotionsIndex->serializeCard($bonus))
            ->values();

        $savedCards = $user
            ? $this->savedBrokers->cardsForUser($user)->take(4)->values()
            : collect();

        $countryBrokers = $countrySlug !== 'global'
            ? $this->countryBrokers->forCountry($countrySlug, 4)
                ->map(fn (Broker $broker) => $this->reviewsIndex->serialize($broker))
                ->values()
            : collect();

        $preferredCountry = $this->countryBrokers->resolvePreferredCountry($countrySlug);

        return [
            'isAuthenticated' => $user !== null,
            'user' => $user,
            'savedBrokerCards' => $savedCards,
            'countryBrokerCards' => $countryBrokers,
            'featuredPromotion' => $featured,
            'promotionCards' => $promotionCards,
            'homepagePromotions' => $allPromos->take(6),
            'preferredCountry' => $preferredCountry,
            'showSavedStrip' => $user && $savedCards->isNotEmpty(),
            'showCountryStrip' => $countryBrokers->isNotEmpty(),
            'showPromosSection' => $promotionCards->isNotEmpty(),
            'countryBrokersUrl' => $this->countryBrokers->brokersPageUrl($countrySlug) ?? route('all_brokers'),
        ];
    }

    public function currentUser(): ?User
    {
        return Auth::guard('web')->user();
    }
}
