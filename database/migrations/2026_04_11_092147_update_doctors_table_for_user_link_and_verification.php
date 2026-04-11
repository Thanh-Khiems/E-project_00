<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();

            $table->string('degree')->nullable()->after('phone');
            $table->string('license_number')->nullable()->after('degree');
            $table->string('hospital')->nullable()->after('experience_years');
            $table->string('clinic_address')->nullable()->after('hospital');
            $table->string('city')->nullable()->after('clinic_address');
            $table->text('bio')->nullable()->after('city');
            $table->decimal('consultation_fee', 10, 2)->nullable()->after('bio');

            $table->enum('verification_status', ['draft', 'pending', 'approved', 'rejected'])
                ->default('draft')
                ->after('status');

            $table->timestamp('submitted_at')->nullable()->after('verification_status');
            $table->timestamp('approved_at')->nullable()->after('submitted_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'degree',
                'license_number',
                'hospital',
                'clinic_address',
                'city',
                'bio',
                'consultation_fee',
                'verification_status',
                'submitted_at',
                'approved_at',
                'rejected_at',
            ]);
        });
    }
};
