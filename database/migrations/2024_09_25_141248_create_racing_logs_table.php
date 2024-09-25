<?php

use App\Models\Pilot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('racing_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pilot::class);
            $table->string('lap')->default(0);
            $table->time('completed_in');
            $table->double('avarage_vel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racing_log');
    }
};
