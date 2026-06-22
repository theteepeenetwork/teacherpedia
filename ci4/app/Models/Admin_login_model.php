<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin_login_model extends Model
{
    protected $table = 'admin_users';
    protected $allowedFields = ['first_name', 'second_name', 'email', 'password', 'created_date', 'username', 'subscriber', 'communication'];
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
        $this->db->update('admin_users', $userdata);
    }
}
