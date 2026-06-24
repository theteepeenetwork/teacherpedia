<?php

namespace App\Database\Seeds;

use App\Models\Admin_login_model;
use CodeIgniter\Database\Seeder;

/**
 * Creates the first admin account (admin_users) if none exists.
 *
 * Teacher self-registration only creates rows in `users`; admins must be
 * seeded. The password is read from env ADMIN_SEED_PASSWORD, falling back to
 * a development default. CHANGE THIS in any real deployment.
 *
 * Run: php spark db:seed AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $model = new Admin_login_model();

        $email = getenv('ADMIN_SEED_EMAIL') ?: 'admin@teacherpedia.test';
        if ($model->where('email', $email)->first() !== null) {
            return; // already exists — idempotent
        }

        $password = getenv('ADMIN_SEED_PASSWORD') ?: 'changeme123';

        // Let the model's beforeInsert hook hash the password.
        $model->insert([
            'first_name'    => 'Site',
            'second_name'   => 'Admin',
            'email'         => $email,
            'username'      => $email,
            'password'      => $password,
            'subscriber'    => 'basic',
            'communication' => 'no',
            'admin'         => 'yes',
            'created_date'  => date('Y-m-d H:i:s'),
        ]);
    }
}
