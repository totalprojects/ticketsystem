<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MenuMaster;

class tbl_user_menu_mapping extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menu() {
        return $this->belongsTo(MenuMaster::class,'menu_id')->orderBy('menu_order','asc');
    }
}
