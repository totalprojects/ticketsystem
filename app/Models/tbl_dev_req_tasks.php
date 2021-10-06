<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DevRequests;
use Employees;

class tbl_dev_req_tasks extends Model
{
    use HasFactory;
    
    protected $casts = [
        'created_at' => 'datetime:d-m-Y h:i a'
    ];
    protected $guarded = [];

    public function request() {
        return $this->belongsTo(DevRequests::class, 'request_id', 'id');
    }

    public function assigned() {
        return $this->belongsTo(Employees::class, 'assigned_to', 'id');
    }
}
