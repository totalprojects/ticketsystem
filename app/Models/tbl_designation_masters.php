<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Role;

class tbl_designation_masters extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function role() {
        return $this->belongsTo(Role::class,'role_id', 'id');
    }
}
