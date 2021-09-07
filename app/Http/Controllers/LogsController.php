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
            $logs = LoginLog::with('user')->latest()->get();
            $dataArray = [];
            foreach($logs as $log) {
                $dataArray[] = [
                    'id' => $log->id,
                    'username' => $log->user->name,
                    'ip' => $log->ip,
                    'agent' => $log->agent,
                    'created_at' => date('d-m-Y h:i a', strtotime($log->created_at))
                ];
            }
            return response(['message' => 'Success', 'data' => $dataArray],200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []],500);
        }        
    }

    public function fetch_visit_logs() {
        $userID = Auth::user()->id;
        try {
            $logs = ActivityLog::where(['visit_type' => 0])->with('user')->latest()->get();
            $dataArray = [];
            foreach($logs as $log) {
                $dataArray[] = [
                    'id' => $log->id,
                    'username' => $log->user->name,
                    'activity_type' => $log->activity_type,
                    'ip' => $log->ip,
                    'created_at' => date('d-m-Y h:i a', strtotime($log->created_at))
                ];
            }
            return response(['message' => 'Success', 'data' => $dataArray],200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []],500);
        }      
    }

    public function fetch_activity_logs() {
        $userID = Auth::user()->id;
        try {
            $logs = ActivityLog::where(['visit_type' => 1])->with('user')->latest()->get();
            $dataArray = [];
            foreach($logs as $log) {
                $dataArray[] = [
                    'id' => $log->id,
                    'username' => $log->user->name,
                    'activity_type' => $log->activity_type,
                    'ip' => $log->ip,
                    'created_at' => date('d-m-Y h:i a', strtotime($log->created_at))
                ];
            }
            return response(['message' => 'Success', 'data' => $dataArray],200);

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'data' => []],500);
        }      
    }
}
