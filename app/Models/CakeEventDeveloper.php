<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CakeEventDeveloper extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'developer_id'
    ];

    public function cakeEvent(): BelongsTo
    {
        return $this->belongsTo(CakeEvent::class, 'event_id');
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }

}
