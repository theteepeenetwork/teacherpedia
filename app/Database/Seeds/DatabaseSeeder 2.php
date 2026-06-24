<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Runs all application seeders. Use: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('ObjectivesSeeder');
        $this->call('ActivitiesSeeder');
    }
}
