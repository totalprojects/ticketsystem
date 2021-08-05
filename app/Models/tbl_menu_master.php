<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MenuMapping;

class tbl_menu_master extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function menu_mappings(){
        return $this->hasMany(MenuMapping::class,'menu_id');
    }
}
