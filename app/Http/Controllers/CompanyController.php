<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CompanyMasters;

class CompanyController extends Controller
{
    //
     //
     public function index() {
        $data['page_title'] = 'Departments';
        return view('company.index');
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = CompanyMasters::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $company_name = $request->company_name;
        $company_code = $request->company_code;

        try {
            $deaprtment_name = CompanyMasters::create([
                'company_name' => $company_name,
                'company_code' => $company_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Company Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $company_name = $request->ecompany_name;
        $company_code = $request->ecompany_code;
        $company_id = $request->ecompany_id;

        try {
            $update = CompanyMasters::where('id', $company_id)->update([
                'company_name' => $company_name,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Company Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
