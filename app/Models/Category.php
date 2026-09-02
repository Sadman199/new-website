<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Category extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'category_name',
        'slug',
        'show_on_menu',
        'category_order',
        'language_id',
    ];

    public function rSubCategory()
    {
        return $this->hasMany(SubCategory::class)
                    ->where('show_on_menu', 'Show')
                    ->orderBy('sub_category_order', 'asc');
    }

    public function classifiedPosts()
    {
        return $this->hasManyThrough(Post::class, SubCategory::class, 'category_id', 'sub_category_id');
    }

    public function rLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function relatedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_related_category')->withTimestamps();
    }

    public function publicSlug(): string
    {
        $slug = trim((string) $this->slug);
        if ($slug !== '') {
            return $slug;
        }

        $fromName = \Illuminate\Support\Str::slug((string) $this->category_name);

        return $fromName !== '' ? $fromName : 'category-'.$this->id;
    }
}
