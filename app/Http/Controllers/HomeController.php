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
        $logs = \SAPApprovalLogs::selectRaw('DATE(created_at) as datetime, count(*) as total_approvals, status')->groupBy(\DB::raw('DATE(created_at), status'))->orderBy(\DB::raw('DATE(created_at)', 'asc'))->limit(30)->get();
        $dataSetA = [];
        $dataSetR = [];
    
        foreach($logs as $each) {
            if($each->status == 1) {
                $dataSetA[] = [
                    'x' => date('F, d', strtotime($each->datetime)),
                    'y' => $each->total_approvals
                ];
            } else {
                $dataSetR[] = [
                    'x' => date('F, d', strtotime($each->datetime)),
                    'y' => $each->total_approvals
                ];
            }
                           
        }

        return response(['approval_set' => $dataSetA, 'rejection_set' => $dataSetR]);
    
    }

    public function approvalCounts(Request $request) {

        $sap_logs = \SAPApprovalLogs::select('id')->get()->Count();
        $crm_logs = 0;
        $email_logs = 0;
        
        return response(['sap' => $sap_logs, 'email' => $email_logs, 'crm' => $crm_logs]);
    }

    public function requestCounts(Request $request) {

        $sap_logs = \SAPRequest::select('id')->get()->Count();
        $crm_logs = 0;
        $email_logs = 0;
        
        return response(['sap' => $sap_logs, 'email' => $email_logs, 'crm' => $crm_logs]);
    }

    public function logStatus(Request $request) {
        $sap_requests = \SAPRequest::selectRaw('count(*) as total, status')->groupBy(\DB::raw('status'))->get();
        $statusArray['pending'] = 0;
        $statusArray['approved'] = 0;
        $statusArray['rejected'] = 0;

        foreach($sap_requests as $each) {

                switch($each->status) {
                    case 0:
                        // pending
                        $statusArray['pending'] = $each->total;
                    break;
                    case 1:
                        // pending
                        $statusArray['approved'] = $each->total;
                    break;
                    case 2:
                        // pending
                        $statusArray['rejected'] = $each->total;
                    break;

                }
        }

        return response($statusArray, 200);
    }
}
