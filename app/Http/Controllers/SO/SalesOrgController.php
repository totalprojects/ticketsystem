<?php

namespace App\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CompanyMasters;
use SO;

class SalesOrgController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'Sales Organization';
        $companies = CompanyMasters::all();
        $data['companies'] = $companies;
        return view('so.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = SO::orderBy('id', 'asc')->with('company');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $so_description = $request->so_description;
        $so_code = $request->so_code;
        $company_code = $request->company_code;

        try {
            $deaprtment_description = SO::create([
                'so_description' => $so_description,
                'so_code' => $so_code,
                'company_code' => $company_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Sales Org Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $so_description = $request->eso_description;
        $so_code = $request->eso_code;
        $so_id = $request->eso_id;
        $company_code = $request->ecompany_code;

        try {
            $update = SO::where('id', $so_id)->update([
                'so_description' => $so_description,
                'so_code' => $so_code,
                'company_code' => $company_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Sales Org Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
