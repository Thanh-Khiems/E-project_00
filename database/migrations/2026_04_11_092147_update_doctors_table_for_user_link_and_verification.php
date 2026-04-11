<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('doctors', 'degree')) {
                $table->string('degree')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('doctors', 'license_number')) {
                $table->string('license_number')->nullable()->after('degree');
            }

            if (!Schema::hasColumn('doctors', 'hospital')) {
                $table->string('hospital')->nullable()->after('experience_years');
            }

            if (!Schema::hasColumn('doctors', 'clinic_address')) {
                $table->string('clinic_address')->nullable()->after('hospital');
            }

            if (!Schema::hasColumn('doctors', 'city')) {
                $table->string('city')->nullable()->after('clinic_address');
            }

            if (!Schema::hasColumn('doctors', 'bio')) {
                $table->text('bio')->nullable()->after('city');
            }

            if (!Schema::hasColumn('doctors', 'consultation_fee')) {
                $table->decimal('consultation_fee', 10, 2)->nullable()->after('bio');
            }

            if (!Schema::hasColumn('doctors', 'verification_status')) {
                $table->enum('verification_status', ['draft', 'pending', 'approved', 'rejected'])
                    ->default('draft')
                    ->after('status');
            }

            if (!Schema::hasColumn('doctors', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('verification_status');
            }

            if (!Schema::hasColumn('doctors', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('submitted_at');
            }

            if (!Schema::hasColumn('doctors', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('doctors', 'rejected_at')) {
                $columnsToDrop[] = 'rejected_at';
            }

            if (Schema::hasColumn('doctors', 'submitted_at')) {
                $columnsToDrop[] = 'submitted_at';
            }

            if (Schema::hasColumn('doctors', 'verification_status')) {
                $columnsToDrop[] = 'verification_status';
            }

            if (Schema::hasColumn('doctors', 'consultation_fee')) {
                $columnsToDrop[] = 'consultation_fee';
            }

            if (Schema::hasColumn('doctors', 'bio')) {
                $columnsToDrop[] = 'bio';
            }

            if (Schema::hasColumn('doctors', 'city')) {
                $columnsToDrop[] = 'city';
            }

            if (Schema::hasColumn('doctors', 'clinic_address')) {
                $columnsToDrop[] = 'clinic_address';
            }

            if (Schema::hasColumn('doctors', 'hospital')) {
                $columnsToDrop[] = 'hospital';
            }

            if (Schema::hasColumn('doctors', 'license_number')) {
                $columnsToDrop[] = 'license_number';
            }

            if (Schema::hasColumn('doctors', 'degree')) {
                $columnsToDrop[] = 'degree';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            if (Schema::hasColumn('doctors', 'user_id')) {
                try {
                    $table->dropConstrainedForeignId('user_id');
                } catch (\Throwable $e) {
                    $table->dropColumn('user_id');
                }
            }
        });
    }
};
