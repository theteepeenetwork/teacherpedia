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
     * The full resource catalogue, defined in CODE so Browse shows every tool
     * with no DB re-seed. This is the single source of truth; the seeder inserts
     * the same list into the DB (used by Admin stats). Each entry carries year
     * coverage (min_year/max_year) for the Browse year filter; live tools cover
     * Years 1-6 unless stated, "soon" tools have none yet.
     */
    public static function catalog(): array
    {
        $base = [
            ['slug' => 'worksheet-generator', 'name' => 'Worksheet Generator',
             'description' => 'The all-in-one builder. Combine any objectives across Years 1-6, set difficulty and print a differentiated practice sheet with answer key.',
             'icon' => '📝', 'tags' => 'Printable,Differentiated', 'status' => 'live', 'route' => '/build', 'sort_order' => 1],
            ['slug' => 'code-breaker', 'name' => 'Code Breaker',
             'description' => 'Solve calculations to crack a number-to-letter cipher and reveal a hidden message. A self-marking puzzle children love.',
             'icon' => '🔍', 'tags' => 'Puzzle,Self-marking', 'status' => 'live', 'route' => '/code-breaker', 'sort_order' => 2],
            ['slug' => 'maths-maze', 'name' => 'Maths Maze',
             'description' => 'Solve a calculation to unlock each step through the grid — only the right answers open the path and a wrong turn is a dead end. Self-marking and prints as a puzzle.',
             'icon' => '⊟', 'tags' => 'Game,Self-marking', 'status' => 'live', 'route' => '/maths-maze', 'sort_order' => 3],
            ['slug' => 'treasure-hunt', 'name' => 'Treasure Hunt',
             'description' => 'Clue cards placed around the room: solve each question, hunt for the card whose answer matches, and follow the loop that visits every card once. A wrong answer breaks the trail — so it self-marks.',
             'icon' => '🗺', 'tags' => 'Game,Self-marking', 'status' => 'live', 'route' => '/treasure-hunt', 'sort_order' => 5],
            ['slug' => 'loop-cards', 'name' => 'Loop Cards',
             'description' => 'A deck of domino-style cards split into answer and question halves that link into one continuous loop. Self-marking — great for pairs and early finishers.',
             'icon' => '🔗', 'tags' => 'Game,Self-marking', 'status' => 'live', 'route' => '/loop-cards', 'sort_order' => 6],
            ['slug' => 'bingo', 'name' => 'Bingo',
             'description' => 'Auto-filled bingo cards (3×3, 4×4 or 5×5) plus a caller sheet. Read questions aloud, children dab the matching answer — whole-class fluency.',
             'icon' => '◉', 'tags' => 'Game,Whole class', 'status' => 'live', 'route' => '/bingo', 'sort_order' => 7],
            ['slug' => 'times-table-grids', 'name' => 'Times Table Grids',
             'description' => 'Auto-filled and blank multiplication grids with mixed and missing-number variations for quick-fire recall.',
             'icon' => '⊞', 'tags' => 'Recall,Printable', 'status' => 'soon', 'route' => null, 'sort_order' => 20],
            ['slug' => 'true-or-false', 'name' => 'True or False',
             'description' => 'Rapid-fire statement cards children sort into true and false — perfect for mental-maths starters and plenaries.',
             'icon' => '✓', 'tags' => 'Starter,Discussion', 'status' => 'soon', 'route' => null, 'sort_order' => 21],
        ];

        $all = array_merge($base, self::columnAliases());

        foreach ($all as &$a) {
            if (! array_key_exists('min_year', $a)) {
                $a['min_year'] = $a['status'] === 'live' ? 1 : null;
                $a['max_year'] = $a['status'] === 'live' ? 6 : null;
            }
        }
        unset($a);

        usort($all, static fn ($x, $y) => ($x['sort_order'] ?? 0) <=> ($y['sort_order'] ?? 0));
        return $all;
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
