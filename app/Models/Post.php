<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Post extends Model
{
    use HasFactory, Cachable;  // Add Cachable trait here

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
        return $this->belongsTo(\App\Models\Author::class, 'author_id');
    }

    // Optional accessor for fallback to admin name
    public function getAuthorNameAttribute()
    {
        if ($this->author_id == 0 && $this->admin_id) {
            $admin = \App\Models\Admin::find($this->admin_id);
            return $admin ? $admin->name : 'Admin';
        }
        return $this->author->name ?? 'Author';
    }
}
