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
        'status', 'route', 'sort_order', 'min_year', 'max_year',
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

    /**
     * Catalogue "aliases" for the Column Methods tool: four searchable entries
     * (one per operation) that all open the same /columns tool with the
     * operation pre-selected via ?op=. Defined in code so they appear in Browse
     * search with no DB re-seed; the seeder also inserts them for a fresh DB.
     */
    public static function columnAliases(): array
    {
        return [
            [
                'slug' => 'column-addition', 'name' => 'Column Addition',
                'description' => 'Generate a column (written method) addition worksheet with carrying, sized to the year group, plus an answer key.',
                'icon' => '➕', 'tags' => 'Printable,Written method', 'status' => 'live',
                'route' => '/columns?op=add', 'sort_order' => 10, 'min_year' => 2, 'max_year' => 6,
            ],
            [
                'slug' => 'column-subtraction', 'name' => 'Column Subtraction',
                'description' => 'Generate a column (written method) subtraction worksheet with exchanging/borrowing, sized to the year group, plus an answer key.',
                'icon' => '➖', 'tags' => 'Printable,Written method', 'status' => 'live',
                'route' => '/columns?op=subtract', 'sort_order' => 11, 'min_year' => 2, 'max_year' => 6,
            ],
            [
                'slug' => 'column-multiplication', 'name' => 'Column Multiplication',
                'description' => 'Generate a short/long multiplication worksheet (written method), sized to the year group, plus an answer key.',
                'icon' => '✖️', 'tags' => 'Printable,Written method', 'status' => 'live',
                'route' => '/columns?op=multiply', 'sort_order' => 12, 'min_year' => 3, 'max_year' => 6,
            ],
            [
                'slug' => 'column-division', 'name' => 'Column Division',
                'description' => 'Generate a short/long (bus-stop) division worksheet, sized to the year group, plus an answer key.',
                'icon' => '➗', 'tags' => 'Printable,Written method', 'status' => 'live',
                'route' => '/columns?op=divide', 'sort_order' => 13, 'min_year' => 3, 'max_year' => 6,
            ],
        ];
    }
}
