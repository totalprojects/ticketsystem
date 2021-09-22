<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Users;
use DB;
use SAPRequest;

class tbl_sap_approval_logs extends Model {
    use HasFactory;
    protected $casts = [
        'created_at'      => 'datetime:d-m-Y h:i a',
        'updated_at'      => 'datetime:d-m-Y h:i a',
    ];
    // protected $casts = [
    //     'created_at' => 'datetime:F, d, Y'
    // ];
    public function request_details() {
        return $this->belongsTo(SAPRequest::class, 'request_id', 'id');
    }
    public function created_by_user() {
        return $this->belongsTo(Users::class, 'created_by', 'id');
    }

    public function getTableColumns(){
        return DB::getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
