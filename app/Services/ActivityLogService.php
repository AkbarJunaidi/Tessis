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
}