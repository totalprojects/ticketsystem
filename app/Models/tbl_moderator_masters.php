<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Employees;

class tbl_moderator_masters extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i A'
    ];

    public function employee() {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}
