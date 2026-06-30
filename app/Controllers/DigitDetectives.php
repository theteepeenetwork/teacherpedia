<?php

namespace App\Controllers;

use App\Models\SavedSheetModel;

/**
 * Digit Detectives — KS1-2 Numeracy resource (/digit-detectives).
 *
 * A grid of small column-addition cards. Each card is a real, correct sum with
 * exactly two digits blanked in two different columns; the solver runs the
 * written method backwards (place value + carries) to recover each missing
 * digit, then reads the two digits through a two-digit codebook (00=A … 25=Z)
 * to decode one letter per puzzle. Reading the letters in order spells a whole-
 * sheet reveal (a joke punchline or praise word) — which self-marks.
 *
 * Thin controller: ships the page; all puzzle logic lives client-side in
 * assets/js/digit-detectives.js. Saving posts to /account/save; a saved sheet
 * (config: year/difficulty/count/source/message/seed) re-prints identically via
 * the seeded PRNG, restored through window.TP_SAVED.
 */
class DigitDetectives extends BaseController
{
    public function index($id = null)
    {
        // Optional: restore a previously saved sheet for the logged-in user
        // (mirrors Build::index). The seed in the config reproduces the sheet.
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

        return view('digit_detectives/index', [
            'accent' => '#34507a',
            'saved'  => $saved,
        ]);
    }
}
