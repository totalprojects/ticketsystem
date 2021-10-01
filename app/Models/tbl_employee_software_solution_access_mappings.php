<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoftwareSolutions;

class tbl_employee_software_solution_access_mappings extends Model
{
    use HasFactory;

       /** Providers Info */
       public function software() {
        return $this->belongsTo(SoftwareSolutions::class, 'software_id', 'id');
     }
}
