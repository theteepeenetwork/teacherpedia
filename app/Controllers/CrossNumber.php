<?php

namespace App\Controllers;

/**
 * Cross-Number Crossword — KS1-2 Numeracy resource (/cross-number).
 *
 * A printable cross-number crossword: each white cell holds one digit and every
 * Across/Down entry is a number whose clue is a calculation that equals it.
 * Crossing entries share cells, so a wrong digit clashes and the grid won't
 * close — the puzzle self-marks; the answer-key tab fills every cell.
 *
 * Thin controller: ships the page; all logic lives client-side in
 * assets/js/cross-number.js. Saving posts to /account/save.
 */
class CrossNumber extends BaseController
{
    public function index()
    {
        return view('cross_number/index', [
            'accent' => '#b45309',
        ]);
    }
}
