<?php

namespace Tests\Feature;

use App\Models\Pilot;
use Database\Seeders\RacingLogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RacingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test api listing of racing classification result. Listing pilots ordered by position
     */
    public function test_list_race_classification(): void
    {
        $this->seed(RacingLogSeeder::class);

        $response = $this->get(route('racing.result'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'position',
            ]
        ]);

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
                'best_lap' => $el->bestLap
            ];
        })
        ->sortBy('total_time')
        ->values()
        ->map(function($el, $index){
            $el['position'] = $index+1;
            return $el;
        })->toArray();

        $response->assertJson($pilotsCompleteTime);
    }
}
