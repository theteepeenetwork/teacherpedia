<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table         = 'activities';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'slug', 'name', 'description', 'icon', 'tags',
        'status', 'route', 'sort_order',
    ];

    /**
     * Only live activities, ordered by sort_order.
     */
    public function live(): array
    {
        return $this->where('status', 'live')
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    /**
     * All activities, ordered by sort_order (live first via seeded ordering).
     */
    public function all(): array
    {
        return $this->orderBy('sort_order', 'ASC')->findAll();
    }
}
