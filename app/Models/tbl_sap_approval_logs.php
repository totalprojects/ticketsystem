<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Users;

class tbl_sap_approval_logs extends Model {
    use HasFactory;

    // protected $casts = [
    //     'created_at' => 'datetime:F, d, Y'
    // ];

    public function created_by_user() {
        return $this->belongsTo(Users::class, 'created_by', 'id');
    }
}
