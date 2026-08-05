<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Support\BrokerTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function switch_country(Request $request)
    {
        $request->validate([
            'country' => ['required', 'string', Rule::in(BrokerTaxonomy::countrySlugs())],
        ]);

        $slug = $request->input('country');
        session(['preferred_country' => $slug]);

        $cookie = cookie('preferred_country', $slug, 60 * 24 * 365);

        $redirectTo = url()->previous();
        if (! $redirectTo || str_contains($redirectTo, '/country/switch')) {
            $redirectTo = route('home');
        }

        return redirect()
            ->to($redirectTo)
            ->withCookie($cookie)
            ->with('country_updated', BrokerTaxonomy::resolvePreferredCountry($slug));
    }
}
