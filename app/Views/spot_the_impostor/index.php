<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Spot the Impostor — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Spot the Impostor — a PRINT-FIRST resource: a pupil WORKSHEET (hand-marked
       on paper) and a matching teacher ANSWER KEY, each its OWN .sheet so each
       prints on its own A4 page. The grid items render DIRECTLY into the grid
       element (#si-grid / #si-key-grid — no nested grid). See "Layout gotchas"
       in dev/RESOURCE_WORKFLOW.md. */
    .si-sheet { min-height: 940px; display: flex; flex-direction: column; }

    /* Only ONE sheet is shown on screen at a time (tab toggle); BOTH print. */
    .si-sheet[hidden] { display: none; }

    /* Gotcha #1/#3: items go straight in the grid; --si-cols set by JS.
       Rows SHARE the page height equally (grid-auto-rows:1fr) and each card
       STRETCHES to fill its track (align-self:stretch), so the card's own box
       consumes the slack — no empty inter-row banding. Content is held compact
       and CENTRED vertically. Holds for worksheet AND answer key alike. */
    #si-grid, #si-key-grid {
      display: grid;
      grid-template-columns: repeat(var(--si-cols, 3), 1fr);
      grid-auto-rows: 1fr;
      gap: 12px 12px;
      flex: 1 1 auto;
      align-content: stretch;
      margin: 4px 0 12px;
    }
    .si-card {
      align-self: stretch;
      border: 1.5px solid rgba(28,36,32,.16);
      border-radius: 12px;
      padding: 12px 13px 11px;
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
      font-size: 10px; font-weight: 800; letter-spacing: .04em;
      color: #4a514a;
    }
    .si-calc {
      font-size: 20px; font-weight: 700; color: #1c2420;
      font-variant-numeric: tabular-nums; line-height: 1.25;
      display: flex; flex-wrap: wrap; align-items: baseline; gap: 6px;
    }
    .si-calc .si-eq { color: #8a908a; font-weight: 600; }
    .si-calc .si-ans { font-weight: 800; }
    .si-working {
      font-size: 11.5px; color: #6c716a; margin-top: 4px; font-variant-numeric: tabular-nums;
    }

    /* ---- WORKSHEET hand-mark target: a printed ✓ / ✗ box pair (NOT buttons,
       just squares to circle/tick) + a ruled correction line. Black-ink. ---- */
    .si-judge {
      display: flex; align-items: center; gap: 10px; margin-top: 11px;
    }
    .si-jbox {
      display: inline-flex; align-items: center; justify-content: center;
      width: 30px; height: 30px;
      border: 1.6px solid #1c2420; border-radius: 7px;
      font-size: 18px; font-weight: 800; line-height: 1; color: #1c2420;
      background: #fff;
    }
    .si-jbox.si-cross { border-radius: 7px; }
    .si-judge-hint {
      font-size: 9.5px; font-weight: 700; letter-spacing: .03em; text-transform: uppercase;
      color: #8a908a;
    }
    .si-corr {
      margin-top: 11px; display: flex; align-items: baseline; gap: 7px;
      font-size: 12px; color: #4a514a;
    }
    .si-corr-lbl { font-weight: 700; white-space: nowrap; }
    .si-corr-line { flex: 1 1 auto; border-bottom: 1.4px solid rgba(28,36,32,.45); height: 18px; min-width: 40px; }

    /* ---- ANSWER KEY: icon + TEXT (never colour alone) — WCAG-AA ---- */
    .si-card-imp { border-color: rgba(28,36,32,.5); border-width: 2px; }
    .si-card-ok { border-style: dashed; }
    .si-verdict { display: flex; align-items: center; gap: 6px; margin-top: 10px; font-weight: 800; font-size: 13px; }
    .si-verdict .si-ic {
      display: inline-flex; align-items: center; justify-content: center;
      width: 22px; height: 22px; border-radius: 6px; font-size: 15px; line-height: 1;
    }
    .si-ok .si-ic { background: #e7f4ec; color: #1f6b41; }
    .si-imp .si-ic { background: #fdecec; color: #a02a1e; }
    .si-ok .si-vtxt { color: #1f6b41; }
    .si-imp .si-vtxt { color: #a02a1e; }
    .si-bug {
      margin-top: 5px; font-size: 12px; font-style: italic; color: #4a514a; line-height: 1.35;
    }
    .si-correct { margin-top: 4px; font-size: 12.5px; color: #1c2420; font-variant-numeric: tabular-nums; }

    /* ---- footer: instruction + printed honest total + EMPTY pupil grand-total box ---- */
    .si-foot-panel {
      margin-top: auto; border: 1.5px solid var(--accent); border-radius: 12px;
      padding: 12px 15px; background: color-mix(in oklab, var(--accent) 5%, #fff);
      display: flex; flex-wrap: wrap; align-items: center; gap: 8px 18px;
    }
    .si-foot-task { font-size: 13px; color: #26302a; line-height: 1.5; flex: 1 1 230px; min-width: 0; }
    .si-honest-box { text-align: center; }
    .si-mini-lbl {
      font-family: var(--font-head, sans-serif); font-size: 9px; font-weight: 800;
      letter-spacing: .07em; text-transform: uppercase; color: var(--accent);
    }
    .si-honest-val { font-size: 26px; font-weight: 800; color: #1c2420; font-variant-numeric: tabular-nums; line-height: 1.1; }
    .si-pupil-box { text-align: center; }
    .si-pupil-lbl {
      font-family: var(--font-head, sans-serif); font-size: 9px; font-weight: 800;
      letter-spacing: .07em; text-transform: uppercase; color: #4a514a; margin-bottom: 4px;
    }
    /* the EMPTY grand-total box the pupil writes their own total into */
    .si-pupil-write {
      width: 120px; height: 44px; border: 1.6px solid #1c2420; border-radius: 9px; background: #fff;
    }
    .si-foot-proof { width: 100%; font-size: 10.5px; font-style: italic; color: #6c716a; margin-top: 2px; }

    .si-sheet .sheet-foot { margin-top: 12px; }

    .si-chiprow { display: flex; gap: 6px; align-items: center; }
    /* visible SEED input in the toolbar */
    #si-seed {
      width: 92px; padding: 7px 9px; border: 1px solid rgba(28,36,32,.16); border-radius: 8px;
      background: #fff; font-family: var(--font-head, inherit); font-weight: 700; font-size: 12px;
      color: var(--ink); font-variant-numeric: tabular-nums;
    }

    /* larger body for the youngest pupils (Y1-2): bump calc + box sizes. */
    .si-sheet[data-yng="1"] .si-calc { font-size: 23px; }
    .si-sheet[data-yng="1"] .si-jbox { width: 34px; height: 34px; font-size: 20px; }

    /* 16-cell (4×4) is the densest worksheet: tighten the cell so it stays on one
       A4 (gotcha 4). Verified by dev/print-preview. */
    #si-grid[data-grid="16"], #si-key-grid[data-grid="16"] { gap: 8px 9px; }
    #si-grid[data-grid="16"] .si-card,
    #si-key-grid[data-grid="16"] .si-card { padding: 8px 10px 8px; border-radius: 10px; }
    #si-grid[data-grid="16"] .si-card-head,
    #si-key-grid[data-grid="16"] .si-card-head { margin-bottom: 4px; min-height: 0; }
    #si-grid[data-grid="16"] .si-calc,
    #si-key-grid[data-grid="16"] .si-calc { font-size: 16px; line-height: 1.2; gap: 4px; }
    #si-grid[data-grid="16"] .si-working,
    #si-key-grid[data-grid="16"] .si-working { font-size: 10px; margin-top: 2px; }
    #si-grid[data-grid="16"] .si-judge { margin-top: 7px; gap: 7px; }
    #si-grid[data-grid="16"] .si-jbox { width: 26px; height: 26px; font-size: 16px; }
    #si-grid[data-grid="16"] .si-corr { margin-top: 7px; }
    #si-key-grid[data-grid="16"] .si-verdict { margin-top: 6px; font-size: 12px; }
    #si-key-grid[data-grid="16"] .si-bug { font-size: 11px; margin-top: 3px; }
    #si-key-grid[data-grid="16"] .si-correct { font-size: 11.5px; margin-top: 3px; }

    @media print {
      /* The sheets sit inside flex/100vh layout wrappers; a flex ancestor SWALLOWS
         break-before:page and the 100vh height clips the 2nd sheet (Chromium).
         Make the whole wrapper chain block-flow + auto height in print so the
         answer-key sheet's page break actually starts a new page. */
      .app-shell, .app-scroll {
        display: block !important; height: auto !important; min-height: 0 !important;
        max-height: none !important; overflow: visible !important; padding: 0 !important;
      }

      /* tp-print.css forces .sheet{position:absolute;min-height:0!important;padding:0!important};
         override with !important to fill the printable A4 (~265mm). */
      #si-ws-sheet, #si-key-sheet {
        min-height: 255mm !important;
        display: flex !important;
        flex-direction: column !important;
        padding: 0 !important;
        position: relative !important;   /* override tp-print's absolute so both sheets FLOW */
        left: auto !important; top: auto !important;
        width: 100% !important;
      }
      /* BOTH sheets print regardless of the on-screen tab; the answer key starts
         on its OWN page so it never shares a page with the worksheet. */
      #si-ws-sheet[hidden], #si-key-sheet[hidden] { display: flex !important; }
      #si-key-sheet { break-before: page; page-break-before: always; }

      .si-card { border-color: rgba(28,36,32,.4); }
      .si-jbox { border-color: #000; }
      .si-corr-line { border-bottom-color: #000; }
      .si-pupil-write { border-color: #000; }

      /* The densest printable page is the Y6 16-cell ANSWER KEY with round/decimals
         on (3-line calcs + a misconception label + a correct-answer line per
         impostor). Tighten that cell on PAPER so it stays legible; the key MAY
         spill to a 2nd page (acceptable per brief) while the worksheet aims for one. */
      #si-key-grid[data-grid="16"] { gap: 7px 9px; }
      #si-key-grid[data-grid="16"] .si-verdict { margin-top: 4px; font-size: 11.5px; }
      #si-key-grid[data-grid="16"] .si-verdict .si-ic { width: 18px; height: 18px; font-size: 13px; }
      #si-key-grid[data-grid="16"] .si-bug { font-size: 10px; margin-top: 2px; line-height: 1.25; }
      #si-key-grid[data-grid="16"] .si-correct { font-size: 10.5px; margin-top: 2px; }
      .si-foot-panel { padding: 9px 14px; gap: 6px 16px; }
      .si-honest-val { font-size: 23px; }
      /* The 16-cell worksheet is the tightest fit: trim cell + footer rhythm so all
         16 cards AND the full footer (incl. the proof note) clear one A4. */
      #si-grid[data-grid="16"] { gap: 7px 8px; margin: 3px 0 8px; }
      #si-grid[data-grid="16"] .si-card { padding: 7px 9px; }
      #si-grid[data-grid="16"] .si-calc { font-size: 15px; }
      #si-grid[data-grid="16"] .si-working { font-size: 9.5px; margin-top: 1px; }
      #si-grid[data-grid="16"] .si-judge { margin-top: 5px; }
      #si-grid[data-grid="16"] .si-jbox { width: 23px; height: 23px; font-size: 14px; }
      #si-grid[data-grid="16"] .si-corr { margin-top: 5px; font-size: 11px; }
      #si-ws-sheet .si-foot-panel { padding: 8px 13px; gap: 3px 14px; }
      #si-ws-sheet .si-foot-proof { margin-top: 1px; }
      .si-ok .si-ic, .si-imp .si-ic { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
      <button type="button" class="chip" data-grid="4">2&times;2</button>
      <button type="button" class="chip" data-grid="9">3&times;3</button>
      <button type="button" class="chip" data-grid="16">4&times;4</button>
    </div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Impostors</span>
    <div id="si-impostors" class="si-chiprow">
      <button type="button" class="chip" data-imp="1">1</button>
      <button type="button" class="chip" data-imp="2">2</button>
      <button type="button" class="chip" data-imp="3">3</button>
      <button type="button" class="chip" data-imp="4">4</button>
      <button type="button" class="chip" data-imp="5">5</button>
    </div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Show</span>
    <div class="si-chiprow">
      <button type="button" class="chip" id="si-working" aria-pressed="true">Working</button>
      <button type="button" class="chip" id="si-names" aria-pressed="false">Pupil names</button>
    </div>

    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Seed</span>
    <div class="si-chiprow">
      <input id="si-seed" type="text" inputmode="numeric" autocomplete="off" spellcheck="false"
             placeholder="random" title="Blank = random; a fixed value reproduces the same sheet" />
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

  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; flex-direction:column; align-items:center; gap:28px;">

    <!-- ===================== PUPIL WORKSHEET ===================== -->
    <div class="sheet si-sheet" id="si-ws-sheet" style="width:660px;">

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
      <p style="margin:0 0 12px; font-size:14px; color:#4a514a; line-height:1.55;">
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
        <div class="si-pupil-box">
          <div class="si-pupil-lbl">Your total</div>
          <div class="si-pupil-write" aria-hidden="true"></div>
        </div>
        <div class="si-foot-proof">A match means your answers are <em>likely</em> correct, not proof.</div>
      </div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Spot the Impostor &middot; worksheet &middot; auto-generated &middot; <span id="si-year"></span></span>
      </div>
    </div>

    <!-- ===================== TEACHER ANSWER KEY ===================== -->
    <div class="sheet si-sheet" id="si-key-sheet" style="width:660px;" hidden>

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow"><span id="si-key-ks">KS1 &middot; Year 1</span> &middot; Numeracy &middot; Answer key</div>
          <h1 class="sheet-title">Spot the Impostor — Answer key</h1>
        </div>
        <div class="sheet-meta">
          <div>Honest total <span class="line" style="min-width:60px;"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 14px;"></div>

      <p style="margin:0 0 12px; font-size:14px; color:#4a514a; line-height:1.55;">
        Each cell is flagged correct or impostor; every impostor names the mistake behind it.
      </p>

      <div id="si-key-grid"></div>

      <div class="si-foot-panel">
        <div class="si-foot-task">
          Pupils correct the impostors, add the correct answers, and self-check against the honest total.
        </div>
        <div class="si-honest-box">
          <div class="si-mini-lbl">Honest total</div>
          <div class="si-honest-val" id="si-key-honest">0</div>
        </div>
        <div class="si-foot-proof">A match means a pupil's answers are <em>likely</em> correct, not proof.</div>
      </div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Spot the Impostor &middot; answer key &middot; auto-generated &middot; <span id="si-key-year"></span></span>
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
