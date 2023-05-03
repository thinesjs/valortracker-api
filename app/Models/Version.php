<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    protected $fillable = [
        'latest_ios',
        'minimum_ios',
        'url_ios',
        'latest_android',
        'minimum_android',
        'url_android',
        'maintenanceMode',
    ];
}
