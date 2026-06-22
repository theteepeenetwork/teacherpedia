<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Keystages: parent_id = 1 (load_keystage() selects WHERE parent_id = 1).
        // Explicit ids so subjects/topics can reference their parents deterministically.
        $keystages = [
            ['id' => 2, 'title' => 'EYFS', 'parent_id' => 1],
            ['id' => 3, 'title' => 'KS1',  'parent_id' => 1],
            ['id' => 4, 'title' => 'KS2',  'parent_id' => 1],
        ];

        // Subjects: parent_id = a keystage id.
        $subjects = [
            ['id' => 5, 'title' => 'Numeracy', 'parent_id' => 4], // under KS2
            ['id' => 6, 'title' => 'English',  'parent_id' => 4], // under KS2
            ['id' => 7, 'title' => 'Science',  'parent_id' => 3], // under KS1
        ];

        // Topics: parent_id = a subject id.
        $topics = [
            ['id' => 8, 'title' => 'Addition',   'parent_id' => 5], // under Numeracy
            ['id' => 9, 'title' => 'Subtraction', 'parent_id' => 5], // under Numeracy
            ['id' => 10, 'title' => 'Reading',   'parent_id' => 6], // under English
        ];

        $this->db->table('category')->insertBatch(array_merge($keystages, $subjects, $topics));
    }
}
