<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Seeds the objectives table from app/Database/data/objectives.json.
 *
 * Mapping:
 *   year            <- json "year"
 *   strand          <- json "strand"
 *   text            <- json "text"
 *   generator_key   <- json "key" (null if absent/empty)
 *   auto_generating <- 1 if the json record has a non-empty "key" (or "a" flag), else 0
 *
 * Idempotent: empties the table before inserting.
 */
class ObjectivesSeeder extends Seeder
{
    public function run()
    {
        $path = APPPATH . 'Database/data/objectives.json';
        if (! is_file($path)) {
            throw new \RuntimeException("objectives.json not found at {$path}");
        }

        $records = json_decode(file_get_contents($path), true);
        if (! is_array($records)) {
            throw new \RuntimeException('objectives.json did not decode to an array');
        }

        $now  = date('Y-m-d H:i:s');
        $rows = [];
        foreach ($records as $r) {
            $key  = isset($r['key']) && $r['key'] !== '' ? $r['key'] : null;
            // auto_generating: explicit "a" flag if present, otherwise derived from key presence.
            $auto = array_key_exists('a', $r) ? (int) (bool) $r['a'] : (int) ($key !== null);

            $rows[] = [
                'key_stage'       => 'KS2',
                'subject'         => 'Numeracy',
                'year'            => isset($r['year']) ? (int) $r['year'] : null,
                'strand'          => $r['strand'] ?? null,
                'text'            => $r['text'] ?? null,
                'generator_key'   => $key,
                'auto_generating' => $auto,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        $builder = $this->db->table('objectives');
        // Idempotent reset.
        $builder->truncate();

        if ($rows !== []) {
            // Insert in batches for speed.
            foreach (array_chunk($rows, 100) as $chunk) {
                $builder->insertBatch($chunk);
            }
        }

        $count = $this->db->table('objectives')->countAllResults();
        $withKey = $this->db->table('objectives')
            ->where('generator_key IS NOT NULL')->countAllResults();
        CLI::write("ObjectivesSeeder: inserted {$count} objectives ({$withKey} with a generator_key).", 'green');
    }
}
