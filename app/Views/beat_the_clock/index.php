<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Beat the Clock — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Beat the Clock — screen-only timed fluency challenge. ------------------ */
    .btc-card {
      background: #fff;
      border-radius: 18px;
      border: 1px solid rgba(28,36,32,.08);
      box-shadow: 0 10px 30px rgba(28,36,32,.08);
      width: 100%;
      max-width: 560px;
      padding: 40px 38px;
      text-align: center;
    }
    .btc-eyebrow {
      font-size: 11px; font-weight: 800; letter-spacing: .09em;
      text-transform: uppercase; color: var(--accent);
      margin-bottom: 8px;
    }
    .btc-heading {
      font-family: var(--font-head);
      font-weight: 800; font-size: 30px; line-height: 1.05;
      letter-spacing: -.02em; color: var(--ink);
      margin: 0 0 10px;
    }
    .btc-sub { font-size: 14px; color: #5c6159; line-height: 1.55; margin: 0 0 26px; }

    /* Running state */
    .btc-timer {
      font-family: var(--font-head);
      font-weight: 800; font-variant-numeric: tabular-nums;
      font-size: 46px; line-height: 1; color: var(--accent);
      margin: 0 0 6px;
    }
    .btc-timer.btc-low { color: #d8553f; }
    .btc-timer-lbl {
      font-size: 11px; font-weight: 800; letter-spacing: .1em;
      text-transform: uppercase; color: var(--muted2, #9a9f95);
      margin-bottom: 22px;
    }
    .btc-question {
      font-family: var(--font-head);
      font-weight: 800; font-variant-numeric: tabular-nums;
      font-size: 34px; line-height: 1.15; color: var(--ink);
      min-height: 44px; margin: 6px 0 18px; word-break: break-word;
    }
    .btc-input {
      width: 220px; max-width: 80%;
      padding: 12px 14px; text-align: center;
      border: 2px solid rgba(28,36,32,.16); border-radius: 11px;
      background: #fff; font-family: var(--font-head);
      font-weight: 800; font-size: 24px; color: var(--ink);
      font-variant-numeric: tabular-nums;
    }
    .btc-input:focus { outline: none; border-color: var(--accent); }
    .btc-input.btc-right { border-color: #2f9e57; background: #effaf1; }
    .btc-input.btc-wrong { border-color: #d8553f; background: #fdecea; }
    .btc-feedback { min-height: 26px; font-size: 24px; font-weight: 800; margin: 14px 0 2px; }
    .btc-feedback.btc-right { color: #2f9e57; }
    .btc-feedback.btc-wrong { color: #d8553f; }
    .btc-score {
      font-size: 13px; font-weight: 700; color: var(--muted, #6c716a);
      font-variant-numeric: tabular-nums; margin-top: 12px;
    }

    /* Results state */
    .btc-stats { display: flex; justify-content: center; gap: 30px; margin: 18px 0 22px; }
    .btc-stat .n {
      font-family: var(--font-head); font-weight: 800; font-size: 34px;
      color: var(--accent); font-variant-numeric: tabular-nums; line-height: 1;
    }
    .btc-stat .l {
      font-size: 11px; font-weight: 800; letter-spacing: .07em;
      text-transform: uppercase; color: var(--muted2, #9a9f95); margin-top: 6px;
    }
    .btc-wronglist {
      text-align: left; margin: 8px 0 24px;
      border-top: 1px dashed rgba(28,36,32,.16); padding-top: 16px;
    }
    .btc-wronglist h4 {
      font-size: 10.5px; font-weight: 800; letter-spacing: .07em;
      text-transform: uppercase; color: #9a9f95; margin: 0 0 11px;
    }
    .btc-wronglist ul { list-style: none; margin: 0; padding: 0; }
    .btc-wronglist li {
      display: flex; justify-content: space-between; gap: 14px;
      padding: 6px 0; font-size: 13.5px; line-height: 1.4;
      border-bottom: 1px solid rgba(28,36,32,.06);
      font-variant-numeric: tabular-nums;
    }
    .btc-wronglist .q { color: var(--ink); flex: 1; min-width: 0; }
    .btc-wronglist .given { color: #d8553f; font-weight: 700; text-decoration: line-through; }
    .btc-wronglist .right { color: #2f9e57; font-weight: 800; }

    .btc-length { display: flex; gap: 7px; }

    @media print { .app-scroll { display: none !important; } }
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
    <div class="app-title">Beat the Clock</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <!-- SETTINGS STRIP -->
  <div class="app-toolbar" style="border-bottom:1px solid rgba(28,36,32,.07); background:rgba(255,255,255,.4); flex-wrap:wrap; gap:14px;">
    <span class="build-eyebrow-lbl">Difficulty</span>
    <div id="btc-difficulty" class="difficulty" style="width:200px;">
      <div class="diff-thumb"></div>
      <button type="button" data-diff="1">1</button>
      <button type="button" data-diff="2">2</button>
      <button type="button" data-diff="3">3</button>
      <button type="button" data-diff="4">4</button>
      <button type="button" data-diff="5">5</button>
    </div>
    <span id="btc-diff-label" class="build-diff-label"></span>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Practise</span>
    <div id="btc-strands" style="display:flex; flex-wrap:wrap; gap:7px;"></div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Length</span>
    <div id="btc-length" class="btc-length">
      <button type="button" class="chip" data-secs="60">1 min</button>
      <button type="button" class="chip" data-secs="120">2 min</button>
      <button type="button" class="chip" data-secs="180">3 min</button>
    </div>
  </div>

  <!-- PLAY AREA -->
  <div class="app-scroll" style="flex:1; padding:48px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="btc-card">

      <!-- READY -->
      <div id="btc-ready">
        <div class="btc-eyebrow">KS2 Numeracy &middot; Fluency sprint</div>
        <h1 class="btc-heading">Beat the Clock</h1>
        <p class="btc-sub">Answer as many questions as you can before time runs out. Press <strong>Enter</strong> to submit each answer &mdash; the next question appears instantly. Choose your strands, difficulty and length above, then go!</p>
        <button type="button" id="btc-start" class="btn btn-primary">&#9654; Start</button>
      </div>

      <!-- RUNNING -->
      <div id="btc-running" style="display:none;">
        <div id="btc-timer" class="btc-timer">2:00</div>
        <div class="btc-timer-lbl">Time left</div>
        <div id="btc-question" class="btc-question"></div>
        <input id="btc-input" class="btc-input" type="text" inputmode="numeric"
               autocomplete="off" spellcheck="false" />
        <div id="btc-feedback" class="btc-feedback"></div>
        <div id="btc-score" class="btc-score">Score 0</div>
      </div>

      <!-- RESULTS -->
      <div id="btc-results" style="display:none;">
        <div class="btc-eyebrow">Time's up</div>
        <h1 class="btc-heading">How did you do?</h1>
        <div class="btc-stats">
          <div class="btc-stat"><div class="n" id="btc-r-attempted">0</div><div class="l">Attempted</div></div>
          <div class="btc-stat"><div class="n" id="btc-r-correct">0</div><div class="l">Correct</div></div>
          <div class="btc-stat"><div class="n" id="btc-r-accuracy">0%</div><div class="l">Accuracy</div></div>
        </div>
        <div id="btc-wrong" class="btc-wronglist" style="display:none;">
          <h4>Have another look</h4>
          <ul id="btc-wrong-list"></ul>
        </div>
        <button type="button" id="btc-again" class="btn btn-primary">&#10227; Play again</button>
      </div>

    </div>
  </div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_OBJECTIVES = <?= json_encode($objectives, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/tp-generators.js') ?>"></script>
  <script src="<?= base_url('assets/js/beat-the-clock.js') ?>"></script>
<?= $this->endSection() ?>
