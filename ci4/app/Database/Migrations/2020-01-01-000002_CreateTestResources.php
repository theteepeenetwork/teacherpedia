<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestResources extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'resource_name'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'resource_author'      => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'resource_description' => ['type' => 'TEXT', 'null' => true],
            'resource_excerpt'     => ['type' => 'TEXT', 'null' => true],
            'link'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'keywords'             => ['type' => 'TEXT', 'null' => true],
            'action'               => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'level'                => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'year'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'category'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'resource_banner'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'resource_thumb'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'slug'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'dateAdded'            => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('test_resources', true);
    }

    public function down()
    {
        $this->forge->dropTable('test_resources', true);
    }
}
