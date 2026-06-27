<?php

namespace App\Controllers;

use App\Models\ObjectiveModel;
use App\Models\SavedSheetModel;

/**
 * Build — the Worksheet Builder tool (/build).
 *
 * Two-panel client-side tool. The controller's job is to ship the objective
 * library to the page (as PHP data for the grouped view AND as a JS array on
 * window.TP_OBJECTIVES) and, when opening a saved sheet, to restore its config
 * via window.TP_SAVED. All interactivity lives in assets/js/build.js.
 */
class Build extends BaseController
{
    public function index($id = null)
    {
        $objectiveModel = new ObjectiveModel();

        // The builder uses the White Rose framework library: one objective per
        // topic/block per year, carrying the Below/Meeting/Exceeding band
        // descriptors (content-as-code, no re-seed).
        $rows = $objectiveModel->framework();

        $objectives = [];
        foreach ($rows as $row) {
            $objectives[] = [
                'id'        => (int) $row['id'],
                'year'      => (int) $row['year'],
                'strand'    => (string) $row['strand'],
                'text'      => (string) $row['text'],
                'key'       => $row['key'] !== null && $row['key'] !== ''
                                ? (string) $row['key']
                                : null,
                'auto'      => (int) ($row['auto_generating'] ?? 0) === 1,
                'below'     => (string) ($row['below'] ?? ''),
                'meeting'   => (string) ($row['meeting'] ?? ''),
                'exceeding' => (string) ($row['exceeding'] ?? ''),
            ];
        }

        // Build the PHP-side grouping (strand => [objectives]) so the view can
        // render a no-JS fallback / SEO list. build.js re-renders interactively
        // from window.TP_OBJECTIVES (single source of truth for interaction).
        $grouped = [];
        foreach ($objectives as $obj) {
            $grouped[$obj['strand']][] = $obj;
        }

        // Optional: restore a previously saved sheet for the logged-in user.
        $saved = null;
        if ($id !== null) {
            $userId = session()->get('id');
            if ($userId) {
                $sheet = (new SavedSheetModel())
                    ->where('id', (int) $id)
                    ->where('user_id', (int) $userId)
                    ->first();

                if ($sheet) {
                    $config = json_decode($sheet['config_json'] ?? '', true);
                    if (is_array($config)) {
                        $saved = [
                            'id'     => (int) $sheet['id'],
                            'title'  => $sheet['title'] ?? '',
                            'config' => $config,
                        ];
                    }
                }
            }
        }

        return view('build/index', [
            'objectives' => $objectives,
            'grouped'    => $grouped,
            'saved'      => $saved,
            'accent'     => '#1f8a4d',
        ]);
    }
}
