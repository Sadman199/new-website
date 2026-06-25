<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\HomeAdvertisement;
use App\Models\Admin;
use App\Models\Author;
use App\Models\SubCategory;
use App\Helper\Helpers;

class PostController extends Controller
{
    public function detail($subcategory_slug, $post_slug)
    {
        Helpers::read_json();
        
        // Fetch the subcategory by slug
        $subcategory = SubCategory::where('slug', $subcategory_slug)->first();
    
        if (!$subcategory) {
            // Handle case where subcategory is not found
            abort(404, 'Subcategory not found');
        }
    
        // Fetch the post by slug and ensure it belongs to the correct subcategory
        $post_detail = Post::with('rSubCategory')
            ->where('slug', $post_slug)
            ->where('sub_category_id', $subcategory->id)
            ->first();
    
        if (!$post_detail) {
            // Handle case where post is not found
            abort(404, 'Post not found');
        }
    
        // Update page view count
        $post_detail->visitors += 1;
        $post_detail->save();
    
        // Fetch user data (either Author or Admin)
        if ($post_detail->author_id == 0) {
            $user_data = Admin::find($post_detail->admin_id);  // Get Admin details
        } else {
            $user_data = Author::find($post_detail->author_id);  // Get Author details
        }
    
        // Fetch tags related to this post
        $tag_data = Tag::where('post_id', $post_detail->id)->get();
    
        // Fetch related posts
        $related_post_array = Post::with('rSubCategory')
            ->where('sub_category_id', $post_detail->sub_category_id)
            ->orderBy('id', 'desc')
            ->get();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

    
        return view('front.pages.post_detail', compact('post_detail', 'user_data', 'tag_data', 'related_post_array','subcategory','home_ad_data'));
    }
    
}