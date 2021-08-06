<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles_has_permission extends Model {
    use HasFactory;
    protected $table   = 'role_has_permissions';
    protected $guarded = [];
    public $timestamps = false;

    public function permission_names() {
        return $this->belongsTo(\App\Models\Permissions\permissions::class, 'permission_id', 'id');
    }
}
