<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AssetsMasters;

class tbl_employee_assets_masters extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i A'
    ];
    
    public function asset() {
        return $this->belongsTo(AssetsMasters::class, 'asset_id', 'id');
    }
}
