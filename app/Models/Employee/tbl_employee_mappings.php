<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Employees;

class tbl_employee_mappings extends Model {
    use HasFactory;

    protected $guarded = [];

    /**
     * Employee info
     */
    public function employee() {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    /** Report to Employee */
    public function report_employee() {
        return $this->belongsTo(Employees::class, 'report_to', 'id');
    }
}
