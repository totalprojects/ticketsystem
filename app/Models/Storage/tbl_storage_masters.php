<?php

namespace App\Models\Storage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plants;

class tbl_storage_masters extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function plant(){
        return $this->belongsTo(Plants::class,'plant_code', 'plant_code');
    }
}
