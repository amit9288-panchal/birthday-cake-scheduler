<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
    ];

    public function cakeEventDeveloper(): BelongsTo
    {
        return $this->belongsTo(CakeEventDeveloper::class);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
