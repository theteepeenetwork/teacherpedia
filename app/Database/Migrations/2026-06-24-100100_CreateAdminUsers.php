<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Creates the `admin_users` table used by App\Models\Admin_login_model and
 * the Admin\Admin_users controller for admin registration/login.
 *
 * Columns mirror the user table; setUserSession() reads `admin`, so it is
 * included here too (defaulting to 'yes' for admin accounts).
 */
class CreateAdminUsers extends Migration
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
            'subscriber'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'basic'],
            'communication'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'verification_key' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'admin'            => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'yes'],
            'created_date'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('admin_users', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin_users', true);
    }
}
