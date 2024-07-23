<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Performer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link'
    ];

    public function years(): HasMany
    {
        return $this->hasMany(Year::class);
    }
}
