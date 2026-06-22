<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateImages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'alt'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'link'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'html_link' => ['type' => 'TEXT', 'null' => true],
            'created'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status'    => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('images', true);
    }

    public function down()
    {
        $this->forge->dropTable('images', true);
    }
}
