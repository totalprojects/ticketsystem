<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use MenuMapping;
use MenuMaster;
use Auth;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events) {

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Add some items to the menu...
            //$event->menu->add('MAIN NAVIGATION');
            $uid         = Auth::user()->id;
            $menu_access = MenuMaster::whereHas('menu_mappings', function ($Q) use ($uid) {
                $Q->where('user_id', $uid);

            })->where('status', 1)->orderBy('menu_order', 'asc');
            $menus           = [];
            $in              = 0;
            $iterate         = 0;
            $nxt_flag        = 0;
            $child_displayed = [];

            foreach ($menu_access->get() as $menu) {

                if ($menu->parent_id > 0) {

                    if (isset($child_displayed[$menu->parent_id])) {
                        if ($child_displayed[$menu->parent_id] == $menu->parent_id) {
                            continue;
                        }
                    }
                    $parent = MenuMaster::where('id', $menu->parent_id)->whereHas('menu_mappings', function ($Q) use ($uid) {
                        $Q->where('user_id', $uid);

                    })->where('status', 1)->orderBy('menu_order', 'asc')->first();
                    if (!$parent) {
                        continue;
                    }
                    $menus = [
                        'id'   => $parent->id,
                        'text' => $parent->menu_name ?? '-',
                        'icon' => $parent->icon ?? '-'
                    ];

                    $submenus = MenuMaster::where('parent_id', $menu->parent_id)->whereHas('menu_mappings', function ($Q) use ($uid) {
                        $Q->where('user_id', $uid);
                    })->where('status', 1)->orderBy('menu_order', 'asc')->get();

                    $i = 0;

                    foreach ($submenus as $sm) {

                        $menus['submenu'][$i] = [
                            'text' => $sm->menu_name,
                            'icon' => $sm->icon
                        ];
                        if (!empty($sm->menu_slug) && $sm->menu_slug != 'null') {

                            $menus['submenu'][$i]['route'] = $sm->menu_slug;
                        }

                        $i++;
                    }

                    if (!empty($parent->menu_slug) && $parent->menu_slug != 'null') {

                        $menus['route'] = $parent->menu_slug;
                    }

                    $child_displayed[$menu->parent_id] = true;

                    $event->menu->add($menus);

                } else {

                    $menus = [
                        'text' => $menu->menu_name ?? '-',
                        'icon' => $menu->icon ?? '-'
                    ];

                    if (!empty($menu->menu_slug) && $menu->menu_slug != 'null') {

                        $menus['route'] = $menu->menu_slug;
                    }
                    $event->menu->add($menus);
                }

            }

        });

        Schema::defaultStringLength(191);
    }
}
