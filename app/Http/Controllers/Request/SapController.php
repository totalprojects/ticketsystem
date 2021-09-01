<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CompanyMasters;
use Divisions;
use Distributions;
use BusinessArea;
use PO;
use PORelease;
use Auth;
use TCodes;
use Permission;
use SAPRequest;
use Plants;
use Storages;
use SO;
use Role;
use SalesOffice;
use ActionMasters;
use App\Models\Role\roles_has_permission as RolePermissions;
use App\Models\Model\model_has_permissions as UserPermissions;
use EmployeeMappings;
use SAPApprovalLogs;
use PurchaseGroup;
use DepartmentMasters;
use Employees;

class SapController extends Controller {
    /**
     * @return view of users
     */
    public function index() {

        $companies     = CompanyMasters::all();
        $divisions     = Divisions::all();
        $distributions = Distributions::all();
        $business      = BusinessArea::all();
        $po            = PO::all();
        $po_release    = PORelease::all();
        $empData       = Employees::where('id', Auth::user()->employee_id)->first();
        $did           = 0;
        if ($empData) {
            $did = $empData->department_id;
            $did = DepartmentMasters::where('id', $did)->first();
            if ($did) {
                $did = $did->id;
            }
        }
        //return Auth::user()->employee_id;

        $roles = Role::when($did > 0, function ($Q) use ($did) {
            $Q->where('type', $did);
        })->get();
        $pg = PurchaseGroup::all();

        return view('request.sap.index')->with(['roles' => $roles, 'companies' => $companies, 'divisions' => $divisions, 'distributors' => $distributions, 'business' => $business, 'po' => $po, 'po_release' => $po_release, 'pg' => $pg]);
    }

    public function team() {

        $roles = Role::all();

        return view('request.sap.team')->with(['roles' => $roles]);
    }

    public function modulesAndTCodes(Request $request) {

        $user_id = Auth::user()->id;
        $role_id = $request->role_id;

        if(empty($role_id)) {
            $role_id = Auth::user()->roles->pluck('id')[0];
        }
        // if ($role_id == 0) {
        //     return response(['data' => [], 'message' => 'No Tcodes found'], 200);
        // }
        //$permissions    = UserPermissions::where('model_id', $user_id)->select('permission_id')->get();
        $role_based_permissions = RolePermissions::where('role_id', $role_id)->select('permission_id')->get();
        $permission_ids         = [];
        foreach ($role_based_permissions as $permission) {
            $permission_ids[] = $permission->permission_id;
        }
        //$modulewithTcodes = TCodes::whereIn('permission_id', $permission_ids)->with('permission', 'action_details')->orderBy('permission_id', 'asc')->get();
        //return $permission_ids;
        // $modulewithTcodes = Permission::whereIn('id', $permission_ids)
        //     ->with(['tcodes' => function ($Q) {
        //         // $Q->take(500)->skip(0);
        //         $Q->with('action_details');
        //     }])
        //     ->orderBy('id', 'asc')->get();
        //->groupBy('id')

        $modules      = [];
        $tcodes       = [];
        $i            = 1;
        $j            = 1;
        $grandChildId = 1;
        // return response(['data' => $modulewithTcodes], 200);

        $modulewithTcodes = Permission::when($role_id > 0, function ($Q) use ($permission_ids) {
            $Q->whereIn('id', $permission_ids);
        })
            ->where('id', '!=', 12)
        // sap department
            ->where('type', 2)
            ->with(['allowed_tcodes' => function ($Q) use($role_id) {
                $Q->when($role_id > 0, function ($Q) use ($role_id) {
                    $Q->where('role_id', $role_id);
                });                
                $Q->with('access_action_details', 'tcode');
            }])->get()->map(function ($each) use (&$modules, &$grandChildId) {

           // $each->tcodes = $each->tcodes->take(50)->skip(0);

            $modules[] = [
                'n_id'       => $grandChildId,
                'n_title'    => $each->name,
                'n_parentid' => 0,
                'n_addional' => ['permission_id' => $each->id],
                'n_checked'  => false
            ];

            $childId1 = $grandChildId;
            $grandChildId += 1;
                //return response($each->allowed_tcodes);
            foreach ($each->allowed_tcodes as $codes) {

                $modules[] = [
                    'n_id'       => $grandChildId,
                    'n_title'    => $codes->tcode->description . '(' . $codes->tcode->t_code . ')',
                    'n_parentid' => $childId1,
                    'n_addional' => ['tcode_id' => $codes->id]
                ];

                $childId2 = $grandChildId;
                $grandChildId += 1;

                foreach ($codes->access_action_details as $eachAction) {
                    $modules[] = [
                        'n_id'       => $grandChildId,
                        'n_title'    => $eachAction->name,
                        'n_parentid' => $childId2,
                        'n_addional' => ['action_id' => $eachAction->id]
                    ];

                    $grandChildId++;
                }

            }

        });

        // foreach ($modulewithTcodes as $each) {

        //     $modules[] = [
        //         'n_id'       => $grandChildId,
        //         'n_title'    => $each->name,
        //         'n_parentid' => 0,
        //         'n_addional' => ['permission_id' => $each->id],
        //         'n_checked'  => false
        //     ];

        //     $childId1 = $grandChildId;
        //     $grandChildId += 1;

        //     foreach ($each->tcodes as $codes) {

        //         $modules[] = [
        //             'n_id'       => $grandChildId,
        //             'n_title'    => $codes->description . '(' . $codes->t_code . ')',
        //             'n_parentid' => $childId1,
        //             'n_addional' => ['tcode_id' => $codes->id]
        //         ];

        //         $childId2 = $grandChildId;
        //         $grandChildId += 1;

        //         foreach ($codes->action_details as $eachAction) {
        //             $modules[] = [
        //                 'n_id'       => $grandChildId,
        //                 'n_title'    => $eachAction->name,
        //                 'n_parentid' => $childId2,
        //                 'n_addional' => ['action_id' => $eachAction->id]
        //             ];

        //             $grandChildId++;
        //         }

        //     }
        // }

        return response(['data' => $modules], 200);
    }

    public function saveRequest(Request $request) {

        $modules = json_decode($request->module);
        $user_id = Auth::user()->id;
        $role_id = $request->role;
        // return $modules;
        $company_name = array_map('intval', $request->company_name);
        $uniqueId     = time();
        $requestId    = 'RN' . $user_id . '/' . date('y') . '/' . date('m') . '/' . $uniqueId;
        // return $company_name;
        $entryArray   = [];
        $i            = 0;
        $permissionid = 0;
        foreach ($modules as $module) {

            if (isset($module->moduleset->permission_id)) {

                if (isset($entryArray[$module->moduleset->permission_id])) {

                }
                $entryArray[] = [
                    'module_id' => $module->moduleset->permission_id
                ];
                $permissionid = $module->moduleset->permission_id;
            }

            //echo var_dump($entryArray);
            //  echo $permissionid . '<br>';
            if (isset($module->moduleset->tcode_id)) {

                foreach ($entryArray as $key => $each) {
                    if ($each['module_id'] == $permissionid) {
                        $entryArray[$key]['tcode_id'][] = $module->moduleset->tcode_id;
                    }
                }

                $tcode_id = $module->moduleset->tcode_id;

            }

            if (isset($module->moduleset->action_id)) {

                foreach ($entryArray as $key => $each) {
                    if ($each['module_id'] == $permissionid) {
                        $entryArray[$key]['action_id'][] = ['t_' . $tcode_id => $module->moduleset->action_id];
                    }
                }

            }

            $i++;
        }

        foreach ($entryArray as $key => $each) {

            foreach ($each['tcode_id'] as $tcodes) {
                $actions = [];
                foreach ($each['action_id'] as $aid) {
                    if (isset($aid['t_' . $tcodes])) {
                        $actions[] = $aid['t_' . $tcodes];
                    }
                }

                $business   = $request->business_area ?? [];
                $plant_code = array_map('intval', $request->plant_name ?? []);

                $array = [
                    'role_id'         => $role_id,
                    'user_id'         => $user_id,
                    'req_int'         => $uniqueId,
                    'request_id'      => $requestId,
                    'company_code'    => $company_name,
                    'plant_code'      => $plant_code,
                    'storage_id'      => array_map('intval', $request->storage_location ?? []),
                    'business_id'     => array_map('intval', $business),
                    'sales_org_id'    => array_map('intval', $request->sales_org ?? []),
                    'purchase_org_id' => array_map('intval', $request->purchase_org ?? []),
                    'division_id'     => array_map('intval', $request->division ?? []),
                    'distribution_id' => array_map('intval', $request->distribution_channel ?? []),
                    'sales_office_id' => array_map('intval', $request->sales_office ?? []),
                    'po_release_id'   => array_map('intval', $request->po_release ?? []),
                    'module_id'       => $each['module_id'],
                    'tcode_id'        => $tcodes,
                    'actions'         => $actions,
                    'status'          => 0,
                    'created_at'      => NOW(),
                    'updated_at'      => NOW()
                ];
                try {

                    SAPRequest::create($array);

                } catch (\Exception $e) {

                    return response(['message' => $e->getMessage()], 500);
                }

            }

        }

        return response(['message' => 'success'], 200);
    }

    public function reviewRequest(Request $request) {

        $modules = json_decode($request->module);
        $user_id = Auth::user()->id;
        // return $modules;
        $company_name = array_map('intval', $request->company_name);
        $role_id      = $request->role;
        $role_name    = '';
        if ($role_id > 0) {
            $role      = Role::where('id', $role_id)->first();
            $role_name = $role->name;
        }

        // return $company_name;
        $entryArray   = [];
        $i            = 0;
        $permissionid = 0;
        foreach ($modules as $module) {

            if (isset($module->moduleset->permission_id)) {

                if (isset($entryArray[$module->moduleset->permission_id])) {

                }
                $entryArray[] = [
                    'module_id' => $module->moduleset->permission_id
                ];
                $permissionid = $module->moduleset->permission_id;
            }

            //echo var_dump($entryArray);
            //  echo $permissionid . '<br>';
            if (isset($module->moduleset->tcode_id)) {

                foreach ($entryArray as $key => $each) {
                    if ($each['module_id'] == $permissionid) {
                        $entryArray[$key]['tcode_id'][] = $module->moduleset->tcode_id;
                    }
                }

                $tcode_id = $module->moduleset->tcode_id;

            }

            if (isset($module->moduleset->action_id)) {

                foreach ($entryArray as $key => $each) {
                    if ($each['module_id'] == $permissionid) {
                        $entryArray[$key]['action_id'][] = ['t_' . $tcode_id => $module->moduleset->action_id];
                    }
                }

            }

            $i++;
        }

        foreach ($entryArray as $key => $each) {

            foreach ($each['tcode_id'] as $tcodes) {
                $actions = [];
                foreach ($each['action_id'] as $aid) {
                    if (isset($aid['t_' . $tcodes])) {
                        $actions[] = $aid['t_' . $tcodes];
                    }
                }
                $business     = $request->business_area ?? [];
                $plant_code   = array_map('intval', $request->plant_name ?? []);
                $storage      = array_map('intval', $request->storage_location ?? []);
                $business     = array_map('intval', $business);
                $so           = array_map('intval', $request->sales_org ?? []);
                $po           = array_map('intval', $request->purchase_org ?? []);
                $division     = array_map('intval', $request->division ?? []);
                $distribution = array_map('intval', $request->distribution_channel ?? []);
                $po_release   = array_map('intval', $request->po_release ?? []);
                $sales_office = array_map('intval', $request->sales_office ?? []);

                try {
                    $companies     = CompanyMasters::whereIn('company_code', $company_name)->get();
                    $plants        = Plants::whereIn('plant_code', $plant_code)->get();
                    $storages      = Storages::whereIn('id', $storage)->get();
                    $businesses    = BusinessArea::whereIn('business_code', $business)->get();
                    $sales_orgs    = SO::whereIn('id', $so)->get();
                    $purchase_orgs = PO::whereIn('id', $po)->get();
                    $sales_offices = SalesOffice::where('id', $sales_office)->get();
                    $divisions     = Divisions::whereIn('division_code', $division)->get();
                    $distributions = Distributions::whereIn('distribution_channel_code', $distribution)->get();
                    $po_releases   = PORelease::whereIn('id', $po_release)->get();
                    $actions       = ActionMasters::whereIn('id', $actions)->get();
                    $module        = Permission::where('id', $each['module_id'])->get();
                    $tcode         = TCodes::where('id', $tcodes)->get();

                } catch (\Exception $e) {

                    return response(['message' => $e->getMessage()], 500);
                }

                $array[] = [
                    'user_id'         => Auth::user()->name,
                    'role_name'       => $role_name,
                    'company_code'    => $companies,
                    'plant_code'      => $plants,
                    'storage_id'      => $storages,
                    'business_id'     => $businesses,
                    'sales_org_id'    => $sales_orgs,
                    'purchase_org_id' => $purchase_orgs,
                    'division_id'     => $divisions,
                    'distribution_id' => $distributions,
                    'sales_office_id' => $sales_offices,
                    'po_release_id'   => $po_releases,
                    'module_id'       => $each['module_id'],
                    'module'          => $module,
                    'tcode_id'        => $tcode,
                    'actions'         => $actions,
                    'created_at'      => NOW()
                ];

            }

        }

        //return $array;

        $htmlTable = "<table class='table table-bordered table-responsive' style='max-width:100%; overflow:auto'>
        <thead>

        <th>Company</th>
        <th>Role</th>
            <th>
                Plant
            </th>
            <th>
                Storage
            </th>
            <th>
                Business
            </th>
            <th>
                Sales Org
            </th>
            <th>
                Division
            </th>
            <th>
                Distribution
            </th>
            <th>
                Sales Office
            </th>
            <th>
                Purchase Org
            </th>
            <th>
                PO Release
            </th>
            <th>
                Module
            </th>
            <th>
                T CODE
            </th>
            <th>
                Actions
            </th>
        </thead>
        <tbody>";

        foreach ($array as $row) {

            $role_name = $row['role_name'];
            $companies = '';
            foreach ($row['company_code'] as $company) {

                $companies .= $company['company_name'] . ' ( ' . $company['company_code'] . '), ';
            }
            $companies = substr($companies, 0, -2);

            $plants = '';
            foreach ($row['plant_code'] as $plant) {

                $plants .= $plant['plant_name'] . ' ( ' . $plant['plant_code'] . '), ';
            }
            $plants = substr($plants, 0, -2);

            $storages = '';
            foreach ($row['storage_id'] as $storage) {

                $storages .= $storage['storage_description'] . ' ( ' . $storage['storage_code'] . '), ';
            }
            $storages = substr($storages, 0, -2);

            $businesses = '';
            foreach ($row['business_id'] as $business) {

                $businesses .= $business['business_name'] . ' ( ' . $business['business_code'] . '), ';
            }
            $businesses = substr($businesses, 0, -2);

            $sales_orgs = '';
            foreach ($row['sales_org_id'] as $sales_org) {

                $sales_orgs .= $sales_org['so_description'] . ' ( ' . $sales_org['so_code'] . '), ';
            }
            $sales_orgs = substr($sales_orgs, 0, -2);

            $purchase_orgs = '';
            foreach ($row['purchase_org_id'] as $purchase_org) {

                $purchase_orgs .= $purchase_org['po_name'] . ' ( ' . $purchase_org['po_code'] . '), ';
            }
            $purchase_orgs = substr($purchase_orgs, 0, -2);

            $divisions = '';
            foreach ($row['division_id'] as $division) {

                $divisions .= $division['division_description'] . ' ( ' . $division['division_code'] . '), ';
            }
            $divisions = substr($divisions, 0, -2);

            $distributions = '';
            foreach ($row['distribution_id'] as $distribution) {

                $distributions .= $distribution['distribution_channel_description'] . ' ( ' . $distribution['distribution_channel_code'] . '), ';
            }
            $distributions = substr($distributions, 0, -2);

            $sales_offices = '';
            foreach ($row['sales_office_id'] as $sales_office) {

                $sales_offices .= $sales_office['sales_office_name'] . ' ( ' . $sales_office['sales_office_code'] . '), ';
            }
            $sales_offices = substr($sales_offices, 0, -2);

            $po_releases_1 = '';
            foreach ($row['po_release_id'] as $po_release) {

                $po_releases_1 .= $po_release['rel_description'] . ' ( ' . $po_release['rel_code'] . '), ';
            }
            $po_releases_1 = substr($po_releases_1, 0, -2);

            $modules = '';
            foreach ($row['module'] as $module) {

                $modules .= $module['name'] . ', ';
            }
            $modules = substr($modules, 0, -2);

            $tcodes = '';
            foreach ($row['tcode_id'] as $tcode) {

                $tcodes .= $tcode['description'] . '( ' . $tcode['t_code'] . ' ), ';
            }
            $tcodes = substr($tcodes, 0, -2);

            $actions = '';
            foreach ($row['actions'] as $action) {

                $actions .= $action['name'] . ', ';
            }
            $actions = substr($actions, 0, -2);

            $htmlTable .= "
            <tr>
                <td>" . $companies . "</td>
                <td>" . $role_name . "</td>
                <td>" . $plants . "</td>
                <td>" . $storages . "</td>
                <td>" . $businesses . "</td>
                <td>" . $sales_orgs . "</td>
                <td>" . $divisions . "</td>
                <td>" . $distributions . "</td>
                <td>" . $sales_offices . "</td>
                <td>" . $purchase_orgs . "</td>
                <td>" . $po_releases_1 . "</td>
                <td>" . $modules . "</td>
                <td>" . $tcodes . "</td>
                <td>" . $actions . "</td>
            </tr>";
        }

        $htmlTable .= `</tbody></table>`;

        return response(['message' => 'success', 'data' => $htmlTable], 200);
    }

    public function fetchSelfRequest(Request $request) {
        $user_id = Auth::user()->id;
        $take    = $request->take;
        $skip    = $request->skip;

        $data       = SAPRequest::where('user_id', $user_id)->with('approval_logs.created_by_user', 'company', 'plant', 'business', 'storage', 'sales_org', 'sales_office', 'distributions', 'divisions', 'purchase_org', 'po_release', 'modules', 'tcodes', 'action');
        $totalCount = $data->get()->Count();
        $dataArray  = [];
        //return $data->get();
        $subArray = [];
        $i        = 1;
        foreach ($data->take($take)->skip($skip)->get() as $each) {

            $company_name = $each->company['company_name'] ?? '-';
            $company_code = $each['company_code'] ?? '-';
            $flag         = 1;
            if (!empty($dataArray)) {

                //return $dataArray;
                foreach ($dataArray as $key => $value) {
                    if ($value['id'] == $each->module_id) {
                        $flag = 0;
                    }
                }
            }

            if ($flag == 1) {

                $request_log = [];
                foreach ($each->approval_logs as $log) {
                    $request_log[] = [
                        'approval_stage' => $log->approval_stage,
                        'created_at'     => date('F, d, Y h:i a', strtotime($log->created_at)),
                        'created_by'     => $log->created_by_user->name
                    ];
                }

                $dataArray[] = [
                    'id'               => $each->module_id,
                    'user_id'          => $each->user_id,
                    'user_name'        => $each->user->name,
                    'request_id'       => $each->request_id,
                    'sl_no'            => $i,
                    'company_name'     => json_encode($each->company),
                    'plant_name'       => json_encode($each->plant),
                    'storage_location' => json_encode($each->storage),
                    'business_area'    => json_encode($each->business),
                    'sales_org'        => json_encode($each->sales_org),
                    'purchase_org'     => json_encode($each->purchase_org),
                    'division'         => json_encode($each->division),
                    'distribution'     => json_encode($each->distributions),
                    'sales_office'     => json_encode($each->sales_office),
                    'po_release'       => json_encode($each->po_release),
                    'status'           => $each->status,
                    'req_log'          => json_encode($request_log),
                    'created_at'       => date('F, d, Y h:i a', strtotime($each->created_at))
                ];

                $i++;
            }

            $subArray[] = [
                'request_id' => $each->request_id,
                'module'     => json_encode($each->modules),
                'tcode'      => json_encode($each->tcodes),
                'action'     => json_encode($each->action),
                'status'     => $each->status
            ];
        }

        return response(['data' => $dataArray, 'subArray' => $subArray, 'message' => 'Success', 'totalCount' => $totalCount], 200);
    }

    public function fetchTeamRequest(Request $request) {

        $user_id = Auth::user()->employee_id;
        $take    = $request->take;
        $skip    = $request->skip;

        $team = EmployeeMappings::where('report_to', $user_id)->select('employee_id')->get();

        $user_ids = [];
        foreach ($team as $each) {
            $user_ids[] = $each->employee_id;
        }

        //return $user_ids;

        $userIds = \Users::whereIn('employee_id', $user_ids)->get();
        $userId  = [];
        foreach ($userIds as $each) {
            $userId[] = $each->id;
        }
        //return $userId;
        $data       = SAPRequest::whereIn('user_id', $userId)->with('approval_logs.created_by_user', 'company', 'plant', 'business', 'storage', 'sales_org', 'sales_office', 'distributions', 'divisions', 'purchase_org', 'po_release', 'modules', 'tcodes', 'action');
        $totalCount = $data->get()->Count();
        $dataArray  = [];
        //return $data->get();
        $subArray = [];
        $i        = 1;
        foreach ($data->take($take)->skip($skip)->get() as $each) {

            $company_name = $each->company['company_name'] ?? '-';
            $company_code = $each['company_code'] ?? '-';
            $flag         = 1;
            if (!empty($dataArray)) {

                //return $dataArray;
                foreach ($dataArray as $key => $value) {
                    if ($value['req_int'] == $each->req_int) {
                        $flag = 0;
                    }
                }
            }

            if ($flag == 1) {

                $request_log = [];
                foreach ($each->approval_logs as $log) {
                    $request_log[] = [
                        'approval_stage' => $log->approval_stage,
                        'created_at'     => date('F, d, Y h:i a', strtotime($log->created_at)),
                        'created_by'     => $log->created_by_user->name
                    ];
                }

                $dataArray[] = [
                    'id'               => $each->module_id,
                    'user_id'          => $each->user_id,
                    'user_name'        => $each->user->name,
                    'request_id'       => $each->request_id,
                    'req_int'          => $each->req_int,
                    'sl_no'            => $i,
                    'company_name'     => json_encode($each->company),
                    'plant_name'       => json_encode($each->plant),
                    'storage_location' => json_encode($each->storage),
                    'business_area'    => json_encode($each->business),
                    'sales_org'        => json_encode($each->sales_org),
                    'purchase_org'     => json_encode($each->purchase_org),
                    'division'         => json_encode($each->division),
                    'distribution'     => json_encode($each->distributions),
                    'sales_office'     => json_encode($each->sales_office),
                    'po_release'       => json_encode($each->po_release),
                    'status'           => $each->status,
                    'req_log'          => json_encode($request_log),
                    'created_at'       => date('F, d, Y h:i a', strtotime($each->created_at))
                ];

                $i++;
            }

            $subArray[] = [
                'request_id' => $each->request_id,
                'module'     => json_encode($each->modules),
                'tcode'      => json_encode($each->tcodes),
                'action'     => json_encode($each->action)
            ];
        }

        return response(['data' => $dataArray, 'subArray' => $subArray, 'message' => 'Success', 'totalCount' => $i], 200);
    }

    public function approveByRM(Request $request) {

        $request_id = $request->request_id;

        try {
            $update = SAPRequest::where('req_int', $request_id)->update([
                'status'     => 1,
                'updated_at' => NOW()
            ]);

            $log = SAPApprovalLogs::insert([
                'request_id'     => $request_id,
                'approval_stage' => 1,
                'created_by'     => Auth::user()->id,
                'created_at'     => NOW(),
                'updated_at'     => NOW()
            ]);

            $req_ca      = SAPRequest::where('req_int', $request_id)->get();
            $created_at  = date('F, d, Y h:i a', strtotime($req_ca[0]->created_at));
            $request_log = [];
            $logs        = SAPApprovalLogs::where('request_id', $request_id)->with('created_by_user')->get();
            foreach ($logs as $log) {
                $request_log[] = [
                    'approval_stage' => $log->approval_stage,
                    'created_at'     => date('F, d, Y h:i a', strtotime($log->created_at)),
                    'created_by'     => $log->created_by_user->name
                ];
            }

            return response(['logs' => $request_log, 'status' => 1, 'created_at' => $created_at], 200);

        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []], 500);
        }

    }
}
