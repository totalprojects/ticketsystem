<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AssetTypes;

class tbl_assets_masters extends Model
{
    use HasFactory;

    public function type() {
        return $this->belongsTo(AssetTypes::class, 'type', 'id');
    }

}
