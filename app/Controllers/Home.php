<?php

namespace App\Controllers;

/**
 * Home — public marketing landing page.
 */
class Home extends BaseController
{
    public function index()
    {
        return view('pages/home', [
            'activeNav' => null,
        ]);
    }
}
