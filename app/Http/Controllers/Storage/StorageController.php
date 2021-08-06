<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storages;

class StorageController extends Controller {
    public function getStorages(Request $request) {

        $plant_id = $request->plant_id;
        $Storage  = [];
        if (is_array($plant_id)) {
            $Storage = Storages::whereIn('plant_code', $plant_id)->get();
        }

        return response(['data' => $Storage], 200);

    }
}
