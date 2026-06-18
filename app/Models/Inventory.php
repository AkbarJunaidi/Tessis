<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'serial_number',
        'qr_code_path'
    ];
}