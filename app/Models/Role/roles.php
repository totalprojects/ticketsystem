<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role\roles_has_permission as RolePermission;
use DepartmentMasters;

class roles extends Model
{
    use HasFactory;

    public function permissions(){
        return $this->hasMany(RolePermission::class,'role_id','id');
    }

    public function departments(){
        return $this->hasMany(DepartmentMasters::class,'type','id');
    }
}
