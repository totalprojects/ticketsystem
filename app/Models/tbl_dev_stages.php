<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DevRequests;

class tbl_dev_stages extends Model
{
    use HasFactory;

    public function requests() {
        return $this->hasMany(DevRequests::class, 'current_stage', 'id');
    }
}
