<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * ResourceInfo — the info page that sits between Browse and each tool
 * (browse -> /resource/{slug} -> tool). Explains how a resource works and
 * shows a feature screenshot, so the worksheet itself stays clean.
 */
class ResourceInfo extends BaseController
{
    public function show(string $slug)
    {
        $entry = ActivityModel::bySlug($slug);
        if ($entry === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('resources/show', [
            'activity'  => $entry,
            'activeNav' => 'browse',
        ]);
    }
}
