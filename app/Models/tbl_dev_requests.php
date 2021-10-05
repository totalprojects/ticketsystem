<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Permission;
use StandardTCodes;

class tbl_dev_requests extends Model
{
    use HasFactory;

    public function permission() {
        return $this->belongsTo(Permission::class, 'module_id', 'id');
    }

    public function tcode() {
        return $this->belongsTo(StandardTCodes::class, 'tcode_id', 'id');
    }
}
