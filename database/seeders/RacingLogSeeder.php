<?php

namespace Database\Seeders;

use App\Models\Pilot;
use App\Models\RacingLog;
use Illuminate\Database\Seeder;

class RacingLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
