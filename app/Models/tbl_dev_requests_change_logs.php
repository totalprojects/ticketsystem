<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DevStages;
use DevRequests;
use Employees;

class tbl_dev_requests_change_logs extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:F m, y h:i a'
    ];

    public function to_stage() {
        return $this->belongsTo(DevStages::class, 'to_stage', 'id');
    }

    public function from_stage() {
        return $this->belongsTo(DevStages::class, 'from_stage', 'id');
    }

    public function request() {
        return $this->belongsTo(DevRequests::class, 'dev_req_id', 'id');
    }

    public function creator() {
        return $this->belongsTo(Employees::class, 'created_by', 'id');
    }
}
