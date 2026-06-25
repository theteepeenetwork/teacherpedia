<?php

namespace App\Controllers;

use App\Models\ObjectiveModel;

/**
 * Beat the Clock — KS2 Numeracy timed fluency challenge (/beat-the-clock).
 *
 * A screen-only, self-marking sprint: pupils answer as many questions as they
 * can before the clock runs out. Questions are generated client-side from the
 * curriculum objective library (window.TP_GEN, keyed by objective). The
 * controller's only job is to ship the lean objective library to the page (so
 * the JS can offer strand-based practice selection); all gameplay lives in
 * assets/js/beat-the-clock.js. Screen-only: no print, no answer key, no save.
 */
class BeatTheClock extends BaseController
{
    public function index()
    {
        $objectiveModel = new ObjectiveModel();

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

        return view('beat_the_clock/index', [
            'objectives' => $objectives,
            'accent'     => '#c0563a',
        ]);
    }
}
