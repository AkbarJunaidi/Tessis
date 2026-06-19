<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Menjadwalkan perintah pembersihan log usang agar berjalan otomatis setiap hari di tengah malam
Schedule::command('mis:clear-logs')->daily();
