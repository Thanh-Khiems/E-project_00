<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->date('start_date');
        $table->date('end_date');
        $table->string('type');
        $table->string('days');
        $table->time('start_time');
        $table->time('end_time');
        $table->integer('max_patients');
        $table->string('location')->nullable();
        $table->text('notes')->nullable();

        $table->timestamps(); // 👈 QUAN TRỌNG
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};