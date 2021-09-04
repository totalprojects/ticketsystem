<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DepartmentMasters;

class DepartmentController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'Departments';
        return view('departments.index');
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $departments = DepartmentMasters::orderBy('id', 'asc');
        $totalCount = $departments->get()->Count();
        return response(['data' => $departments->take($take)->skip($skip)->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $department_name = $request->department_name;

        try {
            $deaprtment_name = DepartmentMasters::create([
                'department_name' => $department_name,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Department Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $department_name = $request->edepartment_name;
        $department_id = $request->edepartment_id;

        try {
            $update = DepartmentMasters::where('id', $department_id)->update([
                'department_name' => $department_name,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Department Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
