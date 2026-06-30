<?php

namespace App\Controllers;

use App\Models\ActivityModel;

/**
 * Browse — public activity catalogue, rendered from the DB.
 */
class Browse extends BaseController
{
    public function index()
    {
        $model      = new ActivityModel();
        $activities = $model->all();              // live first (sort_order), then soon

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
