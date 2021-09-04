<?php

namespace App\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Divisions;

class DivisionController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'Division';
       
        return view('divisions.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = Divisions::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $division_description = $request->division_description;
        $division_code = $request->division_code;

        try {
            $deaprtment_description = Divisions::create([
                'division_description' => $division_description,
                'division_code' => $division_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Distribution Channel Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $division_description = $request->edivision_description;
        $division_code = $request->edivision_code;
        $division_id = $request->edivision_id;

        try {
            $update = Divisions::where('id', $division_id)->update([
                'division_description' => $division_description,
                'division_code' => $division_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Distribution Channel Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
