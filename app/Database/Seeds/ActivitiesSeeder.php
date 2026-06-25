<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Seeds the activity catalog shown on Browse and managed in Admin.
 * Live activities sort first (sort_order 1..), "soon" ones after.
 * Idempotent: empties the table before inserting.
 */
class ActivitiesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $activities = [
            [
                'slug'        => 'worksheet-generator',
                'name'        => 'Worksheet Generator',
                'description' => 'The all-in-one builder. Combine any objectives across Years 3-6, set difficulty and print a differentiated practice sheet with answer key.',
                'icon'        => '📝',
                'tags'        => 'Printable,Differentiated',
                'status'      => 'live',
                'route'       => '/build',
                'sort_order'  => 1,
            ],
            [
                'slug'        => 'code-breaker',
                'name'        => 'Code Breaker',
                'description' => 'Solve calculations to crack a number-to-letter cipher and reveal a hidden message. A self-marking puzzle children love.',
                'icon'        => '🔍',
                'tags'        => 'Puzzle,Self-marking',
                'status'      => 'live',
                'route'       => '/code-breaker',
                'sort_order'  => 2,
            ],
            [
                'slug'        => 'maths-maze',
                'name'        => 'Maths Maze',
                'description' => 'Solve a calculation to unlock each step through the grid — only the right answers open the path and a wrong turn is a dead end. Self-marking and prints as a puzzle.',
                'icon'        => '⊟',
                'tags'        => 'Game,Self-marking',
                'status'      => 'live',
                'route'       => '/maths-maze',
                'sort_order'  => 3,
            ],
            [
                'slug'        => 'treasure-hunt',
                'name'        => 'Treasure Hunt',
                'description' => 'Clue cards placed around the room: solve each question, hunt for the card whose answer matches, and follow the loop that visits every card once. A wrong answer breaks the trail — so it self-marks.',
                'icon'        => '🗺',
                'tags'        => 'Game,Self-marking',
                'status'      => 'live',
                'route'       => '/treasure-hunt',
                'sort_order'  => 5,
            ],
            [
                'slug'        => 'loop-cards',
                'name'        => 'Loop Cards',
                'description' => 'A deck of domino-style cards split into answer and question halves that link into one continuous loop. Self-marking — great for pairs and early finishers.',
                'icon'        => '🔗',
                'tags'        => 'Game,Self-marking',
                'status'      => 'live',
                'route'       => '/loop-cards',
                'sort_order'  => 6,
            ],
            [
                'slug'        => 'bingo',
                'name'        => 'Bingo',
                'description' => 'Auto-filled bingo cards (3×3, 4×4 or 5×5) plus a caller sheet. Read questions aloud, children dab the matching answer — whole-class fluency.',
                'icon'        => '◉',
                'tags'        => 'Game,Whole class',
                'status'      => 'live',
                'route'       => '/bingo',
                'sort_order'  => 7,
            ],
            [
                'slug'        => 'times-table-grids',
                'name'        => 'Times Table Grids',
                'description' => 'Auto-filled and blank multiplication grids with mixed and missing-number variations for quick-fire recall.',
                'icon'        => '⊞',
                'tags'        => 'Recall,Printable',
                'status'      => 'soon',
                'route'       => null,
                'sort_order'  => 8,
            ],
            [
                'slug'        => 'true-or-false',
                'name'        => 'True or False',
                'description' => 'Rapid-fire statement cards children sort into true and false — perfect for mental-maths starters and plenaries.',
                'icon'        => '✓',
                'tags'        => 'Starter,Discussion',
                'status'      => 'soon',
                'route'       => null,
                'sort_order'  => 9,
            ],
        ];

        foreach ($activities as &$a) {
            $a['created_at'] = $now;
            $a['updated_at'] = $now;
            // Year coverage for Browse search. Every live tool currently draws on
            // the KS2 objective library (Years 3-6); coming-soon tools have no
            // coverage yet. (When Year 1-2 content lands, widen these.)
            if (! array_key_exists('min_year', $a)) {
                $a['min_year'] = $a['status'] === 'live' ? 3 : null;
                $a['max_year'] = $a['status'] === 'live' ? 6 : null;
            }
        }
        unset($a);

        $builder = $this->db->table('activities');
        $builder->truncate();
        $builder->insertBatch($activities);

        $count = $this->db->table('activities')->countAllResults();
        $live  = $this->db->table('activities')->where('status', 'live')->countAllResults();
        CLI::write("ActivitiesSeeder: inserted {$count} activities ({$live} live).", 'green');
    }
}
