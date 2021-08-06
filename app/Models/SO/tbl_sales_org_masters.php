<?php

namespace App\Models\SO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SalesOffice;

class tbl_sales_org_masters extends Model {
    use HasFactory;

    public function sales_office() {

        return $this->belongsTo(SalesOffice::class, 'sales_office_code', 'id');
    }
}
