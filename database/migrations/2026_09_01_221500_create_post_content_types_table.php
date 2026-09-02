<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('post_content_types')) {
            Schema::create('post_content_types', function (Blueprint $table) {
                $table->id();
                $table->string('name', 80);
                $table->string('slug', 80)->unique();
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        $now = now();
        $defaults = [
            ['name' => 'Article', 'slug' => 'article', 'sort_order' => 1],
            ['name' => 'News', 'slug' => 'news', 'sort_order' => 2],
            ['name' => 'Analysis', 'slug' => 'analysis', 'sort_order' => 3],
            ['name' => 'Guide', 'slug' => 'guide', 'sort_order' => 4],
            ['name' => 'Review', 'slug' => 'review', 'sort_order' => 5],
            ['name' => 'Comparison', 'slug' => 'comparison', 'sort_order' => 6],
        ];

        foreach ($defaults as $type) {
            $exists = DB::table('post_content_types')->where('slug', $type['slug'])->exists();
            if ($exists) {
                continue;
            }

            DB::table('post_content_types')->insert($type + [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_content_types');
    }
};
