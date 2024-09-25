<?php

namespace Tests\Feature;

use App\Models\Pilot;
use App\Models\RacingLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RacingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test api response of racing classification result. Listing pilots ordered by position
     */
    public function test_return_race_classification(): void
    {
        $this->prepareDatabase();

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
            $el->race_logs;
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
        })->toArray();

        $response->assertJson($pilotsCompleteTime);
    }

    private function prepareDatabase()
    {
        $pilots = Pilot::factory(6)->create();

       $trackLengthInKm = 1.1;

       $raceStartedAt = now();

        for($lap=1; $lap<=4; $lap++) {
            foreach($pilots as $pilot) {
                $previousLap = RacingLog::where('pilot_id', $pilot->id)->orderByDesc('created_at')->first();
                

                $diffInSeconds = now()->diffInSeconds(($previousLap?->created_at ?? $raceStartedAt)
                    ->addSeconds(fake()->numberBetween(60, 290)));
                $completedIn = gmdate('H:i:s', $diffInSeconds);

                $createdAt = $previousLap ? $previousLap->created_at->addSeconds(fake()->numberBetween(60, 290)) : now();

                RacingLog::factory()->state([
                    'lap' => $lap,
                    'pilot_id' => $pilot->id,
                    'completed_in' => $completedIn,
                    'avarage_vel' => $trackLengthInKm / ($diffInSeconds/3600),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt
                ])->create();

            }
        }
    }
}
