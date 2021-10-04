<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Users;

class tbl_activity_logs extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y h:i a'
    ];
    
    public $table = 'tbl_app_activity_logs';

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(Users::class,'user_id', 'id');
    }
    
}
