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
use App\Services\BlogIndexService;
use App\Services\BlogPostDetailService;
use App\Services\EditorialAssignmentService;

class PostController extends Controller
{
    public function detail($subcategory_slug, $post_slug, BlogIndexService $blogIndexService, BlogPostDetailService $blogPostDetailService)
    {
        Helpers::read_json();
        
        // Fetch the subcategory by slug
        $subcategory = SubCategory::where('slug', $subcategory_slug)->first();
    
        if (!$subcategory) {
            // Handle case where subcategory is not found
            abort(404, 'Subcategory not found');
        }
    
        // Fetch the post by slug and ensure it belongs to the correct subcategory
        $relations = [
            'rSubCategory',
            'writtenByAuthor',
            'editedByAuthor',
            'factCheckedByAuthor',
            'writtenByAdmin',
            'editedByAdmin',
            'factCheckedByAdmin',
            'author',
            'tags',
        ];

        if (\Illuminate\Support\Facades\Schema::hasTable('post_broker')) {
            $relations[] = 'brokers';
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('post_related')) {
            $relations[] = 'relatedPosts.rSubCategory';
        }

        $post_detail = Post::with($relations)
            ->where('slug', $post_slug)
            ->where('sub_category_id', $subcategory->id)
            ->first();
    
        if (! $post_detail || ! $post_detail->isPubliclyVisible()) {
            abort(404, 'Post not found');
        }
    
        // Update page view count
        $post_detail->visitors += 1;
        $post_detail->save();
    
        // Fetch user data (either Author or Admin) — primary byline fallback
        if ($post_detail->author_id == 0) {
            $user_data = Admin::find($post_detail->admin_id);
        } else {
            $user_data = Author::find($post_detail->author_id);
        }

        $editorialCredits = EditorialAssignmentService::creditsForPost($post_detail);
        $editorialTeam = EditorialAssignmentService::teamFor($post_detail);

        if ($editorialTeam === []) {
            $editorialTeam = EditorialAssignmentService::defaultGuideTeam();
        }

        if ($editorialCredits === []) {
            $editorialCredits = EditorialAssignmentService::defaultGuideCredits();
        }

        $postMeta = $blogIndexService->serializePost($post_detail);

        // Fetch tags related to this post
        $tag_data = $post_detail->relationLoaded('tags')
            ? $post_detail->tags
            : Tag::where('post_id', $post_detail->id)->get();

        $related_post_array = collect();
        if ($post_detail->shouldShowRelatedPosts()) {
            $manualRelated = collect();
            if (\Illuminate\Support\Facades\Schema::hasTable('post_related')) {
                $manualRelated = $post_detail->relationLoaded('relatedPosts')
                    ? $post_detail->relatedPosts
                    : $post_detail->relatedPosts()->with('rSubCategory')->get();
            }

            $related_post_array = $manualRelated->filter(fn (Post $post) => $post->isPubliclyVisible());

            if ($related_post_array->isEmpty()) {
                $related_post_array = Post::with('rSubCategory')
                    ->published()
                    ->where('sub_category_id', $post_detail->sub_category_id)
                    ->where('id', '!=', $post_detail->id)
                    ->orderBy('id', 'desc')
                    ->limit(6)
                    ->get();
            }
        }

        $relatedCards = $related_post_array
            ->take(6)
            ->map(fn (Post $post) => $blogIndexService->serializePost($post))
            ->values();

        $recommendedBrokers = $blogPostDetailService->recommendedBrokersFor($post_detail);
        $depositBonuses = $blogPostDetailService->latestDepositBonuses();

        $guidePageMeta = [
            'updated_at' => $post_detail->updated_at->format('M j, Y'),
        ];

        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

    
        return view('front.pages.post_detail', compact(
            'post_detail',
            'user_data',
            'tag_data',
            'related_post_array',
            'subcategory',
            'home_ad_data',
            'editorialCredits',
            'editorialTeam',
            'postMeta',
            'relatedCards',
            'recommendedBrokers',
            'depositBonuses',
            'guidePageMeta',
        ));
    }
    
}