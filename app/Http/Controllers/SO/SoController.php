<?php

namespace App\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SOMasters;
use SalesOffice;

class SoController extends Controller {
    //

    public function getSalesOffice(Request $request) {
        $division_code     = $request->division_code;
        $distribution_code = $request->distribution;
        $so                = $request->so;

        $sales_offices = SOMasters::with('sales_office')->whereIn('division_code', $division_code)->whereIn('distribution_channel_code', $distribution_code)->whereIn('sales_org_code', $so)->get();

        return response(['data' => $sales_offices]);
    }

    public function set_so_id() {

        $so = SOMasters::all();

        foreach ($so as $s) {

            $sales_office = SalesOffice::where('sales_office_code', $s->sales_office_code)->select('id', 'sales_office_code')->first();
            if (isset($sales_office->id)) {
                $up = SOMasters::where('id', $s->id)->update(['sales_office_code' => $sales_office->id]);
            }

        }

    }
}
