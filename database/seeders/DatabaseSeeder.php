<?php

namespace Database\Seeders;

use App\Models\Utility;
use Illuminate\Database\Seeder;
use Database\Seeders\RecordsTableSeeder; // Import the RecordsTableSeeder class

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('module:migrate LandingPage');
        \Artisan::call('module:seed LandingPage');

        if (\Request::route()->getName() != 'LaravelUpdater::database') {
            // You can add additional seeders here if needed
            $this->call([
                RecordsTableSeeder::class, // Include the RecordsTableSeeder class here
            ]);
        } else {
            Utility::languagecreate();
        }
    }
}
