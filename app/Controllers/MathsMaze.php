<?php

namespace App\Controllers;

/**
 * Maths Maze — KS2 Numeracy path-finding puzzle (/maths-maze).
 *
 * Solve a calculation to unlock each step through a grid: only answers that
 * match the puzzle rule are safe — a wrong turn is a dead end. Self-marking
 * on screen and prints as a clean puzzle. All logic lives client-side in
 * assets/js/maths-maze.js; saving posts to /account/save.
 */
class MathsMaze extends BaseController
{
    public function index()
    {
        return view('maths_maze/index', [
            'accent' => '#0f9b9b',
        ]);
    }
}
