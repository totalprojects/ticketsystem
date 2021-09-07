<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LoginLog;
use ActivityLog;
use Auth;

class LogsController extends Controller
{
    //
    public function login() {
        
        $data['page_title'] = 'Login Logs';
        return view('logs.login')->with($data);
    }

    public function visits() {
        $data['page_title'] = 'Visit Logs';
        return view('logs.visits')->with($data);
    }

    public function activities() {
        $data['page_title'] = 'Activity Logs';
        return view('logs.activities')->with($data);
    }


    public function fetch_login_logs() {
        $userID = Auth::user()->id;
        try {
            $logs = LoginLog::where('user_id', $userID)->with('user')->latest()->get();
            return response(['message' => 'Success', 'data' => $logs],200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []],500);
        }        
    }

    public function fetch_visit_logs() {
        $userID = Auth::user()->id;
        try {
            $logs = ActivityLog::where(['visit_type' => 0])->with('user')->latest()->get();
            return response(['message' => 'Success', 'data' => $logs],200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []],500);
        }      
    }

    public function fetch_activity_logs() {
        $userID = Auth::user()->id;
        try {
            $logs = ActivityLog::where(['visit_type' => 1])->with('user')->latest()->get();
            return response(['message' => 'Success', 'data' => $logs],200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []],500);
        }      
    }
}
