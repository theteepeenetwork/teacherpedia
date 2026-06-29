<?php

namespace App\Controllers;

use App\Models\SavedSheetModel;

/**
 * Spot the Impostor — KS1-2 Numeracy resource (/spot-the-impostor).
 *
 * A grid of PRE-WORKED calculations; some answers are deliberately wrong using
 * realistic pupil misconceptions (a forgotten carry, smaller-from-larger, an
 * off-by-one table fact …). Pupils JUDGE each cell (✓/✗), correct the impostors
 * and add the corrected board, self-checking against an HONEST-TOTAL footer. The
 * one resource that asks pupils to evaluate, not compute. The Answer-key tab
 * reveals each impostor and NAMES the misconception — the teaching moment.
 *
 * Thin controller: ships the page; all logic lives client-side in
 * assets/js/spot-the-impostor.js. Saving posts to /account/save; a saved sheet
 * (config: year/operations/gridSize/impostorCount/showWorking/pupilNames/seed)
 * re-prints identically via the seeded PRNG, restored through window.TP_SAVED.
 */
class SpotTheImpostor extends BaseController
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

        return view('spot_the_impostor/index', [
            'accent' => '#1f6f78',
            'saved'  => $saved,
        ]);
    }
}
