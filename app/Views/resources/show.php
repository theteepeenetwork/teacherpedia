<?= $this->extend('layouts/app') ?>

<?php
  $a        = $activity;
  $slug     = $a['slug'] ?? '';
  $name     = $a['name'] ?? '';
  $live     = ($a['status'] ?? '') === 'live';
  $icon     = $a['icon'] ?? '';
  $tags     = array_filter(array_map('trim', explode(',', (string) ($a['tags'] ?? ''))));
  $minY     = isset($a['min_year']) && $a['min_year'] !== null ? (int) $a['min_year'] : null;
  $maxY     = isset($a['max_year']) && $a['max_year'] !== null ? (int) $a['max_year'] : null;
  $coverage = '';
  if ($minY !== null && $maxY !== null) {
      $coverage = $minY === $maxY ? 'Year ' . $minY : 'Years ' . $minY . '&ndash;' . $maxY;
  }
  $how   = (isset($a['how']) && is_array($a['how'])) ? $a['how'] : [];
  $blurb = $a['blurb'] ?? ($a['description'] ?? '');
  // Feature image only when declared AND the file physically exists under the webroot.
  $imgRel = $a['image'] ?? '';
  $imgOk  = $imgRel !== '' && is_file(FCPATH . ltrim($imgRel, '/'));
?>

<?= $this->section('title') ?><?= esc($name) ?> — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('content') ?>

  <!-- TOP BAR -->
  <header class="app-header">
    <a href="/" class="brand" style="display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; letter-spacing:-.02em;">
      <span>teacherpedia</span><span class="dot-mark" style="line-height:0;">.</span>
    </a>
    <a href="<?= base_url('browse') ?>" class="app-crumb">&larr; All resources</a>
    <div class="app-divider"></div>
    <div class="app-title"><?= esc($name) ?></div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- INFO PAGE BODY -->
  <div class="app-scroll" style="flex:1; padding:32px 32px 70px; display:flex; justify-content:center; align-items:flex-start;">
    <div style="width:100%; max-width:960px;">

      <!-- Breadcrumb -->
      <div style="font-size:13px; font-weight:600; color:#8a8f86; margin-bottom:18px;">
        <a class="navlink" href="<?= base_url('/') ?>" style="color:#8a8f86;">Home</a>
        &nbsp;&rsaquo;&nbsp;
        <a class="navlink" href="<?= base_url('browse') ?>" style="color:#8a8f86;">Resources</a>
        &nbsp;&rsaquo;&nbsp;
        <span style="color:#3a423b; font-weight:700;"><?= esc($name) ?></span>
      </div>

      <div style="display:grid; grid-template-columns:1.05fr .95fr; gap:36px; align-items:start;">

        <!-- LEFT: feature image / placeholder -->
        <div>
          <?php if ($imgOk): ?>
            <img src="<?= base_url(ltrim($imgRel, '/')) ?>" alt="<?= esc($name, 'attr') ?> feature preview"
                 style="display:block; width:100%; height:auto; border:1px solid rgba(28,36,32,.1); border-radius:18px; box-shadow:0 18px 40px -22px rgba(28,36,32,.34); background:#fff;">
          <?php else: ?>
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:16px; min-height:320px; background:#e7f5ed; border:1px solid rgba(31,138,77,.22); border-radius:18px; padding:40px; text-align:center;">
              <div style="width:96px; height:96px; border-radius:24px; display:flex; align-items:center; justify-content:center; font-size:46px; background:#fff; box-shadow:0 6px 18px -8px rgba(31,138,77,.4);"><?= esc($icon) ?></div>
              <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:22px; color:#206e40; letter-spacing:-.02em;"><?= esc($name) ?></div>
            </div>
          <?php endif; ?>
        </div>

        <!-- RIGHT: title, context, blurb, CTA -->
        <div>
          <h1 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:38px; letter-spacing:-.03em; line-height:1.05; color:#1c2420;"><?= esc($name) ?></h1>

          <!-- Context + coverage + tags -->
          <div style="display:flex; align-items:center; gap:7px; margin-top:16px; flex-wrap:wrap;">
            <span style="background:#1f8a4d; color:#fff; border-radius:999px; padding:5px 13px; font-size:12.5px; font-weight:700;">Key Stage 2 &middot; Numeracy</span>
            <?php if ($coverage !== ''): ?>
              <span style="font-size:12px; font-weight:700; color:#206e40; background:#e7f5ed; border-radius:6px; padding:4px 9px;"><?= $coverage ?></span>
            <?php endif; ?>
            <?php foreach ($tags as $t): ?>
              <span style="font-size:12px; font-weight:700; color:#6c716a; background:rgba(28,36,32,.05); border-radius:6px; padding:4px 9px;"><?= esc($t) ?></span>
            <?php endforeach; ?>
          </div>

          <!-- Description / blurb -->
          <p style="margin:20px 0 0; font-size:15.5px; color:#545b51; line-height:1.6;"><?= esc($blurb) ?></p>

          <!-- Primary CTA -->
          <div style="margin-top:26px;">
            <?php if ($live && ! empty($a['route'])): ?>
              <a href="<?= base_url(ltrim($a['route'], '/')) ?>" class="btn btn-primary btn-lg" style="text-decoration:none;">Open resource &rarr;</a>
            <?php else: ?>
              <button type="button" class="btn btn-primary btn-lg" disabled>Coming soon</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- HOW IT WORKS -->
      <section style="margin-top:44px; padding-top:30px; border-top:1px solid rgba(28,36,32,.1);">
        <h2 style="margin:0 0 18px; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:24px; letter-spacing:-.02em; color:#1c2420;">How it works</h2>
        <?php if (! empty($how)): ?>
          <ol style="margin:0; padding:0; list-style:none; display:flex; flex-direction:column; gap:14px; max-width:760px;">
            <?php foreach ($how as $i => $step): ?>
              <li style="display:flex; gap:14px; align-items:flex-start;">
                <span style="flex:0 0 auto; width:30px; height:30px; border-radius:50%; background:#e7f5ed; color:#206e40; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:14px; display:flex; align-items:center; justify-content:center;"><?= $i + 1 ?></span>
                <span style="font-size:15px; color:#4a514a; line-height:1.55; padding-top:4px;"><?= esc($step) ?></span>
              </li>
            <?php endforeach; ?>
          </ol>
        <?php else: ?>
          <p style="margin:0; font-size:15px; color:#545b51; line-height:1.6; max-width:760px;"><?= esc($a['description'] ?? '') ?></p>
        <?php endif; ?>
      </section>

    </div>
  </div>

<?= $this->endSection() ?>
