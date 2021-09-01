<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Role;
use App\Models\Role\roles_has_permission as RolePermissions;
use App\Models\Model\model_has_roles as UserRoles;
use App\Models\Model\model_has_permissions as UserPermissions;
use Permission;
use RoleTcodeAccess;

class RoleController extends Controller {
    //
    /**
     * @return view of users
     */
    public function index() {
        $all_permissions = Permission::where('type', 2)->get();

        return view('roles.index')->with(['permissions' => $all_permissions]);
    }

    public function fetchRoles() {

        $roles           = Role::where('type',3)->with('permissions.permission_names')->get();
        $all_permissions = Permission::where('type', 2)->get();
        $dataArray       = [];

        foreach ($roles as $role) {
            $dataArray[] = [
                'id'              => $role->id,
                'name'            => $role->name,
                'his_permissions' => $role->permissions,
                'all_permissions' => $all_permissions
            ];
        }

        $totalCount = count($roles);
        return response(['data' => $dataArray, 'totalCount' => $totalCount]);

    }

    public function fetchRolePermissions(Request $request) {
        $role_id         = $request->role_id;
        $all_permissions = Permission::all();
        $role            = Role::where('id', $role_id)->with('permissions.permission_names')->get();
        //$role = Role::findByName('writer');
        return response(['data' => $role, 'status' => 200], 200);
    }

    public function roleTcodeAccess(Request $request) {

        $permission_id = $request->permission_id;
        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $standard_tcodes = \StandardTCodes::where('permission_id', $permission_id)->with('action_details');
        $count = $standard_tcodes->get()->Count();
        return response(['data'=>$standard_tcodes->take($take)->skip($skip)->get(), 'totalCount' => $count], 200);
    }

    public function submitRoleTcodeAccess(Request $request) {

        //return $request->tcodes;
        $pid = $request->pid;
        $tcodes = json_decode($request->tcodes,true);
        //return $tcodes;
        //$tcodes = explode(",", $request->tcodes);
        $role_id = $request->role_id;
        //$actions = json_encode($request->actions) ?? [];

        $remove_current = RoleTcodeAccess::where([
            'role_id' => $role_id,
            'module_id' => $pid,
        ])->delete();

        foreach($tcodes as $each) {

            RoleTcodeAccess::create([
                'role_id' => $role_id,
                'module_id' => $pid,
                'tcode_id' => $each['tcode'],
                'actions' => $each['actions']
            ]);
        }

        return response(['message' => 'success'], 200);
    }

    public function getCurrentTcodes(Request $request) {
        $role_id = $request->role_id;
        $permission_id = $request->permission_id;
        $roleTcodes = RoleTcodeAccess::where([
            'module_id' => $permission_id,
            'role_id' => $role_id
        ])->select('id','tcode_id','actions')->with('access_action_details')->get();

        return response(['data' => $roleTcodes],200);

    }

    public function updateRole(Request $request) {

        //return $request->all();
        $role_id   = $request->role_id;
        $role_name = $request->erole_name;

        $permissions = $request->permissions;

        $rolePermissions = RolePermissions::where('role_id', $role_id)->delete();

        $freshPermissionsForRoles = [];
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {

                $freshPermissionsForRoles = [
                    'permission_id' => $permission,
                    'role_id'       => $role_id
                ];

                RolePermissions::create($freshPermissionsForRoles);
            }
        }

        $usersWithRoles = UserRoles::where('role_id', $role_id)->select('model_id')->get();

        foreach ($usersWithRoles as $uroles) {
            $removeUserPermissions = UserPermissions::where('model_id', $uroles->model_id)->delete();
        }
        if (!empty($permissions)) {
            foreach ($usersWithRoles as $uroles2) {

                foreach ($permissions as $permission) {

                    $freshPermissionsForUsers = [
                        'permission_id' => $permission,
                        'model_type'    => 'App\Models\User',
                        'model_id'      => $uroles2->model_id
                    ];

                    UserPermissions::create($freshPermissionsForUsers);
                }

            }
        }

        //return $permissions;

        $updateArray = [
            'name'       => $role_name,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        try {

            $update = Role::where('id', $role_id)->update($updateArray);

            return response(['message' => 'Success', 'data' => []], 200);

        } catch (\Exception $e) {

            return response(['message' => 'Server Error', 'data' => $e->getMessage()], 500);
        }
    }

    public function addRole(Request $request) {

        //return $request->all();
        $role_name   = $request->role_name;
        $permissions = $request->permissions1 ?? NULL;
        $insertArray = [
            'name'       => $role_name,
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        try {
            # dup check
            $dup = Role::where('name', 'like', '%' . $role_name . '%')->get();
            if ($dup->Count() > 0) {
                return response(['message' => 'Duplicate Role Name', 'data' => []], 409);
            }
            $role_id = Role::insertGetId($insertArray);
            if (!is_null($permissions)) {
                foreach ($permissions as $permission) {

                    RolePermissions::create([
                        'role_id'       => $role_id,
                        'permission_id' => $permission
                    ]);
                }
            }

            return response(['message' => 'Success', 'data' => []], 200);

        } catch (\Exception $e) {

            return response(['message' => 'Server Error', 'data' => $e->getMessage()], 500);
        }
    }
}
