<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Permission;
use StandardTCodes;
use DevRequestLogs;

class tbl_dev_requests extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function permission() {
        return $this->belongsTo(Permission::class, 'module_id', 'id');
    }

    public function tcode() {
        return $this->belongsTo(StandardTCodes::class, 'tcode_id', 'id');
    }

    public function logs() {
        return $this->hasMany(DevRequestLogs::class, 'dev_req_id', 'id');
    }
}
