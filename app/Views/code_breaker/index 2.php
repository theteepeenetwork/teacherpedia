<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Code Breaker — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

  <!-- TOP BAR -->
  <header class="app-header">
    <a href="/" class="brand" style="display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; letter-spacing:-.02em;">
      <span>teacherpedia</span><span class="dot-mark" style="line-height:0;">.</span>
    </a>
    <a href="/browse" class="app-crumb">&larr; All resources</a>
    <div class="app-divider"></div>
    <div class="app-title">Code Breaker</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- SETTINGS STRIP -->
  <div class="app-toolbar" style="flex-wrap:wrap; background:rgba(255,255,255,.42); gap:18px;">
    <span style="font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95;">Operations</span>
    <div id="cb-ops" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-op="+">+ Add</button>
      <button type="button" class="chip" data-op="-">&minus; Subtract</button>
      <button type="button" class="chip" data-op="&times;">&times; Multiply</button>
      <button type="button" class="chip" data-op="&divide;">&divide; Divide</button>
    </div>
    <div class="app-divider"></div>
    <span style="font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95;">Difficulty</span>
    <div id="cb-difficulty" class="difficulty" style="width:200px; height:38px; padding:3px;">
      <div class="diff-thumb" id="cb-diff-thumb" style="top:3px; bottom:3px; width:34px;"></div>
      <button type="button" data-d="1">1</button>
      <button type="button" data-d="2">2</button>
      <button type="button" data-d="3">3</button>
      <button type="button" data-d="4">4</button>
      <button type="button" data-d="5">5</button>
    </div>
    <span id="cb-diff-label" style="font-size:12.5px; font-weight:700; color:var(--ink);">Emerging</span>
  </div>

  <!-- TOOLBAR -->
  <div class="app-toolbar">
    <div id="cb-tabs" class="segmented">
      <div class="seg-thumb" id="cb-tab-thumb"></div>
      <button type="button" data-tab="active">Activity</button>
      <button type="button" data-tab="answers">Answer key</button>
    </div>
    <div style="flex:1;"></div>
    <button type="button" id="cb-save" class="btn btn-ghost btn-sm">&hearts; Save</button>
    <button type="button" id="cb-print" class="btn btn-ghost btn-sm">&#9113; Print / PDF</button>
    <button type="button" id="cb-regen" class="btn btn-primary btn-sm">
      <span id="cb-spin" style="display:inline-block; transition:transform .5s ease;">&#10227;</span> New puzzle
    </button>
  </div>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS2 Numeracy &middot; Code Breaker &middot; <span id="cb-eyebrow-diff">Emerging</span></div>
          <h1 class="sheet-title">Crack the Secret Code</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <p style="margin:0 0 20px; font-size:14px; color:#4a514a; line-height:1.55;">&#128269; Solve each calculation. Find your answer in the <strong>code key</strong> below and write its letter in the box. Read the letters in order to reveal the secret message!</p>

      <div style="border:1.5px solid color-mix(in oklab,var(--accent) 35%,#fff); background:color-mix(in oklab,var(--accent) 5%,#fff); border-radius:12px; padding:14px 16px; margin-bottom:24px;">
        <div style="font-size:10.5px; font-weight:800; letter-spacing:.07em; text-transform:uppercase; color:color-mix(in oklab,var(--accent) 55%,var(--ink)); margin-bottom:11px;">Code key</div>
        <div id="cb-cipher" style="display:flex; flex-wrap:wrap; gap:8px;"></div>
      </div>

      <ul id="cb-questions" class="sheet-qlist two-col" style="margin:0 0 26px;"></ul>

      <div style="border-top:1px dashed rgba(28,36,32,.2); padding-top:20px;">
        <div style="font-size:10.5px; font-weight:800; letter-spacing:.07em; text-transform:uppercase; color:#9a9f95; margin-bottom:13px;">The secret message is&hellip;</div>
        <div id="cb-message" style="display:flex; flex-wrap:wrap; gap:18px;"></div>
      </div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Code Breaker &middot; auto-generated &middot; <span id="cb-year"></span></span>
      </div>
    </div>
  </div>

  <div id="cb-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script src="<?= base_url('assets/js/code-breaker.js') ?>"></script>
<?= $this->endSection() ?>
