<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Admin;
use App\Models\Author;
use App\Models\Language;

class NewsController extends Controller
{
    public function latestNews()
    {
        // Load language JSON or translations here if needed
        // If you have a helper function like read_json(), call it here:
        // read_json();

        // Determine current language short name from session or default
        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        // Fetch current language ID
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        // Query latest posts for current language
        $posts = Post::with(['rSubCategory', 'author'])
            ->where('language_id', $current_language_id)
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('front.pages.news_all', [
            'section_title' => 'Latest Forex News',
            'posts' => $posts
        ]);
    }

    public function popularNews()
    {
        // Load language JSON or translations here if needed
        // read_json();

        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        // Query popular posts ordered by 'visitors' count descending
        $posts = Post::with(['rSubCategory', 'author'])
            ->where('language_id', $current_language_id)
            ->orderBy('visitors', 'desc')
            ->paginate(12);

        return view('front.pages.news_all', [
            'section_title' => 'Popular Forex News',
            'posts' => $posts
        ]);
    }
}
