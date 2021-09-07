<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Users;

class tbl_login_logs extends Model {
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:d-m-Y h:i a'
    ];
    public function user() {
        return $this->belongsTo(Users::class,'user_id', 'id');
    }
}
