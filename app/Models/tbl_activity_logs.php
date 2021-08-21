<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_activity_logs extends Model {
    use HasFactory;

    public $timestamps = false;

    public $table = 'tbl_app_activity_logs';

    protected $guarded = [];
}
