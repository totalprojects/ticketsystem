<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MenuMaster;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $menus = [
        //     'menu_name' => 'Roles',
        //     'menu_slug' => 'roles.view',
        //     'icon' => 'fas fa-key'
        // ];
        // $menus = [
        //     'menu_name' => 'Users',
        //     'menu_slug' => 'users.view',
        //     'icon' => 'fas fa-users'
        // ];
        $menus = [
            'menu_name' => 'Change Password',
            'menu_slug' => 'user.change.password',
            'icon' => 'fas fa-key'
        ];

        MenuMaster::create($menus);
    }
}
