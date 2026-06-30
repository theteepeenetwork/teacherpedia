<?php

namespace App\Controllers;

/**
 * Code Breaker — KS2 Numeracy cipher puzzle tool (/code-breaker).
 *
 * Renders the self-contained worksheet builder. All puzzle logic lives
 * client-side in assets/js/code-breaker.js; saving posts to /account/save.
 */
class CodeBreaker extends BaseController
{
    public function index()
    {
        $data = [
            'accent' => '#7a4fbf',
        ];

        return view('code_breaker/index', $data);
    }
}
