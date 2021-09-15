<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ActionMasters;
use TCodes;
use CriticalTCodes;
use StandardTCodes;

class tbl_role_wise_module_tcode_access extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $table = 'tbl_role_wise_module_tcode_access';

    protected $guarded = [];

    protected $casts = [
        'actions' => 'array'
    ];

    public function access_action_details() {
        return $this->belongsToJson(ActionMasters::class, 'actions', 'id');
    }

    public function tcode() {
        return $this->belongsTo(StandardTCodes::class, 'tcode_id', 'id');
    }

    public function critical() {
        return $this->belongsTo(CriticalTCodes::class, 'tcode_id', 'tcode_id');
    }
}
