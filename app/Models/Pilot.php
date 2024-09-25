<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pilot extends Model
{
    use HasFactory;

    /* ===== RELATIONSHIPS ===== */

    public function raceLogs(): HasMany
    {
        return $this->hasMany(RacingLog::class);
    }
}
