<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\BrokerRecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrokerRecommendationController extends Controller
{
    public function recommend(Request $request, BrokerRecommendationService $service): JsonResponse
    {
        $validated = $request->validate([
            'country' => ['required', 'string', 'max:80'],
            'markets' => ['required', 'array', 'min:1'],
            'markets.*' => ['string', Rule::in(['forex', 'gold', 'crypto', 'stocks', 'indices'])],
            'experience' => ['required', Rule::in(['beginner', 'intermediate', 'active', 'professional'])],
            'cost_focus' => ['required', Rule::in(['low', 'balanced', 'premium'])],
            'activity' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'investor'])],
            'deposit' => ['required', Rule::in(['10', '50', '100', '500'])],
            'extras' => ['nullable', 'array'],
            'extras.*' => ['string', Rule::in(['copy_trading', 'islamic', 'vps', 'mobile', 'education', 'bonuses'])],
        ]);

        $validated['extras'] = $validated['extras'] ?? [];

        return response()->json($service->recommend($validated));
    }
}
