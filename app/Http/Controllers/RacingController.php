<?php

namespace App\Http\Controllers;

use App\Models\Pilot;
use Illuminate\Support\Carbon;

class RacingController extends Controller
{
    public function __invoke()
    {
        $pilots = Pilot::with('raceLogs')->get();

        $pilotsCompleteTime = $pilots->map(function($el) {
            $el->totalTime = $el->raceLogs->pluck('completed_in')
                ->map(fn($el) => Carbon::parse($el)->secondsSinceMidnight())
                ->reduce(function(?int $carry, int $item) {
                    return $carry + $item;
                });

            return [
                'id' => $el->id,
                'name' => $el->name,
                'total_time' => $el->totalTime
            ];
        })
        ->sortBy('total_time')
        ->values()
        ->map(function($el, $index){
            $el['position'] = $index+1;
            return $el;
        });

        return $pilotsCompleteTime;
    }
}
