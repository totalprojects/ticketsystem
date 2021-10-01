<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Employees;
use EmployeeMappings;
use CompanyMasters;
use DepartmentMasters;
use App\Models\Role\roles_has_permission as RolePermissions;
use Designations;

class EmployeeController extends Controller {

    // view for employee list
    public function index() {

        $data['page_title'] = "Employee Lists";
        $up                 = \Auth::user()->getAllPermissions();
        $add_permission     = false;
        $edit_permission    = false;
        $create_permission  = false;

        $designations = Designations::all();

        foreach ($up as $e) {

            if ($e->name == 'Add User') {
                $add_permission = true;
            }

            if ($e->name == 'Edit User') {
                $edit_permission = true;
            }

            if ($e->name == 'Create User') {
                $create_permission = true;
            }
        }

        $data['designations'] = $designations;

        $data['permission'] = ['create' => $create_permission, 'add' => $add_permission, 'edit' => $edit_permission];

        return view('employees.index')->with($data);
    }

    public function profile(Request $request) {
        
        $data['page_title'] = "Employee Profile";

        $id = base64_decode($request->id);

        $empData = Employees::where('id', $id)->with(
         'departments',
         'state',
         'district',
         'company',
         'report_to.report_employee',
         'assets.asset.type',
        //  'user.alloted_permissions.permission.allowed_tcodes.tcode',
        //  'user.alloted_permissions.permission.allowed_tcodes.access_action_details',
        //  'user.alloted_permissions.permission.allowed_tcodes.critical'
        'email_access.provider',
        'software_access.software'
         )->get()->toArray();
       // return $empData;
        return view('employees.profile.index')->with($empData);
    }

    public function sap_report() {

        $data['page_title'] = "SAP Report";

        return view('user_sap_access_report.index')->with($data);
    }
    public function fetchEmployeeSAPReport(Request $request) {

        $empData = Employees::with(
            'departments',
            'designation',
            'company',
            'report_to.report_employee',
            'assets.asset.type',
            'user.alloted_permissions.permission.allowed_tcodes.tcode',
            'user.alloted_permissions.permission.allowed_tcodes.access_action_details',
            'user.alloted_permissions.permission.allowed_tcodes.critical',
            'user.alloted_permissions.permission.module_head.user_details',
            'email_access.provider',
            'software_access.software'
            )->where('id', '!=', 1)->get();

            $dataArray = [];
            $subData = [];
          //  return $empData;
            foreach($empData as $each) {

                $dataArray[] = [
                    'id' => $each->id,
                    'first_name' => $each->first_name,
                    'last_name' => $each->last_name,
                    'sap_code' => $each->sap_code,
                    'department' => $each->departments->department_name,
                    'company' => $each->company->company_name,
                    'designation' => $each->designation->designation_name ?? '-',
                    'report_to' => !is_null($each->report_to) ? $each->report_to->report_employee->first_name.' '.$each->report_to->report_employee->last_name : '-'
                ];
                if(count($each->user->alloted_permissions)>0) 
                    {
                foreach($each->user->alloted_permissions as $permission) {

                    $module_name = $permission->permission->name;
                    $module_head = $permission->permission->module_head;
                    $tcodes1 = '';
                    if(count($permission->permission->allowed_tcodes)>0) 
                    {
                        foreach($permission->permission->allowed_tcodes as $tcodes) {
                            $actions1 = '';
                            $tcodes1 .= $tcodes->tcode->t_code.', ';
                            if(isset($tcodes->access_action_details)) {
                                foreach($tcodes->access_action_details as $actions) {
                                    $actions1 .= $actions->name.', ';
                                }
                            }
                           
                        }
                        
                        $subData[] = [
                            'id' => $each->id,
                            'module' => $module_name,
                            'module_head' => $module_head,
                            'tcodes' => substr($tcodes1, 0, -2),
                            'actions' => substr($actions1, 0, -2)
                        ];
                    }
                

                }
            }
            }

            return response(['data' => $dataArray, 'subData' => $subData, 'totalCount' => count($dataArray)]);
    }

    // fetch employees
    public function fetchEmployees(Request $request) {

        $employee_model = Employees::with('departments', 'state', 'district', 'company', 'report_to.report_employee', 'assets.asset.type')->get();

        $dataArray = [];

        return $employee_model;

    }

    public function fetchCompanies(Request $request) {

        $companies = CompanyMasters::all();

        return response(['companies' => $companies]);
    }

    public function fetchDepartments(Request $request) {

        $departments = DepartmentMasters::all();

        return response(['departments' => $departments]);
    }

    public function fetchRoles(Request $request) {

        $departments = \Role::whereNotIn('id', [3])->get();

        return response(['roles' => $departments]);
    }

    public function fetchReportTos(Request $request) {

        $dept = $request->dept_id;

        $data = Employees::where('department_id', $dept)->with('departments')->select('id', 'first_name', 'last_name', 'department_id')->get();

        $report_tos_array = [];

        foreach ($data as $each) {
            $report_tos_array[] = [
                'id'   => $each->id,
                'name' => $each->first_name . ' ' . $each->last_name . '(' . $each->departments->department_name . ')'
            ];
        }

        return response(['report_to' => $report_tos_array]);
    }

    // add employee
    public function addEmployee(Request $request) {

        //return $request->all();

        $result = Employees::where('email', $request->email)->orWhere('contact_no', $request->contact_no)->get();

        if ($result->Count() == 0) {

            $employeeArray = [
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'sap_code'      => $request->sap_code,
                'contact_no'    => $request->contact_no,
                'state_id'      => $request->state,
                'district_id'   => $request->district,
                'company_code'  => $request->company_code,
                'department_id' => $request->department,
                'pincode'       => $request->pincode,
                'address'       => $request->address
            ];

            try {

                $employee = Employees::create($employeeArray);
                if($employee) {

                    $eid      = Employees::where('email', $request->email)->first();
                    $id       = $eid->id;
                    //return $id;
                    if($id==0) {
                        return response(['message' => 'Something went wrong id could not be retrived', 'status' => 500], 500);
                    }
                    if (!empty($request->reporting_to)) {

                        $employeeMappingArray = [
                            'employee_id' => $id,
                            'report_to'   => $request->reporting_to
                        ];
                        // add report_to information
                        EmployeeMappings::create($employeeMappingArray);
                    }

                    $newUser = \Users::create([
                        'name'        => $request->first_name . ' ' . $request->last_name,
                        'email'       => $request->email,
                        'password'    => \Hash::make('password'),
                        'employee_id' => $id
                    ]);

                    $newUser->syncRoles([$request->role]);

                    # provide dashboard access (10)
                    \MenuMapping::create([
                        'menu_id'     => 10,
                        'user_id'     => $newUser->id,
                        'status'      => 1,
                        'sub_menu_id' => 0
                    ]);
                }
                # mail functions

                return response(['message' => 'Employee Added and User Created Successfully', 'status' => 200], 200);

            } catch (\Exception $e) {

                return response(['message' => $e->getMessage()], 500);
            }

        } else {

            return response(['message' => 'Employee Already Exists', 'status' => 201]);
        }

    }

    // edit employee
    public function editEmployee(Request $request) {

        //return $request->all();

        $result = Employees::where('id', $request->id1)->get();

        if ($result->Count() == 1) {

            $employeeArray = [
                'first_name'    => $request->first_name1,
                'last_name'     => $request->last_name1,
                'email'         => $request->email1,
                'sap_code'      => $request->sap_code1,
                'contact_no'    => $request->contact_no1,
                'state_id'      => $request->state1,
                'district_id'   => $request->district1,
                'company_code'  => $request->company_code1,
                'department_id' => $request->department1,
                'pincode'       => $request->pincode1,
                'address'       => $request->address1
            ];

            try {

                $employee = Employees::where('id', $request->id1)->update($employeeArray);

                $user = \Users::where('email', $request->email1)->get();
                //return dd($user);
                $id = $request->id1;

                $update = \Users::where('employee_id', $id)->update([
                    'name' => $request->first_name1. ' '.$request->last_name1,
                    'email' => $request->email1
                ]);
                

                //$user->syncRoles($request->role1);
                // $permissions  = RolePermissions::where('role_id', $request->role1)->get();
                // $permissions1 = [];
                // foreach ($permissions as $permission) {
                //     $permissions1[] = $permission->permission_id;
                // }
                // $user->syncPermissions($permissions1);

                if (!empty($request->reporting_to1)) {

                    $id                   = $request->id1;
                    $employeeMappingArray = [
                        'employee_id' => $id,
                        'report_to'   => $request->reporting_to1
                    ];
                    // add report_to information
                    $emp = EmployeeMappings::where('employee_id', $id)->first();
                    if ($emp) {
                        EmployeeMappings::where('employee_id', $id)->update($employeeMappingArray);
                    } else {
                        EmployeeMappings::create($employeeMappingArray);
                    }
                }

                return response(['message' => 'Employee Edited Successfully', 'status' => 200], 200);

            } catch (\Exception $e) {

                return response(['message' => "Inthis " . $e->getMessage()], 500);
            }

        } else {

            return response(['message' => 'Employee Already Exists', 'status' => 201]);
        }

    }

}
