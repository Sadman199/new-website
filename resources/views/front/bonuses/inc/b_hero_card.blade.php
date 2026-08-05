
@php
$allCards = [
    [
        'types' => ['no-deposit-bonuses'],
        'title' => 'Free Trading Funds',
        'desc' => 'Start trading without deposit',
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
    ],
    [
        'types' => ['no-deposit-bonuses', 'demo-contests'],
        'title' => 'Risk-Free Trading',
        'desc' => 'Practice with real money',
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
    ],
    [
        'types' => ['no-deposit-bonuses', 'deposit-bonuses', 'live-contests', 'cashback-rebates', 'crypto-bonuses'],
        'title' => 'Profit Withdrawal',
        'desc' => [
            'no-deposit-bonuses' => 'Keep what you earn',
            'deposit-bonuses' => 'Withdraw bonus profits',
            'live-contests' => 'Claim your contest winnings',
            'cashback-rebates' => 'Withdraw your rebates',
            'crypto-bonuses' => 'Withdraw crypto profits',
        ],
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
    ],
    [
        'types' => ['live-contests', 'demo-contests'],
        'title' => 'Time-Sensitive',
        'desc' => 'Limited time competitions',
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
    ],
    [
        'types' => ['deposit-bonuses', 'cashback-rebates', 'crypto-bonuses'],
        'title' => 'Trading Benefits',
        'desc' => [
            'deposit-bonuses' => 'Increased trading power',
            'cashback-rebates' => 'Lower trading costs',
            'crypto-bonuses' => 'Enhanced crypto trading',
        ],
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
    ],
    [
        'types' => ['no-deposit-bonuses', 'deposit-bonuses', 'crypto-bonuses'],
        'title' => 'Verified Brokers',
        'desc' => 'Regulated and trustworthy',
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
    ],
    [
        'types' => ['demo-contests'],
        'title' => 'Skill Development',
        'desc' => 'Improve without risk',
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
    ],
    [
        'types' => ['deposit-bonuses'],
        'title' => 'Bonus Tiers',
        'desc' => 'Higher deposits get bigger bonuses',
        'icon' => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 text-lg sm:text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>',
    ],
];

// Step 1: Get all cards matching the current type
$cardsForType = array_filter($allCards, fn($card) => in_array($type, $card['types']));

// Step 2: If less than 4, fill with cards from a fallback list (excluding duplicates)
if(count($cardsForType) < 4) {
    $fallbackCards = array_filter($allCards, function($card) use ($cardsForType, $type) {
        return !in_array($type, $card['types']) && !in_array($card, $cardsForType, true);
    });

    foreach ($fallbackCards as $fallbackCard) {
        if(count($cardsForType) >= 4) break;
        $cardsForType[] = $fallbackCard;
    }
}

// Limit to max 4 cards total
$cardsForType = array_slice($cardsForType, 0, 4);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    @foreach($cardsForType as $card)
    <div class="border border-gray-200 rounded-lg p-4 sm:p-5 hover:border-yellow-500 transition-colors duration-200 group">
        <div class="flex items-start space-x-3 sm:space-x-4">
            <div class="p-2 border border-gray-200 rounded-md group-hover:border-yellow-400 group-hover:text-blue-600 transition-colors duration-200">
                {!! $card['icon'] !!}
            </div>
            <div class="text-left">
                <span class="block font-medium text-gray-900 text-sm sm:text-base mb-1">{{ $card['title'] }}</span>
                <span class="text-xs sm:text-sm text-gray-500">
                    @if(is_array($card['desc']))
                        {{ $card['desc'][$type] ?? reset($card['desc']) }}
                    @else
                        {{ $card['desc'] }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    @endforeach
</div>