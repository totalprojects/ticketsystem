<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Users;
use App\Models\Permissions\permissions as Permission;
use Auth;
use Role;
use MenuMaster;
use MenuMapping;
use Spatie\Permission\Models\Role as Roles;
use Spatie\Permission\Models\Permission as Permissions;
use App\Models\Role\roles_has_permission as RolePermissions;
use App\Imports\UsersImport;
//use App\Helpers\Helper;

class UserController extends Controller {
    /**
     * @return view of users
     */
    public function index() {

        $user_permissions = Auth::user()->getAllPermissions();
        // storeActivity([
        //     'user_id'          => Auth::user()->id,
        //     'activity_type'    => "Opened User Page",
        //     'description_meta' => json_encode([])
        // ]);

        return view('users.index');
    }

    public function fetchUsers() {

        $modeled = Users::when(\Auth::user()->id != 1, function ($Q) {
            $Q->where('id', '!=', 1);
        })->when(\Auth::user()->id != 1, function ($Q) {
            $Q->where('id', '!=', Auth::user()->id);
        })->with('roles', 'permissions', 'assigned_menus')->with(['assigned_menus' => function ($Q) {
            $Q->with('menu');
        }])->selectRaw('*, DATE(created_at) as created')->get();
        $totalCount      = count($modeled);
        $all_permissions = \SystemModules::with('permissions')->get();
        //return $all_permissions;
        $all_roles       = Role::all();
        $all_menus       = MenuMaster::orderBy('menu_order', 'asc')->get();
        $reorderedMenu = [];
        foreach ($all_menus as $each) {
            $index = $each->id - 1;
            if ($each->parent_id > 0 && !empty($reorderedMenu)) {
                # submenu
                foreach ($reorderedMenu as $k => $r) {
                    if ($r['id'] == $each->parent_id) {
                        // echo $r['id'] . '<br>';
                        $reorderedMenu[$k]['children'][] = [
                            'id'        => $each->id,
                            'menu_name' => $each->menu_name,
                            'slug'      => $each->menu_slug
                        ];
                    }
                }
            }
            if ($each->parent_id == 0) {
                $reorderedMenu[] = [
                    'id'        => $each->id,
                    'menu_name' => $each->menu_name,
                    'slug'      => $each->menu_slug,
                    'children'  => []
                ];
            }

        }
        $dataArray = [];
        foreach ($modeled as $model) {
            $permissions    = '';
            $roles          = '';
            $assigned_menus = '';
            foreach ($model->roles as $r) {
                $roles .= $r->name . ',';
            }
            if (!empty($roles)) {
                $roles = substr($roles, 0, -1);
            }

            foreach ($model->permissions as $r) {
                $permissions .= $r->name . ',';
            }
            if (!empty($permissions)) {
                $permissions = substr($permissions, 0, -1);
            }

            foreach ($model->assigned_menus as $r) {
                $assigned_menus .= $r->name . ',';
            }
            if (!empty($assigned_menus)) {
                $assigned_menus = substr($assigned_menus, 0, -1);
            }

            $dataArray[] = [
                'id'              => $model->id,
                'roles'           => $roles,
                'name'            => $model->name,
                'permissions'     => $permissions,
                'assigned_menus'  => $assigned_menus,
                'email'           => $model->email,
                'created'         => date('d-m-Y', strtotime($model->created)),
                'all_permissions' => $all_permissions,
                'all_menus'       => $reorderedMenu,
                'his_menus'       => json_encode($model->assigned_menus),
                'his_roles'       => json_encode($model->roles),
                'his_permissions' => json_encode($model->permissions),
                'all_roles'       => $all_roles
            ];
        }

        return response(['data' => $dataArray, 'totalCount' => $totalCount]);
    }

    public function updatePermissions(Request $request) {

        $user        = Users::where('id', $request->user_id)->first();
        $permissions = $request->permissions;
        $user->syncPermissions($permissions);

        return response(['message' => 'Success', 'status' => 200], 200);

    }

    public function updateRole(Request $request) {

        $user         = Users::where('id', $request->user_id)->first();
        $roles        = $request->roles;
        $permissions  = RolePermissions::where('role_id', $roles[0])->get();
        $permissions1 = [];
        foreach ($permissions as $permission) {
            $permissions1[] = $permission->permission_id;
        }
        $user->syncRoles($roles);
        $user->syncPermissions($permissions1);

        return response(['message' => 'Success', 'status' => 200], 200);

    }

    public function updateMenus(Request $request) {

        $user          = Users::where('id', $request->user_id)->first();
        $menu_mappings = MenuMapping::where('user_id', $request->user_id)->delete();
        $new_menus     = $request->menus;
        foreach ($new_menus as $menu) {
            $new_menu = [
                'menu_id'     => $menu,
                'user_id'     => $request->user_id,
                'status'      => 1,
                'sub_menu_id' => 0
            ];

            MenuMapping::create($new_menu);
        }

        return response(['message' => 'Success', 'status' => 200], 200);

    }

    public function changePasswordPage(Request $request) {

        $data['page_title'] = 'Change Password';

        return view('users.change-password')->with($data);
    }

    public function settings() {

    }

    public function changePassword(Request $request) {
        $new_password           = $request->new_password;
        $current_password       = $request->current_password;
        $user_id                = Auth::user()->id;
        $check_current_password = Auth::user()->password;

        try {
            # verify old password
            $isVerified = \Hash::check($current_password, $check_current_password);
            //return dd($isVerified);
            if ($isVerified == 1) {

                # change password
                $changePwd = Users::where('id', $user_id)->update([
                    'password' => \Hash::make($new_password)
                ]);

                return response(['status' => 200, 'data' => [], 'message' => 'Password has been changed successfully'], 200);

            } else {
                return response(['status' => 400, 'data' => [], 'message' => 'Invalid Current Password'], 200);
            }
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function importUsers(Request $request) {

        return Excel::import(new UsersImport(), $request->file('file'));
    }
}
