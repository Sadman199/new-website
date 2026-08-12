<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class SubCategory extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'sub_category_name',
        'slug',
        'show_on_menu',
        'show_on_home',
        'sub_category_order',
        'category_id',
        'language_id',
    ];

    public function rCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function rPost()
    {
        return $this->hasMany(Post::class)->orderBy('id', 'desc');
    }

    public function rLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
