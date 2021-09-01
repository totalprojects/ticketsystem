<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCodes;
use ModuleHead;
use App\Models\Model\model_has_permissions as UserPermissions;
use ModuleApprovalStages;
use CriticalTCodes;
use RoleTcodeAccess;

class permissions extends Model {
    use HasFactory;

    public function tcodes() {
        return $this->hasMany(TCodes::class, 'permission_id', 'id')->orderBy('permission_id', 'asc');
    }

    public function allowed_tcodes() {
        return $this->hasMany(RoleTcodeAccess::class, 'module_id', 'id')->orderBy('module_id', 'asc');
    }

    public function module_head() {
        return $this->hasOne(ModuleHead::class, 'permission_id', 'id');
    }

    public function model_permissions() {
        return $this->hasMany(UserPermissions::class, 'permission_id');
    }

    public function module_approval_stages(){
        return $this->hasMany(ModuleApprovalStages::class, 'module_id', 'id')->orderBy('approval_matrix_id', 'asc');
    }

}
