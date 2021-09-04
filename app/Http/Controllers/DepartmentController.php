<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DepartmentMasters;

class DepartmentController extends Controller
{
    //
    public function index() {

        return view('departments.index');
    }

    public function get(Request $request) {

        $departments = DepartmentMasters::all();

        return response(['data' => $departments, 'totalCount' => $departments->Count()]);
    }
}
