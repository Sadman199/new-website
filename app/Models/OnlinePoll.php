<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePoll extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'yes_vote',
        'no_vote',
        'language_id',
    ];

    public function rLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
