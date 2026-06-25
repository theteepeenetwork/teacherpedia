<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Loop Cards — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Loop Cards — domino-style cards split [ ANSWER | QUESTION ]. */
    .lc-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
      margin: 4px 0 8px;
    }
    .lc-card {
      position: relative;
      display: flex;
      align-items: stretch;
      min-height: 86px;
      border: 1.5px dashed rgba(28,36,32,.32); /* faint cut lines */
      border-radius: 12px;
      background: #fff;
      overflow: hidden;
      break-inside: avoid;
      page-break-inside: avoid;
    }
    .lc-card .lc-station {
      position: absolute; top: 6px; right: 9px;
      font-size: 9px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase;
      color: #b8bcb2;
    }
    .lc-half {
      flex: 1; min-width: 0;
      display: flex; flex-direction: column; justify-content: center;
      gap: 4px; padding: 14px 16px;
      text-align: center;
      font-variant-numeric: tabular-nums;
    }
    .lc-half-lbl {
      font-size: 9px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
      color: #9a9f95;
    }
    /* LEFT half — the ANSWER to someone else's question (big). */
    .lc-answer-half {
      background: color-mix(in oklab, var(--accent) 9%, #fff);
    }
    .lc-answer-half .lc-answer-val {
      font-family: var(--font-head); font-weight: 800; font-size: 24px; line-height: 1.05;
      color: color-mix(in oklab, var(--accent) 70%, var(--ink));
      word-break: break-word;
    }
    /* RIGHT half — the QUESTION. */
    .lc-q-half .lc-q-val {
      font-weight: 700; font-size: 15px; line-height: 1.2; color: var(--ink);
      word-break: break-word;
    }
    .lc-divider {
      width: 0; border-left: 1.5px dashed rgba(28,36,32,.32);
      align-self: stretch; margin: 8px 0;
    }
    /* Answer-key loop table */
    .lc-loop-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .lc-loop-table th, .lc-loop-table td {
      text-align: left; padding: 8px 10px; border-bottom: 1px solid rgba(28,36,32,.1);
      vertical-align: top;
    }
    .lc-loop-table th {
      font-size: 10px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
      color: #9a9f95;
    }
    .lc-loop-table td.lc-ord { font-weight: 800; color: var(--muted); width: 48px; }
    .lc-loop-table td.lc-ans {
      font-weight: 800; font-variant-numeric: tabular-nums;
      color: color-mix(in oklab, var(--accent) 70%, var(--ink));
    }
    .lc-loop-table td.lc-qn { font-variant-numeric: tabular-nums; }
    .lc-warn {
      font-size: 12.5px; font-weight: 600; color: #b23c28;
      background: #fdecea; border: 1px solid #f3c6bd; border-radius: 9px;
      padding: 10px 13px; margin: 4px 0 16px; line-height: 1.45;
    }
    @media print {
      .lc-grid { gap: 18px; }
      .lc-card { box-shadow: none; }
    }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

  <!-- TOP BAR -->
  <header class="app-header">
    <a href="/" class="brand" style="display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; letter-spacing:-.02em;">
      <span>teacherpedia</span><span class="dot-mark" style="line-height:0;">.</span>
    </a>
    <a href="/browse" class="app-crumb">&larr; All resources</a>
    <div class="app-divider"></div>
    <div class="app-title">Loop Cards</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- Standard resource toolbar (difficulty + tabs + Save/Print/New).
       Loop-Cards-specific settings (strands to practise + deck size) go in
       settings_extra. -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Practise</span>
    <div id="lc-strands" style="display:flex; flex-wrap:wrap; gap:7px;"></div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Deck</span>
    <div id="lc-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="8">8</button>
      <button type="button" class="chip" data-count="12">12</button>
      <button type="button" class="chip" data-count="16">16</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'lc',
        'tabs'           => [['key' => 'cards', 'label' => 'Cards'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New deck',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS2 Numeracy &middot; Loop Cards &middot; <span id="lc-eyebrow-diff">●●●○○</span></div>
          <h1 class="sheet-title">Loop Cards</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <p style="margin:0 0 16px; font-size:14px; color:#4a514a; line-height:1.55;">
        &#128279; Cut out the cards and deal them around. Start anywhere and read the
        <strong>question</strong> on your card. Whoever holds the matching
        <strong>answer</strong> reads it, then their question &mdash; keep going until
        the cards form one continuous loop back to the start.
      </p>

      <div id="lc-warn" class="lc-warn" style="display:none;"></div>

      <div id="lc-grid" class="lc-grid"></div>
      <div id="lc-answer" style="display:none;"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Loop Cards &middot; auto-generated &middot; <span id="lc-year"></span></span>
      </div>
    </div>
  </div>

  <div id="lc-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_OBJECTIVES = <?= json_encode($objectives, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/loop-cards.js') ?>"></script>
<?= $this->endSection() ?>
