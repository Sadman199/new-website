<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Tag extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'post_id',
        'tag_name',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
