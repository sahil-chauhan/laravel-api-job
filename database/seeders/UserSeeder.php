<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'user_name' => 'admin_one',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'user_role' => 'admin'
            ]
        ];

        if( DB::table('users')->get()->count() == 0  )
        {
            DB::table('users')->insert($data);
        }
    }
}
