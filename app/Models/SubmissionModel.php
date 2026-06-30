<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionModel extends Model
{
    protected $table         = 'submissions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $updatedField  = '';
    protected $allowedFields = [
        'author_id', 'name', 'type', 'subject', 'year', 'strand',
        'objective', 'generator_code', 'status',
    ];

    /**
     * Submissions awaiting admin review, oldest first.
     */
    public function pending(): array
    {
        return $this->where('status', 'pending')
            ->orderBy('created_at', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Most recent submissions regardless of status.
     */
    public function recent(int $limit = 20): array
    {
        return $this->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll($limit);
    }
}
