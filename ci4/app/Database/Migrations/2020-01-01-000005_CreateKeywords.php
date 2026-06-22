<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeywords extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'word'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'count' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('keywords', true);
    }

    public function down()
    {
        $this->forge->dropTable('keywords', true);
    }
}
