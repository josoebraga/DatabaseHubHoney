<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users_type')->insert([
            'type' => 'Administrador',
            'status' => true,
        ]);
        DB::table('users_type')->insert([
            'type' => 'Analista',
            'status' => true,
        ]);
        DB::table('users_type')->insert([
            'type' => 'Visualisador',
            'status' => true,
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@softui.com',
            'password' => Hash::make('secret'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_type_id' => 1,
            'status' => true,
        ]);

        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_type_id' => 2,
            'status' => true,
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_type_id' => 3,
            'status' => true,
        ]);
    }
}
