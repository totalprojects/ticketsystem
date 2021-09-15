<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ActionMasters;
use Permission;
use CriticalTCodes;

class tbl_standard_tcode_masters extends Model
{
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

    public function critical_tcodes(){
        return $this->belongsTo(CriticalTCodes::class, 'id', 'tcode_id')->orderBy('tcode_id', 'asc')->where('status', 1);
    }

}
