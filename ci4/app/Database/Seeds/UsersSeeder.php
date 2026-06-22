<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // -------------------------------------------------------------------
        // LOGIN CREDENTIALS (plaintext password documented for testing):
        //   Regular user : user@teacherpedia.test  /  Password123!
        //   Admin user   : admin@teacherpedia.test /  Password123!
        //
        // Login is validated by App\Validation\UserRules::validateUser(), which
        // looks the account up by email in the `users` table and calls
        // password_verify($input, $user['password']). Because this seeder
        // inserts directly (bypassing the model's beforeInsert hash hook) we
        // MUST store an already-hashed password here.
        //
        // NOTE: both the user-login (User\Users) and admin-login flows validate
        // against the `users` table via UserRules/Login_model, so the admin
        // account is seeded into BOTH `users` (so password_verify can succeed)
        // and `admin_users` (so Admin_login_model lookups find it).
        // -------------------------------------------------------------------
        $hash = password_hash('Password123!', PASSWORD_DEFAULT);
        $now  = date('Y-m-d H:i:s');

        // Regular verified user.
        $this->db->table('users')->insert([
            'first_name'       => 'Test',
            'second_name'      => 'User',
            'email'            => 'user@teacherpedia.test',
            'password'         => $hash,
            'username'         => 'testuser',
            'subscriber'       => 'free',
            'communication'    => 'yes',
            'verification_key' => 'verified',
            'admin'            => 'no',
            'created_date'     => $now,
            'updated_at'       => null,
        ]);

        // Admin account in the `users` table so password_verify succeeds for the
        // admin login form too, with admin flag set to 'yes'.
        $this->db->table('users')->insert([
            'first_name'       => 'Site',
            'second_name'      => 'Admin',
            'email'            => 'admin@teacherpedia.test',
            'password'         => $hash,
            'username'         => 'admin',
            'subscriber'       => 'basic',
            'communication'    => 'yes',
            'verification_key' => 'verified',
            'admin'            => 'yes',
            'created_date'     => $now,
            'updated_at'       => null,
        ]);

        // Admin account in the dedicated admin_users table.
        $this->db->table('admin_users')->insert([
            'first_name'       => 'Site',
            'second_name'      => 'Admin',
            'email'            => 'admin@teacherpedia.test',
            'password'         => $hash,
            'username'         => 'admin',
            'subscriber'       => 'basic',
            'communication'    => 'yes',
            'verification_key' => 'verified',
            'admin'            => 'yes',
            'created_date'     => $now,
            'updated_at'       => null,
        ]);
    }
}
