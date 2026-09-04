<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerAlternativeItem extends Model
{
    protected $fillable = [
        'page_id',
        'alternative_broker_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(BrokerAlternativePage::class, 'page_id');
    }

    public function alternativeBroker(): BelongsTo
    {
        return $this->belongsTo(Broker::class, 'alternative_broker_id');
    }
}
