<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class RacingLog extends Model
{
    use HasFactory;

    protected $appends = ['completed_in_seconds'];

    /* ===== ATTRIBUTES ===== */

    public function completedInSeconds(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->completed_in)->secondsSinceMidnight()
        );
    }

    /* ===== RELATIONSHIPS ===== */

    public function pilot(): BelongsTo
    {
        return $this->belongsTo(Pilot::class);
    }
}
