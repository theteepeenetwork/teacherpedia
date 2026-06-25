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

  <!-- Standard resource toolbar (difficulty + tabs + Save/Print/New).
       Code-Breaker-specific settings (operations + secret message) are passed
       in as the settings_extra slot. -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span style="font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95;">Operations</span>
    <div id="cb-ops" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-op="+">+ Add</button>
      <button type="button" class="chip" data-op="-">&minus; Subtract</button>
      <button type="button" class="chip" data-op="&times;">&times; Multiply</button>
      <button type="button" class="chip" data-op="&divide;">&divide; Divide</button>
    </div>
    <div class="app-divider"></div>
    <span style="font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95;">Secret message</span>
    <input id="cb-word" type="text" maxlength="24" autocomplete="off" spellcheck="false"
           placeholder="e.g. WELL DONE"
           style="text-transform:uppercase; width:170px; padding:7px 11px; border:1px solid rgba(28,36,32,.16); border-radius:8px; background:#fff; font-family:var(--font-head); font-weight:700; letter-spacing:.04em; font-size:13px; color:var(--ink);" />
    <button type="button" id="cb-random" class="btn btn-ghost btn-sm" title="Pick a random message">&#127922; Random</button>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'cb',
        'tabs'           => [['key' => 'active', 'label' => 'Activity'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff_label'     => 'Set B',
        'regen_label'    => 'New puzzle',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS2 Numeracy &middot; Code Breaker &middot; <span id="cb-eyebrow-diff">Set B</span></div>
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
