<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EmployeeMappings;
use DistrictMasters;
use StateMasters;
use CompanyMasters;
use DepartmentMasters;
use EmployeeAssets;
use Users;
use EmployeeEmailAccess;
use EmployeeSoftwareAccess;
use Designations;

class tbl_employee_masters extends Model {

    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i A'
    ];

    /**
     * Employee Report to [ multiple ]
     */
    public function report_to_many() {

        return $this->hasMany(EmployeeMappings::class, 'employee_id', 'id');
    }

    public function report_to() {

        return $this->hasOne(EmployeeMappings::class, 'employee_id', 'id');
    }

    /** District */
    public function district() {

        return $this->belongsTo(DistrictMasters::class, 'district_id', 'district_id');
    }

    /** States */
    public function state() {

        return $this->belongsTo(StateMasters::class, 'state_id', 'state_id');
    }

    /** Company */
    public function company() {

        return $this->belongsTo(CompanyMasters::class, 'company_code', 'id');
    }

    /** Department */
    public function departments() {

        return $this->belongsTo(DepartmentMasters::class, 'department_id', 'id');
    }

    /** Designation */
    public function designation() {

        return $this->belongsTo(Designations::class, 'designation_id', 'id');
    }

    /** Assets */
    public function assets() {
        return $this->hasMany(EmployeeAssets::class, 'user_id', 'id');
    }

    /** User */
    public function user() {
        return $this->belongsTo(Users::class, 'id', 'employee_id');
    }

    /** Employee Email Access */
    public function email_access() {
        return $this->belongsTo(EmployeeEmailAccess::class, 'id', 'employee_id');
    }

    /** Employee Software Access */
    public function software_access() {
        return $this->belongsTo(EmployeeSoftwareAccess::class, 'id', 'employee_id');
    }

    
}
