<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use POGroup;

class PoGroupController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'PO Group';
      
        return view('po.group')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = POGroup::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $pg_description = $request->pg_description;
        $pg_code = $request->pg_code;
       
        try {
            $deaprtment_description = POGroup::create([
                'pg_description' => $pg_description,
                'pg_code' => $pg_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'PO Group Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $pg_description = $request->epg_description;
        $pg_code = $request->epg_code;
        $so_id = $request->eso_id;


        try {
            $update = POGroup::where('id', $so_id)->update([
                'pg_description' => $pg_description,
                'pg_code' => $pg_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'PO Group Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
