<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CompanyMasters;
use Plants;
use BusinessArea;
use Storages;
use SO;
use PO;
use Divisions;
use Distributions;
use SalesOffice;
use PORelease;
use ActionMasters;
use Permission;
use TCodes;
use Users;
use SAPApprovalLogs;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class tbl_sap_requests extends Model {
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    protected $casts = [
        'created_at'      => 'datetime:d-m-Y',
        'company_code'    => 'array',
        'plant_code'      => 'array',
        'business_id'     => 'array',
        'storage_id'      => 'array',
        'sales_org_id'    => 'array',
        'sales_office_id' => 'array',
        'distribution_id' => 'array',
        'division_id'     => 'array',
        'purchase_org_id' => 'array',
        'po_release_id'   => 'array',
        'actions'         => 'array'
    ];

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function company() {
        return $this->belongsToJson(CompanyMasters::class, 'company_code', 'company_code');
    }

    public function plant() {
        return $this->belongsToJson(Plants::class, 'plant_code', 'plant_code');
    }

    public function business() {
        return $this->belongsToJson(BusinessArea::class, 'business_id', 'business_code');
    }

    public function storage() {
        return $this->belongsToJson(Storages::class, 'storage_id', 'id');
    }

    public function sales_org() {
        return $this->belongsToJson(SO::class, 'sales_org_id', 'id');
    }

    public function sales_office() {
        return $this->belongsToJson(SalesOffice::class, 'sales_office_id', 'id');
    }

    public function distributions() {
        return $this->belongsToJson(Distributions::class, 'distribution_id', 'distribution_channel_code');
    }

    public function divisions() {
        return $this->belongsToJson(Divisions::class, 'division_id', 'division_code');
    }

    public function purchase_org() {
        return $this->belongsToJson(PO::class, 'purchase_org_id', 'id');
    }

    public function po_release() {
        return $this->belongsToJson(PoRelease::class, 'po_release_id', 'id');
    }

    public function action() {
        return $this->belongsToJson(ActionMasters::class, 'actions', 'id');
    }

    public function modules() {
        return $this->belongsTo(Permission::class, 'module_id', 'id');
    }

    public function tcodes() {
        return $this->belongsToJson(TCodes::class, 'tcode_id', 'id');
    }

    public function approval_logs() {
        return $this->hasMany(SAPApprovalLogs::class, 'request_id', 'id');
    }

    public function getTableColumns(){
        return DB::getSchemaBuilder()->getColumnListing($this->getTable());
    }

}
