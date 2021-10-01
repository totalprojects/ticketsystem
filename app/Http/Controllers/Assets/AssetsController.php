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
}
