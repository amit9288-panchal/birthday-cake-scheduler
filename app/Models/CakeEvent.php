<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CakeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'cake_date',
        'small_cakes',
        'large_cakes',
        'developers',
    ];

    public function cakeEventDeveloper(): HasMany
    {
        return $this->hasMany(CakeEventDeveloper::class, 'event_id');
    }
}
