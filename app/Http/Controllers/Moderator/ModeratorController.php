<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Moderators;
use Employees;

class ModeratorController extends Controller
{
    //
    public function index() {

        $up                 = \Auth::user()->getAllPermissions();
        $add_permission     = false;
        $edit_permission    = false;
        $create_permission  = false;

        foreach ($up as $e) {

            if ($e->name == 'Add User') {
                $add_permission = true;
            }

            if ($e->name == 'Edit User') {
                $edit_permission = true;
            }

            if ($e->name == 'Create User') {
                $create_permission = true;
            }
        }

        $data['page_title'] = "Moderators";
        try{
            $employees = Employees::select('id', 'first_name', 'last_name')->get();
        }catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
        $data['employees'] = $employees;

        $data['permission'] = ['create' => $create_permission, 'add' => $add_permission, 'edit' => $edit_permission];

        return view('moderators.index')->with($data);
    }

    public function fetchModerators(Request $request) {
        try{
            $moderators = Moderators::with('employee')->get();
        }catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
        return response(['status' => 200, 'data' => $moderators],200);
    }

    public function add_moderator(Request $request) {
        $employee_id = $request->employee_id;
        $type_id = $request->type ?? -1;

        try {

            if($employee_id > 0) {
                Moderators::create([
                    'employee_id' => $employee_id,
                    'type_id' => $type_id,
                    'created_at' => NOW(), 
                    'updated_at' => NOW()
                ]);
        
            }

            return response(['status' => 200, 'message' => 'success'], 200);

        }catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function updateModerator(Request $request) {

        $employee_id = $request->employee_id1;
        $type_id = $request->type1 ?? -1;
        $id = $request->id;

        try {

            if($employee_id > 0) {
                Moderators::where('id', $id)->update([
                    'employee_id' => $employee_id,
                    'type_id' => $type_id,
                    'created_at' => NOW(), 
                    'updated_at' => NOW()
                ]);
        
            }

            return response(['status' => 200, 'message' => 'success'], 200);

        }catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
