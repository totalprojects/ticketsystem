<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_system_modules_masters extends Model
{
    use HasFactory;

    public function permissions() {
        return $this->hasMany(\Permission::class, 'type', 'id');
    }
}
