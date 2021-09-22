<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        //$user->syncRoles([]);
        //$user->givePermissionTo('edit articles');
        //return $user->getPermissionNames();
        $loginLogs = \LoginLog::where('user_id', $user->id)->latest()->get();
        //return $loginLogs;
        $data['login_logs'] = $loginLogs;
        return view('home')->with($data);
    }

    public function approvalTimeAnalytics(Request $request) {

        // find out the difference time span betweeen approvals and request place
        $logs = \SAPApprovalLogs::orderBy(\DB::raw('DATE(created_at)', 'desc'))->limit(30)->get();
        $dataSet = [];

        foreach($logs as $each) {
            $date1=date_create(date('Y-m-d h:i:s a', strtotime($each->created_at)));
            $date2=date_create(date('Y-m-d h:i:s a', strtotime($each->updated_at)));
            //echo date('Y-m-d', strtotime($each->created_at)). ' ----- '.date('Y-m-d', strtotime($each->updated_at)).PHP_EOL;
            $interval = date_diff($date1,$date2);
            $difference = $interval->format('%i');
            if(!empty($dataSet)) {
                foreach($dataSet as $e) {
                    if($e['x'] != date('Y-m-d', strtotime($each->created_at))) {
                        if($difference>0) {
                            $dataSet[] = [
                                'x' => date('Y-m-d', strtotime($each->created_at)),
                                'y' => $difference
                            ];
                        }
                    }
                }
            } else {
                
                if($difference>0) {
                    $dataSet[] = [
                        'x' => date('Y-m-d', strtotime($each->created_at)),
                        'y' => $difference
                    ];
                }
            }
          
           
        }

        return $dataSet;

    
    }
}
