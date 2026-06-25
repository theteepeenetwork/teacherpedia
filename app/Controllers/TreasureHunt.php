<?php

namespace App\Controllers;

use App\Models\ObjectiveModel;

/**
 * Treasure Hunt / Trail — KS2 Numeracy room-trail activity (/treasure-hunt).
 *
 * A set of clue cards placed around the room. Each card shows an ANSWER at the
 * top and a QUESTION at the bottom. Children solve the question, hunt for the
 * card whose answer matches, forming a single closed loop that visits every
 * card once. A wrong answer breaks the trail — so it self-marks.
 *
 * The controller is thin: it ships the auto-generating objective library to the
 * page (projected to the lean {id,year,strand,key} shape) as window.TP_OBJECTIVES.
 * All trail logic lives client-side in assets/js/treasure-hunt.js; saving posts
 * to /account/save.
 */
class TreasureHunt extends BaseController
{
    public function index()
    {
        $objectiveModel = new ObjectiveModel();

        // Pull the whole library, ordered by strand then id, and project to the
        // lean shape the JS trail builder needs (it only cares about the
        // generator key + strand grouping).
        $rows = $objectiveModel
            ->select('id, year, strand, generator_key')
            ->orderBy('strand', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $objectives = [];
        foreach ($rows as $row) {
            $objectives[] = [
                'id'     => (int) $row['id'],
                'year'   => (int) $row['year'],
                'strand' => (string) $row['strand'],
                'key'    => $row['generator_key'] !== null && $row['generator_key'] !== ''
                                ? (string) $row['generator_key']
                                : null,
            ];
        }

        return view('treasure_hunt/index', [
            'objectives' => $objectives,
            'accent'     => '#b8742e',
        ]);
    }
}
