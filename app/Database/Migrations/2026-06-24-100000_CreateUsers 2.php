<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Creates the `users` table used by App\Models\Login_model and the
 * User\Users controller for registration, login and email verification.
 *
 * Columns are derived from the model's $allowedFields plus the session/
 * controller reads (e.g. `admin`).
 */
class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INTEGER', 'auto_increment' => true],
            'first_name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'second_name'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'username'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'password'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'subscriber'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'free'],
            'communication'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'verification_key' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'admin'            => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'no'],
            'created_date'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
