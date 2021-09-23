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
        $logs = \SAPApprovalLogs::selectRaw('DATE(created_at) as datetime, count(*) as total_approvals')->groupBy(\DB::raw('DATE(created_at)'))->orderBy(\DB::raw('DATE(created_at)', 'asc'))->limit(30)->get();
        $dataSet = [];

        foreach($logs as $each) {
           
                            $dataSet[] = [
                                'x' => date('F, d', strtotime($each->datetime)),
                                'y' => $each->total_approvals
                            ];
        }

        return $dataSet;

    
    }
}
