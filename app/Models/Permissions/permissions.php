<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCodes;
use ModuleHead;
use App\Models\Model\model_has_permissions as UserPermissions;

class permissions extends Model {
    use HasFactory;

    public function tcodes() {
        return $this->hasMany(TCodes::class, 'permission_id', 'id');
    }

    public function module_head() {
        return $this->hasOne(ModuleHead::class, 'permission_id', 'id');
    }

    public function model_permissions() {
        return $this->hasMany(UserPermissions::class, 'permission_id');
    }
}
