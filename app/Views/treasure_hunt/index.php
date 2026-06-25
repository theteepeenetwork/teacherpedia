<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Treasure Hunt — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Trail cards — a printed grid of clue cards, two per row. Each card shows
       a station letter + theme icon, a big ANSWER at the top, a divider and the
       QUESTION at the bottom. break-inside:avoid keeps cards whole on print. */
    .th-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
      margin: 4px 0 8px;
    }
    .th-card {
      break-inside: avoid;
      border: 1.5px solid color-mix(in oklab, var(--accent) 35%, rgba(28,36,32,.18));
      border-radius: 12px;
      background: #fff;
      padding: 14px 16px 16px;
      display: flex;
      flex-direction: column;
      min-height: 150px;
    }
    .th-card-top {
      display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .th-station {
      width: 30px; height: 30px; flex-shrink: 0;
      border-radius: 8px; background: var(--accent); color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-head); font-weight: 800; font-size: 16px;
    }
    .th-icon { font-size: 19px; line-height: 1; }
    .th-answer-lbl {
      font-size: 9.5px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
      color: color-mix(in oklab, var(--accent) 55%, var(--ink)); margin: 12px 0 2px;
    }
    .th-answer {
      font-family: var(--font-head); font-weight: 800; font-size: 30px; line-height: 1.05;
      color: var(--ink); font-variant-numeric: tabular-nums; word-break: break-word;
    }
    .th-card-rule { height: 1px; background: rgba(28,36,32,.12); margin: 13px 0; }
    .th-question-lbl {
      font-size: 9.5px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
      color: #9a9f95; margin-bottom: 3px;
    }
    .th-question {
      font-size: 16px; font-weight: 600; color: #2c322c; line-height: 1.3;
      font-variant-numeric: tabular-nums; word-break: break-word; margin-top: auto;
    }
    /* Answer-order (teacher solution) tab */
    .th-order {
      font-size: 15px; font-weight: 600; line-height: 1.7; color: var(--ink);
      margin: 4px 0 22px; word-break: break-word;
    }
    .th-order .th-letter {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 24px; height: 24px; padding: 0 5px; margin: 0 1px;
      border-radius: 6px; background: color-mix(in oklab, var(--accent) 14%, #fff);
      border: 1.5px solid var(--accent); color: var(--accent);
      font-family: var(--font-head); font-weight: 800; font-size: 13px;
    }
    .th-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .th-table th, .th-table td {
      text-align: left; padding: 7px 10px; border-bottom: 1px solid rgba(28,36,32,.1);
      vertical-align: top; line-height: 1.35;
    }
    .th-table th {
      font-size: 10px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
      color: #9a9f95;
    }
    .th-table td.th-t-station { font-weight: 800; color: var(--accent); width: 56px; }
    .th-table td.th-t-answer { font-weight: 700; font-variant-numeric: tabular-nums; white-space: nowrap; }
    .th-warn {
      border: 1.5px solid #d8a23f; background: #fdf4e2; border-radius: 10px;
      padding: 12px 14px; font-size: 13px; font-weight: 600; color: #8a5a16; margin-bottom: 14px;
    }
    @media print { .th-card { box-shadow: none; } }
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
    <div class="app-title">Treasure Hunt</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- Standard resource toolbar (difficulty + tabs + Save/Print/New).
       Treasure-Hunt-specific settings (practise strands + card count) go in
       settings_extra. -->
  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Practise</span>
    <div id="th-strands" style="display:flex; flex-wrap:wrap; gap:7px;"></div>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Cards</span>
    <div id="th-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="12">12</button>
      <button type="button" class="chip" data-count="16">16</button>
      <button type="button" class="chip" data-count="20">20</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'th',
        'tabs'           => [['key' => 'cards', 'label' => 'Trail cards'], ['key' => 'answers', 'label' => 'Answer order']],
        'diff'           => 3,
        'year'           => 4,
        'year_min'       => 3,
        'year_max'       => 6,
        'regen_label'    => 'New trail',
        'settings_extra' => $settings_extra,
      ]) ?>

  <!-- DESK -->
  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow">KS2 Numeracy &middot; Treasure Hunt &middot; <span id="th-eyebrow-diff">●●●○○</span></div>
          <h1 class="sheet-title">Maths Trail</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 18px;"></div>

      <p style="margin:0 0 18px; font-size:14px; color:#4a514a; line-height:1.55;">
        &#128506;&#65039; Print and cut out the cards, then place them around the room.
        Start anywhere: solve the <strong>question</strong> at the bottom, hunt for the card whose
        <strong>answer</strong> matches at the top, and keep going. The trail forms one loop that visits
        every card once &mdash; a wrong answer breaks the trail!
      </p>

      <div id="th-grid" class="th-grid"></div>
      <div id="th-answer" style="display:none;"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Treasure Hunt &middot; auto-generated &middot; <span id="th-year"></span></span>
      </div>
    </div>
  </div>

  <div id="th-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_OBJECTIVES = <?= json_encode($objectives, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/treasure-hunt.js') ?>"></script>
<?= $this->endSection() ?>
