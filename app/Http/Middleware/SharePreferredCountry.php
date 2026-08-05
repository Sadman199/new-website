<?php

namespace App\Http\Middleware;

use App\Support\BrokerTaxonomy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SharePreferredCountry
{
    public function handle(Request $request, Closure $next)
    {
        View::share('brokerCountries', BrokerTaxonomy::countriesWithFlags());
        View::share('preferredCountry', BrokerTaxonomy::resolvePreferredCountry());

        return $next($request);
    }
}
