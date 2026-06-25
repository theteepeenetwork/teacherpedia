<?php

namespace App\Controllers;

use App\Models\ObjectiveModel;

/**
 * Loop Cards / Dominoes — KS2 Numeracy self-marking tabletop game (/loop-cards).
 *
 * A deck of domino-style cards, each split into [ ANSWER | QUESTION ]. Laid end
 * to end, correct matching forms one continuous closed loop back to the start —
 * the same closed-chain data as a Treasure Hunt, but rendered as dominoes for
 * pairs / early finishers.
 *
 * Thin controller (cf. Build.php): it ships the objective library to the page as
 * a JS array on window.TP_OBJECTIVES so loop-cards.js can pick generatable
 * objectives by strand and batch questions client-side. All interactivity lives
 * in assets/js/loop-cards.js; saving posts to /account/save.
 */
class LoopCards extends BaseController
{
    public function index()
    {
        $objectiveModel = new ObjectiveModel();

        // Pull the whole library, ordered by strand then id. Project to the lean
        // shape the JS expects ({id,year,strand,key}); only objectives whose key
        // exists in TP_GEN are auto-generating and therefore usable as a deck.
        $rows = $objectiveModel->library();

        $objectives = [];
        foreach ($rows as $row) {
            $objectives[] = [
                'id'     => (int) $row['id'],
                'year'   => (int) $row['year'],
                'strand' => (string) $row['strand'],
                'key'    => $row['generator_key'] !== null && $row['generator_key'] !== ''
                                ? (string) $row['generator_key']
                                : null,
                'auto'   => (int) ($row['auto_generating'] ?? 0) === 1,
            ];
        }

        return view('loop_cards/index', [
            'objectives' => $objectives,
            'accent'     => '#2a6fdb',
        ]);
    }
}
