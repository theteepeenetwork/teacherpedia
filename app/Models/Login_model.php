<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Model
{
    protected $table = 'users';
    protected $allowedFields = ['first_name', 'second_name', 'email', 'password', 'verification_key', 'created_date', 'username', 'subscriber', 'communication'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];




    protected function beforeInsert(array $data)
    {
        $data = $this->passwordHash($data);
        $data['data']['created_date'] = date('Y-m-d H:i:s');

        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data = $this->passwordHash($data);
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function passwordHash(array $data)
    {
        if (isset($data['data']['password']))
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }


    public function update_user($id, $userdata)
    {
        $this->db->where('id', $id);
        $this->db->update('users', $userdata);
    }

    public function verify_email($email, $verification_code)
    {
        $db      = \Config\Database::connect();
        $verified = false;
        $builder = $db->table('users');
        $query = $builder
            ->where('email', $email)
            ->get()
            ->getRowArray();
        if ($query['verification_key'] == $verification_code) {
            $data = [
                'verification_key' => 'verified'
            ];
            $builder->where('email', $email)
                ->update($data);
            $verified = 'done';
        } elseif ($query['verification_key'] == 'verified') {
            $verified = 'already_verified';
        } else {
            $verified = 'expired';
        }

        return $verified;
    }
}
