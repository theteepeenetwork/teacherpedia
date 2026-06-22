<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'parent_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('category', true);
    }

    public function down()
    {
        $this->forge->dropTable('category', true);
    }
}
