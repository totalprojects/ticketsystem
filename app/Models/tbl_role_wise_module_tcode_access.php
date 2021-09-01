<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ActionMasters;
use TCodes;

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
        return $this->belongsTo(TCodes::class, 'tcode_id', 'id');
    }
}
