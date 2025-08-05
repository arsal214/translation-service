<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Language::insert([
            ['locale' => 'en', 'name' => 'English'],
            ['locale' => 'fr', 'name' => 'French'],
            ['locale' => 'es', 'name' => 'Spanish'],
        ]);
    }
}
