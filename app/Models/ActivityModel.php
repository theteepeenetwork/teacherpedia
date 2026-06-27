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
             'blurb' => 'Code Breaker turns calculation practice into a puzzle. Every answer maps to a letter, and the letters spell out a secret message you choose — so children are motivated to get each sum right. Because a wrong answer produces a wrong letter, the activity quietly self-marks.',
             'icon' => '🔍', 'tags' => 'Puzzle,Self-marking', 'status' => 'live', 'route' => '/code-breaker', 'sort_order' => 2,
             'image' => '/assets/images/resources/code-breaker.png',
             'how' => [
                 'Type the secret message you want children to reveal (or hit Random), and choose which operations to include.',
                 'Each distinct letter is given a unique number; every question is a calculation whose answer is that number.',
                 'Children solve each calculation, find the answer in the code key, and write its letter in the box.',
                 'Reading the letters in order spells out the secret message — a wrong sum gives a wrong letter, so it self-checks.',
                 'Switch to the Answer key tab to reveal every value and the full message for instant marking.',
             ]],
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
            ['slug' => 'arithmagons', 'name' => 'Arithmagon Triangles',
             'description' => 'Number triangles where each edge is its two corners added (or multiplied). Reason forwards or backwards to fill the gaps. In the Inverse challenge the figure is over-constrained, so a wrong value breaks two edges and the puzzle self-checks. Three challenge levels.',
             'blurb' => 'Arithmagon Triangles build number sense through reasoning. Each edge box equals the two corner circles beside it, combined by addition or multiplication. Forward puzzles give the corners; Inverse puzzles give the edges and ask children to reason back to the corners — and because the figure is over-constrained, a wrong value breaks two edges, so the puzzle self-checks.',
             'icon' => '△', 'tags' => 'Puzzle,Self-marking,Reasoning', 'status' => 'live', 'route' => '/arithmagons', 'sort_order' => 8,
             'image' => '/assets/images/resources/arithmagons.png',
             'how' => [
                 'Each edge box equals the two corner circles it sits between, combined by add (+) or multiply (×).',
                 'Forward: the three corners are given — combine adjacent corners to fill each edge.',
                 'Inverse: the three edges are given — reason backwards to recover the three corners.',
                 'Mixed: one corner and two edges are given — work in both directions to complete the triangle.',
                 'In the Inverse challenge a wrong value breaks two edges, so the puzzle self-checks. Use the Answer key tab to mark.',
             ]],
            ['slug' => 'cross-number', 'name' => 'Cross-Number Crossword',
             'description' => 'A crossword where every answer is a number. Each numbered clue is a calculation; its answer fills that run of squares, one digit per box. Across and Down answers share squares, so a wrong digit clashes and the grid won\'t close — it self-checks. Three challenge levels.',
             'blurb' => 'Cross-Number Crossword swaps words for numbers. Each numbered clue is a calculation — like 24 × 3 or 156 + 88 — and its answer is written one digit per square along an Across or Down run. Where an Across answer crosses a Down answer they share a square, so the digits must agree; a wrong answer clashes at the crossing and the grid won\'t close, which means the puzzle quietly marks itself.',
             'icon' => '▦', 'tags' => 'Puzzle,Self-marking,Reasoning', 'status' => 'live', 'route' => '/cross-number', 'sort_order' => 9,
             'image' => '/assets/images/resources/cross-number.png',
             'how' => [
                 'Choose the year group and difficulty, and pick which operations the clues use (+ − × ÷).',
                 'Each numbered clue is a calculation. Work out its answer — that\'s the number that goes in that run of squares, one digit per square.',
                 'Write digits across and down. Where an Across answer crosses a Down answer they share a square, so the digits must match.',
                 'If a crossing square clashes, one of your answers is wrong — the grid won\'t close, so it checks itself.',
                 'Switch to the Answer key tab to reveal every digit and clue value for instant marking.',
             ]],
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
     * Look up a single catalogue entry by slug (the code-defined catalog() is
     * the source of truth). Returns the entry array, or null if not found —
     * used by the resource info page (browse -> /resource/{slug} -> tool).
     */
    public static function bySlug(string $slug): ?array
    {
        foreach (self::catalog() as $a) {
            if (($a['slug'] ?? null) === $slug) {
                return $a;
            }
        }
        return null;
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
