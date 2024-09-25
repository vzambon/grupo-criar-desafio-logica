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
            $lapsTime = $el->raceLogs->pluck('completed_in_seconds');

            $el->totalTime = $lapsTime->reduce(function(?int $carry, int $item) {
                    return $carry + $item;
                });

            $el->bestLap = $el->raceLogs->sortBy('completed_in_seconds')->first()->lap;

            return [
                'id' => $el->id,
                'name' => $el->name,
                'total_time' => $el->totalTime,
                'best_lap' => (int) $el->bestLap
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
