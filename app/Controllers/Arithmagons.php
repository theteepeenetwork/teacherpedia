<?php

namespace App\Controllers;

/**
 * Arithmagon Triangles — KS1-2 Numeracy structural-reasoning puzzle (/arithmagons).
 *
 * A triangle of three corner circles and three edge boxes, where each edge
 * equals its two touching corners combined (+ or ×). In the Inverse challenge
 * the three given edges over-determine the corners, so a wrong value breaks two
 * edges at once and the puzzle self-checks with no key needed.
 *
 * Challenge maps to the attainment bands: Forward (Below) = corners given,
 * combine to the edges; Inverse (Meeting) = edges given, reason back to the
 * corners; Mixed (Exceeding) = one corner + two edges given. Self-marking on
 * screen and prints clean. All logic lives client-side in assets/js/arithmagons.js;
 * saving posts to /account/save.
 */
class Arithmagons extends BaseController
{
    public function index()
    {
        return view('arithmagons/index', [
            'accent' => '#7b4cc4',
        ]);
    }
}
