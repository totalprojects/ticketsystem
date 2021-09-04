<?php

namespace App\Models\SO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CompanyMasters;

class tbl_sales_org extends Model {
    use HasFactory;

    public $table = 'tbl_sales_org';

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(CompanyMasters::class,'company_code', 'company_code');
    }
}
