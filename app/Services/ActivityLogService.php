<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Mencatat aktivitas secara dinamis ke tabel activity_logs dengan Polymorphic Relation.
     */
    public function log(Model $model, string $activityMessage): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id() ?? null, // Default null jika dijalankan via seeder/cron
            'activity' => $activityMessage,
            'loggable_id' => $model->id,
            'loggable_type' => get_class($model)
        ]);
    }
    /**
     * Mengambil semua data log aktivitas beserta relasi user-nya.
     */
    public function getAllLogs()
    {
        // Menggunakan Eloquent ORM, melakukan eager loading 'user', dan diurutkan dari yang terbaru
        return ActivityLog::with('user')->latest()->paginate(10);
    }
}
