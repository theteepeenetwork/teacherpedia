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

        // Hide retired tools even if a DB still has their seeded row (no reseed
        // needed). Keep this list in sync when a tool is removed.
        $removed    = ['beat-the-clock'];
        $activities = array_values(array_filter($activities, static function ($a) use ($removed) {
            return ! in_array($a['slug'] ?? '', $removed, true);
        }));

        $live = 0;
        $soon = 0;
        foreach ($activities as &$a) {
            if (($a['status'] ?? '') === 'live') {
                $live++;
            } else {
                $soon++;
            }
            // Year coverage for the Browse year filter. Prefer the DB columns
            // if present (migration applied); otherwise fall back to a sensible
            // default so the feature works with no migration/seed step required:
            // every current live tool draws on the KS2 library (Years 3-6).
            if (empty($a['min_year'])) {
                $a['min_year'] = ($a['status'] ?? '') === 'live' ? 3 : null;
                $a['max_year'] = ($a['status'] ?? '') === 'live' ? 6 : null;
            }
        }
        unset($a);

        return view('pages/browse', [
            'activeNav'  => 'browse',
            'activities' => $activities,
            'liveCount'  => $live,
            'soonCount'  => $soon,
        ]);
    }
}
