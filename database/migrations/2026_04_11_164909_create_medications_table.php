<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('medications', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('dosage');

        $table->foreignId('medicine_type_id')
              ->nullable()
              ->constrained('medicine_types')
              ->onDelete('cascade');

        $table->string('category')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('medications');
}

   
};