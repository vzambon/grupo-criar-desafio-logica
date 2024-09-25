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
                $previousLap = RacingLog::where('pilot_id', '=', $pilot->id)->orderByDesc('created_at')->orderByDesc('id')->first();
                $completedInSeconds = fake()->numberBetween(60, 290);
                $completedIn = gmdate('H:i:s', $completedInSeconds);
                $createdAt = ($previousLap?->created_at ?? $raceStartedAt)->addSeconds($completedInSeconds);

                RacingLog::factory()->state([
                    'lap' => $lap,
                    'pilot_id' => $pilot->id,
                    'completed_in' => $completedIn,
                    'avarage_vel' => $trackLengthInKm / ($completedInSeconds/3600),
                    'created_at' => $createdAt->toISOString(),
                    'updated_at' => $createdAt->toISOString()
                ])->create();
            }
        }
    }
}
