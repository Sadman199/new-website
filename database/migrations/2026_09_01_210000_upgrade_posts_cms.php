<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $this->addString($table, 'excerpt', 500, true);
            $this->addUnsignedInt($table, 'reading_time', true);
            $this->addString($table, 'content_type', 32, false, 'article');
            $this->addString($table, 'status', 32, false, 'published');
            $this->addTimestamp($table, 'publish_at', true);
            $this->addTimestamp($table, 'scheduled_at', true);
            $this->addBoolean($table, 'is_featured', false);
            $this->addBoolean($table, 'is_breaking', false);
            $this->addBoolean($table, 'featured_homepage', false);
            $this->addBoolean($table, 'featured_blog', false);
            $this->addBoolean($table, 'is_editors_pick', false);
            $this->addBoolean($table, 'is_popular', false);
            $this->addBoolean($table, 'show_author', true);
            $this->addBoolean($table, 'show_related_posts', true);
            $this->addString($table, 'focus_keyword', 120, true);
            $this->addString($table, 'canonical_url', 500, true);
            $this->addString($table, 'og_title', 255, true);
            $this->addText($table, 'og_description', true);
            $this->addString($table, 'og_image', 255, true);
            $this->addBoolean($table, 'robots_index', true);
            $this->addBoolean($table, 'robots_follow', true);
            $this->addString($table, 'schema_type', 32, false, 'Article');
            $this->addString($table, 'image_alt', 255, true);
            $this->addString($table, 'image_caption', 255, true);
            $this->addString($table, 'social_image', 255, true);
        });

        $this->addIndexIfMissing('posts', 'posts_status_index', ['status']);
        $this->addIndexIfMissing('posts', 'posts_publish_at_index', ['publish_at']);
        $this->addIndexIfMissing('posts', 'posts_content_type_index', ['content_type']);
        $this->addIndexIfMissing('posts', 'posts_language_status_index', ['language_id', 'status']);

        if (Schema::hasColumn('posts', 'status')) {
            DB::table('posts')->whereNull('status')->update(['status' => 'published']);
        }

        if (Schema::hasColumn('posts', 'content_type')) {
            DB::table('posts')->whereNull('content_type')->update(['content_type' => 'article']);
        }

        if (Schema::hasColumn('posts', 'schema_type')) {
            DB::table('posts')->whereNull('schema_type')->update(['schema_type' => 'Article']);
        }

        if (Schema::hasColumn('posts', 'publish_at')) {
            DB::table('posts')->whereNull('publish_at')->update([
                'publish_at' => DB::raw('created_at'),
            ]);
        }

        $this->createPivot(
            'post_broker',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('broker_id');
                $table->timestamps();
                $table->unique(['post_id', 'broker_id']);
                $table->index('broker_id');
            }
        );

        $this->createPivot(
            'post_related',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('related_post_id');
                $table->timestamps();
                $table->unique(['post_id', 'related_post_id']);
                $table->index('related_post_id');
            }
        );

        $this->createPivot(
            'post_related_category',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('category_id');
                $table->timestamps();
                $table->unique(['post_id', 'category_id']);
                $table->index('category_id');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('post_related_category');
        Schema::dropIfExists('post_related');
        Schema::dropIfExists('post_broker');

        if (! Schema::hasTable('posts')) {
            return;
        }

        $columns = array_values(array_filter([
            'excerpt', 'reading_time', 'content_type', 'status', 'publish_at', 'scheduled_at',
            'is_featured', 'is_breaking', 'featured_homepage', 'featured_blog', 'is_editors_pick',
            'is_popular', 'show_author', 'show_related_posts', 'focus_keyword', 'canonical_url',
            'og_title', 'og_description', 'og_image', 'robots_index', 'robots_follow', 'schema_type',
            'image_alt', 'image_caption', 'social_image',
        ], fn (string $column) => Schema::hasColumn('posts', $column)));

        if ($columns !== []) {
            Schema::table('posts', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    protected function addString(Blueprint $table, string $column, int $length, bool $nullable, ?string $default = null): void
    {
        if (Schema::hasColumn('posts', $column)) {
            return;
        }

        $definition = $table->string($column, $length);

        if ($nullable) {
            $definition->nullable();
        } elseif ($default !== null) {
            $definition->default($default);
        }
    }

    protected function addText(Blueprint $table, string $column, bool $nullable): void
    {
        if (Schema::hasColumn('posts', $column)) {
            return;
        }

        $definition = $table->text($column);
        if ($nullable) {
            $definition->nullable();
        }
    }

    protected function addUnsignedInt(Blueprint $table, string $column, bool $nullable): void
    {
        if (Schema::hasColumn('posts', $column)) {
            return;
        }

        $definition = $table->unsignedSmallInteger($column);
        if ($nullable) {
            $definition->nullable();
        }
    }

    protected function addTimestamp(Blueprint $table, string $column, bool $nullable): void
    {
        if (Schema::hasColumn('posts', $column)) {
            return;
        }

        $definition = $table->timestamp($column);
        if ($nullable) {
            $definition->nullable();
        }
    }

    protected function addBoolean(Blueprint $table, string $column, bool $default): void
    {
        if (Schema::hasColumn('posts', $column)) {
            return;
        }

        $table->boolean($column)->default($default);
    }

    protected function addIndexIfMissing(string $table, string $index, array $columns): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($index, $columns) {
                $blueprint->index($columns, $index);
            });
        } catch (\Throwable $e) {
            // Index already exists, or the driver cannot add it.
        }
    }

    protected function createPivot(string $table, callable $callback): void
    {
        if (Schema::hasTable($table)) {
            return;
        }

        Schema::create($table, $callback);
    }
};
