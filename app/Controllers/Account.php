<?php

namespace App\Controllers;

use App\Models\SavedSheetModel;

/**
 * Account — the logged-in teacher's "My saved sheets" area.
 *
 * Routes (all behind the 'auth' filter, so session('id') is guaranteed):
 *   GET  /account            -> index()   list saved sheets
 *   POST /account/save       -> save()    JSON endpoint, called by the tools
 *   GET  /account/delete/{n} -> delete()  delete an owned sheet, redirect back
 */
class Account extends BaseController
{
    /** Activities we accept / know how to render. */
    private const ALLOWED_ACTIVITIES = ['worksheet', 'code-breaker', 'maths-maze', 'treasure-hunt', 'loop-cards', 'bingo', 'columns', 'arithmagons', 'cross-number', 'digit-detectives', 'area-maze', 'spot-the-impostor'];

    /**
     * List the current user's saved sheets (newest first).
     */
    public function index()
    {
        $model  = new SavedSheetModel();
        $sheets = $model->forUser((int) session('id'));

        return view('account/index', [
            'activeNav' => null,
            'sheets'    => $sheets,
        ]);
    }

    /**
     * Save a sheet for the current user.
     *
     * Request (POST, form-encoded — the Build & Code Breaker tools fetch() here):
     *   title    string  non-empty
     *   activity string  'worksheet' | 'code-breaker'
     *   config   string  a JSON document (worksheet/code-breaker config schema)
     *
     * Response (JSON):
     *   200 { ok: true,  id: <int> }
     *   422 { ok: false, error: <string> }
     */
    public function save()
    {
        $title    = trim((string) $this->request->getPost('title'));
        $activity = (string) $this->request->getPost('activity');
        $config   = (string) $this->request->getPost('config');

        if ($title === '') {
            return $this->failJson('A title is required.');
        }
        if (! in_array($activity, self::ALLOWED_ACTIVITIES, true)) {
            return $this->failJson('Unknown activity type.');
        }
        if ($config === '') {
            return $this->failJson('Missing sheet configuration.');
        }

        // config must be a valid JSON document.
        json_decode($config);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->failJson('Configuration is not valid JSON.');
        }

        $model = new SavedSheetModel();
        $id    = $model->insert([
            'user_id'     => (int) session('id'),
            'title'       => $title,
            'activity'    => $activity,
            'config_json' => $config,
        ], true);

        if (! $id) {
            return $this->failJson('Could not save the sheet.');
        }

        return $this->response->setJSON([
            'ok' => true,
            'id' => (int) $id,
        ]);
    }

    /**
     * Delete a saved sheet, but only when it belongs to the current user.
     * Redirects back to /account with a flash message.
     */
    public function delete($id = null)
    {
        $model = new SavedSheetModel();
        $sheet = $model->find((int) $id);

        // Ownership check: the row must exist AND belong to session('id').
        if ($sheet !== null && (int) $sheet['user_id'] === (int) session('id')) {
            $model->delete((int) $id);
            session()->setFlashdata('msg', 'Saved sheet deleted.');
        } else {
            session()->setFlashdata('error', 'That sheet could not be found.');
        }

        return redirect()->to(base_url('account'));
    }

    /**
     * Helper: JSON validation-failure response with 422 status.
     */
    private function failJson(string $error)
    {
        return $this->response
            ->setStatusCode(422)
            ->setJSON(['ok' => false, 'error' => $error]);
    }
}
