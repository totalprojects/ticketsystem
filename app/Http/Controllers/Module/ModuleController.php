<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;
use ApprovalMatrix;
use ModuleApprovalStages;


class ModuleController extends Controller
{
    //
    public function approval_matrix() {
        $data['page_title'] = "Approval Matrix";
        $approval_matrix = ApprovalMatrix::all();
        $data['approval_matrix'] = $approval_matrix;
        return view('approval_matrix.index')->with($data);
    }


    public function fetchModuleApprovalStages(Request $request) {
        $take = $request->take;
        $skip = $request->skip;
        //tcodes.action_details:id,name
        // only sap module permissions
        $permissions = Permission::with('model_permissions.users:id,name', 'module_head.user_details', 'module_approval_stages.approval_matrix')->where('type', 2);

        $totalCount = count($permissions->get());
        return response(['data' => $permissions->take($take)->skip($skip)->get(), 'totalCount' => $totalCount]);

    }

    public function changeModuleApprovalStages(Request $request) {
       // return $request->all();
        if(empty($request->module_id)) {

            return response(['status' => 400], 200);
        }

       try {

        $removeOld = ModuleApprovalStages::where('module_id', $request->module_id)->delete();
        $stages = $request->stages;
        if(is_array($stages) && !empty($stages)) {

            foreach($stages as $each) {

                ModuleApprovalStages::create([
                    'module_id' => $request->module_id,
                    'approval_matrix_id' => $each,
                    'created_at'    => NOW(),
                    'updated_at' => NOW()
                ]);

            }
        }
        
        return response(['status' => 200, 'message' => 'Update was successful']);

       } catch(\Exception $e) {
            return response(['status' => 400, 'message' => $e->getMessage()], 500);
       }
    }
}
