<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Author extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'token',
        'can_write',
        'can_edit',
        'can_fact_check',
        'bio',
        'twitter_url',
        'linkedin_url',
        'facebook_url',
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    protected $casts = [
        'can_write' => 'boolean',
        'can_edit' => 'boolean',
        'can_fact_check' => 'boolean',
    ];

    public function postsWritten()
    {
        return $this->hasMany(Post::class, 'written_by_author_id');
    }

    public function postsEdited()
    {
        return $this->hasMany(Post::class, 'edited_by_author_id');
    }

    public function postsFactChecked()
    {
        return $this->hasMany(Post::class, 'fact_checked_by_author_id');
    }

    public function legacyPosts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function scopeWriters(Builder $query): Builder
    {
        return $query->where('can_write', true);
    }

    public function scopeEditors(Builder $query): Builder
    {
        return $query->where('can_edit', true);
    }

    public function scopeFactCheckers(Builder $query): Builder
    {
        return $query->where('can_fact_check', true);
    }

    /** @return array<int, string> */
    public function roleLabels(): array
    {
        $roles = [];

        if ($this->can_write) {
            $roles[] = 'Written';
        }
        if ($this->can_edit) {
            $roles[] = 'Edited';
        }
        if ($this->can_fact_check) {
            $roles[] = 'Fact-Checked';
        }

        return $roles;
    }

    public function photoUrl(): string
    {
        if ($this->photo) {
            return asset('uploads/' . $this->photo);
        }

        return asset('uploads/default.png');
    }

    /** @return array<int, array{platform: string, url: string, icon: string}> */
    public function socialLinks(): array
    {
        if (! \Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'twitter_url')) {
            return [];
        }

        $links = [];

        if (! empty($this->twitter_url)) {
            $links[] = [
                'platform' => 'Twitter',
                'url' => $this->twitter_url,
                'icon' => 'fab fa-twitter',
            ];
        }

        if (! empty($this->linkedin_url)) {
            $links[] = [
                'platform' => 'LinkedIn',
                'url' => $this->linkedin_url,
                'icon' => 'fab fa-linkedin-in',
            ];
        }

        if (! empty($this->facebook_url)) {
            $links[] = [
                'platform' => 'Facebook',
                'url' => $this->facebook_url,
                'icon' => 'fab fa-facebook-f',
            ];
        }

        return $links;
    }
}
