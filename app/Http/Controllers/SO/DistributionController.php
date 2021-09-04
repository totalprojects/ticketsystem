<?php

namespace App\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Distributions;

class DistributionController extends Controller
{
    //
    public function index() {
        $data['page_title'] = 'Distribution Channel';
       
        return view('distribution_channels.index')->with($data);
    }

    public function get(Request $request) {

        $take = $request->take ?? 10000;
        $skip = $request->skip ?? 0;
        $companies = Distributions::orderBy('id', 'asc');
        $totalCount = $companies->get()->Count();
        return response(['data' => $companies->get(), 'totalCount' => $totalCount]);
    }

    public function create(Request $request) {

        $distribution_channel_description = $request->distribution_channel_description;
        $distribution_channel_code = $request->distribution_channel_code;

        try {
            $deaprtment_description = Distributions::create([
                'distribution_channel_description' => $distribution_channel_description,
                'distribution_channel_code' => $distribution_channel_code,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Distribution Channel Added Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request) {

        $distribution_channel_description = $request->edistribution_channel_description;
        $distribution_channel_code = $request->edistribution_channel_code;
        $distribution_channel_id = $request->edistribution_channel_id;

        try {
            $update = Distributions::where('id', $distribution_channel_id)->update([
                'distribution_channel_description' => $distribution_channel_description,
                'distribution_channel_code' => $distribution_channel_code,
                'updated_at' => NOW()
            ]);

            return response(['message' => 'Distribution Channel Updated Successfully', 'status' => 200], 200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
