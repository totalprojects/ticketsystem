<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TreeController extends Controller {
    //
    public function index() {
        // ->with('employee:id,first_name,last_name', 'report_employee:id,first_name,last_name')
        $reportings = \EmployeeMappings::selectRaw('employee_id, report_to')->with('employee:id,first_name,last_name', 'report_employee:id,first_name,last_name')->orderBy('report_to')->get();

        $reportingArray = [];

        foreach ($reportings as $reporting) {
            $reportingArray[] = [
                'employee_id'        => $reporting->employee_id,
                'report_to'          => $reporting->report_to,
                'emp_name'           => $reporting->employee->first_name . ' ' . $reporting->employee->last_name,
                'reporting_emp_name' => $reporting->report_employee->first_name . ' ' . $reporting->report_employee->last_name
            ];
        }
        // return $reportingArray;

        return view('tree.index')->with(['reportings' => $reportingArray]);
    }
}
