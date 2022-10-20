<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
         $skills = [
             'Laravel',
             'Codeigniter',
             'Ajax',
             'Vue.js',
             'MySQL',
             'API',
         ];

         foreach ($skills as $skill){
             Skill::create([
                'name' => $skill
             ]);
         }
    }
}
