<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedSheetModel extends Model
{
    protected $table         = 'saved_sheets';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $updatedField  = '';
    protected $allowedFields = [
        'user_id', 'title', 'activity', 'config_json',
    ];

    /**
     * All sheets saved by a given user, newest first.
     */
    public function forUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }
}
