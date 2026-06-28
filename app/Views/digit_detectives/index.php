<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Digit Detectives — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Digit Detectives sheet — a Codebook strip across the top, a grid of column-
       addition cards (rendered DIRECTLY into #dd-grid — no nested grid), and a
       reveal footer of lettered slots pinned at the bottom. See "Layout gotchas"
       in dev/RESOURCE_WORKFLOW.md. */
    .dd-sheet { min-height: 940px; display: flex; flex-direction: column; }

    /* Codebook strip: digit -> letter cipher key. */
    .dd-codebook {
      border: 1.4px solid rgba(52,80,122,.30); border-radius: 12px;
      padding: 9px 12px; margin: 0 0 14px;
      background: color-mix(in oklab, var(--accent) 6%, #fff);
      display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .dd-cb-title {
      font-family: var(--font-head, inherit);
      font-size: 12px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase;
      color: var(--accent); white-space: nowrap;
    }
    .dd-cb-keys { display: flex; gap: 9px 14px; flex-wrap: wrap; flex: 1; justify-content: space-between; }
    .dd-cb-key {
      display: inline-flex; align-items: center; gap: 3px;
      font-size: 14px; font-variant-numeric: tabular-nums;
    }
    .dd-cb-key b { font-weight: 800; color: #1a1a1a; }
    .dd-cb-key i { color: var(--accent); font-style: normal; font-size: 12px; }
    .dd-cb-key u { font-weight: 800; text-decoration: none; color: var(--accent); }

    /* The card grid IS the grid element — items go straight in. Compact cards;
       rows spread with align-content:space-between so slack falls between rows. */
    #dd-grid {
      display: grid;
      grid-template-columns: repeat(var(--dd-cols, 2), 1fr);
      gap: 12px 16px;
      flex: 1 1 auto;
      align-content: space-between;
      margin: 2px 0 12px;
    }
    .dd-card {
      margin: 0;
      border: 1.5px solid rgba(28,36,32,.14);
      border-radius: 14px;
      padding: 12px 10px 14px;
      min-height: var(--dd-cardh, 150px);
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      background: #fff;
      position: relative;
      break-inside: avoid;
    }
    .dd-card-no {
      position: absolute; top: 8px; left: 10px;
      font-size: 11px; font-weight: 800; letter-spacing: .04em;
      color: var(--accent);
    }

    /* The formal column sum: a CSS grid, one narrow operator gutter + N digit
       columns. Monospaced tabular cells; capped width so 4/6/9-up all fit one
       A4 (gotcha 4). */
    .dd-sum {
      display: grid;
      gap: 2px 2px;
      width: 100%; max-width: 188px; margin: 4px auto 0;
      font-variant-numeric: tabular-nums;
      font-family: "SFMono-Regular", ui-monospace, "Menlo", "Consolas", monospace;
    }
    .dd-op { display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; color: #1a1a1a; }
    .dd-cell {
      display: flex; align-items: center; justify-content: center;
      height: 30px; font-size: 22px; font-weight: 700; color: #1a1a1a;
    }
    /* A blank: an OUTLINED box (distinguishable from solid given glyphs in B&W),
       with a small superscript label. */
    .dd-blank {
      position: relative;
      border: 1.6px solid var(--accent);
      border-radius: 5px;
      background: color-mix(in oklab, var(--accent) 5%, #fff);
    }
    .dd-bl-lab {
      position: absolute; top: -7px; right: -4px;
      font-size: 9px; font-weight: 800; line-height: 1;
      color: var(--accent);
      background: #fff; padding: 0 1px; border-radius: 3px;
      font-family: var(--font-head, sans-serif);
    }
    .dd-blank.dd-solved { color: var(--accent); }
    .dd-rule { height: 0; border-top: 2px solid #1a1a1a; align-self: center; margin: 2px 0; }
    /* carry ticks (answer key only) */
    .dd-carry { display: flex; align-items: flex-end; justify-content: center; height: 14px; }
    .dd-carry small { font-size: 10px; font-weight: 700; color: var(--accent); line-height: 1; }

    /* Reveal footer: a row of lettered slots per card, captioned. */
    .dd-reveal {
      border-top: 1.5px solid rgba(28,36,32,.14);
      padding-top: 12px; margin-top: auto;
    }
    .dd-reveal-cap {
      font-size: 13px; font-weight: 700; color: #4a514a; margin-bottom: 10px;
    }
    .dd-reveal-rows { display: flex; flex-wrap: wrap; gap: 10px 22px; }
    .dd-reveal-word { display: inline-flex; align-items: center; gap: 5px; }
    .dd-rw-no {
      font-size: 11px; font-weight: 800; color: var(--accent);
      min-width: 16px; text-align: right;
    }
    .dd-slot {
      position: relative;
      width: 24px; height: 28px;
      border: 1.4px solid rgba(28,36,32,.40);
      border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
    }
    .dd-slot-lab {
      position: absolute; top: -7px; left: 50%; transform: translateX(-50%);
      font-size: 8px; font-weight: 800; color: var(--muted, #6c716a);
      background: #fff; padding: 0 2px;
    }
    .dd-slot-ltr { font-size: 17px; font-weight: 800; color: var(--accent); }
    .dd-rw-word {
      margin-left: 6px; font-size: 16px; font-weight: 800; letter-spacing: .12em;
      color: var(--accent); text-transform: uppercase;
    }
    .dd-rw-word i { font-style: italic; font-weight: 600; letter-spacing: 0; text-transform: none; color: #b0593a; }

    @media print {
      /* tp-print.css forces .sheet{position:absolute;min-height:0!important;padding:0!important};
         override with !important to fill the printable A4 (≈265mm). */
      .dd-sheet {
        min-height: 255mm !important;
        display: flex !important;
        flex-direction: column !important;
        padding: 0 !important;
      }
      .dd-card { border-color: rgba(28,36,32,.30); }
      .dd-blank { background: #fff; }
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
    <div class="app-title">Digit Detectives</div>
    <div class="app-context"><span class="dot"></span>KS1&ndash;2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Items</span>
    <div id="dd-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="4">4</button>
      <button type="button" class="chip" data-count="6">6</button>
      <button type="button" class="chip" data-count="9">9</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'dd',
        'tabs'           => [['key' => 'sheet', 'label' => 'Worksheet'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New sheet',
        'settings_extra' => $settings_extra,
        // Column addition with exchanging is a Year 3-6 method; Y1/Y2 are shown
        // disabled so no off-curriculum sheet is generated.
        'year'           => 4,
        'year_min'       => 3,
        'year_max'       => 6,
      ]) ?>

  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet dd-sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS1&ndash;2 Numeracy &middot; Digit Detectives &middot; <span id="dd-eyebrow-diff">&#9679;&#9679;&#9679;&#9675;&#9675;</span></div>
          <h1 class="sheet-title">Digit Detectives</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 14px;"></div>

      <div id="dd-codebook"></div>

      <!-- Keep on-sheet text to ONE short task line; the how-it-works lives on
           the info page (/resource/digit-detectives). -->
      <p id="dd-intro" style="margin:0 0 12px; font-size:14px; color:#4a514a; line-height:1.55;">
        Find each missing digit, then use the codebook to spell each secret word.
      </p>

      <div id="dd-grid"></div>

      <div id="dd-reveal"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Digit Detectives &middot; auto-generated &middot; <span id="dd-year"></span></span>
      </div>
    </div>
  </div>

  <div id="dd-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/digit-detectives.js') ?>"></script>
<?= $this->endSection() ?>
