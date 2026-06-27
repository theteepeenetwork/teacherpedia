<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Sheets saved by teachers from the builders (worksheet, code breaker, etc.).
 * config_json stores the activity configuration as JSON.
 */
class CreateSavedSheets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id'     => ['type' => 'INTEGER', 'null' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'activity'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'config_json' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('saved_sheets', true);
    }

    public function down()
    {
        $this->forge->dropTable('saved_sheets', true);
    }
}
