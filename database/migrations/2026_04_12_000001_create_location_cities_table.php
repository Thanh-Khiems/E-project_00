<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('districts');
            $table->timestamps();
        });

        $locations = config('locations', []);
        $now = now();

        foreach ($locations as $cityName => $districts) {
            DB::table('location_cities')->insert([
                'name' => $cityName,
                'districts' => json_encode($districts, JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('location_cities');
    }
};
