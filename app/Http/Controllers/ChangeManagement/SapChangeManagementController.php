<?php

namespace App\Http\Controllers\ChangeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SapChangeManagementController extends Controller
{
    //
    public function index() {
        $data['pag_title'] = "SAP Change Management";

        return view('change_management.sap.index')->with($data);
    }
}
