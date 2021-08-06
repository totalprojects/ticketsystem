<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Users;

class model_has_permissions extends Model {
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function users() {
        return $this->belongsTo(Users::class, 'model_id', 'id');
    }
}
