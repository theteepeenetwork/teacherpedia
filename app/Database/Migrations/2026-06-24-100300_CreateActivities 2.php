<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Activity catalog (the builder tools teachers compose objectives into).
 * Some activities are 'live', others 'soon' (in development).
 */
class CreateActivities extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tags'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'soon'],
            'route'       => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'sort_order'  => ['type' => 'INTEGER', 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('activities', true);
    }

    public function down()
    {
        $this->forge->dropTable('activities', true);
    }
}
