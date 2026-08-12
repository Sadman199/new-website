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

    public function rLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
