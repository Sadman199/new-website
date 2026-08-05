<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * Only user-editable profile fields are mass assignable. Privileged fields
     * (is_verified, verified_at, status, last_login_*) are intentionally left
     * out and must be set explicitly (admin actions / auth flow) to prevent
     * mass-assignment privilege escalation.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'avatar',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Reviews submitted by this user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Activity log entries for this user.
     */
    public function activities()
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    /**
     * The user's avatar URL, falling back to a generated initials placeholder.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
            . '&background=F59E0B&color=fff&bold=true';
    }

    /**
     * Two-letter initials for compact avatars.
     */
    public function getInitialsAttribute(): string
    {
        return strtoupper(Str::substr($this->name, 0, 2));
    }
}
