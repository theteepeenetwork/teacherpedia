<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Maths Maze — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Maze grid — squares sized from a --mm-n custom property set by the JS. */
    .mm-grid {
      display: grid;
      grid-template-columns: repeat(var(--mm-n, 5), 1fr);
      gap: 7px;
      margin: 4px 0 22px;
    }
    .mm-cell {
      position: relative;
      aspect-ratio: 1 / 1;
      border: 1.5px solid rgba(28,36,32,.18);
      border-radius: 9px;
      background: #fff;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 3px;
      padding: 4px;
      text-align: center;
      font-variant-numeric: tabular-nums;
      cursor: pointer;
      transition: background .12s, border-color .12s, transform .08s;
    }
    .mm-cell:hover { border-color: var(--accent); }
    .mm-cell .mm-q { font-weight: 700; font-size: 15px; color: var(--ink); line-height: 1.1; }
    .mm-cell .mm-a {
      font-size: 12px; font-weight: 800;
      color: color-mix(in oklab, var(--accent) 65%, var(--ink));
    }
    .mm-cell .mm-tag {
      font-size: 9px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
      color: #fff; background: var(--accent); border-radius: 5px; padding: 1px 6px;
    }
    /* States */
    .mm-cell.mm-endpoint { border-color: var(--accent); border-width: 2px; }
    .mm-cell.mm-correct {
      background: color-mix(in oklab, var(--accent) 16%, #fff);
      border-color: var(--accent); border-width: 2px;
    }
    .mm-cell.mm-dead {
      background: #fdecea; border-color: #d8553f;
    }
    .mm-cell.mm-dead .mm-q { color: #b23c28; }
    /* Answer-key reveal: highlight the true path */
    .mm-cell.mm-path {
      background: color-mix(in oklab, var(--accent) 14%, #fff);
      border-color: var(--accent);
    }
    .mm-status {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: 13px; font-weight: 700; color: var(--muted);
      min-height: 22px;
    }
    .mm-status .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
    @media print { .mm-cell { cursor: default; } .mm-cell:hover { border-color: rgba(28,36,32,.18); } }
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
    <div class="app-title">Maths Maze</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- Standard resource toolbar (difficulty + tabs + Save/Print/New).
       Maze-specific settings (operations + grid size) go in settings_extra. -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Operations</span>
    <div id="mm-ops" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-op="+">+ Add</button>
      <button type="button" class="chip" data-op="-">&minus; Subtract</button>
      <button type="button" class="chip" data-op="&times;">&times; Multiply</button>
      <button type="button" class="chip" data-op="&divide;">&divide; Divide</button>
    </div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Grid</span>
    <div id="mm-size" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-size="4">4&times;4</button>
      <button type="button" class="chip" data-size="5">5&times;5</button>
      <button type="button" class="chip" data-size="6">6&times;6</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'mm',
        'tabs'           => [['key' => 'puzzle', 'label' => 'Puzzle'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New maze',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS2 Numeracy &middot; Maths Maze &middot; <span id="mm-eyebrow-diff">●●●○○</span></div>
          <h1 class="sheet-title">Find the Path</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <p style="margin:0 0 16px; font-size:14px; color:#4a514a; line-height:1.55;">
        &#129513; Start at <strong>START</strong> and move up, down, left or right through touching squares to reach <strong>FINISH</strong>.
        Solve each square&rsquo;s calculation: <strong id="mm-rule-text">step only on squares with an even answer</strong>.
        A wrong turn is a dead end!
      </p>

      <div class="mm-status" id="mm-status"><span class="dot"></span><span id="mm-status-text">Click START to begin.</span></div>

      <div id="mm-grid" class="mm-grid"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Maths Maze &middot; auto-generated &middot; <span id="mm-year"></span></span>
      </div>
    </div>
  </div>

  <div id="mm-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/maths-maze.js') ?>"></script>
<?= $this->endSection() ?>
