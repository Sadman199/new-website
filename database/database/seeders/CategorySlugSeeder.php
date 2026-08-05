<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySlugSeeder extends Seeder
{
    public function run()
    {
        // Get all categories with an empty slug
        $categories = Category::where('slug', '')->get(); // You can add more conditions if needed

        // Update the slug for each category
        foreach ($categories as $category) {
            $category->slug = Str::slug($category->category_name);
            $category->save();
        }
    }
}
