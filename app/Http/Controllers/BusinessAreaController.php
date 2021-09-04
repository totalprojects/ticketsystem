<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BusinessArea;

class BusinessAreaController extends Controller
{
    public function index() {
        $data['page_title'] = 'Business Area';
        return view('business_area.index');
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $business_area = BusinessArea::orderBy('id', 'asc');
        $totalCount = $business_area->get()->Count();
        return response(['data' => $business_area->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $business_name = $request->business_name;
        $business_code = $request->business_code;

        try {
            $deaprtment_name = BusinessArea::create([
                'business_name' => $business_name,
                'business_code' => $business_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Business Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $business_name = $request->ebusiness_name;
        $business_code = $request->ebusiness_code;
        $business_id = $request->ebusiness_id;

        try {
            $update = BusinessArea::where('id', $business_id)->update([
                'business_name' => $business_name,
                'business_code' => $business_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Business Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
