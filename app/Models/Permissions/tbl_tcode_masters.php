<?php

namespace App\Models\Permision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ActionMasters;

class tbl_tcode_masters extends Model {
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'actions' => 'array'
    ];

    public function actions() {
        return $this->belongsToJson(ActionMasters::class, 'id');
    }

    
    public function critical_tcodes(){
        return $this->hasOne(CriticalTCodes::class, 'tcode_id', 'id')->orderBy('tcode_id', 'asc');
    }

}
