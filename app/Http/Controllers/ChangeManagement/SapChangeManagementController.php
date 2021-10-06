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
use DevRequestLogs;
use Permission;


class SapChangeManagementController extends Controller
{
    //
    public function index() {
       // return dd(checkifAnyModerator());
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
        $role = Auth::user()->roles[0]->name ?? '';
        $is_developer = ($role === 'ZM_DEVELOPER') ? true : false;
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
                $Q->with('permission', 'tcode', 'logs.to_stage','logs.from_stage', 'logs.creator');
         }])->get();


         $stageswithRequestArray = [];
       //  return $stageswithRequest;
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
        $empId = Auth::user()->employee_id;
        if(!checkIfAnyModerator()) {
            return response(['status' => 401, 'message' => 'Operation not permitted'], 200);
        }

        DevRequestLogs::create([
            'dev_req_id' => $reqId,
            'from_stage' => $from_stage,
            'to_stage' => $to_stage,
            'remarks' => 'N/A',
            'created_by' => $empId
        ]);

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

    public function addTask(Request $request) {
        $assiged_to = $request->assigned_to;
        $description = $request->task_description;
        $due_date = $request->task_due_date;
        $req_id = $request->treq_id;
        $createdAt = NOW();

        try {
            //$isDuplicate = DevRequests::where(['module_id' => ])

            $save = \DevRequestTasks::create([
                'assigned_to' => $assiged_to,
                'request_id' => $req_id,
                'description' => $description,
                'due_date' => $due_date,
                'status' => 0,
                'created_at' => $createdAt,
                'updated_at' => NULL
            ]);


            return response(['message' => 'Success', 'last_id' => $save->id ?? -1], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function getRspEmployees(Request $request) {
        $req_id = $request->req_id;

        $getTask = DevRequests::where('id', $req_id)->get();

        if($getTask->Count() == 0) {
            return response(['data' => []], 500);
        }

        $module_id = $getTask[0]->module_id;

        $getUsers = UserPermissions::where('permission_id', $module_id)->has('users')->get();
        $respUsersArray = [];
        foreach($getUsers as $each) {
            if($each->users->employee_id>0) {
                $respUsersArray[] = [
                    'id' => $each->users->employee_id,
                    'name' => $each->users->name
                ];
            }
        }

        $existingTasks = \DevRequestTasks::where('request_id', $req_id)->with('request','assigned')->get();


        return response(['data' => $respUsersArray, 'existingTasks' => $existingTasks, 'status' => 200], 200);
    }

    public function dashboard() {
        $data = [];
        $moduleWiseRequests = Permission::with('requests', 'stageWise')->get();
       // return $moduleWiseRequests;
      
        return view('dev_dashboard.index')->with($data);
    }

    public function fetchModules() {
        $moduleWiseRequests = Permission::with('requests', 'stageWise')->where('type',2)->get();
        $moduleStageWiseRequest = DevStages::all();
        $dataArray = [];
        $drilled = [];
       // return $moduleWiseRequests;   
        foreach($moduleWiseRequests as $each) {
            $dataArray[] = [
                'name' => $each->name,
                'y' => (count($each->requests) > 0) ? (int) $each->requests[0]->total_request : 0,
                'drilldown' => $each->name
            ];

            foreach($moduleStageWiseRequest as $ee) {
                $eev = 0;
                foreach($each->stageWise as $e) {
                    if($ee->id == $e->current_stage) {
                        $eev = $e->total_request;
                    }
                }
                if(isset($drilled) && !empty($drilled)) {
                    $flag = 1;
                    foreach($drilled as $key => $x) {

                        if($x['name'] == $each->name) {
                            $flag = 0;
                            array_push($drilled[$key]['data'], [$ee->name, (int) $eev]);
                        }
                    }


                    if($flag) {
                        $drilled[] = [
                            'name' => $each->name,
                            'id' => $each->name,
                            'data' => [
                               [$ee->name, (int) $eev]
                             ]
                        ];
                    }
                } else {
                    $drilled[] = [
                        'name' => $each->name,
                        'id' => $each->name,
                        'data' => [
                           [$ee->name, (int) $eev]
                         ]
                    ];
                }


                
            }   
        }

        return response(['data' => $dataArray, 'drilleddata' => $drilled], 200);
    }


    public function fetchStagesBar(Request $request) {

            $req = DevStages::with('grouped_requests')->orderBy('id','asc')->get();
            $reqArr = [];
            foreach($req as $each) {
                $total = 0;
                if(count($each->grouped_requests)>0) {
                    $total = $each->grouped_requests[0]->total_request;
                }
                $reqArr[] = $total; 
            }
            return response(['data' => $reqArr], 200);
            
    }

    public function fetchDevRequests(Request $request) {

        # search filters
        $req_id = $request->req_id;
        $tcode = $request->tcode;
        $module = $request->module_id;
        $user_id = $request->user_id;
        $fromDate = !empty($request->fromDate) ? date('Y-m-d', strtotime($request->fromDate)) : '';
        $toDate = !empty($request->toDate) ? date('Y-m-d', strtotime($request->toDate)) : date('Y-m-d');

        $requests = DevRequests::when(!empty($fromDate), function($Q) use($fromDate, $toDate) {
            $Q->whereBetween('created_at', [$fromDate, $toDate]);
        })->when(!empty($req_id), function($Q) use($req_id) {
            if(stristr($req_id, '/')) {
                $id = explode("/", $req_id);
                $id = $id[2] ?? NULL;
                if($id !== null) {
                    $Q->where('id', $id);
                }   
            }
        })->when(!empty($module), function($Q) use($module) {
            $Q->whereHas('permission', function($Q) use($module) {
                $Q->where('name', 'like', '%' .$module . '%');
            });
        })->when(!empty($user_id), function($Q) use($user_id) {
            $Q->whereHas('creator', function($Q) use($user_id) {
            $Q->with('departments');
                $Q->where('first_name', 'like', '%' .$user_id . '%');
                $Q->orWhere('last_name', 'like', '%' .$user_id . '%');
            });
        })->whereHas('stage')
        ->with(['logs' => function($Q) {
            $Q->with('from_stage');
            $Q->with('to_stage');
            $Q->with('creator');
        }])->with('creator.departments', 'tcode', 'permission', 'stage')
        ->when(!empty($tcode), function($Q) use($tcode) {
            $Q->whereHas('tcode', function($Q) use($tcode) {
                    $Q->where('t_code', $tcode);
            });
        })->get();

        return response(['data' => $requests], 200);
    }
}
