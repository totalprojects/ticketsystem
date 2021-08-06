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
}
