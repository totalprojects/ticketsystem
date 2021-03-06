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

    public function grouped_requests() {
        return $this->hasMany(DevRequests::class, 'current_stage', 'id')->selectRaw('current_stage, count(id) as total_request')->groupBy(\DB::raw('current_stage'));
    }
}
