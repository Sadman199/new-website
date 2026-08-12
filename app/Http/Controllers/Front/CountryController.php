<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CountryBrokersService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function switch_country(Request $request)
    {
        $countryService = app(CountryBrokersService::class);

        $request->validate([
            'country' => ['required', 'string', Rule::in($countryService->selectableCountrySlugs())],
        ]);

        $slug = $request->input('country');
        session(['preferred_country' => $slug]);

        $cookie = cookie('preferred_country', $slug, 60 * 24 * 365);

        $redirectTo = url()->previous();
        if (! $redirectTo || str_contains($redirectTo, '/country/switch')) {
            $redirectTo = route('home');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()
                ->json([
                    'country' => $countryService->resolvePreferredCountry($slug),
                    'broker_count' => $countryService->countForCountry($slug),
                ])
                ->withCookie($cookie);
        }

        return redirect()
            ->to($redirectTo)
            ->withCookie($cookie)
            ->with('country_updated', $countryService->resolvePreferredCountry($slug));
    }
}
