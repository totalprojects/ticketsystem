<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Permission;
use StandardTCodes;
use DevRequestLogs;
use DevStages;
use Employees;

class tbl_dev_requests extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:d/m/y h:i a'
    ];
    public function permission() {
        return $this->belongsTo(Permission::class, 'module_id', 'id');
    }

    public function tcode() {
        return $this->belongsTo(StandardTCodes::class, 'tcode_id', 'id');
    }

    public function logs() {
        return $this->hasMany(DevRequestLogs::class, 'dev_req_id', 'id');
    }

    public function stage() {
        return $this->belongsTo(DevStages::class, 'current_stage', 'id');
    }

    public function creator() {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

}
