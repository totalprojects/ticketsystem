<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Users;

class tbl_module_head_masters extends Model {
    use HasFactory;

    public function user_details() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
