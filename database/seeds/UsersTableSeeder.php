<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'role_id'=>'1',
        	'name'=>'Shohel Rana',
        	'username'=>'shohel',
        	'email'=>'gsshohel1314@gmail.com',
        	'password'=>bcrypt('11111111'),
        ]);

        DB::table('users')->insert([
        	'role_id'=>'2',
        	'name'=>'Nasim Kobir',
        	'username'=>'nasim',
        	'email'=>'nasim@gmail.com',
        	'password'=>bcrypt('11111111'),
        ]);
    }
}
