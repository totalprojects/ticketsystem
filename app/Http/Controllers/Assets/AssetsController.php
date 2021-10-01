<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use AssetsMasters;
use AssetTypes;

class AssetsController extends Controller
{
    //

    public function index() {
        $data['page_title'] = "Assets";
        $assetTypes = AssetTypes::all();
        $data['asset_types'] = $assetTypes;
        return view('assets.index')->with($data);
    }

    public function get(Request $request) {

        # filterations 
        $take = $request->take;
        $skip = $request->skip;

        $assets = AssetsMasters::where('id', '!=', 0)->with('type');
        $totalCount = $assets->get()->Count();
        $assetsChunk = $assets->take($take)->skip($skip)->get();


        return response(['data' => $assetsChunk, 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $description = $request->description;
        $type = $request->type;
        $company = $request->company;
        $specs = $request->specs;
        $sl = $request->sl;
        $issue_date = $request->issue_date;
        $warenty = $request->warrenty;
        $qty = $request->qty;

        try {
            $save = AssetsMasters::create([
                'description' => $description,
                'type' => $type,
                'specifications' => $specs,
                'company' => $company,
                'serial_number' => $sl,
                'issue_date' => $issue_date,
                'warrenty_period' => $warenty,
                'quantity' => $qty,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Asset Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $description = $request->edescription;
        $id = $request->eid;
        $type = $request->etype;
        $company = $request->ecompany;
        $specs = $request->especs;
        $sl = $request->esl;
        $issue_date = $request->eissue_date;
        $warenty = $request->ewarrenty;
        $qty = $request->eqty;


        try {
            $update = AssetsMasters::where('id', $id)->update([
                'description' => $description,
                'type' => $type,
                'specifications' => $specs,
                'company' => $company,
                'serial_number' => $sl,
                'issue_date' => $issue_date,
                'warrenty_period' => $warenty,
                'quantity' => $qty,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Asset Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
