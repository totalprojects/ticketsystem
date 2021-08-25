<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCodes;

class tbl_critical_tcode_masters extends Model
{
    use HasFactory;

    public function tcodes(){
        return $this->belongsTo(TCodes::class, 'tcode_id', 'id');
    }
}
