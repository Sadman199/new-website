<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\Language;
use App\Helper\Helpers;
use Illuminate\Http\Request;

class ScamBrokerController extends Controller
{
    public function index(Request $request)
    {
        Helpers::read_json();

        if (!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        $query = Broker::where('is_scam', true);

        $search = trim((string) $request->get('q'));
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $scamBrokers = $query
            ->orderByDesc('scam_reported_date')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $scamCount = Broker::where('is_scam', true)->count();

        return view('front.scam-brokers', compact('scamBrokers', 'scamCount', 'page_data', 'home_ad_data', 'search'));
    }

    public function show($slug)
    {
        Helpers::read_json();

        if (!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        // Only flagged brokers have a scam details page. Resolve by the clean,
        // name-based scam slug (e.g. "neex"), falling back to the broker's own slug
        // so older links keep working.
        $slug = \Illuminate\Support\Str::slug($slug);
        $broker = Broker::where('is_scam', true)->get()
            ->first(fn ($b) => $b->scam_slug === $slug || \Illuminate\Support\Str::slug((string) $b->slug) === $slug);
        abort_if(!$broker, 404);

        // A few other flagged brokers for the "related" section.
        $relatedScam = Broker::where('is_scam', true)
            ->where('id', '!=', $broker->id)
            ->orderByDesc('scam_reported_date')
            ->take(3)
            ->get();

        $scamCount = Broker::where('is_scam', true)->count();

        return view('front.scam-broker-detail', compact('broker', 'relatedScam', 'scamCount', 'page_data', 'home_ad_data'));
    }
}
