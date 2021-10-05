<?php

namespace App\Http\Controllers\ChangeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RoleTcodeAccess;
use Auth;
use App\Models\Model\model_has_permissions as UserPermissions;
use DevStages;
use DevRequests;
use ModuleHead;

class SapChangeManagementController extends Controller
{
    //
    public function index() {

        $data['pag_title'] = "SAP Change Management";
        $user_id = Auth::user()->id;
        $emp_id = Auth::user()->employee_id;
        $permissions = [];
        $modules = UserPermissions::where('model_id', $user_id)->with('permission')->get();
        $is_module_head =   ModuleHead::where('user_id', $user_id)->get();
        $is_mh = false;
        if($is_module_head->Count() > 0) {
            $is_mh = true;
            foreach($is_module_head as $each) {
                $permissions[] = $each->permission_id;
            }
        }
      
        $is_developer = false;
        $is_basis = \Moderators::where(['employee_id' => $emp_id, 'type_id' => 4])->get();
        $is_basis = ($is_basis->Count()>0) ? true : false;

        $modulesArray = [];

         foreach($modules as $each) {
             $modulesArray[] = [
                'id' => $each->permission_id,
                'name' => !is_null($each->permission->name) ? $each->permission->name : '-'
             ];
         }
         // return $modulesArray;
         $data['modules'] = $modulesArray;

         $stageswithRequest = DevStages::with(['requests' => function($Q) use($emp_id, $permissions, $is_developer) {
            $Q->when(count($permissions) > 0, function($Q) use($permissions, $emp_id) {
                $Q->whereIn('module_id', $permissions);
                $Q->orWhere('employee_id', $emp_id);
                
            });
            // is not a module head
            $Q->when(count($permissions) == 0, function($Q) use($emp_id, $is_developer) {
                if($is_developer===false) {
                    $Q->where('employee_id', $emp_id);
                }
                
            });
                $Q->with('permission', 'tcode');
         }])->get();


         $stageswithRequestArray = [];
        // return $stageswithRequest[0]->name;
         foreach($stageswithRequest as $each) {

            $isDraggable = $this->checkIfDraggable($each->id, $is_mh, $is_developer, $is_basis);
            $isDropable = $this->checkIfDropable($each->id, $is_mh, $is_developer, $is_basis);
             $stageswithRequestArray[] = [
                'id' => $each->id,
                'name' => $each->name,
                'requests' => json_encode($each->requests),
                'isDraggable' => $isDraggable,
                'isDropable' => $isDropable,
             ];
         }
        // return $stageswithRequestArray;
         $data['stages'] = $stageswithRequestArray;
         $data['moderators'] = [
            'isDeveloper' => $is_developer,
            'isBasis' => $is_basis,
            'isModuleHead' => $is_mh
         ];

        return view('change_management.sap.index')->with($data);
    }

    public function checkIfDraggable($stage_id, $is_mh, $is_developer, $is_basis) {
        $isDraggable = false;
        switch($stage_id) {
            case 1:
                $isDraggable = ($is_mh==true) ? true : false;
            break;

            case 2:
                $isDraggable = ($is_developer==true) ? true : false;
            break;

            case 3:
                $isDraggable = ($is_developer == true) ? true : false;
            break;

            case 4:
                $isDraggable = ($is_basis == true) ? true : false;
            break;

        }

        return (bool) $isDraggable;
    }

    public function checkIfDropable($stage_id, $is_mh, $is_developer, $is_basis) {
        $isDraggable = false;
        switch($stage_id) {
            case 1:
                $isDraggable = ($is_mh==true) ? true : false;
            break;

            case 2:
                $isDraggable = ($is_mh==true) ? true : false;
            break;

            case 3:
                $isDraggable = ($is_developer == true) ? true : false;
            break;

            case 4:
                $isDraggable = ($is_basis == true) ? true : false;
            break;

        }

        return (bool) $isDraggable;
    }

    public function stageChange(Request $request) {
        $from_stage = $request->stage_id;
        $to_stage = $request->to_stage;
        $reqId = $request->req_id;

        $checkBeforeChange = DevRequests::where(['id' => $reqId])->select('id', 'current_stage')->get();

        if($checkBeforeChange->Count() == 1) {

            $currentStage = $checkBeforeChange[0]->current_stage;
            
            if($to_stage == ($currentStage + 1)) {

                try {

                    DevRequests::where(['id' => $reqId])->update([
                        'current_stage' => $to_stage,
                        'updated_at' => NOW()
                    ]);

                    return response(['status' => 200], 200);


                } catch(\Exception $e) {
                    return response(['message' => $e->getMessage()], 500);
                }

            } else {
                return response(['status' => 400], 200);
            }

            

        } else {
            return response(['status' => 404], 200);
        }
    }

    public function allowedTcodes(Request $request) {
        $module_id = $request->module_id;

        if($module_id > 0) {

            $codes = \RoleTcodeAccess::where('module_id', $module_id)->with('tcode')->get();

            if($codes->Count() > 0) {

                $codesArray = [];
                foreach($codes as $e) {
                    $codesArray[] = [
                        'id' => $e->tcode_id,
                        'name' => $e->tcode->t_code
                    ];
                }

                return response(['data' => $codesArray, 'message' => 'Found'], 200);
            }

        } else {
            return response(['data' => [], 'message' => 'No module id found'], 500);
        }
    }

    public function addRequest(Request $request) {
        $module_id = $request->module_id;
        $tcode_id = $request->tcode_id;
        $employee_id = Auth::user()->employee_id;
        $description = $request->description;
        $currentStage = 1;
        $status = 0;
        $createdAt = NOW();

        try {
            //$isDuplicate = DevRequests::where(['module_id' => ])

            $save = DevRequests::create([
                'module_id' => $module_id,
                'tcode_id' => $tcode_id,
                'employee_id' => $employee_id,
                'description' => $description,
                'current_stage' => $currentStage,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => NULL
            ]);


            return response(['message' => 'Success', 'last_id' => $save->id ?? -1], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
