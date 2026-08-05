<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

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

    public function getAuthorNameAttribute()
    {
        if ($this->author_id == 0 && $this->admin_id) {
            $admin = Admin::find($this->admin_id);
            return $admin ? $admin->name : 'Admin';
        }
        return $this->author->name ?? 'Author';
    }
}
