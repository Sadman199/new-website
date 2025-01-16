<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\HomeAdvertisement;
use App\Models\ForexBonus;
use App\Models\Post;
use App\Helper\Helpers;

class SubCategoryController extends Controller
{
    public function index($slug)
{
    Helpers::read_json();
    
    $current_language_id = session('language_id', 1); 

    // Fetch sub-category data by slug
    $sub_category_data = SubCategory::where('slug', $slug)->first();

    // If the sub-category doesn't exist, return a 404 error or redirect
    if (!$sub_category_data) {
        abort(404, 'Subcategory not found');
    }

    // Fetch posts under this sub-category
    $post_data = Post::where('sub_category_id', $sub_category_data->id)->orderBy('id', 'desc')->paginate(9);
    $home_ad_data = HomeAdvertisement::where('id', 1)->first();

    // Fetch all distinct tags
    $all_tags = \App\Models\Tag::select('tag_name')->distinct()->get();

    // Filter tags that have posts in the current language
    $filtered_tags = [];
    foreach ($all_tags as $item) {
        $count = 0;
        $all_data = \App\Models\Tag::where('tag_name', $item->tag_name)->get();
        
        foreach ($all_data as $row) {
            $temp = \App\Models\Post::where('id', $row->post_id)
                ->where('language_id', $current_language_id)
                ->count();
            
            if ($temp > 0) {
                $count = 1;
                break;
            }
        }
        
        // Only add the tag if it has posts
        if ($count == 1) {
            $filtered_tags[] = $item;
        }
    }



    // Pass the data to the view
    return view('front.sub_category', compact('sub_category_data', 'post_data', 'filtered_tags','home_ad_data'));
}

    
    

}
