<?php

namespace App\Controllers;

use App\Models\SavedSheetModel;

/**
 * Area Maze — KS2 Numeracy resource (/area-maze).
 *
 * A rectangle recursively guillotine-split into smaller rectangles, drawn
 * schematic and "not drawn to scale". Some pieces show their area, some edges
 * their length; exactly one value is the missing '?'. The solver chains
 * area = length × width across shared edges to force the target as a whole
 * number — so it self-marks (single correct answer; answer key shows the
 * step-by-step deduction). Year 4–6 only (area-as-multiplication is Y4+).
 *
 * Thin controller: ships the page; all puzzle logic lives client-side in
 * assets/js/area-maze.js. Saving posts to /account/save; a saved sheet
 * (config: year/difficulty/count/seed) re-prints identically via the seeded
 * PRNG, restored through window.TP_SAVED.
 */
class AreaMaze extends BaseController
{
    public function index($id = null)
    {
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

        return view('area_maze/index', [
            'accent' => '#a23b5e',
            'saved'  => $saved,
        ]);
    }
}
