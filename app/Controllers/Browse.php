<?php

namespace App\Controllers;

use App\Models\ActivityModel;

/**
 * Browse — public activity catalogue, rendered from the code-defined
 * ActivityModel::catalog() so every resource is searchable with no DB re-seed.
 */
class Browse extends BaseController
{
    public function index()
    {
        // The catalogue is code-defined (ActivityModel::catalog()) so EVERY
        // resource is searchable here with no DB re-seed. Coverage + sort order
        // are already set on each entry.
        $activities = ActivityModel::catalog();

        $live = 0;
        $soon = 0;
        foreach ($activities as $a) {
            if (($a['status'] ?? '') === 'live') {
                $live++;
            } else {
                $soon++;
            }
        }

        return view('pages/browse', [
            'activeNav'  => 'browse',
            'activities' => $activities,
            'liveCount'  => $live,
            'soonCount'  => $soon,
        ]);
    }
}
