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
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'staff'],
        ]);

        DB::table('security_questions')->insert([
            ['name' => 'What is the name of your pet?'],
            ['name' => 'What is your favorite ballpen?'],
        ]);

        DB::table('genders')->insert([
            ['name' => 'Gender 1'],
            ['name' => 'Gender 2'],
        ]);

        DB::table('archive_reasons')->insert([
            ['name' => 'Reason 1'],
            ['name' => 'Reason 2'],
        ]);
        
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'first_name' => 'FNAdmin',
                'middle_name' => null,
                'last_name' => 'LNAdmin',
                'role_id' => 1,
                'security_question_id' => 1,
                'security_question_answer' => 'answer',
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'staff',
                'first_name' => 'FNStaff',
                'middle_name' => null,
                'last_name' => 'LNStaff',
                'role_id' => 2,
                'security_question_id' => 1,
                'security_question_answer' => 'answer',
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

        // DO NOT ALTER
        DB::table('certificate_types')->insert([
            ['name' => 'Business Permit'],
            ['name' => 'Barangay Clearance'],
            ['name' => 'Barangay Indigency'],
        ]);

        // DO NOT ALTER
        DB::table('blotter_roles')->insert([
            ['name' => 'reporter'],
            ['name' => 'victim'],
            ['name' => 'suspect'],
        ]);

        // DO NOT ALTER
        DB::table('complaint_roles')->insert([
            ['name' => 'complainant'],
            ['name' => 'defendant'],
        ]);

        // DO NOT ALTER
        DB::table('blotter_status')->insert([
            ['name' => 'unresolved'],
            ['name' => 'active'],
            ['name' => 'settled'],
            ['name' => 'rescheduled'],
        ]);

        DB::table('activities')->insert([
            [
                'name' => 'Test Activity',
                'description' => 'Test Activity Description',
                'start_date' => now(),
                'is_all_day' => 1,
            ],
        ]);

        DB::table('barangay_information')->insert([
            'name' => '53-A Yakal',
        ]);

        DB::table('official_positions')->insert([
            ['name' => 'Barangay Captain'],
            ['name' => 'SK Kagawad'],
        ]);

        Household::factory(10)->create();
        Resident::factory(1000)->create();
    }
}
