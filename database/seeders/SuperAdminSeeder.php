<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'), // Make sure to hash the password
            'role' => 'superadmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kleio')->insert([
            'recommendation' => 'To improve wheat yield, consider using certified seeds and adopting modern irrigation techniques like drip irrigation. Timely fertilizer application and proper pest control can significantly boost productivity. Additionally, crop rotation with legumes can enhance soil fertility and reduce disease risk.',
            'fun_fact'=>'Did you know? Pakistan is one of the top 10 producers of mangoes in the world, and the famous Sindhri and Chaunsa varieties are exported globally for their unmatched sweetness!',
            'record_date' => '2024-01-10',
            'farm_id' => 0,
        ]);
    }
}
