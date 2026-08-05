<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use App\Helper\Helpers;
use App\Services\AwardsIndexService;

class AwardController extends Controller
{
    public function index(AwardsIndexService $awardsIndexService)
    {
        Helpers::read_json();

        if (! session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
        $page_data = Page::where('language_id', $current_language_id)->first();

        $brokers = $awardsIndexService->baseBrokers();
        $awardCards = $awardsIndexService->awardCards($brokers);
        $stats = $awardsIndexService->stats($brokers);
        $evaluationPillars = $awardsIndexService->evaluationPillars();

        return view('front.awards.index', compact(
            'awardCards',
            'stats',
            'evaluationPillars',
            'page_data',
            'current_language_id',
            'current_short_name'
        ));
    }
}
