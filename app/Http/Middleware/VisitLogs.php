<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;

class VisitLogs {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        $rn            = $request->path();
        $activity_name = explode('/', $rn);
        $activity = 'Visited ';
        foreach($activity_name as $each) {
            
            $activity .= $each. ' ';
        }
        
        try {

            \ActivityLog::create([
                'user_id'          => \Auth::user()->id,
                'activity_type'    => ucwords($activity),
                'ip'               => $request->ip(),
                'visit_type' => 0,
                'description_meta' => json_encode([])
            ]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            abort(500);
        }

        return $next($request);
    }
}
