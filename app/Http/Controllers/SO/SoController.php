<?php

namespace App\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SOMasters;
use SalesOffice;
use SO;

class SoController extends Controller {
    //

    public function getSalesOffice(Request $request) {
        $division_code     = $request->division_code;
        $distribution_code = $request->distribution;
        $so                = $request->so;
        if (isset($so) && isset($distribution_code) && isset($division_code)) {
            $getSOCode = SO::whereIn('id', $so)->get();
            $socode    = [];
            foreach ($getSOCode as $code) {
                $socode[] = $code->so_code;
            }

            $sales_offices = SOMasters::with('sales_office')->whereIn('division_code', $division_code)->whereIn('distribution_channel_code', $distribution_code)->whereIn('sales_org_code', $socode)->get();

            return response(['data' => $sales_offices]);
        }
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

    public function index() {
        $data['page_title'] = 'Sales Office';
        return view('sales_offices.index');
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = SalesOffice::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $sales_office_name = $request->sales_office_name;
        $sales_office_code = $request->sales_office_code;

        try {
            $deaprtment_name = SalesOffice::create([
                'sales_office_name' => $sales_office_name,
                'sales_office_code' => $sales_office_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Company Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $sales_office_name = $request->esales_office_name;
        $sales_office_code = $request->esales_office_code;
        $sales_office_id = $request->esales_office_id;

        try {
            $update = SalesOffice::where('id', $sales_office_id)->update([
                'sales_office_name' => $sales_office_name,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Company Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
