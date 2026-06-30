<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Teacher submissions (new generators or activities) awaiting admin review.
 */
class CreateSubmissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
            'author_id'      => ['type' => 'INTEGER', 'null' => true],
            'name'           => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'type'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'subject'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'year'           => ['type' => 'INTEGER', 'null' => true],
            'strand'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'objective'      => ['type' => 'TEXT', 'null' => true],
            'generator_code' => ['type' => 'TEXT', 'null' => true],
            'status'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->createTable('submissions', true);
    }

    public function down()
    {
        $this->forge->dropTable('submissions', true);
    }
}
