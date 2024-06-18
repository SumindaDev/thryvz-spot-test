<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("SET foreign_key_checks = 0");
        DB::table('users')->truncate();
        
        //system user
        DB::table('users')->insert([
            'name' => "Admin",
            'email' => "admin@myworld.com",
            'password' => Hash::make('admin@123')
            
        ]);

        // API user
        DB::table('users')->insert([
            'name' => "API",
            'email' => "api@myworld.com",
            'password' => Hash::make('api@123')
            
        ]);

    }
}
