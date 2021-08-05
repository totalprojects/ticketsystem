<?php

namespace App\Http\Controllers\Plant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CompanyMasters;
use Plants;
use SO;

class PlantController extends Controller {
    //

    public function getPlants(Request $request) {

        $company_id = $request->company_id;
        $plants     = [];
        $sos        = [];

        if (is_array($company_id)) {
            $plants = Plants::whereIn('company_code', $company_id)->get();

            $sos = SO::whereIn('company_code', $company_id)->get();
        }

        return response(['data' => $plants, 'so' => $sos], 200);

    }
}
