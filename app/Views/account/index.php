<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>My saved sheets — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="wrap" style="padding-top:42px; padding-bottom:64px;">

  <!-- Page header -->
  <div class="row wrap-row" style="justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
    <div class="stack" style="gap:6px;">
      <span class="eyebrow">Your account</span>
      <h1 style="margin:0;">My saved sheets</h1>
      <?php $first = trim((string) session('first_name')); ?>
      <p style="margin:4px 0 0; color:var(--muted2);">
        <?php if ($first !== ''): ?>
          Welcome back, <strong><?= esc($first) ?></strong> — here are the sheets you&rsquo;ve saved.
        <?php else: ?>
          Here are the sheets you&rsquo;ve saved.
        <?php endif; ?>
      </p>
    </div>
    <div class="row" style="gap:10px;">
      <a class="btn btn-ghost btn-sm" href="<?= base_url('browse') ?>">Browse resources</a>
      <a class="linkline" href="<?= base_url('logout') ?>">Log out</a>
    </div>
  </div>

  <!-- Flash messages -->
  <?php if (session('msg')): ?>
    <div class="card card-warm" style="padding:14px 18px; margin-bottom:20px;">
      <?= esc(session('msg')) ?>
    </div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="card" style="padding:14px 18px; margin-bottom:20px; border-color:#e0b4b4; background:#fbeaea;">
      <?= esc(session('error')) ?>
    </div>
  <?php endif; ?>

  <?php if (empty($sheets)): ?>

    <!-- Empty state -->
    <div class="card card-warm stack" style="align-items:center; text-align:center; gap:14px; padding:56px 26px;">
      <div class="card-icon" style="width:56px; height:56px; font-size:26px;">+</div>
      <h2 style="margin:6px 0 0;">No saved sheets yet</h2>
      <p style="margin:0; max-width:440px; color:var(--muted2);">
        When you build a worksheet or a code breaker, save it here so you can
        reopen and reprint it any time.
      </p>
      <a class="btn btn-primary" href="<?= base_url('browse') ?>">Browse resources to build &rarr;</a>
    </div>

  <?php else: ?>

    <!-- Saved sheet grid -->
    <div class="grid grid-3">
      <?php foreach ($sheets as $sheet): ?>
        <?php
          $activity    = $sheet['activity'] ?? '';
          $isWorksheet = $activity === 'worksheet';
          $isDigitDet  = $activity === 'digit-detectives';
          if ($isWorksheet) {
              $tagLabel = 'Worksheet';
              $openUrl  = base_url('build/' . (int) $sheet['id']);
          } elseif ($isDigitDet) {
              $tagLabel = 'Digit Detectives';
              $openUrl  = base_url('digit-detectives/' . (int) $sheet['id']);
          } else {
              $tagLabel = 'Code Breaker';
              $openUrl  = base_url('code-breaker');
          }
          $ts   = strtotime((string) ($sheet['created_at'] ?? ''));
          $date = $ts ? date('j M Y', $ts) : '';
        ?>
        <article class="card stack" style="gap:14px; justify-content:space-between;">
          <div class="stack" style="gap:10px;">
            <div class="row" style="justify-content:space-between; align-items:flex-start; gap:10px;">
              <span class="tag <?= $isWorksheet ? 'tag-muted' : 'tag-soon' ?>"><?= esc($tagLabel) ?></span>
              <?php if ($date !== ''): ?>
                <span style="font-size:12px; color:var(--muted2); white-space:nowrap;">Saved <?= esc($date) ?></span>
              <?php endif; ?>
            </div>
            <h3 style="margin:0;"><?= esc($sheet['title'] ?? 'Untitled sheet') ?></h3>
          </div>

          <div class="row" style="gap:10px;">
            <a class="btn btn-primary btn-sm" href="<?= esc($openUrl) ?>">Open</a>
            <a class="btn btn-ghost btn-sm"
               href="<?= base_url('account/delete/' . (int) $sheet['id']) ?>"
               onclick="return confirm('Delete this saved sheet? This cannot be undone.');">
              Delete
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

</section>

<?= $this->endSection() ?>
