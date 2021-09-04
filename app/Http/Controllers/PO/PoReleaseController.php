<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PORelease;

class PoReleaseController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'PO Releaseanization';
      
        return view('po_release.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = PORelease::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $rel_description = $request->rel_description;
        $rel_code = $request->rel_code;
       
        try {
            $deaprtment_description = PORelease::create([
                'rel_description' => $rel_description,
                'rel_code' => $rel_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'PO Release Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $rel_description = $request->erel_description;
        $rel_code = $request->erel_code;
        $so_id = $request->eso_id;


        try {
            $update = PORelease::where('id', $so_id)->update([
                'rel_description' => $rel_description,
                'rel_code' => $rel_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'PO Release Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
