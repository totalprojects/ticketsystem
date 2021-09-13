<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ApprovalMatrix;

class tbl_mail_templates extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function approval_matrix(){
        return $this->belongsTo(ApprovalMatrix::class, 'approval_matrix_id', 'id')->orderBy('id','asc');
    }
}
