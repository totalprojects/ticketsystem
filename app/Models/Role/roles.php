<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role\roles_has_permission as RolePermission;

class roles extends Model
{
    use HasFactory;

    public function permissions(){
        return $this->hasMany(RolePermission::class,'role_id','id');
    }
}
