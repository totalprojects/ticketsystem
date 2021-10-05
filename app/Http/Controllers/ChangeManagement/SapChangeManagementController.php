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
        if($is_module_head->Count() > 0) {
            foreach($is_module_head as $each) {
                $permissions[] = $each->permission_id;
            }
        }
       // return $permissions;
        $is_developer = false;

        $modulesArray = [];

         foreach($modules as $each) {
             $modulesArray[] = [
                'id' => $each->permission_id,
                'name' => !is_null($each->permission->name) ? $each->permission->name : '-'
             ];
         }
         // return $modulesArray;
         $data['modules'] = $modulesArray;

         $stageswithRequest = DevStages::with(['requests' => function($Q) use($emp_id, $permissions) {
            $Q->when(count($permissions) > 0, function($Q) use($permissions) {
                $Q->whereIn('module_id', $permissions);
            });
            // is not a module head
            $Q->when(count($permissions) == 0, function($Q) use($emp_id) {
                $Q->where('employee_id', $emp_id);
            });
                $Q->with('permission', 'tcode');
         }])->get();

         //return $stageswithRequest;
         $data['stages'] = $stageswithRequest;

        return view('change_management.sap.index')->with($data);
    }
}
