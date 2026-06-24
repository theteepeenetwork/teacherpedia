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
                'slug'        => 'times-table-grids',
                'name'        => 'Times Table Grids',
                'description' => 'Auto-filled and blank multiplication grids with mixed and missing-number variations for quick-fire recall.',
                'icon'        => '⊞',
                'tags'        => 'Recall,Printable',
                'status'      => 'soon',
                'route'       => null,
                'sort_order'  => 3,
            ],
            [
                'slug'        => 'maze-race',
                'name'        => 'Maze Race',
                'description' => 'Find the path through the maze by only stepping on cells with correct answers. Great as an early-finisher task.',
                'icon'        => '⊟',
                'tags'        => 'Game,Reasoning',
                'status'      => 'soon',
                'route'       => null,
                'sort_order'  => 4,
            ],
            [
                'slug'        => 'true-or-false',
                'name'        => 'True or False',
                'description' => 'Rapid-fire statement cards children sort into true and false — perfect for mental-maths starters and plenaries.',
                'icon'        => '✓',
                'tags'        => 'Starter,Discussion',
                'status'      => 'soon',
                'route'       => null,
                'sort_order'  => 5,
            ],
            [
                'slug'        => 'bingo-cards',
                'name'        => 'Bingo Cards',
                'description' => 'Generate a class set of unique bingo cards plus a caller sheet, with answers drawn from any objective.',
                'icon'        => '◉',
                'tags'        => 'Game,Whole class',
                'status'      => 'soon',
                'route'       => null,
                'sort_order'  => 6,
            ],
        ];

        foreach ($activities as &$a) {
            $a['created_at'] = $now;
            $a['updated_at'] = $now;
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
