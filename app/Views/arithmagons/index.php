<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Arithmagon Triangles — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Arithmagon sheet — a responsive grid of triangle puzzles. */
    .ag-grid {
      display: grid;
      grid-template-columns: repeat(var(--ag-cols, 2), 1fr);
      gap: 18px 14px;
      margin: 6px 0 10px;
    }
    .ag-card {
      margin: 0;
      border: 1.5px solid rgba(28,36,32,.12);
      border-radius: 12px;
      padding: 10px 6px 6px;
      display: flex; flex-direction: column; align-items: center;
      background: #fff;
      break-inside: avoid;
    }
    .ag-svg { display: block; max-width: 100%; height: auto; }
    .ag-cap {
      font-size: 11px; font-weight: 800; letter-spacing: .04em;
      text-transform: uppercase; color: var(--muted, #6c716a);
      margin-top: 2px;
    }
    @media print {
      .ag-card { border-color: rgba(28,36,32,.18); }
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
    <div class="app-title">Arithmagon Triangles</div>
    <div class="app-context"><span class="dot"></span>KS1&ndash;2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- Standard resource toolbar + Arithmagon-specific settings (operation,
       challenge level, puzzles per sheet). -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Operation</span>
    <div id="ag-ops" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-op="+">+ Add</button>
      <button type="button" class="chip" data-op="&times;">&times; Multiply</button>
    </div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Challenge</span>
    <div id="ag-pattern" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-pat="forward" title="Corners given — combine to find the edges">Forward</button>
      <button type="button" class="chip" data-pat="inverse" title="Edges given — reason back to the corners">Inverse</button>
      <button type="button" class="chip" data-pat="mixed" title="One corner + two edges given">Mixed</button>
    </div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Puzzles</span>
    <div id="ag-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="4">4</button>
      <button type="button" class="chip" data-count="6">6</button>
      <button type="button" class="chip" data-count="9">9</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'ag',
        'tabs'           => [['key' => 'puzzle', 'label' => 'Puzzle'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New puzzles',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS1&ndash;2 Numeracy &middot; Arithmagon Triangles &middot; <span id="ag-eyebrow-diff">&#9679;&#9679;&#9679;&#9675;&#9675;</span></div>
          <h1 class="sheet-title">Arithmagon Triangles</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <p id="ag-intro" style="margin:0 0 16px; font-size:14px; color:#4a514a; line-height:1.55;">
        &#9651; Fill in every empty circle and box.
      </p>

      <div id="ag-grid" class="ag-grid"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Arithmagon Triangles &middot; auto-generated &middot; <span id="ag-year"></span></span>
      </div>
    </div>
  </div>

  <div id="ag-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/arithmagons.js') ?>"></script>
<?= $this->endSection() ?>
