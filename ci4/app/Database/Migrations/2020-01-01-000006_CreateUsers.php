<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'first_name'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'second_name'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'password'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'username'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'subscriber'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'communication'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'verification_key' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            // admin column is required: setUserSession() reads $user['admin'] for the users table.
            'admin'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_date'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
