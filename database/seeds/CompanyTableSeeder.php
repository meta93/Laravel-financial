<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'compCode' => 11001,
            'compName' => 'ABC Company',
            'state' => 'Dhaka',
            'city' => 'Dhaka',
            'country'=>'Bangladesh',
            'postCode'=>'1200',
            'phoneNo'=>'8801712144474',
            'email'=>'admin@gmail.com',
            'currency'=>'USD'
        ]);

        DB::table('basic_properties')->insert([
            'compCode' => 11001,
            'fpStart' => '2018-01-01'
        ]);
    }
}
