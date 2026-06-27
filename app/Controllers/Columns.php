<?php

namespace App\Controllers;

/**
 * Column Methods — KS1-2 Numeracy formal written-method worksheet (/columns).
 *
 * Generates formal column / standard-method calculations for a SINGLE chosen
 * operation (column addition, column subtraction, short/long multiplication,
 * short/long division), printable with an answer key. The operation can be
 * PRE-SELECTED from a ?op= query parameter (synonyms accepted). The tool
 * self-generates by year — no objective library needed. All logic lives
 * client-side in assets/js/columns.js; saving posts to /account/save.
 */
class Columns extends BaseController
{
    public function index()
    {
        $raw = (string) $this->request->getGet('op');

        // Whitelist / map (with synonyms) to one of the four operations.
        $map = [
            'add'            => 'add',
            'addition'       => 'add',
            'subtract'       => 'subtract',
            'subtraction'    => 'subtract',
            'multiply'       => 'multiply',
            'multiplication' => 'multiply',
            'divide'         => 'divide',
            'division'       => 'divide',
        ];
        $op = $map[strtolower(trim($raw))] ?? 'add';

        return view('columns/index', [
            'op'     => $op,
            'accent' => '#c0563a',
        ]);
    }
}
