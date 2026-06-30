<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Curriculum objectives library (UK primary KS2 Numeracy, Years 3-6).
 * Each objective may optionally have a JS generator keyed by generator_key,
 * which aligns with the G.<key> functions ported into window.TP_GEN.
 */
class CreateObjectives extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INTEGER', 'auto_increment' => true],
            'key_stage'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'KS2'],
            'subject'         => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Numeracy'],
            'year'            => ['type' => 'INTEGER', 'null' => true],
            'strand'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'text'            => ['type' => 'TEXT', 'null' => true],
            'generator_key'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'auto_generating' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('year');
        $this->forge->addKey('strand');
        $this->forge->createTable('objectives', true);
    }

    public function down()
    {
        $this->forge->dropTable('objectives', true);
    }
}
