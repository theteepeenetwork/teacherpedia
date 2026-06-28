<?php

namespace App\Controllers;

/**
 * Digit Detectives — KS1-2 Numeracy resource (/digit-detectives).
 *
 * A correct column addition is printed with several interior digits blanked as
 * lettered boxes. The solver recovers each missing digit by running the addition
 * algorithm backwards (place value + carries), then reads the recovered digits
 * through a printed cipher to spell a hidden word — which only spells correctly
 * if every digit is right, so the puzzle self-marks.
 *
 * Thin controller: ships the page; all logic lives client-side in
 * assets/js/digit-detectives.js. Saving posts to /account/save.
 */
class DigitDetectives extends BaseController
{
    public function index()
    {
        return view('digit_detectives/index', [
            'accent' => '#34507a',
        ]);
    }
}
