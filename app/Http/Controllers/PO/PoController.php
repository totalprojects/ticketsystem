<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PO;

class PoController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'Purchase Organization';
      
        return view('po.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = PO::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $po_name = $request->po_name;
        $po_code = $request->po_code;
       
        try {
            $deaprtment_description = PO::create([
                'po_name' => $po_name,
                'po_code' => $po_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Purchase Org Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $po_name = $request->epo_name;
        $po_code = $request->epo_code;
        $so_id = $request->eso_id;


        try {
            $update = PO::where('id', $so_id)->update([
                'po_name' => $po_name,
                'po_code' => $po_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Purchase Org Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
