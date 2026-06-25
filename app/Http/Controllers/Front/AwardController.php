<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use App\Helper\Helpers;

class AwardController extends Controller
{
    /**
     * Show the Award page
     */
    public function index()
    {
        // Load JSON settings
        Helpers::read_json();

        // Determine current language
        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        // Fetch page data (if award page content exists in DB)
        $page_data = Page::where('language_id', $current_language_id)->first();

        // Define awards dynamically (no database needed)
        
        // In AwardController - update the award names to match your database values
        $awardColumns = [
            [
                [
                    'name' => 'Best Broker 2025',
                    'slug' => 'most-trusted',
                    'color' => 'yellow',
                    'description' => 'Known for reliable service, clear policies, and strong client trust.',
                ],
                [
                    'name' => 'Fast Execution',
                    'slug' => 'fast-execution',
                    'color' => 'purple',
                    'description' => 'Offers lightning-fast order execution with consistently low slippage.',
                ],
                [
                    'name' => 'ECN Account',
                    'slug' => 'ecn-raw',
                    'color' => 'red',
                    'description' => 'Delivers raw ECN spreads with direct market access and low fees.',
                ],
            ],
            [
                [
                    'name' => 'Top Trusted Broker',
                    'slug' => 'most-trusted',
                    'color' => 'green',
                    'description' => 'A secure, regulated broker focused on protection and transparency.',
                ],
                [
                    'name' => 'Best for Beginners',
                    'slug' => 'beginner-friendly',
                    'color' => 'blue',
                    'description' => 'Beginner-friendly platform with simple tools and easy onboarding.',
                ],
                [
                    'name' => 'Low Spread Broker',
                    'slug' => 'low-spread',
                    'color' => 'pink',
                    'description' => 'Consistently low spreads perfect for scalping and active trading.',
                ],
            ],
        ];



        return view('front.awards.index', compact(
            'awardColumns',
            'page_data',
            'current_language_id',
            'current_short_name'
        ));
    }
}
