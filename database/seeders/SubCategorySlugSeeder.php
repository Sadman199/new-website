<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class SubCategorySlugSeeder extends Seeder
{
    public function run()
    {
        // Get all subcategories with an empty slug
        $subcategories = SubCategory::where('slug', '')->get(); // You can also add other conditions if needed

        // Update the slug for each subcategory
        foreach ($subcategories as $subcategory) {
            // Create the slug based on sub_category_name
            $subcategory->slug = Str::slug($subcategory->sub_category_name);
            $subcategory->save(); // Save the updated slug
        }
    }
}
