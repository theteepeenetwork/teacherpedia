<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>__NAME__ — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* __NAME__ sheet — a grid of items that fills the page (see
       dev/RESOURCE_WORKFLOW.md "Layout gotchas"). */
    .__PREFIX__-grid {
      display: grid;
      grid-template-columns: repeat(var(--__PREFIX__-cols, 2), 1fr);
      gap: 14px;
      margin: 12px 0 14px;
    }
    .__PREFIX__-card {
      margin: 0;
      border: 1.5px solid rgba(28,36,32,.12);
      border-radius: 14px;
      padding: 8px;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      background: #fff;
      break-inside: avoid;
    }
    /* Item fills the card width but is capped so every item count fits one A4
       page; centred in wider (2-column) layouts. Tune the cap with the print
       tool (node dev/print-preview/preview.js --slug __SLUG__). */
    .__PREFIX__-svg { display: block; width: 100%; max-width: 220px; height: auto; margin: 0 auto; }
    .__PREFIX__-cap {
      font-size: 12px; font-weight: 800; letter-spacing: .04em;
      text-transform: uppercase; color: var(--muted, #6c716a);
      margin-top: 6px;
    }
    /* Fill the page: compact cards, rows spread with align-content:space-between
       (NOT stretched cards), footer at the bottom. Same on screen and print. */
    .__PREFIX__-sheet { min-height: 940px; display: flex; flex-direction: column; }
    .__PREFIX__-sheet #__PREFIX__-grid { flex: 1 1 auto; align-content: space-between; }
    .__PREFIX__-sheet .sheet-foot { margin-top: 14px; }

    @media print {
      /* tp-print.css forces .sheet{position:absolute;min-height:0!important;padding:0!important};
         override with !important to fill the printable A4 (≈265mm). */
      .__PREFIX__-sheet {
        min-height: 255mm !important;
        display: flex !important;
        flex-direction: column !important;
      }
      .__PREFIX__-card { border-color: rgba(28,36,32,.22); }
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
    <div class="app-title">__NAME__</div>
    <div class="app-context"><span class="dot"></span>KS1&ndash;2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <?php ob_start(); ?>
    <!-- TODO: resource-specific settings chips. Example: items per sheet. -->
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Items</span>
    <div id="__PREFIX__-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="4">4</button>
      <button type="button" class="chip" data-count="6">6</button>
      <button type="button" class="chip" data-count="9">9</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => '__PREFIX__',
        'tabs'           => [['key' => 'sheet', 'label' => 'Worksheet'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New sheet',
        'settings_extra' => $settings_extra,
      ]) ?>

  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet __PREFIX__-sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS1&ndash;2 Numeracy &middot; __NAME__ &middot; <span id="__PREFIX__-eyebrow-diff">&#9679;&#9679;&#9679;&#9675;&#9675;</span></div>
          <h1 class="sheet-title">__NAME__</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <!-- Keep on-sheet text to ONE short task line; the how-it-works lives on
           the info page (/resource/__SLUG__). -->
      <p id="__PREFIX__-intro" style="margin:0 0 16px; font-size:14px; color:#4a514a; line-height:1.55;">
        TODO: one short task line for the child.
      </p>

      <div id="__PREFIX__-grid" class="__PREFIX__-grid"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>__NAME__ &middot; auto-generated &middot; <span id="__PREFIX__-year"></span></span>
      </div>
    </div>
  </div>

  <div id="__PREFIX__-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/__SLUG__.js') ?>"></script>
<?= $this->endSection() ?>
