<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Cross-Number Crossword — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Cross-Number Crossword sheet — an inline-SVG grid on top, two clue columns
       (Across / Down) below, footer pinned at the bottom. The SVG is rendered
       DIRECTLY into #cn-grid (no nested grid). See "Layout gotchas". */
    .cn-sheet { min-height: 940px; display: flex; flex-direction: column; }
    /* Footer pinned to the bottom; the grid (sized per tier) and naturally-spaced
       clue lists sit at the top. We do NOT slack-fill the clue lists — spreading
       a handful of clues down the page reads as awkward gaps; the grid is the
       page-filling element (its --cn-grid-max grows for sparser tiers). */
    .cn-sheet .sheet-foot { margin-top: auto; }

    /* Grid block. The SVG is sized PER TIER (set as --cn-grid-max by the JS) so a
       small 5×5 Below grid prints large and an 8×8 Exceeding grid still fits one
       A4 — keeps the page full at every setting. The clue lists below flex to
       take the remaining height. Tune with the print tool
       (node dev/print-preview/preview.js --slug cross-number). */
    #cn-grid { display: flex; justify-content: center; margin: 6px 0 26px; }
    .cn-svg { display: block; width: 100%; max-width: var(--cn-grid-max, 420px); height: auto; }

    /* Two clue columns at natural height (the footer pins to the bottom). */
    .cn-clues {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 40px;
      margin-bottom: 14px;
    }
    .cn-col { display: flex; flex-direction: column; min-height: 0; }
    .cn-col h2 {
      font-family: var(--font-head, inherit);
      font-size: 14px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
      color: var(--accent); margin: 0 0 12px; padding-bottom: 7px;
      border-bottom: 1.5px solid rgba(28,36,32,.14);
    }
    .cn-col ol {
      list-style: none; margin: 0; padding: 0;
      display: flex; flex-direction: column; gap: 14px;
    }
    .cn-clue {
      font-size: 16px; line-height: 1.5; color: var(--ink, #26302a);
      font-variant-numeric: tabular-nums;
      display: flex; align-items: baseline; gap: 8px;
    }
    .cn-clue .cn-num { font-weight: 800; min-width: 24px; color: #1a1a1a; }
    .cn-clue .cn-calc { font-weight: 600; }
    .cn-clue .cn-val { font-weight: 800; color: color-mix(in oklab, var(--accent) 70%, var(--ink)); margin-left: 4px; }

    @media print {
      /* tp-print.css forces .sheet{position:absolute;min-height:0!important;padding:0!important};
         override with !important to fill the printable A4 (≈265mm). */
      .cn-sheet {
        min-height: 255mm !important;
        display: flex !important;
        flex-direction: column !important;
      }
    }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

  <header class="app-header">
    <a href="/" class="brand" style="display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; letter-spacing:-.02em;">
      <span>teacherpedia</span><span class="dot-mark" style="line-height:0;">.</span>
    </a>
    <a href="/browse" class="app-crumb">&larr; All resources</a>
    <div class="app-divider"></div>
    <div class="app-title">Cross-Number Crossword</div>
    <div class="app-context"><span class="dot"></span>KS1&ndash;2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Clues use</span>
    <div id="cn-ops" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-op="+">+ Add</button>
      <button type="button" class="chip" data-op="-">&minus; Subtract</button>
      <button type="button" class="chip" data-op="&times;">&times; Multiply</button>
      <button type="button" class="chip" data-op="&divide;">&divide; Divide</button>
      <button type="button" class="chip" data-op="f">&frac34; Fraction of</button>
      <button type="button" class="chip" data-op="%">% of</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'cn',
        'tabs'           => [['key' => 'sheet', 'label' => 'Worksheet'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New sheet',
        'settings_extra' => $settings_extra,
      ]) ?>

  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet cn-sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS1&ndash;2 Numeracy &middot; Cross-Number Crossword &middot; <span id="cn-eyebrow-diff">&#9679;&#9679;&#9679;&#9675;&#9675;</span></div>
          <h1 class="sheet-title">Cross-Number Crossword</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <!-- Keep on-sheet text to ONE short task line; the how-it-works lives on
           the info page (/resource/cross-number). -->
      <p id="cn-intro" style="margin:0 0 16px; font-size:14px; color:#4a514a; line-height:1.55;">
        Solve each clue and write <strong>one digit per square</strong>. Where an Across answer crosses a Down answer they share a square, so the digits must agree.
      </p>

      <div id="cn-grid"></div>

      <div class="cn-clues">
        <div class="cn-col"><h2>Across</h2><ol id="cn-across"></ol></div>
        <div class="cn-col"><h2>Down</h2><ol id="cn-down"></ol></div>
      </div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Cross-Number Crossword &middot; auto-generated &middot; <span id="cn-year"></span></span>
      </div>
    </div>
  </div>

  <div id="cn-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/cross-number.js') ?>"></script>
<?= $this->endSection() ?>
