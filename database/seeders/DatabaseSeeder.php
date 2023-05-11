<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'staff'],
        ]);

        DB::table('users')->insert([
            [
                'username' => 'admin',
                'first_name' => 'FNAdmin',
                'middle_name' => null,
                'last_name' => 'LNAdmin',
                'role_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'staff',
                'first_name' => 'FNStaff',
                'middle_name' => null,
                'last_name' => 'LNStaff',
                'role_id' => 2,
                'password' => bcrypt('password'),
            ]
        ]);

        DB::table('religions')->insert([
            ['name' => 'catholic'],
            ['name' => 'islam'],
        ]);

        DB::table('occupations')->insert([
            ['name' => 'employed'],
            ['name' => 'unemployed'],
        ]);

        DB::table('civil_status')->insert([
            ['name' => 'single'],
            ['name' => 'married'],
            ['name' => 'divorced'],
            ['name' => 'separated'],
            ['name' => 'widowed'],
        ]);

        DB::table('certificate_types')->insert([
            ['name' => 'Business Permit'],
            ['name' => 'Barangay Clearance'],
            ['name' => 'Barangay Indigency'],
        ]);

        DB::table('blotter_roles')->insert([
            ['name' => 'reporter'],
            ['name' => 'victim'],
            ['name' => 'suspect'],
        ]);

        DB::table('blotter_status')->insert([
            ['name' => 'inactive'],
            ['name' => 'active'],
            ['name' => 'settled'],
            ['name' => 'scheduled'],
        ]);

        DB::table('activities')->insert([
            [
                'name' => 'Happy Birthday',
                'description' => 'Birthday Ni Mayor',
                'start_date' => now(),
                'is_all_day' => 1,
            ],
            [
                'name' => 'Happy New Year',
                'description' => 'New Year, New Barangay',
                'start_date' => now(),
                'is_all_day' => 1,
            ]
        ]);

        DB::table('barangay_information')->insert([
            'name' => '53-A Yakal',
        ]);

        DB::table('official_positions')->insert([
            ['name' => 'Barangay Captain'],
            ['name' => 'SK Kagawad'],
        ]);

        Household::factory(10)->create();
        Resident::factory(100)->create();
    }
}
