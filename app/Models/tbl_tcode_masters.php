<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ActionMasters;
use Permission;

class tbl_tcode_masters extends Model {
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'actions' => 'array'
    ];

    public function action_details() {
        return $this->belongsToJson(ActionMasters::class, 'actions', 'id');
    }

    public function permission() {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

}
