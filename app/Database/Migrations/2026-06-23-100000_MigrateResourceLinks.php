<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Rewrite stored resource paths from the old, web-served "resources/..."
 * location to the consolidated, non-public view path "resources_generated/...".
 *
 * Run with: php spark migrate
 *
 * Note: App\Models\ResourcesModel::viewBase() also normalises legacy paths at
 * runtime, so the app works whether or not this migration has been applied;
 * this migration simply makes the stored data consistent.
 */
class MigrateResourceLinks extends Migration
{
    private array $tables = ['resources', 'deleted_resources'];

    public function up()
    {
        foreach ($this->tables as $table) {
            if (! $this->db->tableExists($table)) {
                continue;
            }
            // 'resources/' is 10 characters; keep everything after it.
            $this->db->query(
                "UPDATE `{$table}` SET `link` = CONCAT('resources_generated/', SUBSTRING(`link`, 11)) WHERE `link` LIKE 'resources/%'"
            );
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            if (! $this->db->tableExists($table)) {
                continue;
            }
            // 'resources_generated/' is 20 characters.
            $this->db->query(
                "UPDATE `{$table}` SET `link` = CONCAT('resources/', SUBSTRING(`link`, 21)) WHERE `link` LIKE 'resources_generated/%'"
            );
        }
    }
}
