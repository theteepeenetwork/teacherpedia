<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Record which school years each activity/resource covers, so Browse search can
 * filter by year (e.g. "Year 2" must not surface KS2-only resources).
 *
 * min_year / max_year are inclusive (NULL = unspecified / coming-soon). A
 * resource covers year Y when min_year <= Y <= max_year.
 */
class AddYearCoverageToActivities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities', [
            'min_year' => ['type' => 'INTEGER', 'null' => true, 'after' => 'route'],
            'max_year' => ['type' => 'INTEGER', 'null' => true, 'after' => 'min_year'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', ['min_year', 'max_year']);
    }
}
