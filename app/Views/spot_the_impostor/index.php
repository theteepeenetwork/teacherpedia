<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Spot the Impostor — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Spot the Impostor sheet — a grid of pre-worked calculation cards rendered
       DIRECTLY into #si-grid (it IS the grid — no nested grid), footer pinned at
       the bottom. See "Layout gotchas" in dev/RESOURCE_WORKFLOW.md. */
    .si-sheet { min-height: 940px; display: flex; flex-direction: column; }

    /* Gotcha #1/#3: items go straight in the grid; --si-cols set by JS.
       FILLING THE A4 WITHOUT DEAD SPACE — the rule that took the iterations:
       rows SHARE the page height equally (grid-auto-rows:1fr, so N rows span the
       full sheet) AND each card STRETCHES to fill its whole track
       (align-self:stretch). The card's OWN bordered box therefore consumes the
       slack — there are no empty inter-row bands to strand short cards in (the
       "space-between banding" gotcha #3 warns against). Inside the card the
       content is held compact and CENTRED vertically (justify-content:center on
       the card flex), so a sparse Y1 board grows roomy readable cards instead of
       leaving ~40-50% dead space between rows, while a dense Y6 board is already
       full. This holds for worksheet (working on/off) AND answer key alike. */
    #si-grid {
      display: grid;
      grid-template-columns: repeat(var(--si-cols, 3), 1fr);
      grid-auto-rows: 1fr;
      gap: 10px 10px;
      flex: 1 1 auto;
      align-content: stretch;
      margin: 4px 0 12px;
    }
    .si-card {
      align-self: stretch;
      border: 1.5px solid rgba(28,36,32,.16);
      border-radius: 12px;
      padding: 11px 12px 10px;
      background: #fff;
      display: flex; flex-direction: column; justify-content: center;
      break-inside: avoid;
      min-width: 0;
    }
    .si-card-head {
      display: flex; align-items: baseline; justify-content: space-between;
      gap: 8px; margin-bottom: 6px; min-height: 14px;
    }
    .si-no {
      font-size: 11px; font-weight: 800; letter-spacing: .03em; color: var(--accent);
    }
    .si-name {
      font-family: var(--font-head, sans-serif);
      font-size: 9.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
      color: #6c716a;
    }
    .si-calc {
      font-size: 19px; font-weight: 700; color: #1c2420;
      font-variant-numeric: tabular-nums; line-height: 1.25;
      display: flex; flex-wrap: wrap; align-items: baseline; gap: 6px;
    }
    /* 12-up is the densest layout (and the answer key adds verdict + bug text):
       tighten the vertical rhythm so even a Y6 all-ops + names board stays on ONE
       A4. Verified one-page by dev/print-preview. */
    #si-grid[data-grid="12"] { gap: 7px 9px; }
    #si-grid[data-grid="12"] .si-card { padding: 8px 10px 8px; border-radius: 10px; }
    #si-grid[data-grid="12"] .si-card-head { margin-bottom: 4px; min-height: 0; }
    #si-grid[data-grid="12"] .si-calc { font-size: 16px; line-height: 1.2; gap: 4px; }
    #si-grid[data-grid="12"] .si-working { margin-top: 2px; }
    #si-grid[data-grid="12"] .si-judge { margin-top: 6px; gap: 6px; }
    #si-grid[data-grid="12"] .si-verdict { margin-top: 6px; font-size: 12px; }
    #si-grid[data-grid="12"] .si-bug { font-size: 11px; margin-top: 3px; }
    #si-grid[data-grid="12"] .si-correct { font-size: 11.5px; margin-top: 3px; }
    .si-calc .si-eq { color: #8a908a; font-weight: 600; }
    .si-calc .si-ans { font-weight: 800; }
    .si-working {
      font-size: 11px; color: #8a908a; margin-top: 3px; font-variant-numeric: tabular-nums;
    }

    /* ✓ / ✗ judge control — touch targets >= 44px, keyboard operable, focus ring. */
    .si-judge { display: flex; gap: 8px; margin-top: 9px; }
    .si-btn {
      min-width: 44px; min-height: 44px; flex: 1 1 0;
      border: 1.6px solid rgba(28,36,32,.22); border-radius: 10px; background: #fff;
      font-size: 20px; font-weight: 800; line-height: 1; cursor: pointer; color: #4a514a;
      display: inline-flex; align-items: center; justify-content: center;
      transition: background .12s, border-color .12s, color .12s;
    }
    .si-btn:hover { border-color: rgba(28,36,32,.4); }
    .si-btn:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }
    .si-tick.on { background: #e7f4ec; border-color: #2f8f5b; color: #1f6b41; }
    .si-cross.on { background: #fdecec; border-color: #c0392b; color: #a02a1e; }

    .si-corr { margin-top: 8px; }
    .si-corr-lbl {
      display: block; font-size: 9.5px; font-weight: 700; letter-spacing: .04em;
      text-transform: uppercase; color: #6c716a; margin-bottom: 3px;
    }
    .si-corr-in {
      width: 100%; box-sizing: border-box; min-height: 40px;
      border: 1.5px solid rgba(28,36,32,.28); border-radius: 8px;
      padding: 6px 9px; font-size: 16px; font-variant-numeric: tabular-nums;
      font-family: inherit; color: #1c2420;
    }
    .si-corr-in:focus-visible { outline: 3px solid var(--accent); outline-offset: 1px; border-color: var(--accent); }

    /* ---- Answer-key reveal: icon + TEXT (never colour alone) — WCAG-AA ---- */
    .si-card-imp { border-color: rgba(28,36,32,.45); border-width: 2px; }
    .si-card-ok { border-style: dashed; }
    .si-verdict { display: flex; align-items: center; gap: 6px; margin-top: 9px; font-weight: 800; font-size: 13px; }
    .si-verdict .si-ic {
      display: inline-flex; align-items: center; justify-content: center;
      width: 22px; height: 22px; border-radius: 6px; font-size: 15px; line-height: 1;
    }
    .si-ok .si-ic { background: #e7f4ec; color: #1f6b41; }
    .si-imp .si-ic { background: #fdecec; color: #a02a1e; }
    .si-ok .si-vtxt { color: #1f6b41; }
    .si-imp .si-vtxt { color: #a02a1e; }
    .si-bug {
      margin-top: 4px; font-size: 12px; font-style: italic; color: #4a514a; line-height: 1.35;
    }
    .si-correct { margin-top: 4px; font-size: 12.5px; color: #1c2420; font-variant-numeric: tabular-nums; }

    /* ---- footer: honest total + pupil running total ---- */
    .si-foot-panel {
      margin-top: auto; border: 1.5px solid var(--accent); border-radius: 12px;
      padding: 11px 14px; background: color-mix(in oklab, var(--accent) 5%, #fff);
      display: flex; flex-wrap: wrap; align-items: center; gap: 8px 18px;
    }
    .si-foot-task { font-size: 13px; color: #26302a; line-height: 1.5; flex: 1 1 240px; min-width: 0; }
    .si-honest-box, .si-pupil-box { text-align: center; }
    .si-mini-lbl {
      font-family: var(--font-head, sans-serif); font-size: 9px; font-weight: 800;
      letter-spacing: .07em; text-transform: uppercase; color: var(--accent);
    }
    .si-honest-val { font-size: 26px; font-weight: 800; color: #1c2420; font-variant-numeric: tabular-nums; line-height: 1.1; }
    .si-pupil-val { font-size: 22px; font-weight: 800; color: #4a514a; font-variant-numeric: tabular-nums; line-height: 1.1; }
    #si-pupil-wrap.si-match .si-pupil-val { color: #1f6b41; }
    .si-pupil-note { font-size: 10px; color: #6c716a; margin-top: 2px; max-width: 150px; }
    .si-foot-proof { width: 100%; font-size: 10.5px; font-style: italic; color: #6c716a; margin-top: 2px; }

    .si-sheet .sheet-foot { margin-top: 12px; }

    .si-chiprow { display: flex; gap: 6px; align-items: center; }

    @media print {
      /* tp-print.css forces .sheet{position:absolute;min-height:0!important;padding:0!important};
         override with !important to fill the printable A4 (~265mm). */
      .si-sheet {
        min-height: 255mm !important;
        display: flex !important;
        flex-direction: column !important;
        padding: 0 !important;
      }
      .si-card { border-color: rgba(28,36,32,.32); }
      /* on PAPER the ✓/✗ buttons are tick-boxes, not touch targets — shrink the
         12-up ones so the densest board fits one page (on screen they stay >=44px
         for one-handed iPad use). */
      #si-grid[data-grid="12"] .si-btn { min-height: 34px; font-size: 17px; }
      /* The densest printable board is the Y6 12-up ANSWER KEY with every op
         category on (round/decimals add 3-line calcs + a misconception label +
         a correct-answer line per impostor). Tighten that cell's vertical rhythm
         on PAPER so even that worst case stays one A4 — verified by
         dev/print-preview. The worksheet 12-up has no verdict/bug/correct lines,
         so this only bites where the extra rows actually appear. */
      #si-grid[data-grid="12"] { gap: 6px 9px; }
      #si-grid[data-grid="12"] .si-calc { line-height: 1.12; }
      #si-grid[data-grid="12"] .si-working { font-size: 10px; margin-top: 1px; }
      #si-grid[data-grid="12"] .si-verdict { margin-top: 4px; font-size: 11.5px; }
      #si-grid[data-grid="12"] .si-verdict .si-ic { width: 18px; height: 18px; font-size: 13px; }
      #si-grid[data-grid="12"] .si-bug { font-size: 10px; margin-top: 2px; line-height: 1.25; }
      #si-grid[data-grid="12"] .si-correct { font-size: 10.5px; margin-top: 2px; }
      .si-foot-panel { padding: 8px 13px; gap: 5px 16px; }
      .si-honest-val { font-size: 23px; }
      /* the live "your total" box is a screen affordance; the printed sheet keeps
         only the honest total + the pupil's own pen-and-paper working. */
      #si-pupil-wrap { display: none !important; }
      .si-btn, .si-verdict .si-ic { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
    <div class="app-title">Spot the Impostor</div>
    <div class="app-context"><span class="dot"></span>KS1&ndash;2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Operations</span>
    <div id="si-ops" class="si-chiprow">
      <button type="button" class="chip" data-op="+">+</button>
      <button type="button" class="chip" data-op="-">&minus;</button>
      <button type="button" class="chip" data-op="×">&times;</button>
      <button type="button" class="chip" data-op="÷">&divide;</button>
      <button type="button" class="chip" data-op="round">Round</button>
      <button type="button" class="chip" data-op="dec">Decimals</button>
    </div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Grid</span>
    <div id="si-grid-size" class="si-chiprow">
      <button type="button" class="chip" data-grid="6">6</button>
      <button type="button" class="chip" data-grid="9">9</button>
      <button type="button" class="chip" data-grid="12">12</button>
    </div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Impostors</span>
    <div id="si-impostors" class="si-chiprow">
      <button type="button" class="chip" data-imp="2">2</button>
      <button type="button" class="chip" data-imp="3">3</button>
      <button type="button" class="chip" data-imp="4">4</button>
    </div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Show</span>
    <div class="si-chiprow">
      <button type="button" class="chip" id="si-working" aria-pressed="true">Working</button>
      <button type="button" class="chip" id="si-names" aria-pressed="false">Pupil names</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'si',
        'tabs'           => [['key' => 'worksheet', 'label' => 'Worksheet'], ['key' => 'answerkey', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New sheet',
        'settings_extra' => $settings_extra,
        // A judging task across the whole primary range (Years 1-6).
        'year'           => 1,
        'year_min'       => 1,
        'year_max'       => 6,
      ]) ?>

  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet si-sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow"><span id="si-eyebrow-ks">KS1 &middot; Year 1</span> &middot; Numeracy &middot; <span id="si-eyebrow-diff">&#9679;&#9679;&#9679;&#9675;&#9675;</span></div>
          <h1 class="sheet-title">Spot the Impostor</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 14px;"></div>

      <!-- ONE short task line on the sheet; the how-it-works lives on the info
           page (/resource/spot-the-impostor). -->
      <p id="si-intro" style="margin:0 0 12px; font-size:14px; color:#4a514a; line-height:1.55;">
        Some answers are wrong. Tick the right ones, cross the impostors and write the correction.
      </p>

      <div id="si-grid"></div>

      <div class="si-foot-panel">
        <div class="si-foot-task" id="si-foot-task">
          Fix the impostors, then add up all the correct answers — you should land on the honest total.
        </div>
        <div class="si-honest-box">
          <div class="si-mini-lbl">Honest total</div>
          <div class="si-honest-val" id="si-honest">0</div>
        </div>
        <div class="si-pupil-box" id="si-pupil-wrap">
          <div class="si-mini-lbl">Your total</div>
          <div class="si-pupil-val" id="si-pupil-total">0</div>
          <div class="si-pupil-note" id="si-pupil-note">Judge every cell to compare.</div>
        </div>
        <div class="si-foot-proof">A match means your answers are <em>likely</em> correct, not proof.</div>
      </div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Spot the Impostor &middot; auto-generated &middot; <span id="si-year"></span></span>
      </div>
    </div>
  </div>

  <div id="si-toast" class="toast hide">&#10003; Saved</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script>
    window.TP_SAVE_URL  = <?= json_encode(base_url('account/save')) ?>;
    window.TP_LOGIN_URL = <?= json_encode(base_url('login')) ?>;
    <?php if (! empty($saved)): ?>
    window.TP_SAVED = <?= json_encode($saved, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    <?php endif; ?>
  </script>
  <script src="<?= base_url('assets/js/tp-tool.js') ?>"></script>
  <script src="<?= base_url('assets/js/spot-the-impostor.js') ?>"></script>
<?= $this->endSection() ?>
