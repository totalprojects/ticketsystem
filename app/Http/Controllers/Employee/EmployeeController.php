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

    // fetch employees
    public function fetchEmployees(Request $request) {

        $employee_model = Employees::with('departments', 'state', 'district', 'company', 'report_to.report_employee')->get();

        $dataArray = [];

        return $employee_model;

    }

    public function createEmployee(Request $request) {

        $result = Employees::where('email', $request->email1)->orWhere('contact_no', $request->contact_no1)->get();

        if ($result->Count() == 1) {

            $employeeArray = [
                'first_name'    => $request->first_name1,
                'last_name'     => $request->last_name1,
                'email'         => $request->email1,
                'contact_no'    => $request->contact_no1,
                'state_id'      => $request->state1,
                'district_id'   => $request->district1,
                'company_code'  => $request->company_code1,
                'department_id' => $request->department1,
                'pincode'       => $request->pincode1,
                'address'       => $request->address1,
                'status'        => 2
            ];

            try {

                $employee = Employees::where('email', $request->email1)->update($employeeArray);

                $eid = Employees::where('email', $request->email1)->first();

                if (!empty($request->reporting_to1)) {

                    $id                   = $eid->id;
                    $employeeMappingArray = [
                        'employee_id' => $id,
                        'report_to'   => $request->reporting_to1
                    ];

                    $maps = EmployeeMappings::where($employeeMappingArray)->get();

                    if ($maps->Count() == 0) {

                        EmployeeMappings::where('employee_id', $id)->delete();
                        EmployeeMappings::create($employeeMappingArray);
                    }

                    // $newUser = \Users::create([
                    //     'name'        => $request->first_name1 . ' ' . $request->last_name1,
                    //     'email'       => $request->email1,
                    //     'password'    => \Hash::make('password'),
                    //     'employee_id' => $id
                    // ]);

                    return response(['message' => 'User Updated Successfully', 'status' => 200], 200);

                } else {
                    return response(['message' => 'Reporting to is missing', 'status' => 500], 500);
                }

            } catch (\Exception $e) {

                return response(['message' => $e->getMessage()], 500);
            }

        } else {

            return response(['message' => 'Employee Already Exists', 'status' => 201]);
        }

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
                $eid      = Employees::where('email', $request->email)->first();
                $id       = $eid->id;
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

                $user = \Users::where('email', $request->email1)->first();
                //return dd($user);
                $id = $request->id1;

                if (!$user) {
                    $user = \Users::create([
                        'name'        => $request->first_name1 . ' ' . $request->last_name1,
                        'email'       => $request->email1,
                        'password'    => \Hash::make('password'),
                        'employee_id' => $id
                    ]);
                }

                $user->syncRoles($request->role1);
                $permissions  = RolePermissions::where('role_id', $request->role1)->get();
                $permissions1 = [];
                foreach ($permissions as $permission) {
                    $permissions1[] = $permission->permission_id;
                }
                $user->syncPermissions($permissions1);

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
