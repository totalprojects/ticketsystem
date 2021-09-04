<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storages;
use Plants;

class StorageController extends Controller {

    public function getStorages(Request $request) {

        $plant_id = $request->plant_id;
        $Storage  = [];
        if (is_array($plant_id)) {
            $Storage = Storages::whereIn('plant_code', $plant_id)->get();
        }

        return response(['data' => $Storage], 200);

    }
    public function index() {
        $plants = Plants::all();
        $data['page_title'] = 'Storage';
        $data['plants'] = $plants;
        return view('storages.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $storages = Storages::orderBy('id', 'asc')->with('plant');
        $totalCount = $storages->get()->Count();
        return response(['data' => $storages->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $storage_name = $request->storage_name;
        $storage_code = $request->storage_code;
        $plant_code = $request->plant_id;

        try {
            $deaprtment_name = Storages::create([
                'storage_description' => $storage_name,
                'storage_code' => $storage_code,
                'plant_code'  => $plant_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Storage Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $storage_name = $request->estorage_name;
        $storage_code = $request->estorage_code;
        $storage_id = $request->estorage_id;
        $plant_code = $request->eplant_id;

        try {
            $update = Storages::where('id', $storage_id)->update([
                'storage_description' => $storage_name,
                'plant_code' => $plant_code,
                'storage_code' => $storage_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Storage Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
