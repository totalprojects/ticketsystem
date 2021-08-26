<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;
use TCodes;
use Users;
use ActionMasters;
use ModuleHead;
use App\Models\Model\model_has_permissions as UserPermissions;

class PermissionController extends Controller {
    //
    /**
     * @return view of users
     */
    public function index() {
        $permissions = Permission::where('type', 2)->get();
        $actions     = ActionMasters::all();
        $users       = Users::all();
        $tcodes      = TCodes::orderBy('id', 'asc')->get();
        // return $permissions;
        return view('permission.index')->with(['modules' => $permissions, 'actions' => $actions, 'tcodes' => $tcodes, 'users' => $users]);
    }

    /** Permission for Application */
    public function app() {
        $permissions = Permission::where('type', 1)->get();
        $users       = Users::all();
        // return $permissions;
        return view('permission.app-permissions')->with(['modules' => $permissions, 'users' => $users]);
    }

    public function appPermissions(Request $request) {
        $take = $request->take;
        $skip = $request->skip;
        //tcodes.action_details:id,name
        // only sap module permissions
        $roles = Permission::with('model_permissions.users:id,name', 'module_head.user_details')->where('type', 1);

        $totalCount = count($roles->get());
        return response(['data' => $roles->take($take)->skip($skip)->get(), 'totalCount' => $totalCount]);

    }

    public function fetchPermissions(Request $request) {
        $take = $request->take;
        $skip = $request->skip;
        //tcodes.action_details:id,name
        // only sap module permissions
        $roles = Permission::with('model_permissions.users:id,name', 'module_head.user_details')->where('type', 2);

        $totalCount = count($roles->get());
        return response(['data' => $roles->take($take)->skip($skip)->get(), 'totalCount' => $totalCount]);

    }

    public function fetchModuleTCodes(Request $request) {
        $permission_id = $request->permission_id;

        $tcode = $request->tcode;
        $desc  = $request->description;
        //return $permission_id;
        $take   = $request->take ?? 25;
        $skip   = $request->skip ?? 0;
        $tcodes = TCodes::where('permission_id', $permission_id)
            ->when(!empty($tcode), function ($Q) use ($tcode) { 
                $Q->where('t_code', $tcode);
            })->when(!empty($desc), function ($Q) use ($desc) {
            $Q->where('description', 'like', '%' . $desc . '%');
        })->with('action_details', 'permission','critical_tcodes');
        $permission_data = Permission::where('id', $permission_id)->first();
        $module_name     = $permission_data->name;
        $totalCount      = $tcodes->get()->Count();
        $limitData       = $tcodes->take($take)->skip($skip)->get();

        return response(['data' => $limitData, 'totalCount' => $totalCount, 'module_name' => $module_name, 'status' => 200]);
    }

    public function updatePermission(Request $request) {

        //return $request->all();
        $permission_id   = $request->permission_id;
        $permission_name = $request->emodule;
        $permission_code = $request->epermission_code;
        $permission_type = $request->epermission_type;
        $module_head_id  = $request->emodule_head;

        $updateArray = [
            'name'       => $permission_name,
            'code'       => $permission_code,
            'type'       => $permission_type,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        try {
            $update        = Permission::where('id', $permission_id)->update($updateArray);
            $checkifExists = ModuleHead::where('permission_id', $permission_id)->get();
            if ($checkifExists->count() > 0) {

                $add_module_head = ModuleHead::where('permission_id', $permission_id)->update([
                    'permission_id' => $permission_id,
                    'user_id'       => $module_head_id,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
            } else {

                $add_module_head = ModuleHead::insert([
                    'permission_id' => $permission_id,
                    'user_id'       => $module_head_id,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
            }

            return response(['message' => 'Success', 'data' => []], 200);

        } catch (\Exception $e) {

            return response(['message' => 'Server Error', 'data' => $e->getMessage()], 500);
        }
    }

    public function addPermission(Request $request) {

        //return $request->all();
        $permission_name = $request->permission_name;
        $permission_code = $request->permission_code;
        $permission_type = $request->permission_type;

        $module_head_id = $request->module_head;

        $insertArray = [
            'name'       => $permission_name,
            'code'       => $permission_code,
            'type'       => $permission_type,
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        try {
            # dup check
            $dup = Permission::where('code', 'like', '%' . $permission_code . '%')->get();

            if ($dup->Count() > 0) {
                return response(['message' => 'Duplicate Permission Code', 'data' => []], 409);
            }

            $update = Permission::insert($insertArray);

            $lastID = Permission::where('name', '=', $permission_name)->select('id')->first();

            if ($lastID) {
                if ($module_head_id > 0) {
                    $add_module_head = ModuleHead::insert([
                        'permission_id' => $lastID->id,
                        'user_id'       => $module_head_id,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }

            }

            return response(['message' => 'Success', 'data' => []], 200);

        } catch (\Exception $e) {

            return response(['message' => 'Server Error', 'data' => $e->getMessage()], 500);
        }
    }

    public function addTcode(Request $request) {

        //return $request->all();
        $tcode         = $request->tcode;
        $tcode_desc    = $request->tcode_desc;
        $permission_id = $request->module;
        $actions       = $request->actions;
        $status        = $request->status ?? 1;
        $type = $request->tcode_type;

        $insertArray = [
            't_code'        => $tcode,
            'permission_id' => $permission_id,
            'description'   => $tcode_desc,
            'actions'       => json_encode($actions, TRUE),
            'status'        => $status,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];
        try {
            # dup check
            $dup = TCodes::where('t_code', 'like', '%' . $tcode . '%')->where('permission_id', $permission_id)->get();
            if ($dup->Count() > 0) {
                return response(['message' => 'Duplicate TCode Name', 'data' => []], 409);
            }
            $insert = TCodes::insert($insertArray);
            $lastId = TCodes::orderBy('id','desc')->limit(1)->first();
            if($type == 1) {
                
                $ctc =  \CriticalTCodes::where('tcode_id',$lastId->id)->get();
 
                 if($ctc->Count() == 0) {
                     \CriticalTCodes::create([
                         'tcode_id' => $lastId->id,
                         'status' => 1,
                         'created_at' => NOW(),
                         'updated_at' => NOW()
                     ]);
                 }
             }
            return response(['message' => 'Success', 'data' => []], 200);

        } catch (\Exception $e) {

            return response(['message' => 'Server Error', 'data' => $e->getMessage()], 500);
        }
    }

    public function trashTcode(Request $request) {

        $id = $request->id;

        if ($id > 0) {
            try {
                Tcodes::where('id', $id)->delete();

                return response(['message' => 'Removed'], 200);
            } catch (\Exception $e) {

                return response($e->getMessage());
            }
        }

    }

    public function dxUpdateTcode(Request $request) {
        // return $request->all();
        $id        = $request->t_id;
        $module_id = $request->t_module_id;
        $tcode     = $request->tt_code;
        $tdesc     = $request->t_description;
        $actions   = json_encode($request->t_actions, TRUE);
        $status    = $request->t_status;
        $type = $request->tcode_type;

        try {

            TCodes::where('id', $id)->update([
                't_code'        => $tcode,
                'permission_id' => $module_id,
                'description'   => $tdesc,
                'actions'       => $actions,
                'status'        => $status,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

            if($type == 1) {

               $ctc =  \CriticalTCodes::where('tcode_id',$id)->get();

                if($ctc->Count() == 0) {
                    \CriticalTCodes::create([
                        'tcode_id' => $id,
                        'status' => 1,
                        'created_at' => NOW(),
                        'updated_at' => NOW()
                    ]);
                } else {
                    $ctc =  \CriticalTCodes::where('tcode_id',$id)->update([
                        'tcode_id' => $id,
                        'status' => 1,
                        'updated_at' => NOW()
                    ]);
                }
            } else {

                \CriticalTCodes::where('tcode_id',$id)->update([
                    'status' => 0,
                    'updated_at' => NOW()
                ]);
            }

            return response(['status' => 200, 'message' => 'Success'], 200);

        } catch (\Exception $e) {

            return response(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }
}
