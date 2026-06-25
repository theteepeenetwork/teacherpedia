<?php

namespace App\Controllers;

use App\Models\ObjectiveModel;

/**
 * Bingo — KS2 Numeracy whole-class bingo generator (/bingo).
 *
 * Auto-filled bingo cards (3×3, 4×4 or 5×5 with a free centre). The teacher
 * reads / projects the questions from the caller sheet; children dab the
 * matching answer on their card. A whole-class fluency game.
 *
 * Thin controller: it ships the objective library to the page (as a JS array on
 * window.TP_OBJECTIVES, the single source of truth the builder reads) exactly
 * like Build.php. All card/caller logic lives client-side in assets/js/bingo.js;
 * saving posts to /account/save.
 */
class Bingo extends BaseController
{
    public function index()
    {
        $objectiveModel = new ObjectiveModel();

        // Pull the whole library and project to the lean shape the JS builder
        // expects (matches Build.php so window.TP_OBJECTIVES is identical).
        $rows = $objectiveModel
            ->select('id, year, strand, text, generator_key, auto_generating')
            ->orderBy('strand', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $objectives = [];
        foreach ($rows as $row) {
            $objectives[] = [
                'id'     => (int) $row['id'],
                'year'   => (int) $row['year'],
                'strand' => (string) $row['strand'],
                'text'   => (string) $row['text'],
                'key'    => $row['generator_key'] !== null && $row['generator_key'] !== ''
                                ? (string) $row['generator_key']
                                : null,
                'auto'   => (int) ($row['auto_generating'] ?? 0) === 1,
            ];
        }

        return view('bingo/index', [
            'objectives' => $objectives,
            'accent'     => '#7a4fbf',
        ]);
    }
}
