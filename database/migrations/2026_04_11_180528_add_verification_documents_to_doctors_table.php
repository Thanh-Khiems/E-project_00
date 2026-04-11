<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'doctor_dob')) {
                $table->date('doctor_dob')->nullable()->after('degree');
            }

            if (!Schema::hasColumn('doctors', 'citizen_id')) {
                $table->string('citizen_id')->nullable()->after('doctor_dob');
            }

            if (!Schema::hasColumn('doctors', 'citizen_id_front')) {
                $table->string('citizen_id_front')->nullable()->after('citizen_id');
            }

            if (!Schema::hasColumn('doctors', 'citizen_id_back')) {
                $table->string('citizen_id_back')->nullable()->after('citizen_id_front');
            }

            if (!Schema::hasColumn('doctors', 'degree_image')) {
                $table->string('degree_image')->nullable()->after('citizen_id_back');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('doctors', 'degree_image')) {
                $columnsToDrop[] = 'degree_image';
            }

            if (Schema::hasColumn('doctors', 'citizen_id_back')) {
                $columnsToDrop[] = 'citizen_id_back';
            }

            if (Schema::hasColumn('doctors', 'citizen_id_front')) {
                $columnsToDrop[] = 'citizen_id_front';
            }

            if (Schema::hasColumn('doctors', 'citizen_id')) {
                $columnsToDrop[] = 'citizen_id';
            }

            if (Schema::hasColumn('doctors', 'doctor_dob')) {
                $columnsToDrop[] = 'doctor_dob';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
