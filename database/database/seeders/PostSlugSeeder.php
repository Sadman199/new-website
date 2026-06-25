<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSlugSeeder extends Seeder
{
    public function run()
    {
        // Get all posts that have an empty slug
        $posts = Post::where('slug', '')->get(); // You can adjust this condition if needed

        // Update the slug for each post
        foreach ($posts as $post) {
            $post->slug = Str::slug($post->post_title);
            $post->save();
        }
    }
}
