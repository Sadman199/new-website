<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PropFirmAttribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'group',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function propFirms(): BelongsToMany
    {
        return $this->belongsToMany(PropFirm::class, 'prop_firm_attribute_prop_firm');
    }
}
