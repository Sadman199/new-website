<?php

namespace App\Http\Controllers\Front;

use App\Support\BrokerTaxonomy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BrokerCountryController extends FrontController
{
    public function showBrokersByCountry(string $country): RedirectResponse
    {
        $slug = Str::slug(urldecode($country));

        if ($slug === '' || $slug === 'global') {
            return redirect()->route('broker.reviews.index', [], 301);
        }

        abort_unless(isset(BrokerTaxonomy::countriesWithFlags()[$slug]), 404);

        return redirect()->route('brokers.best', ['slug' => $slug], 301);
    }
}
