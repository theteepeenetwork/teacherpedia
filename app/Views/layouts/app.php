<?php
/**
 * =============================================================================
 * layouts/app.php — shared layout for FULL-VIEWPORT TOOL pages
 * =============================================================================
 * Used by: the Worksheet / Multi-Generator builder (/build), Code Breaker
 *          (/code-breaker), Admin Studio — any 100vh "app" screen with its own
 *          slim top-bar chrome and NO public marketing footer.
 *
 * HOW TO USE (feature-page contract — do not deviate):
 *
 *   <?= $this->extend('layouts/app') ?>
 *
 *   <?= $this->section('title') ?>Worksheet Builder — Teacherpedia<?= $this->endSection() ?>
 *
 *   <?= $this->section('content') ?>
 *      <!-- Top bar: reuse .app-header (see teacherpedia.css §10) -->
 *      <header class="app-header"> ... </header>
 *      <div class="app-body"> ...panels / main... </div>
 *   <?= $this->endSection() ?>
 *
 * SECTIONS this layout renders:
 *   - 'title'       (optional)  text for <title>. Falls back to "Teacherpedia".
 *   - 'pageHead'    (optional)  extra <head> markup (e.g. load tp-print.css on
 *                               pages that render a .sheet, page-specific CSS).
 *   - 'content'     (REQUIRED)  the full app screen. The layout wraps it in a
 *                               <div class="app-shell"> (100vh flex column with
 *                               the radial cream gradient background). The page
 *                               supplies its own .app-header + body inside.
 *   - 'pageScripts' (optional)  page JS, injected just before </body>.
 *
 * VARIABLES the page may set:
 *   - $accent  (optional)  hex accent colour for this tool; sets the --accent
 *                          CSS variable (default brand green #1f8a4d). The
 *                          mockups theme per-tool via --accent.
 *
 * Notes:
 *   - teacherpedia.css + Google Fonts load here; do NOT re-add them.
 *   - window.TP_generate / window.TP_GEN live in assets/js/tp-generators.js;
 *     load it from a page's 'pageScripts' section when the tool needs to
 *     generate questions client-side.
 *   - There is intentionally no public footer here.
 * =============================================================================
 */
$accent = $accent ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->renderSection('title') ?: 'Teacherpedia' ?></title>

  <!-- Google Fonts: Bricolage Grotesque (headings), Hanken Grotesk (body), JetBrains Mono (code) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Hanken+Grotesk:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= base_url('assets/css/teacherpedia.css') ?>">

  <?php if ($accent !== ''): ?>
  <style>:root{ --accent: <?= esc($accent, 'css') ?>; }</style>
  <?php endif; ?>

  <?= $this->renderSection('pageHead') ?>
</head>
<body>

  <div class="app-shell">
    <?= $this->renderSection('content') ?>
  </div>

  <?= $this->renderSection('pageScripts') ?>
</body>
</html>
