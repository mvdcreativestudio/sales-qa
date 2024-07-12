<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('company_settings')->insert([
          'id'      => 1,
          'name'    => 'MVD Studio',
      ]);
    }

    /**
     * Reverse the database seeds.
     *
     * @return void
    */
    public function down()
    {
      DB::table('ecommerce_settings')->truncate();
    }
}
