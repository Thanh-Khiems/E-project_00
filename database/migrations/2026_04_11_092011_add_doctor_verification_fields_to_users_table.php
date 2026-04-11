<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('doctor_verification_status', ['none', 'pending', 'approved', 'rejected'])
                ->default('none')
                ->after('role');

            $table->timestamp('doctor_verified_at')->nullable()->after('doctor_verification_status');
            $table->text('doctor_rejection_reason')->nullable()->after('doctor_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'doctor_verification_status',
                'doctor_verified_at',
                'doctor_rejection_reason',
            ]);
        });
    }
};
