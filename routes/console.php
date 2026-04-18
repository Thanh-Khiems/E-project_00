<?php

use App\Models\Appointment;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('appointments:cleanup-expired', function () {
    $deletedCount = Appointment::purgeExpired();

    $this->info("Cleaned up {$deletedCount} expired appointments.");
})->purpose('Delete expired appointments that are no longer valid');
