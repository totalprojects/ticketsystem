<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MenuMapping;

class MenuMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dataset = [
            'menu_id' => 4,
            'sub_menu_id'=>0,
            'user_id'=>1,
            'status'=>1
        ];

        MenuMapping::create($dataset);
    }
}
