<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Post extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'sub_category_id', 'post_title', 'slug', 'post_detail', 'post_photo', 'visitors',
        'author_id', 'admin_id', 'is_share', 'is_comment', 'language_id',
        'meta_title', 'meta_description', 'meta_keywords', 'author',
        'written_by_author_id', 'edited_by_author_id', 'fact_checked_by_author_id',
        'written_by_admin_id', 'edited_by_admin_id', 'fact_checked_by_admin_id',
        'excerpt', 'reading_time', 'content_type', 'status', 'publish_at', 'scheduled_at',
        'is_featured', 'is_breaking', 'featured_homepage', 'featured_blog',
        'is_editors_pick', 'is_popular', 'show_author', 'show_related_posts',
        'focus_keyword', 'canonical_url', 'og_title', 'og_description', 'og_image',
        'robots_index', 'robots_follow', 'schema_type', 'image_alt', 'image_caption',
        'social_image',
    ];

    protected $casts = [
        'is_share' => 'boolean',
        'is_comment' => 'boolean',
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'featured_homepage' => 'boolean',
        'featured_blog' => 'boolean',
        'is_editors_pick' => 'boolean',
        'is_popular' => 'boolean',
        'show_author' => 'boolean',
        'show_related_posts' => 'boolean',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'publish_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'reading_time' => 'integer',
        'visitors' => 'integer',
    ];

    /** @return array<string, string> */
    public static function fallbackContentTypes(): array
    {
        return [
            'article' => 'Article',
            'news' => 'News',
            'analysis' => 'Analysis',
            'guide' => 'Guide',
            'review' => 'Review',
            'comparison' => 'Comparison',
        ];
    }

    /** @return array<string, string> */
    public static function contentTypes(bool $activeOnly = true): array
    {
        if (Schema::hasTable('post_content_types')) {
            $query = PostContentType::query()->orderBy('sort_order')->orderBy('name');
            if ($activeOnly) {
                $query->where('is_active', true);
            }

            $types = $query->pluck('name', 'slug')->all();
            if ($types !== []) {
                return $types;
            }
        }

        return self::fallbackContentTypes();
    }

    public function contentTypeLabel(): string
    {
        $slug = $this->content_type ?: 'article';
        $types = self::contentTypes(false);

        return $types[$slug] ?? \Illuminate\Support\Str::title(str_replace(['-', '_'], ' ', $slug));
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending_review' => 'Pending Review',
            'published' => 'Published',
            'scheduled' => 'Scheduled',
            'archived' => 'Archived',
        ];
    }

    /** @return array<string, string> */
    public static function schemaTypes(): array
    {
        return [
            'Article' => 'Article',
            'NewsArticle' => 'NewsArticle',
            'BlogPosting' => 'BlogPosting',
        ];
    }

    public function rSubCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function rLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function writtenByAuthor()
    {
        return $this->belongsTo(Author::class, 'written_by_author_id');
    }

    public function editedByAuthor()
    {
        return $this->belongsTo(Author::class, 'edited_by_author_id');
    }

    public function factCheckedByAuthor()
    {
        return $this->belongsTo(Author::class, 'fact_checked_by_author_id');
    }

    public function writtenByAdmin()
    {
        return $this->belongsTo(Admin::class, 'written_by_admin_id');
    }

    public function editedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'edited_by_admin_id');
    }

    public function factCheckedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'fact_checked_by_admin_id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function brokers()
    {
        return $this->belongsToMany(Broker::class, 'post_broker')->withTimestamps();
    }

    public function relatedPosts()
    {
        return $this->belongsToMany(self::class, 'post_related', 'post_id', 'related_post_id')->withTimestamps();
    }

    public function relatedCategories()
    {
        return $this->belongsToMany(Category::class, 'post_related_category')->withTimestamps();
    }

    public function getAuthorNameAttribute()
    {
        if ($this->author_id == 0 && $this->admin_id) {
            $admin = $this->relationLoaded('admin') ? $this->admin : Admin::find($this->admin_id);

            return $admin ? $admin->name : 'Admin';
        }

        return $this->author->name ?? ($this->attributes['author'] ?? 'Author');
    }

    public function scopePublished(Builder $query): Builder
    {
        if (! Schema::hasColumn($this->getTable(), 'status')) {
            return $query;
        }

        $now = now();

        return $query->where(function (Builder $inner) use ($now) {
            $inner->where('status', 'published')
                ->orWhereNull('status')
                ->orWhere(function (Builder $scheduled) use ($now) {
                    $scheduled->where('status', 'scheduled')
                        ->where(function (Builder $when) use ($now) {
                            $when->where(function (Builder $at) use ($now) {
                                $at->whereNotNull('scheduled_at')->where('scheduled_at', '<=', $now);
                            })->orWhere(function (Builder $pub) use ($now) {
                                $pub->whereNotNull('publish_at')->where('publish_at', '<=', $now);
                            });
                        });
                });
        });
    }

    public function isPubliclyVisible(): bool
    {
        if (! Schema::hasColumn($this->getTable(), 'status')) {
            return true;
        }

        $status = $this->status ?: 'published';

        if ($status === 'published') {
            return true;
        }

        if ($status !== 'scheduled') {
            return false;
        }

        $goLive = $this->scheduled_at ?? $this->publish_at;

        return $goLive !== null && $goLive->lte(now());
    }

    public function statusLabel(): string
    {
        $status = $this->effectiveStatus();

        return self::statuses()[$status] ?? ucfirst((string) $status);
    }

    public function effectiveStatus(): string
    {
        $status = $this->status ?: 'published';

        if ($status === 'scheduled' && $this->isPubliclyVisible()) {
            return 'published';
        }

        return $status;
    }

    public function photoUrl(?string $field = 'post_photo'): ?string
    {
        $path = $this->{$field} ?? null;

        if (! filled($path)) {
            return null;
        }

        if (str_starts_with((string) $path, 'http://') || str_starts_with((string) $path, 'https://') || str_starts_with((string) $path, '//')) {
            return $path;
        }

        $relative = ltrim((string) $path, '/');

        if (! str_starts_with($relative, 'uploads/')) {
            $relative = 'uploads/'.$relative;
        }

        return asset($relative);
    }

    public function socialSharePath(): ?string
    {
        foreach (['social_image', 'og_image', 'post_photo'] as $field) {
            $path = $this->{$field} ?? null;
            if (! filled($path)) {
                continue;
            }

            $relative = ltrim((string) $path, '/');
            if (! str_starts_with($relative, 'uploads/') && ! str_starts_with($relative, 'http')) {
                $relative = 'uploads/'.$relative;
            }

            return $relative;
        }

        return null;
    }

    public function robotsDirective(): string
    {
        $index = Schema::hasColumn($this->getTable(), 'robots_index')
            ? ($this->robots_index ?? true)
            : true;
        $follow = Schema::hasColumn($this->getTable(), 'robots_follow')
            ? ($this->robots_follow ?? true)
            : true;

        return ($index ? 'index' : 'noindex').', '.($follow ? 'follow' : 'nofollow');
    }

    public function shouldShowAuthor(): bool
    {
        if (! Schema::hasColumn($this->getTable(), 'show_author')) {
            return true;
        }

        return $this->show_author !== false;
    }

    public function shouldShowRelatedPosts(): bool
    {
        if (! Schema::hasColumn($this->getTable(), 'show_related_posts')) {
            return true;
        }

        return $this->show_related_posts !== false;
    }

    public function publicUrl(): ?string
    {
        $sub = $this->rSubCategory;

        if (! $sub?->slug || ! $this->slug) {
            return null;
        }

        return route('news_detail', [
            'subcategory_slug' => $sub->slug,
            'post_slug' => $this->slug,
        ]);
    }

    public static function estimateReadingTime(?string $html): int
    {
        $words = str_word_count(trim(preg_replace('/\s+/', ' ', strip_tags((string) $html)) ?? ''));

        return max(1, (int) ceil($words / 200));
    }
}
