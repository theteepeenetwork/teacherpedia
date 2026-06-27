<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Worksheet Builder — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Page-local tweaks layered on the shared tool chrome */
    .build-panel { width: 418px; }
    .build-panel-head { padding: 18px 20px 14px; border-bottom: 1px solid rgba(28,36,32,.08); }
    .build-lib { flex: 1; padding: 4px 14px 16px; }
    .build-strand-head { display: flex; align-items: center; gap: 8px; padding: 0 6px 6px; }
    .build-strand-dot { width: 9px; height: 9px; border-radius: 3px; }
    .build-strand-name {
      font-family: var(--font-head); font-weight: 700; font-size: 11.5px;
      letter-spacing: .04em; text-transform: uppercase; color: var(--muted);
    }
    .build-strand-line { height: 1px; flex: 1; background: rgba(28,36,32,.1); }
    .build-strand-count { font-size: 10.5px; font-weight: 700; color: #b8bcb2; }
    .build-group { margin-top: 13px; }

    .build-tick {
      width: 20px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    }
    .build-tick .box { width: 18px; height: 18px; border-radius: 6px; border: 1.6px solid rgba(28,36,32,.2); }
    .build-tick .box.soon { border-style: dashed; border-color: rgba(28,36,32,.16); }
    .build-tick .box.sel {
      width: 19px; height: 19px; border: none; color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 800;
    }
    .build-orow-bar { position: absolute; left: 0; top: 8px; bottom: 8px; width: 3px; border-radius: 3px; }
    .build-otext { flex: 1; min-width: 0; }
    .build-otext .t { font-size: 12.5px; font-weight: 700; line-height: 1.3; }
    .build-odesc { font-size: 11px; color: #6c716a; line-height: 1.35; margin-top: 1px; }
    .build-ometa { display: flex; align-items: center; gap: 7px; margin-top: 2px; }
    .build-oyear { font-size: 10.5px; color: #a8a294; font-weight: 600; }
    .build-soon-tag {
      font-size: 9.5px; font-weight: 700; letter-spacing: .03em; text-transform: uppercase;
      color: #bcae7a; background: #fbf3d6; border-radius: 5px; padding: 1px 6px;
    }
    .build-add {
      flex-shrink: 0; border: 1px solid rgba(28,36,32,.16); background: #fff; color: var(--ink);
      border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 17px;
      font-weight: 600; line-height: 1; display: flex; align-items: center; justify-content: center;
    }
    .build-add:hover { border-color: rgba(28,36,32,.3); }

    .build-panel-foot {
      flex-shrink: 0; border-top: 1px solid rgba(28,36,32,.1);
      padding: 12px 18px; display: flex; align-items: center; gap: 12px; background: #fbfaf6;
    }
    .build-total-q { font-family: var(--font-head); font-weight: 700; font-size: 15px; line-height: 1; }
    .build-total-sub { font-size: 11.5px; color: var(--muted); margin-top: 3px; }

    /* .build-diff-label / .build-eyebrow-lbl now live in the shared stylesheet
       (standardised toolbar labels used by every resource). */

    .build-sheet-wrap { flex: 1; padding: 42px 44px 80px; display: flex; justify-content: center; align-items: flex-start; }
    .build-empty-lib { text-align: center; color: #a8a294; font-size: 13px; padding: 40px 20px; line-height: 1.5; }

    /* Objectives outside the selected year(s): shown but greyed / not selectable */
    .orow.orow-offyear { opacity: .45; }
    .orow.orow-offyear:hover { background: transparent; }
    /* Banner shown above the library when the selected year has no content yet */
    .build-year-banner {
      margin: 2px 6px 10px; padding: 9px 11px; border-radius: 9px;
      background: #fbf3d6; color: #8a6d2b; font-size: 12px; font-weight: 600; line-height: 1.4;
    }

    /* Visual question media (clock faces, shapes, angles) rendered under the
       question caption. block + margin so the SVG sits on its own line. */
    .sheet-qvisual { display: block; margin: 7px 0 2px; line-height: 0; }
    .sheet-qvisual svg { display: inline-block; vertical-align: top; }
    .tp-clock, .tp-shape, .tp-angle { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    /* Regenerate spin polish */
    @keyframes build-spin { to { transform: rotate(360deg); } }
    .build-spinning { animation: build-spin .5s linear; }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

  <!-- ===== TOP BAR ===== -->
  <header class="app-header">
    <a href="<?= base_url('/') ?>" class="brand" style="display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; letter-spacing:-.02em; text-decoration:none; color:inherit;">
      <span>teacherpedia</span><span class="dot-mark" style="line-height:0;">.</span>
    </a>
    <a href="<?= base_url('browse') ?>" class="app-crumb" style="text-decoration:none;">&larr; All resources</a>
    <div class="app-divider"></div>
    <div class="app-title">Worksheet Builder</div>
    <div class="app-context"><span class="dot"></span>Numeracy · KS2</div>
    <div style="flex:1;"></div>
    <a href="<?= base_url('account') ?>" class="app-crumb" style="text-decoration:none; padding:8px 6px;">My saved sheets</a>
    <?php
      $first = session()->get('first_name') ?? '';
      $second = session()->get('second_name') ?? '';
      $initials = strtoupper(substr($first, 0, 1) . substr($second, 0, 1));
      if ($initials === '') { $initials = 'TP'; }
    ?>
    <div class="avatar"><?= esc($initials) ?></div>
  </header>

  <div class="app-body">

    <!-- ===== LEFT : OBJECTIVE LIBRARY ===== -->
    <aside class="app-panel build-panel">

      <div class="build-panel-head">
        <input id="build-title" class="fld" type="text"
               placeholder="Worksheet title — e.g. Mental Starter"
               style="font-weight:600; margin-bottom:13px;" />

        <div class="fld-search" style="margin-bottom:12px;">
          <span class="fld-icon">&#9906;</span>
          <input id="build-search" class="fld fld-soft" type="text" placeholder="Search all objectives…" />
        </div>

        <div style="display:flex; align-items:center; gap:7px;">
          <span class="build-eyebrow-lbl" style="margin-right:2px;">Year</span>
          <button type="button" class="chip" data-year="1">Y1</button>
          <button type="button" class="chip" data-year="2">Y2</button>
          <button type="button" class="chip" data-year="3">Y3</button>
          <button type="button" class="chip" data-year="4">Y4</button>
          <button type="button" class="chip" data-year="5">Y5</button>
          <button type="button" class="chip" data-year="6">Y6</button>
          <div style="flex:1;"></div>
          <button type="button" id="build-auto" class="chip" title="Show only auto-generating objectives">&#9889; Auto</button>
        </div>
      </div>

      <!-- Interactive library: rendered by build.js from window.TP_OBJECTIVES -->
      <div id="build-library" class="app-scroll build-lib" aria-live="polite"></div>

      <!-- No-JS / SEO fallback list (hidden once build.js boots) -->
      <noscript>
        <div class="build-lib">
          <?php foreach (($grouped ?? []) as $strand => $items): ?>
            <div class="build-group">
              <div class="build-strand-head">
                <span class="build-strand-name"><?= esc($strand) ?></span>
                <span class="build-strand-line"></span>
                <span class="build-strand-count"><?= count($items) ?></span>
              </div>
              <?php foreach ($items as $obj): ?>
                <div class="orow">
                  <div class="build-otext">
                    <div class="t"><?= esc($obj['text']) ?></div>
                    <div class="build-ometa"><span class="build-oyear">Year <?= (int) $obj['year'] ?></span></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </noscript>

      <div class="build-panel-foot">
        <div style="flex:1;">
          <div class="build-total-q"><span id="build-total-q">0</span> questions</div>
          <div class="build-total-sub"><span id="build-sel-count">0</span> objectives selected</div>
        </div>
        <button type="button" id="build-clear" class="btn btn-ghost btn-sm">Clear</button>
      </div>
    </aside>

    <!-- ===== RIGHT : PREVIEW ===== -->
    <main class="app-main">

      <!-- Standard resource toolbar (tabs + Save/Print/New). The builder uses
           an attainment-band selector (Below/Meeting/Exceeding) instead of the
           1-5 meter, and its own Year chips in the left panel — so the partial's
           difficulty + year rows are turned off and the band selector + sheet
           toggles go in the settings slot. -->
      <?php ob_start(); ?>
        <span class="build-eyebrow-lbl">Level</span>
        <div id="build-band" style="display:flex; gap:7px;">
          <button type="button" class="chip" data-band="below">Below</button>
          <button type="button" class="chip chip-on" data-band="meeting">Meeting</button>
          <button type="button" class="chip" data-band="exceeding">Exceeding</button>
        </div>
        <div style="flex:1;"></div>
        <button type="button" id="build-twocol" class="chip">&#9638; Two columns</button>
        <button type="button" id="build-answerspace" class="chip">&#9135; Answer space</button>
      <?php $settings_extra = ob_get_clean(); ?>
      <?= view('partials/tool_toolbar', [
            'prefix'          => 'build',
            'tabs'            => [['key' => 'worksheet', 'label' => 'Worksheet'], ['key' => 'answerkey', 'label' => 'Answer key']],
            'show_year'       => false,
            'show_difficulty' => false,
            'regen_label'     => 'Regenerate',
            'settings_extra'  => $settings_extra,
          ]) ?>

      <!-- A4 sheet preview -->
      <div class="app-scroll build-sheet-wrap">
        <div class="sheet" id="build-sheet">

          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
            <div style="min-width:0;">
              <div class="sheet-eyebrow" id="build-sheet-eyebrow">Year 6 · Numeracy · ●●●○○</div>
              <h1 class="sheet-title" id="build-sheet-title">Mixed Maths Practice</h1>
            </div>
            <div class="sheet-meta">
              <div>Name <span class="line"></span></div>
              <div>Date <span class="line"></span></div>
            </div>
          </div>
          <div class="sheet-rule"></div>

          <div id="build-keybadge" class="sheet-keybadge" style="display:none;">
            <span class="dot"></span>Answer key
          </div>

          <ul id="build-qlist" class="sheet-qlist two-col"></ul>
          <div id="build-sheet-empty" class="sheet-empty">
            Your worksheet is empty.<br>
            <span style="font-size:13px;">Add objectives from the library on the left.</span>
          </div>

          <div class="sheet-foot">
            <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
            <span id="build-sheet-foot-meta">0 questions · auto-generated · <?= date('Y') ?></span>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Saved toast -->
  <div id="build-toast" class="toast" style="display:none;">&#10003; Saved to your sheets</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_OBJECTIVES = <?= json_encode($objectives, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    <?php if (! empty($saved)): ?>
    window.TP_SAVED = <?= json_encode($saved, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    <?php endif; ?>
    window.TP_SAVE_URL = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
    window.TP_YEAR = <?= (int) date('Y') ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-skill-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/build.js') ?>"></script>
<?= $this->endSection() ?>
