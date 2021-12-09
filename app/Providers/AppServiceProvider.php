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
            })->select('id', 'parent_id', 'menu_name', 'icon', 'menu_slug')->where('status', 1)->orderBy('menu_order', 'asc');
            $dataset         = $menu_access->get();
            $renderMenuArray = build_menu($dataset);
            $event->menu->add(...$renderMenuArray);

        });

        Schema::defaultStringLength(191);
    }

}
