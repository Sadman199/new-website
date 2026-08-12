<?php

namespace App\Http\Controllers\Front;

use App\Helper\Helpers;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends FrontController
{
    public function show(string $tag_name)
    {
        $this->bootFront();

        $postIds = Tag::query()
            ->where('tag_name', $tag_name)
            ->pluck('post_id')
            ->unique()
            ->values()
            ->all();

        $all_posts = Post::query()
            ->with('rSubCategory')
            ->when($postIds !== [], fn ($query) => $query->whereIn('id', $postIds))
            ->when($postIds === [], fn ($query) => $query->whereRaw('1 = 0'))
            ->orderByDesc('id')
            ->paginate(12);

        return view('front.pages.tag', [
            'all_post_ids' => $postIds,
            'all_posts' => $all_posts,
            'tag_name' => $tag_name,
        ]);
    }
}
