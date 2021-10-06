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
use StandardTCodes;
use DevRequests;

class permissions extends Model {
    use HasFactory;

    public function tcodes() {
        return $this->hasMany(StandardTCodes::class, 'permission_id', 'id')->orderBy('permission_id', 'asc');
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

    public function parent_module() {
        return $this->belongsTo(SystemModules::class, 'id', 'type');
    }

    public function requests() {
        return $this->hasMany(DevRequests::class, 'module_id', 'id')->selectRaw("module_id, count(id) as total_request")->groupBy(\DB::raw('module_id'));
    }

    public function stageWise() {
        return $this->hasMany(DevRequests::class, 'module_id', 'id')->selectRaw("module_id, current_stage, count(id) as total_request")->groupBy(\DB::raw('module_id, current_stage'));
    }
}


