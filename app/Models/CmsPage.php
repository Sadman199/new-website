<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'template',
        'meta_title',
        'meta_description',
        'status',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(CmsPageSection::class, 'page_id')->orderBy('sort_order');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function seoDescription(): ?string
    {
        return $this->meta_description;
    }
}
