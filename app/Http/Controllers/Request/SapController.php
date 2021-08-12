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
                    'actions'         => json_encode($actions),
                    'status'          => 0,
                    'created_at'      => NOW(),
                    'updated_at'      => NOW()
                ];

                SAPRequest::create($array);

            }

        }

        return response(['message' => 'success'], 200);
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

            if ($flag == 1) {

                $dataArray[] = [
                    'id'               => $each->module_id,
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
                    'po_release'       => json_encode($each->po_release)
                ];

                $i++;
            }

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
