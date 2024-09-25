<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RacingLog extends Model
{
    use HasFactory;

    /* ===== RELATIONSHIPS ===== */

    public function pilot(): BelongsTo
    {
        return $this->belongsTo(Pilot::class);
    }
}
