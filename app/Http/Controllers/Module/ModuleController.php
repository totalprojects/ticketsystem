<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;
use ApprovalMatrix;
use ModuleApprovalStages;
use CriticalTCodes;
use ActionMasters;
use Auth;

class ModuleController extends Controller
{
    //
    public function approval_matrix() {
        $data['page_title'] = "Approval Matrix";
        $approval_matrix = ApprovalMatrix::all();
        $data['approval_matrix'] = $approval_matrix;
        return view('approval_matrix.index')->with($data);
    }

    public function critical_page() {
        $data['page_title'] = "Critical Page";
        // $critical = CriticalTCodes::all();
        // $data['critical'] = $critical;
        return view('request.sap.critical')->with($data);
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

    public function critical_tcodes() {

        $permissions = Permission::where('type', 1)->get();
        $actions     = ActionMasters::all();
        return view('critical_tcodes.index')->with(['modules' => $permissions, 'actions' => $actions]);
    }

    public function criticalTCodes(Request $request) {
        $permission = $request->permission_id;
        $take = $request->take ?? 100000;
        $skip = $request->skip ?? 0;
        $critical_tcodes = CriticalTCodes::with('tcodes.permission')->whereHas('tcodes', function($Q) use($permission) {
            $Q->where('permission_id', $permission);
        })->orderBy('id', 'asc');
        $totalCount = $critical_tcodes->get()->Count();

        return response(['data' => $critical_tcodes->take($take)->skip($skip)->get(), 'totalCount' => $totalCount]);
    }

    public function fetchStages(Request $request) {

        $req_id = $request->request_id;

        $getData = \SAPRequest::where('id', $req_id)->select('module_id', 'user_id')->first();
        $module_id = !empty($getData) ? $getData->module_id : NULL;
        $userID = !empty($getData) ? $getData->user_id : NULL;
        $IS_RM = false;
        $IS_MH = false;
        $employee_id = 0;
        if(!is_null($userID)) {
            $emp = \Users::where('id',$userID)->select('employee_id')->first();
            if($emp) {
                $employee_id = $emp->employee_id;
            }
        }
        
        $stages = [];
       
        if($module_id > 0) {

            $is_module_head = \ModuleHead::where(['user_id' => Auth::user()->id, 'permission_id' => $module_id])->get();
            if($is_module_head->Count()>0) {
               $IS_MH = true;
            }

            $is_rm = \EmployeeMappings::where('report_to', Auth::user()->employee_id)->get();
            if($is_rm->Count() > 0) {
                foreach($is_rm as $rm) {
                    if($rm->employee_id == $employee_id) {
                        $IS_RM = true;
                    }
                }
            }
            $getStages = \ModuleApprovalStages::where('module_id', $module_id)->select('module_id','approval_matrix_id')->orderBy('approval_matrix_id', 'asc')->get();
        }
       // return $getStages;
        foreach($getStages as $stage) {
            $stages[] = $stage->approval_matrix_id;
        }
        //return $stages;
        return response(['data' => $stages, 'IS_RM' => $IS_RM, 'IS_MH' => $IS_MH],200);

    }
}
