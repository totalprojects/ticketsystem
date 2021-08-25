<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ApprovalMatrix;
use Permission;

class tbl_module_approval_stages extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function approval_matrix(){
        return $this->belongsTo(ApprovalMatrix::class, 'approval_matrix_id', 'id')->orderBy('id','asc');
    }

    public function module(){
        return $this->belongsTo(Permission::class, 'module_id', 'id');
    }

}
