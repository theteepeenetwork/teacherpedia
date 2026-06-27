<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubmissionModel;

/**
 * Admin Studio — the 3-pane resource authoring tool.
 *
 * Routes (under the 'admin' filter):
 *   GET  /admin/studio            -> index()        new resource
 *   GET  /admin/studio/(:num)     -> index($id)     review an existing submission
 *   POST /admin/studio/draft      -> draft()        save a draft (status=pending)
 *   POST /admin/studio/submit     -> submit()       submit for review (status=pending)
 *
 * The page talks to draft()/submit() via fetch with X-Requested-With, expecting
 * JSON. Normal (non-AJAX) POSTs fall back to a redirect with flash.
 */
class Studio extends BaseController
{
    /**
     * Render the studio. If $id is given, prefill from that submission.
     */
    public function index($id = null)
    {
        $submission = null;
        if ($id !== null) {
            $submission = (new SubmissionModel())->find((int) $id);
        }

        return view('admin/studio', [
            'firstName'  => session('first_name') ?: 'Admin',
            'submission' => $submission,
        ]);
    }

    /**
     * Save a draft. Persists a submission row (status=pending) so it is
     * recoverable, and responds JSON {ok:true,id} for fetch callers.
     */
    public function draft()
    {
        $data = $this->collect();

        // A draft needs at least a name to be worth persisting.
        $id = null;
        if ($data['name'] !== '' || $data['generator_code'] !== '') {
            $model = new SubmissionModel();
            $id    = $model->insert($data, true);
        }

        if ($this->wantsJson()) {
            return $this->response->setJSON(['ok' => true, 'id' => $id]);
        }

        session()->setFlashdata('success', 'Draft saved');
        return redirect()->to('/admin/studio' . ($id ? '/' . $id : ''));
    }

    /**
     * Submit for review. Validates name + generator_code, inserts a pending
     * submission, responds JSON {ok:true,id}.
     */
    public function submit()
    {
        $data = $this->collect();

        $errors = [];
        if ($data['name'] === '') {
            $errors[] = 'A resource name is required.';
        }
        if ($data['generator_code'] === '') {
            $errors[] = 'Generator code cannot be empty.';
        }

        if ($errors) {
            $msg = implode(' ', $errors);
            if ($this->wantsJson()) {
                return $this->response->setStatusCode(422)
                    ->setJSON(['ok' => false, 'errors' => $errors]);
            }
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }

        $model = new SubmissionModel();
        $id    = $model->insert($data, true);

        if ($this->wantsJson()) {
            return $this->response->setJSON(['ok' => true, 'id' => $id]);
        }

        session()->setFlashdata('success', 'Submitted for review — an admin will approve it.');
        return redirect()->to('/admin');
    }

    /**
     * Gather + sanitise the submission fields from the POST request.
     */
    private function collect(): array
    {
        $req = $this->request;
        $year = $req->getPost('year');

        return [
            'author_id'      => session('id') ?: null,
            'name'           => trim((string) $req->getPost('name')),
            'type'           => trim((string) $req->getPost('type')) ?: 'generator',
            'subject'        => trim((string) $req->getPost('subject')),
            'year'           => ($year !== null && $year !== '') ? (int) $year : null,
            'strand'         => trim((string) $req->getPost('strand')),
            'objective'      => trim((string) $req->getPost('objective')),
            'generator_code' => (string) $req->getPost('generator_code'),
            'status'         => 'pending',
        ];
    }

    /**
     * True when the caller expects a JSON response (fetch with X-Requested-With).
     */
    private function wantsJson(): bool
    {
        return $this->request->isAJAX()
            || strtolower((string) $this->request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
    }
}
