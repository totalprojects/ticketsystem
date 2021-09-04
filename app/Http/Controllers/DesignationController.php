<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Designations;
use Role;

class DesignationController extends Controller
{
     //
     public function index() {
        $data['page_title'] = 'Designations';
        $roles = Role::whereNotIn('id',[3, 4, 2])->get();
        $data['roles'] = $roles;
        return view('designations.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $designations = Designations::with('role')->orderBy('id', 'asc');
        $totalCount = $designations->get()->Count();
        return response(['data' => $designations->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $designation_name = $request->designation_name;
        $role_id = $request->role_id ?? 1;

        try {
            $deaprtment_name = Designations::create([
                'designation_name' => $designation_name,
                'role_id'          => $role_id,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'designation Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $designation_name = $request->edesignation_name;
        $designation_id = $request->edesignation_id;
        $role_id = $request->erole_id;

        try {
            $update = Designations::where('id', $designation_id)->update([
                'designation_name' => $designation_name,
                'role_id' => $role_id,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Designation Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
