<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Area Maze — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tp-print.css') ?>">
  <style>
    /* Area Maze sheet — a grid of schematic-figure cards rendered DIRECTLY into
       #am-grid (it IS the grid — no nested grid), with the footer pinned at the
       bottom. See "Layout gotchas" in dev/RESOURCE_WORKFLOW.md. */
    .am-sheet { min-height: 940px; display: flex; flex-direction: column; }

    /* Gotcha #3: compact, CONTENT-sized cards + align-content:space-between. Rows
       hug their content; page slack is shed as small gaps BETWEEN rows, never as a
       dead band INSIDE a card. The figures are sized LARGE (big max-height caps,
       see .am-svg) so every card is tall and the rows nearly fill the A4 — keeping
       the inter-row gaps small. Plain (non-example) cards get a min-height tuned to
       the example card's height so the two rows match and no card is left short. */
    #am-grid {
      display: grid;
      grid-template-columns: repeat(var(--am-cols, 2), 1fr);
      grid-auto-rows: min-content;
      gap: 16px 16px;
      flex: 1 1 auto;
      align-content: space-between;
      margin: 4px 0 12px;
    }
    /* 3-up: the worked EXAMPLE (carries the deduction chain, so it is the tallest)
       spans the full width on row 1; puzzles 2 & 3 share row 2 at equal size. This
       balances the sheet (no tiny-vs-huge cards) and leaves no empty trailing cell. */
    #am-grid[data-count="3"] .am-card:nth-child(1) { grid-column: 1 / -1; }
    .am-card {
      margin: 0;
      border: 1.5px solid rgba(28,36,32,.14);
      border-radius: 14px;
      padding: 12px 12px 11px;
      display: flex; flex-direction: column; align-items: center;
      justify-content: center;
      background: #fff;
      position: relative;
      break-inside: avoid;
    }
    /* Match plain-card height to the chain-bearing example so rows are even and no
       card strands its figure. Tuned per layout (verified one-page by the print
       tool). The example card sizes itself (figure + chain). */
    #am-grid[data-count="2"] .am-card:not(.am-card-example) { min-height: 300px; }
    #am-grid[data-count="4"] .am-card:not(.am-card-example) { min-height: 320px; }
    #am-grid[data-count="3"] .am-card:not(.am-card-example) { min-height: 300px; }
    .am-card-example {
      border-color: var(--accent);
      border-width: 2px;
      background: color-mix(in oklab, var(--accent) 5%, #fff);
    }
    .am-card-head {
      width: 100%; display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 4px;
    }
    .am-card-no {
      font-size: 12px; font-weight: 800; letter-spacing: .03em; color: var(--accent);
    }
    .am-tag {
      font-family: var(--font-head, sans-serif);
      font-size: 8.5px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
      color: #fff; background: var(--accent);
      padding: 2px 7px; border-radius: 999px;
    }
    /* The figure is sized LARGE so it genuinely fills the card (gotcha #3: never
       strand a tiny figure in a tall cell). Caps are per-layout: width-cap keeps it
       inside the card, height-cap is generous so the figure grows down and the card
       is tall (small inter-row gap). preserveAspectRatio="meet" letter-boxes it so a
       tall-narrow stack never overshoots the card. Verified one-page by print tool. */
    .am-fig { width: 100%; flex: 1 1 auto; display: flex; align-items: center; justify-content: center; }
    .am-svg { display: block; width: 100%; height: auto; margin: 0 auto; }
    #am-grid[data-count="2"] .am-svg { max-width: 460px; max-height: 290px; }
    /* The 2-up worked example also carries a chain, so on a 1-column sheet (where
       only ~two card-heights fit) its figure is capped shorter so the example card
       (figure + a long Y6 chain) plus the plain card still total one A4. */
    #am-grid[data-count="2"] .am-card-example .am-svg { max-height: 200px; }
    #am-grid[data-count="3"] .am-svg { max-width: 360px; max-height: 230px; }
    #am-grid[data-count="3"] .am-card:nth-child(1) .am-svg { max-width: 460px; max-height: 250px; }
    #am-grid[data-count="4"] .am-svg { max-width: 320px; max-height: 215px; }
    /* A plain (no-chain) card has more vertical room than the chain-bearing example,
       so its figure grows taller to fill the matched card height (no floating). */
    #am-grid[data-count="4"] .am-card:not(.am-card-example) .am-svg { max-height: 250px; }
    #am-grid[data-count="3"] .am-card:not(.am-card-example) .am-svg { max-height: 245px; }
    #am-grid[data-count="2"] .am-card:not(.am-card-example) .am-svg { max-height: 300px; }
    .am-foot-note {
      font-size: 9px; font-style: italic; color: #6c716a; letter-spacing: .02em;
      margin-top: 5px; text-align: center;
    }

    /* Answer-key deduction chain (also shown on the worked Example card). */
    .am-chain-wrap {
      width: 100%; margin-top: 8px; padding-top: 7px;
      border-top: 1px dashed rgba(28,36,32,.20);
    }
    .am-chain-title {
      font-family: var(--font-head, sans-serif);
      font-size: 9px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
      color: var(--accent); margin-bottom: 4px;
    }
    .am-chain {
      margin: 0; padding-left: 16px;
      font-size: 11px; line-height: 1.5; color: #26302a;
    }
    .am-chain li { margin: 1px 0; }

    .am-sheet .sheet-foot { margin-top: 12px; }

    /* Dense answer key (carries deduction chains, and 4-up sheets) keeps the key
       on ONE A4. Cards stay content-sized; the chain text and figure both shrink
       a touch so three/four chained cards pack onto one page without a 2nd. The
       figure caps stay generous so the figure still fills the card (no dead band)
       — the chain underneath is what makes these cards naturally tall. */
    #am-grid.am-dense { gap: 12px 14px; }
    #am-grid.am-dense .am-card { padding: 8px 10px; }
    #am-grid.am-dense[data-count="2"] .am-svg { max-width: 340px; max-height: 195px; }
    #am-grid.am-dense[data-count="3"] .am-svg { max-width: 300px; max-height: 190px; }
    #am-grid.am-dense[data-count="3"] .am-card:nth-child(1) .am-svg { max-width: 360px; max-height: 210px; }
    #am-grid.am-dense[data-count="4"] .am-svg { max-width: 250px; max-height: 168px; }
    #am-grid.am-dense .am-foot-note { margin-top: 2px; }
    #am-grid.am-dense .am-chain { font-size: 9.5px; line-height: 1.38; }
    #am-grid.am-dense .am-chain-wrap { margin-top: 4px; padding-top: 4px; }
    #am-grid.am-dense .am-chain-title { font-size: 8.5px; margin-bottom: 3px; }

    @media print {
      /* tp-print.css forces .sheet{position:absolute;min-height:0!important;padding:0!important};
         override with !important to fill the printable A4 (≈265mm). */
      .am-sheet {
        min-height: 255mm !important;
        display: flex !important;
        flex-direction: column !important;
        padding: 0 !important;
      }
      .am-card { border-color: rgba(28,36,32,.30); }
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
    <div class="app-title">Area Maze</div>
    <div class="app-context"><span class="dot"></span>KS2 &middot; Numeracy</div>
    <div style="flex:1;"></div>
    <a href="/account" class="app-crumb" style="padding:8px 6px;">My saved sheets</a>
    <div class="avatar">MP</div>
  </header>

  <?php ob_start(); ?>
    <div class="app-divider"></div>
    <span class="build-eyebrow-lbl">Puzzles</span>
    <div id="am-count" style="display:flex; gap:7px;">
      <button type="button" class="chip" data-count="2">2</button>
      <button type="button" class="chip" data-count="3">3</button>
      <button type="button" class="chip" data-count="4">4</button>
    </div>
  <?php $settings_extra = ob_get_clean(); ?>
  <?= view('partials/tool_toolbar', [
        'prefix'         => 'am',
        'tabs'           => [['key' => 'puzzle', 'label' => 'Worksheet'], ['key' => 'answers', 'label' => 'Answer key']],
        'diff'           => 3,
        'regen_label'    => 'New sheet',
        'settings_extra' => $settings_extra,
        // Area as multiplication is a Year 4+ method, so Y1–3 are shown disabled
        // (no off-curriculum sheet); the catalogue min_year/max_year agree (4–6).
        'year'           => 4,
        'year_min'       => 4,
        'year_max'       => 6,
      ]) ?>

  <div class="app-scroll" style="flex:1; padding:38px 44px 80px; display:flex; justify-content:center; align-items:flex-start;">
    <div class="sheet am-sheet" style="width:660px;">

      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
        <div style="min-width:0;">
          <div class="sheet-eyebrow"><span id="am-eyebrow-ks">KS2 &middot; Year 4</span> &middot; Numeracy &middot; <span id="am-eyebrow-diff">&#9679;&#9679;&#9679;&#9675;&#9675;</span></div>
          <h1 class="sheet-title">Area Maze</h1>
        </div>
        <div class="sheet-meta">
          <div>Name <span class="line"></span></div>
          <div>Date <span class="line"></span></div>
        </div>
      </div>
      <div class="sheet-rule" style="margin:16px 0 14px;"></div>

      <!-- Keep on-sheet text to ONE short task line; the how-it-works lives on
           the info page (/resource/area-maze). -->
      <p id="am-intro" style="margin:0 0 12px; font-size:14px; color:#4a514a; line-height:1.55;">
        Work out each missing value. Not drawn to scale.
      </p>

      <div id="am-grid"></div>

      <div class="sheet-foot">
        <span class="brand">teacherpedia<span class="dot-mark">.</span></span>
        <span>Area Maze &middot; auto-generated &middot; <span id="am-year"></span></span>
      </div>
    </div>
  </div>

  <div id="am-toast" class="toast hide">&#10003; Saved</div>

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
  <script src="<?= base_url('assets/js/area-maze.js') ?>"></script>
<?= $this->endSection() ?>
