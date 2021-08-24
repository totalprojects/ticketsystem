<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tcodes;
use StandardTCodes;
use Permission;

class MigrationController extends Controller {
    //
    /**
     * Migrate Standard Tcodes from Tcode masters
     */
    public function tcodeMigrate() {
        $modulewithTcodes = Permission::with(['tcodes' => function ($Q) {
            $Q->with('action_details');
        }])->get()->map(function ($each) {
            $each->tcodes = $each->tcodes->take(30)->skip(0);

            foreach ($each->tcodes as $codes) {
                $insertArray = [
                    'permission_id' => $each->id,
                    't_code'        => $codes->t_code,
                    'description'   => $codes->description,
                    'actions'       => $codes->actions,
                    'created_at'    => NOW(),
                    'updated_at'    => NOW()
                ];

                StandardTCodes::create($insertArray);
            }
        });
    }
}
