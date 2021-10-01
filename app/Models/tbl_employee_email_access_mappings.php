<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EmailProviders;

class tbl_employee_email_access_mappings extends Model
{
    use HasFactory;

     /** Providers Info */
     public function provider() {
        return $this->belongsTo(EmailProviders::class, 'provider_id', 'id');
     }
}
