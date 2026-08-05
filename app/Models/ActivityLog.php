<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'description', 'ip_address', 'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an activity for a user (defaults to the authenticated user).
     * Captures the request IP and user agent automatically.
     */
    public static function record(string $action, ?string $description = null, ?int $userId = null): void
    {
        try {
            static::create([
                'user_id' => $userId ?? auth()->id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
            ]);
        } catch (\Throwable $e) {
            // Never let activity logging break the main flow.
        }
    }

    /**
     * Human-friendly label for the stored action key.
     */
    public function getLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->action));
    }
}
