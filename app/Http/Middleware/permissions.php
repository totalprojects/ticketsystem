<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;
use MenuMapping;
use Users;

class permissions {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $rn   = Route::currentRouteName();
        $uid  = \Auth::user()->id;
        $flag = 0;

        $user_menus = MenuMapping::has('menu')->with('menu')->where('user_id', $uid)->get();

        if ($user_menus) {

            foreach ($user_menus as $menu) {

                if ($menu->menu->menu_slug == $rn) {

                    $flag = 1;
                }
            }
        }

        if (!$flag && \Auth::user()->id != 1) {
            abort(403);
        }

        return $next($request);
    }
}
