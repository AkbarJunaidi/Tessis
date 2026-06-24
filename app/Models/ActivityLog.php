<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /**
     * Nama tabel yang dikelola oleh model ini.
     */
    protected $table = 'activity_logs';


    /**
     * Atribut yang diizinkan untuk diisi secara massal (Mass Assignment Protection).
     */
    protected $fillable = [
        'user_id',
        'module',
        'action',
    ];

    /**
     * Relasi balik ke User yang melakukan aktivitas (Belongs To).
     * Mengembalikan NULL jika aksi dilakukan secara otomatis oleh sistem.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
