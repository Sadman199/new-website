<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\HomeAdvertisement;
use App\Models\ForexBonus;
use App\Models\Post;
use App\Models\Tag;
use App\Helper\Helpers;

class SubCategoryController extends Controller
{
    public function index($slug)
    {
        // Load language JSON
        Helpers::read_json();

        // Get current language ID from session or fallback to 1
        $current_language_id = session('language_id', 1);

        // Fetch sub-category by slug
        $sub_category_data = SubCategory::where('slug', $slug)->first();

        if (!$sub_category_data) {
            abort(404, 'Subcategory not found');
        }

        // Fetch posts under the sub-category with related sub-category and author
        $post_data = Post::with(['rSubCategory', 'author'])
            ->where('sub_category_id', $sub_category_data->id)
            ->where('language_id', $current_language_id)
            ->orderBy('id', 'desc')
            ->paginate(9);

        // Fetch ad data
        $home_ad_data = HomeAdvertisement::find(1);

        // Fetch unique tag names
        $all_tags = Tag::select('tag_name')->distinct()->get();

        // Filter tags with at least one post in current language
        $filtered_tags = [];

        foreach ($all_tags as $tag) {
            $tag_posts = Tag::where('tag_name', $tag->tag_name)->pluck('post_id');

            $count = Post::whereIn('id', $tag_posts)
                ->where('language_id', $current_language_id)
                ->count();

            if ($count > 0) {
                $filtered_tags[] = $tag;
            }
        }

        return view('front.pages.sub_category', compact(
            'sub_category_data',
            'post_data',
            'filtered_tags',
            'home_ad_data'
        ));
    }
}