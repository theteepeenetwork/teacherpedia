<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Column Methods — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Column-method grid — problems laid out in 2-3 columns; each problem is a
       self-contained, right-aligned, tabular block so digits line up. */
    .col-grid {
      display: grid;
      grid-template-columns: repeat(var(--col-n, 3), 1fr);
      gap: 26px 30px;
      margin: 6px 0 26px;
    }
    .col-prob {
      break-inside: avoid;
      page-break-inside: avoid;
      display: flex;
      gap: 9px;
      align-items: flex-start;
    }
    .col-prob .col-num {
      font-size: 12px; font-weight: 800;
      color: color-mix(in oklab, var(--accent) 60%, var(--ink));
      min-width: 16px; line-height: 1.5;
      padding-top: 2px;
    }
    .col-prob .col-body { flex: 1; min-width: 0; }

    /* Vertical stacks for add / subtract / multiply. */
    .col-stack {
      display: inline-block;
      text-align: right;
      font-variant-numeric: tabular-nums;
      font-family: var(--font-mono, ui-monospace, "SF Mono", Menlo, Consolas, monospace);
      font-size: 19px;
      font-weight: 700;
      color: var(--ink);
      line-height: 1.42;
      letter-spacing: .06em;
      min-width: 86px;
    }
    .col-stack .col-row { white-space: pre; }
    .col-stack .col-op-row { display: flex; justify-content: space-between; gap: 10px; }
    .col-stack .col-op { font-weight: 800; }
    .col-stack .col-bar {
      border-top: 2px solid var(--ink);
      margin: 3px 0;
      height: 0;
    }
    .col-stack .col-ans { min-height: 1.42em; }
    .col-stack .col-ans.blank { color: transparent; }

    /* Bus-stop layout for division. */
    .col-bus {
      display: inline-flex;
      align-items: stretch;
      font-variant-numeric: tabular-nums;
      font-family: var(--font-mono, ui-monospace, "SF Mono", Menlo, Consolas, monospace);
      font-size: 19px;
      font-weight: 700;
      color: var(--ink);
      letter-spacing: .06em;
    }
    .col-bus .col-divisor {
      align-self: flex-end;
      padding: 0 6px 2px 0;
      line-height: 1.4;
    }
    .col-bus .col-house {
      border-left: 2px solid var(--ink);
      border-top: 2px solid var(--ink);
      border-top-left-radius: 7px;
      padding: 2px 10px 0 11px;
    }
    .col-bus .col-quot {
      min-height: 1.3em;
      line-height: 1.3;
      text-align: left;
    }
    .col-bus .col-quot.blank { color: transparent; }
    .col-bus .col-dividend { line-height: 1.4; padding-top: 1px; }

    @media print { .col-grid { gap: 24px 26px; } }
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
    <div class="app-title">Column Methods</div>
    <div class="app-context"><span class="dot"></span>KS1-2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- Standard resource toolbar (year + difficulty + tabs + Save/Print/New).
       Column-specific settings (single-select operation + question count) go in
       the settings_extra slot. -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Operation</span>
    <div id="col-op" style="display:flex; gap:7px;">
      <button type="button" class="chip<?= $op === 'add' ? ' chip-on' : '' ?>" data-cop="add">+ Addition</button>
      <button type="button" class="chip<?= $op === 'subtract' ? ' chip-on' : '' ?>" data-cop="subtract">&minus; Subtraction</button>
      <button type="button" class="chip<?= $op === 'multiply' ? ' chip-on' : '' ?>" data-cop="multiply">&times; Multiplication</button>
      <button type="button" class="chip<?= $op === 'divide' ? ' chip-on' : '' ?>" data-cop="divide">&divide; Division</button>
    </div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Questions</span>
    <div id="col-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="8">8</button>
      <button type="button" class="chip chip-on" data-count="12">12</button>
      <button type="button" class="chip" data-count="16">16</button>
      <button type="button" class="chip" data-count="20">20</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'col',
        'tabs'           => [['key' => 'worksheet', 'label' => 'Worksheet'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'year'           => 4,
        'year_min'       => 2,
        'year_max'       => 6,
        'regen_label'    => 'New sheet',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow"><span id="col-eyebrow">Column Methods</span></div>
          <h1 class="sheet-title">Written Methods</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <p style="margin:0 0 18px; font-size:14px; color:#4a514a; line-height:1.55;">
        &#9999;&#65039; Work out each calculation using the formal written method. Set out your working neatly and line up the digits in their columns.
      </p>

      <div id="col-grid" class="col-grid"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Column Methods &middot; auto-generated &middot; <span id="col-year"></span></span>
      </div>
    </div>
  </div>

  <div id="col-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_COL_OP    = <?= json_encode($op) ?>;
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/columns.js') ?>"></script>
<?= $this->endSection() ?>
