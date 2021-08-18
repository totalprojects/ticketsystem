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
use SalesOffice;
use ActionMasters;

use App\Models\Model\model_has_permissions as UserPermissions;

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

        return view('request.sap.index')->with(['companies' => $companies, 'divisions' => $divisions, 'distributors' => $distributions, 'business' => $business, 'po' => $po, 'po_release' => $po_release]);
    }

    public function modulesAndTCodes(Request $request) {

        $user_id = Auth::user()->id;

        $permissions    = UserPermissions::where('model_id', $user_id)->select('permission_id')->get();
        $permission_ids = [];
        foreach ($permissions as $permission) {
            $permission_ids[] = $permission->permission_id;
        }
        //$modulewithTcodes = TCodes::whereIn('permission_id', $permission_ids)->with('permission', 'action_details')->orderBy('permission_id', 'asc')->get();
        $modulewithTcodes = Permission::whereIn('id', $permission_ids)->with('tcodes.action_details')->groupBy('id')->orderBy('id', 'asc')->get();

        $modules      = [];
        $tcodes       = [];
        $i            = 1;
        $j            = 1;
        $grandChildId = 1;
        //return response(['data' => $modulewithTcodes], 200);
        foreach ($modulewithTcodes as $each) {

            $modules[] = [
                'n_id'       => $grandChildId,
                'n_title'    => $each->name,
                'n_parentid' => 0,
                'n_addional' => ['permission_id' => $each->id],
                'n_checked'  => false
            ];

            $childId1 = $grandChildId;
            $grandChildId += 1;

            foreach ($each->tcodes as $codes) {

                $modules[] = [
                    'n_id'       => $grandChildId,
                    'n_title'    => $codes->description . '(' . $codes->t_code . ')',
                    'n_parentid' => $childId1,
                    'n_addional' => ['tcode_id' => $codes->id]
                ];

                $childId2 = $grandChildId;
                $grandChildId += 1;

                foreach ($codes->action_details as $eachAction) {
                    $modules[] = [
                        'n_id'       => $grandChildId,
                        'n_title'    => $eachAction->name,
                        'n_parentid' => $childId2,
                        'n_addional' => ['action_id' => $eachAction->id]
                    ];

                    $grandChildId++;
                }

            }
        }

        return response(['data' => $modules], 200);
    }

    public function saveRequest(Request $request) {

        $modules = json_decode($request->module);
        $user_id = Auth::user()->id;
        // return $modules;
        $company_name = array_map('intval', $request->company_name);
        $requestId    = 'RN' . $user_id . '/' . date('y') . '/' . date('m') . '/' . time();
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
                    'user_id'         => $user_id,
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

        $htmlTable = "<table class='table table-bordered table-responsive' style='max-width:100%; overflow:auto'><thead><th>Company</th>
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

        $data       = SAPRequest::where('user_id', $user_id)->with('company', 'plant', 'business', 'storage', 'sales_org', 'sales_office', 'distributions', 'divisions', 'purchase_org', 'po_release', 'modules', 'tcodes', 'action');
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

            // if ($flag == 1) {

            $dataArray[] = [
                'id'               => $each->module_id,
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
                'module'           => json_encode($each->modules),
                'tcode'            => json_encode($each->tcodes),
                'action'           => json_encode($each->action),
                'status'           => $each->status
            ];

            $i++;
            // }

            $subArray[] = [
                'id'     => $each->module_id,
                'module' => json_encode($each->modules),
                'tcode'  => json_encode($each->tcodes),
                'action' => json_encode($each->action),
                'status' => $each->status
            ];
        }

        return response(['data' => $dataArray, 'subArray' => $subArray, 'message' => 'Success', 'totalCount' => $totalCount], 200);
    }
}
