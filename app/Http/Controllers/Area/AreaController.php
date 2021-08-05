<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StateMasters;
use DistrictMasters;

class AreaController extends Controller {
    //

    public function fetchStates(Request $request) {

        $states = StateMasters::all();

        return response(['states' => $states], 200);
    }

    public function fetchDistricts(Request $request) {

        $districts = DistrictMasters::where('state_id', $request->state_id)->get();

        return response(['districts' => $districts], 200);
    }
}
