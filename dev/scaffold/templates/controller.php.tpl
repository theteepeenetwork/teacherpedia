<?php

namespace App\Controllers;

/**
 * __NAME__ — KS1-2 Numeracy resource (/__SLUG__).
 *
 * TODO: one-line description of the activity and how it self-marks.
 *
 * Thin controller: ships the page; all logic lives client-side in
 * assets/js/__SLUG__.js. Saving posts to /account/save.
 */
class __CLASS__ extends BaseController
{
    public function index()
    {
        return view('__SLUG_US__/index', [
            'accent' => '__ACCENT__',
        ]);
    }
}
