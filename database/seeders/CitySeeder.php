<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('sql/cities.sql');

        if (File::exists($sqlPath)) {
            DB::unprepared(File::get($sqlPath));
            $this->command->info('Cities imported successfully.');

            DB::table('cities')->update([
                'city' => DB::raw("REPLACE(REPLACE(city, 'ي', 'ی'), 'ك', 'ک')")
            ]);

            $this->command->info('Cities normalized successfully.');
        } else {
            $this->command->error('SQL file not found: ' . $sqlPath);
        }
    }
}
