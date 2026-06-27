<?php

namespace App\Controllers;

/**
 * Pages — simple static public marketing pages.
 */
class Pages extends BaseController
{
    public function pricing()
    {
        return view('pages/pricing', ['activeNav' => 'pricing']);
    }

    public function vision()
    {
        return view('pages/vision', ['activeNav' => 'vision']);
    }

    public function privacy()
    {
        // Privacy is reachable from the footer; no primary nav item to highlight.
        return view('pages/privacy', ['activeNav' => null]);
    }
}
