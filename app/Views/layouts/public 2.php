<?php
/**
 * =============================================================================
 * layouts/public.php — shared layout for PUBLIC marketing pages
 * =============================================================================
 * Used by: Home, Browse/Resources, Pricing, Vision (About), Contact, Privacy,
 *          Login — every page that shares the sticky cream header + dark footer.
 *
 * HOW TO USE (feature-page contract — do not deviate):
 *
 *   <?= $this->extend('layouts/public') ?>
 *
 *   <?= $this->section('title') ?>Pricing — Teacherpedia<?= $this->endSection() ?>
 *
 *   <?= $this->section('content') ?>
 *      ...your page body (sections, .wrap containers, etc.)...
 *   <?= $this->endSection() ?>
 *
 * SECTIONS this layout renders:
 *   - 'title'       (optional)  text for <title>. Falls back to "Teacherpedia".
 *   - 'pageHead'    (optional)  extra <head> markup: page-specific <style>,
 *                               <link> (e.g. tp-print.css), preloads, meta.
 *   - 'content'     (REQUIRED)  the page body, rendered between header & footer.
 *   - 'pageScripts' (optional)  page JS, injected just before </body>
 *                               (after any shared scripts).
 *
 * VARIABLES the page may set (via $this->setVar / controller data):
 *   - $activeNav  (optional)  one of: 'browse' | 'pricing' | 'vision'
 *                             | 'contact' | 'login'. Adds .is-active to the
 *                             matching nav link.
 *
 * Notes:
 *   - teacherpedia.css + Google Fonts are loaded here; pages must NOT re-add
 *     them. Reuse the component classes from teacherpedia.css.
 *   - The footer copyright year is rendered server-side with date('Y').
 * =============================================================================
 */
$activeNav = $activeNav ?? '';
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

  <?= $this->renderSection('pageHead') ?>
</head>
<body>

  <!-- ============ SHARED PUBLIC HEADER ============ -->
  <header class="site-header">
    <div class="wrap">
      <a href="<?= base_url('/') ?>" class="brand">teacherpedia<span class="dot-mark">.</span></a>
      <nav class="nav">
        <a class="navlink<?= $activeNav === 'browse'  ? ' is-active' : '' ?>" href="<?= base_url('browse') ?>">Resources</a>
        <a class="navlink<?= $activeNav === 'pricing' ? ' is-active' : '' ?>" href="<?= base_url('pricing') ?>">Pricing</a>
        <a class="navlink<?= $activeNav === 'vision'  ? ' is-active' : '' ?>" href="<?= base_url('vision') ?>">Our vision</a>
        <a class="navlink<?= $activeNav === 'contact' ? ' is-active' : '' ?>" href="<?= base_url('contact') ?>">Contact</a>
      </nav>
      <div class="spacer"></div>
      <a class="navlink<?= $activeNav === 'login' ? ' is-active' : '' ?>" href="<?= base_url('login') ?>">Log in</a>
      <a href="<?= base_url('build') ?>" class="btn btn-primary btn-sm">Start building &rarr;</a>
    </div>
  </header>

  <!-- ============ PAGE CONTENT ============ -->
  <main>
    <?= $this->renderSection('content') ?>
  </main>

  <!-- ============ SHARED DARK FOOTER ============ -->
  <footer class="site-footer">
    <div class="wrap footer-grid">
      <div>
        <div class="brand">teacherpedia<span class="dot-mark">.</span></div>
        <p>Auto-regenerating, curriculum-aligned teaching resources. Built by a teacher, in the North East of England.</p>
        <div class="footer-social">
          <span>Instagram</span><span>Facebook</span><span>X</span>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Resources</div>
        <div class="footer-links">
          <a class="navlink" href="<?= base_url('browse') ?>">Browse all</a>
          <a class="navlink" href="<?= base_url('build') ?>">Worksheet Generator</a>
          <a class="navlink" href="<?= base_url('code-breaker') ?>">Code Breaker</a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Company</div>
        <div class="footer-links">
          <a class="navlink" href="<?= base_url('vision') ?>">Our vision</a>
          <a class="navlink" href="<?= base_url('pricing') ?>">Pricing</a>
          <a class="navlink" href="<?= base_url('contact') ?>">Contact</a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Account</div>
        <div class="footer-links">
          <a class="navlink" href="<?= base_url('login') ?>">Log in</a>
          <a class="navlink" href="<?= base_url('login') ?>">Create account</a>
          <a class="navlink" href="<?= base_url('privacy') ?>">Privacy policy</a>
        </div>
      </div>
    </div>
    <div class="footer-bar">
      <div class="wrap">
        <span>&copy; <?= date('Y') ?> Teacherpedia. All rights reserved.</span>
        <span>Made for teachers, by a teacher.</span>
      </div>
    </div>
  </footer>

  <?= $this->renderSection('pageScripts') ?>
</body>
</html>
