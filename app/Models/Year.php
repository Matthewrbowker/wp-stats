<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'performer_id',
        'year',
        'won'
    ];

    public function performer(): BelongsTo
    {
        return $this->belongsTo(Performer::class);
    }
}
