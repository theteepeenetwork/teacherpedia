<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Seeds the activity catalog shown on Browse and managed in Admin.
 * Live activities sort first (sort_order 1..), "soon" ones after.
 * Idempotent: empties the table before inserting.
 */
class ActivitiesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Single source of truth: the code-defined catalogue (same list Browse
        // shows). Stamp timestamps and insert into the DB for Admin stats.
        $activities = \App\Models\ActivityModel::catalog();
        foreach ($activities as &$a) {
            $a['created_at'] = $now;
            $a['updated_at'] = $now;
        }
        unset($a);

        $builder = $this->db->table('activities');
        $builder->truncate();
        $builder->insertBatch($activities);

        $count = $this->db->table('activities')->countAllResults();
        $live  = $this->db->table('activities')->where('status', 'live')->countAllResults();
        CLI::write("ActivitiesSeeder: inserted {$count} activities ({$live} live).", 'green');
    }
}
