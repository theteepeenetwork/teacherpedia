<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityModel;
use App\Models\ObjectiveModel;
use App\Models\SubmissionModel;

/**
 * Admin dashboard controller.
 *
 * Routes (under the 'admin' filter, see Config\Routes):
 *   GET  /admin                          -> index()
 *   GET  /admin/submissions/approve/(:n) -> approve($id)
 *
 * The 'admin' filter guarantees session('admin') === 'yes'.
 */
class Admin extends BaseController
{
    /**
     * Render the admin dashboard with real stats computed from the DB.
     */
    public function index()
    {
        $objectives  = new ObjectiveModel();
        $activities  = new ActivityModel();
        $submissions = new SubmissionModel();

        // ---- Stat cards ------------------------------------------------------
        $liveActivities = count($activities->live());

        // Generators = objectives that HAVE a generator_key; need = those without.
        // Use a fresh model per count so accumulated where()s don't leak between
        // queries (countAllResults(false) intentionally keeps the builder).
        $totalObj      = (new ObjectiveModel())->countAllResults();
        $generators    = (new ObjectiveModel())
            ->where('generator_key IS NOT NULL')
            ->where('generator_key !=', '')
            ->countAllResults();
        $needGenerator = $totalObj - $generators;

        $pendingList   = $submissions->pending();
        $pendingReview = count($pendingList);

        // Distinct strands that already have at least one generator (for the
        // "across N strands" caption under the Generators card).
        $strandRows = (new ObjectiveModel())->select('strand')
            ->where('generator_key IS NOT NULL')
            ->where('generator_key !=', '')
            ->groupBy('strand')
            ->findAll();
        $strandCount = count($strandRows);

        // ---- Activities table ------------------------------------------------
        $activityRows = $activities->all();

        // ---- Submission queue ------------------------------------------------
        // Show pending first (the queue); fall back to recent if none pending.
        $queue = $pendingList ?: $submissions->recent(5);

        // ---- Coverage by year (Y3–Y6) ---------------------------------------
        $byYear   = $objectives->countsByYear();
        $coverage = [];
        foreach ([3, 4, 5, 6] as $y) {
            if (! isset($byYear[$y])) {
                continue;
            }
            $v     = $byYear[$y];
            $total = (int) $v['total'];
            $auto  = (int) $v['auto'];
            $pct   = $total > 0 ? (int) round($auto / $total * 100) : 0;
            $coverage[] = [
                'label' => 'Year ' . $y,
                'pct'   => $pct,
                'auto'  => $auto,
                'total' => $total,
            ];
        }

        return view('admin/dashboard', [
            'firstName'      => session('first_name') ?: 'Admin',
            'role'           => 'Administrator',
            'liveActivities' => $liveActivities,
            'liveNames'      => array_column($activities->live(), 'name'),
            'generators'     => $generators,
            'strandCount'    => $strandCount,
            'needGenerator'  => $needGenerator,
            'pendingReview'  => $pendingReview,
            'activities'     => $activityRows,
            'submissions'    => $queue,
            'coverage'       => $coverage,
        ]);
    }

    /**
     * Approve a submission: set status='approved', redirect back to /admin.
     */
    public function approve($id = null)
    {
        $submissions = new SubmissionModel();
        $row = $id ? $submissions->find((int) $id) : null;

        if (! $row) {
            session()->setFlashdata('error', 'Submission not found.');
            return redirect()->to('/admin');
        }

        $submissions->update((int) $id, ['status' => 'approved']);
        session()->setFlashdata('success', 'Submission "' . $row['name'] . '" approved.');

        return redirect()->to('/admin');
    }
}
