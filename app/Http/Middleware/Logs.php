<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;

class Logs {
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
        $activity_name = $activity_name[1] ?? 'No activity detected';
        try {

            \ActivityLog::create([
                'user_id'          => \Auth::user()->id,
                'activity_type'    => 'Visited ' . ucwords(str_replace("/", " ", str_replace("-", " ", $activity_name))) . ' Page',
                'description_meta' => json_encode([])
            ]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            abort(500);
        }

        return $next($request);
    }
}
