<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Bingo — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Bingo cards grid — sized from a --bg-n custom property set by the JS. */
    .bg-cards { display: flex; flex-wrap: wrap; gap: 22px; }
    .bg-card {
      flex: 1 1 calc(50% - 11px);
      min-width: 240px;
      border: 2px solid var(--accent);
      border-radius: 12px;
      overflow: hidden;
      background: #fff;
      break-inside: avoid;
      page-break-inside: avoid;
    }
    .bg-card-head {
      display: flex; align-items: center; justify-content: space-between;
      gap: 8px;
      padding: 8px 12px;
      background: color-mix(in oklab, var(--accent) 12%, #fff);
      border-bottom: 2px solid var(--accent);
    }
    .bg-card-title {
      font-family: var(--font-head); font-weight: 800; letter-spacing: .18em;
      font-size: 16px; color: var(--accent);
    }
    .bg-card-code { font-size: 11px; font-weight: 700; color: color-mix(in oklab, var(--accent) 60%, var(--ink)); }
    .bg-grid {
      display: grid;
      grid-template-columns: repeat(var(--bg-n, 4), 1fr);
      gap: 0;
    }
    .bg-cell {
      aspect-ratio: 1 / 1;
      display: flex; align-items: center; justify-content: center;
      text-align: center;
      padding: 4px;
      border-right: 1px solid rgba(28,36,32,.16);
      border-bottom: 1px solid rgba(28,36,32,.16);
      font-variant-numeric: tabular-nums;
      font-weight: 700; font-size: 15px; color: var(--ink);
      line-height: 1.1;
      word-break: break-word;
    }
    .bg-cell:nth-child(var(--bg-n)) { border-right: none; }
    .bg-free {
      font-weight: 800; letter-spacing: .04em; font-size: 12px;
      color: #fff; background: var(--accent);
    }
    /* Caller list table */
    .bg-caller { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .bg-caller th, .bg-caller td {
      text-align: left; padding: 7px 10px;
      border-bottom: 1px solid rgba(28,36,32,.12);
      font-variant-numeric: tabular-nums;
    }
    .bg-caller th {
      font-size: 10.5px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase;
      color: color-mix(in oklab, var(--accent) 55%, var(--ink));
      border-bottom: 2px solid color-mix(in oklab, var(--accent) 35%, #fff);
    }
    .bg-caller td.bg-call-n { width: 56px; font-weight: 800; color: var(--accent); }
    .bg-caller td.bg-call-a { font-weight: 800; color: color-mix(in oklab, var(--accent) 65%, var(--ink)); }
    .bg-warn {
      margin: 0 0 18px; padding: 11px 14px;
      border: 1px solid #d8a13f; background: #fbf3d6; border-radius: 9px;
      font-size: 13px; color: #8a5a13; line-height: 1.5;
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
    <div class="app-title">Bingo</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- Standard resource toolbar (difficulty + tabs + Save/Print/New).
       Bingo-specific settings (objectives + grid size + number of cards) go in
       settings_extra. -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Practise</span>
    <div id="bingo-strands"></div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Grid</span>
    <div id="bingo-size" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-size="3">3&times;3</button>
      <button type="button" class="chip" data-size="4">4&times;4</button>
      <button type="button" class="chip" data-size="5">5&times;5</button>
    </div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Cards</span>
    <div id="bingo-cards-n" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-n="10">10</button>
      <button type="button" class="chip" data-n="20">20</button>
      <button type="button" class="chip" data-n="30">30</button>
      <button type="button" class="chip" data-n="35">35</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'bingo',
        'tabs'           => [['key' => 'cards', 'label' => 'Bingo cards'], ['key' => 'caller', 'label' => 'Caller']],
        'diff'           => 3,
        'year'           => 4,
        'year_min'       => 1,
        'year_max'       => 6,
        'regen_label'    => 'New game',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS2 Numeracy &middot; Bingo &middot; <span id="bingo-eyebrow-diff">●●●○○</span></div>
          <h1 class="sheet-title">Maths Bingo</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <div id="bingo-grid"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Bingo &middot; auto-generated &middot; <span id="bingo-year"></span></span>
      </div>
    </div>
  </div>

  <div id="bingo-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_OBJECTIVES = <?= json_encode($objectives ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/bingo.js') ?>"></script>
<?= $this->endSection() ?>
