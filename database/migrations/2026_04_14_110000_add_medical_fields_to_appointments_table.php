<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'diagnosis')) {
                $table->text('diagnosis')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('appointments', 'doctor_advice')) {
                $table->text('doctor_advice')->nullable()->after('diagnosis');
            }

            if (! Schema::hasColumn('appointments', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('doctor_advice');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'completed_at')) {
                $table->dropColumn('completed_at');
            }

            if (Schema::hasColumn('appointments', 'doctor_advice')) {
                $table->dropColumn('doctor_advice');
            }

            if (Schema::hasColumn('appointments', 'diagnosis')) {
                $table->dropColumn('diagnosis');
            }
        });
    }
};
