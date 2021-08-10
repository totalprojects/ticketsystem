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
        $permissions = Permission::all();
        $actions     = ActionMasters::all();
        $users       = Users::all();
        // return $permissions;
        return view('permission.index')->with(['modules' => $permissions, 'actions' => $actions, 'users' => $users]);
    }

    public function fetchPermissions() {
        $roles      = Permission::with('tcodes.action_details:id,name', 'model_permissions.users:id,name', 'module_head.user_details')->get();
        $totalCount = count($roles);
        //
        //
        return response(['data' => $roles, 'totalCount' => $totalCount]);

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
        $permission_name = $request->module;
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
                return response(['message' => 'Duplicate Permission Name', 'data' => []], 409);
            }

            $update = Permission::insert($insertArray);

            $lastID = Permission::where('name', '=', $permission_name)->select('id')->first();

            if ($lastID) {
                $add_module_head = ModuleHead::insert([
                    'permission_id' => $lastID->id,
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

    public function addTcode(Request $request) {

        //return $request->all();
        $tcode         = $request->tcode;
        $tcode_desc    = $request->tcode_desc;
        $permission_id = $request->module;
        $actions       = $request->actions;

        $insertArray = [
            't_code'        => $tcode,
            'permission_id' => $permission_id,
            'description'   => $tcode_desc,
            'actions'       => json_encode($actions, TRUE),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];
        try {
            # dup check
            $dup = TCodes::where('t_code', 'like', '%' . $tcode . '%')->where('permission_id', $permission_id)->get();
            if ($dup->Count() > 0) {
                return response(['message' => 'Duplicate TCode Name', 'data' => []], 409);
            }
            $update = TCodes::insert($insertArray);

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
}
