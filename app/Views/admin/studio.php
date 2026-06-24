<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Resource Studio — Teacherpedia Admin<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
<style>
  .st-shell { display:flex; flex-direction:column; height:100vh; background:#f3ede1; color:#1c2420; overflow:hidden; }

  /* TOP BAR */
  .st-top { display:flex; align-items:center; gap:16px; padding:0 24px; height:60px; background:#fff; border-bottom:1px solid rgba(28,36,32,.1); flex-shrink:0; z-index:6; }
  .st-brand { display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; font-size:19px; letter-spacing:-.02em; }
  .st-brand .dot-mark { color:#1f8a4d; font-size:24px; line-height:0; }
  .st-crumb { font-size:13px; font-weight:600; color:#5c6159; }
  .st-div { width:1px; height:22px; background:rgba(28,36,32,.14); }
  .st-titlewrap { display:flex; align-items:center; gap:8px; }
  .st-title { font-family:var(--font-head); font-weight:700; font-size:15px; }
  .st-draftbadge { font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#b8742e; background:#fbf3d6; padding:3px 9px; border-radius:6px; }
  .st-btn { font-weight:700; font-size:13.5px; border-radius:10px; cursor:pointer; font-family:inherit; transition:transform .12s ease, box-shadow .12s; }
  .st-btn:hover { transform:translateY(-1px); }
  .st-ghost { border:1px solid rgba(28,36,32,.16); background:#fff; color:#1c2420; padding:9px 15px; }
  .st-primary { border:none; background:#1f8a4d; color:#fff; padding:9px 17px; box-shadow:0 8px 18px -6px rgba(31,138,77,.5); }

  .st-body { flex:1; display:flex; min-height:0; }

  /* LEFT: metadata */
  .st-left { width:300px; flex-shrink:0; background:#fff; border-right:1px solid rgba(28,36,32,.1); overflow-y:auto; padding:22px 20px; }
  .st-sectlabel { font-size:11px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:#9a9f95; margin-bottom:14px; }
  .st-flabel { display:block; font-size:12.5px; font-weight:700; color:#5c6159; margin-bottom:6px; }
  .st-fld { width:100%; border:1px solid rgba(28,36,32,.16); border-radius:10px; padding:10px 12px; font-size:14px; background:#fbfaf6; font-family:inherit; margin-bottom:15px; transition:border-color .15s, box-shadow .15s; }
  .st-fld:focus { outline:none; border-color:#1f8a4d; box-shadow:0 0 0 3px rgba(31,138,77,.16); }
  select.st-fld { padding:10px 8px; font-size:13.5px; }
  textarea.st-fld { font-size:13.5px; resize:vertical; line-height:1.5; }
  .st-typechips { display:flex; gap:7px; margin-bottom:15px; }
  .st-chip { flex:1; border-radius:9px; padding:8px 6px; cursor:pointer; font-family:inherit; font-size:12px; font-weight:700; border:1px solid rgba(28,36,32,.16); background:#fff; color:#5c6159; }
  .st-chip.on { border:none; background:#1f8a4d; color:#fff; }
  .st-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:15px; }
  .st-valbox { background:#f3ede1; border-radius:11px; padding:13px 14px; }
  .st-valbox .h { font-size:11.5px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#9a9f95; margin-bottom:8px; }
  #meta-checks { display:flex; flex-direction:column; gap:7px; font-size:12.5px; color:#5c6159; }

  /* CENTER: editor */
  .st-center { flex:1; display:flex; flex-direction:column; min-width:0; background:#1a1f1c; }
  .st-edbar { display:flex; align-items:center; gap:12px; padding:0 18px; height:44px; background:#222a26; border-bottom:1px solid rgba(255,255,255,.07); flex-shrink:0; }
  .st-edname { font-family:'JetBrains Mono',monospace; font-size:12.5px; color:#cdd3cb; font-weight:500; }
  .st-edsig { font-size:11px; color:#7f877e; font-family:'JetBrains Mono',monospace; }
  .st-edbtn { font-family:inherit; cursor:pointer; transition:transform .12s ease; }
  .st-edbtn:hover { transform:translateY(-1px); }
  .st-reset { font-size:12px; font-weight:600; color:#9fa79b; background:transparent; border:1px solid rgba(255,255,255,.14); border-radius:7px; padding:5px 11px; }
  .st-runbtn { font-size:12.5px; font-weight:700; color:#1a1f1c; background:#6cc78e; border:none; border-radius:7px; padding:6px 14px; display:inline-flex; align-items:center; gap:6px; }

  .st-edarea { flex:1; display:flex; min-height:0; }
  #ed-gutter { flex-shrink:0; width:46px; background:#1a1f1c; color:#525a52; font-family:'JetBrains Mono',monospace; font-size:13px; line-height:21px; text-align:right; padding:14px 8px 14px 0; overflow:hidden; user-select:none; white-space:pre; }
  #ed-wrap { flex:1; min-width:0; position:relative; overflow:hidden; background:#1a1f1c; font-family:'JetBrains Mono',monospace; font-size:13px; line-height:21px; }
  #ed-hl, #ed-ta { margin:0; padding:14px 16px; border:0; white-space:pre; tab-size:2; font-family:inherit; font-size:inherit; line-height:inherit; letter-spacing:0; }
  #ed-hl { position:absolute; inset:0; pointer-events:none; overflow:hidden; color:#e6e6e6; }
  #ed-ta { position:absolute; inset:0; width:100%; height:100%; background:transparent; color:transparent; caret-color:#6cc78e; resize:none; overflow:auto; outline:none; }
  .ed-scroll::-webkit-scrollbar { width:11px; height:11px; }
  .ed-scroll::-webkit-scrollbar-thumb { background:rgba(255,255,255,.16); border-radius:8px; border:3px solid transparent; background-clip:padding-box; }
  .tok-kw{ color:#c678dd; } .tok-str{ color:#98c379; } .tok-num{ color:#d19a66; } .tok-com{ color:#7a8593; font-style:italic; } .tok-fn{ color:#61afef; } .tok-op{ color:#56b6c2; }

  /* RIGHT: preview */
  .st-right { width:380px; flex-shrink:0; background:#f3ede1; border-left:1px solid rgba(28,36,32,.1); overflow-y:auto; }
  .st-status { padding:16px 20px 12px; border-bottom:1px solid rgba(28,36,32,.08); }
  #run-dot { width:9px; height:9px; border-radius:50%; background:#b8bcb2; }
  .st-diffwrap { padding:14px 20px 8px; display:flex; align-items:center; gap:10px; }
  .st-diffwrap .h { font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95; }
  #diff-row { display:flex; gap:5px; }
  .st-outcard { background:#fffdf7; border:1px solid rgba(28,36,32,.1); border-radius:13px; overflow:hidden; box-shadow:0 12px 30px -18px rgba(28,36,32,.3); }
  .st-outhead { padding:11px 15px; border-bottom:1px solid rgba(28,36,32,.08); display:flex; align-items:center; gap:8px; }
  .st-outhead .h { font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:#1f8a4d; }
  .st-reroll { font-size:11px; font-weight:700; color:#1f8a4d; background:none; border:none; cursor:pointer; font-family:inherit; }
  #sample-list { list-style:none; margin:0; padding:8px 15px 14px; }

  .st-scroll::-webkit-scrollbar { width:10px; height:10px; }
  .st-scroll::-webkit-scrollbar-thumb { background:rgba(28,36,32,.18); border-radius:8px; border:3px solid transparent; background-clip:padding-box; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
  $sub  = $submission ?? null;
  $review = $sub !== null;
  $sName  = $sub['name'] ?? '';
  $sType  = strtolower((string) ($sub['type'] ?? 'generator'));
  $sSubject = $sub['subject'] ?? '';
  $sYear  = (int) ($sub['year'] ?? 6);
  $sStrand = $sub['strand'] ?? '';
  $sObjective = $sub['objective'] ?? '';
  $sCode  = $sub['generator_code'] ?? '';
?>
<div class="st-shell">

  <!-- TOP BAR -->
  <header class="st-top">
    <a href="/admin" class="st-brand">teacherpedia<span class="dot-mark">.</span></a>
    <a href="/admin" class="st-crumb">&larr; Admin</a>
    <div class="st-div"></div>
    <div class="st-titlewrap">
      <span class="st-title">Resource studio</span>
      <span class="st-draftbadge"><?= $review ? esc(ucfirst((string) ($sub['status'] ?? 'Draft'))) : 'Draft' ?></span>
    </div>
    <div style="flex:1;"></div>
    <button type="button" id="st-save" class="st-btn st-ghost">Save draft</button>
    <button type="button" id="st-submit" class="st-btn st-primary">Submit for review &rarr;</button>
  </header>

  <div class="st-body">

    <!-- LEFT: METADATA -->
    <aside class="st-left st-scroll">
      <div class="st-sectlabel">Resource details</div>

      <label class="st-flabel">Name</label>
      <input id="f-name" class="st-fld" value="<?= esc($sName, 'attr') ?>" placeholder="e.g. Roman numerals to 1000" />

      <label class="st-flabel">Resource type</label>
      <div class="st-typechips">
        <button type="button" class="st-chip <?= $sType === 'activity' ? '' : 'on' ?>" data-type="generator">Question generator</button>
        <button type="button" class="st-chip <?= $sType === 'activity' ? 'on' : '' ?>" data-type="activity">Activity layout</button>
      </div>
      <input type="hidden" id="f-type" value="<?= esc($sType ?: 'generator', 'attr') ?>" />

      <div class="st-grid2">
        <div>
          <label class="st-flabel">Subject</label>
          <select id="f-subject" class="st-fld" style="margin-bottom:0;">
            <?php foreach (['Numeracy', 'Literacy', 'Science'] as $opt): ?>
              <option<?= $sSubject === $opt ? ' selected' : '' ?>><?= esc($opt) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="st-flabel">Year</label>
          <select id="f-year" class="st-fld" style="margin-bottom:0;">
            <?php foreach ([3, 4, 5, 6] as $y): ?>
              <option value="<?= $y ?>"<?= $sYear === $y ? ' selected' : '' ?>>Year <?= $y ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <label class="st-flabel">Strand</label>
      <select id="f-strand" class="st-fld">
        <?php foreach (['Place value', 'Addition & subtraction', 'Multiplication & division', 'Fractions, decimals & %', 'Reading & writing', 'Measurement'] as $opt): ?>
          <option<?= $sStrand === $opt ? ' selected' : '' ?>><?= esc($opt) ?></option>
        <?php endforeach; ?>
      </select>

      <label class="st-flabel">Objective ("I can&hellip;")</label>
      <textarea id="f-objective" class="st-fld" rows="3" placeholder="I can read Roman numerals to 1000 (M) and recognise years written in Roman numerals."><?= esc($sObjective) ?></textarea>

      <div class="st-valbox">
        <div class="h">Validation</div>
        <div id="meta-checks"></div>
      </div>
    </aside>

    <!-- CENTER: EDITOR -->
    <main class="st-center">
      <div class="st-edbar">
        <span class="st-edname">generator.js</span>
        <span class="st-edsig">function generate(difficulty) &rarr; { question, answer }</span>
        <div style="flex:1;"></div>
        <button type="button" id="ed-reset" class="st-edbtn st-reset">Reset</button>
        <button type="button" id="ed-run" class="st-edbtn st-runbtn">&#9656; Run</button>
      </div>
      <div class="st-edarea">
        <div id="ed-gutter" class="ed-scroll"></div>
        <div id="ed-wrap" class="ed-scroll">
          <pre id="ed-hl"><code id="ed-code"></code></pre>
          <textarea id="ed-ta" class="ed-scroll" spellcheck="false" autocomplete="off" autocapitalize="off"></textarea>
        </div>
      </div>
    </main>

    <!-- RIGHT: LIVE PREVIEW -->
    <aside class="st-right st-scroll">
      <div class="st-status">
        <div style="display:flex; align-items:center; gap:9px;">
          <div id="run-dot"></div>
          <span id="run-status" style="font-size:13.5px; font-weight:700; color:#5c6159;">Not run yet</span>
        </div>
        <div id="run-detail" style="font-size:12px; color:#8a8f86; margin-top:5px;">Press Run (or edit code) to test your generator.</div>
      </div>

      <div class="st-diffwrap">
        <span class="h">Difficulty</span>
        <div id="diff-row"></div>
      </div>

      <div style="padding:6px 20px 18px;">
        <div class="st-outcard">
          <div class="st-outhead">
            <span class="h">Sample output</span>
            <div style="flex:1;"></div>
            <button type="button" id="ed-reroll" class="st-reroll">&#8635; Reroll</button>
          </div>
          <ul id="sample-list"></ul>
        </div>
        <div style="font-size:11.5px; color:#9a9f95; margin-top:10px; line-height:1.5;">The same generator runs at every difficulty and inside any activity — worksheet, code breaker and more.</div>
      </div>
    </aside>
  </div>

  <div id="st-toast" class="toast hide"><span style="color:#6cc78e;">&#10003;</span> <span id="st-toast-msg">Draft saved</span></div>
</div>

<?php if ($review && $sCode !== ''): ?>
<script type="text/plain" id="seed-code"><?= esc($sCode) ?></script>
<?php endif; ?>
<script>
  window.TP_STUDIO = {
    draftUrl: '<?= site_url('admin/studio/draft') ?>',
    submitUrl: '<?= site_url('admin/studio/submit') ?>',
    hasSeed: <?= ($review && $sCode !== '') ? 'true' : 'false' ?>
  };
</script>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
  <script src="<?= base_url('assets/js/admin-studio.js') ?>"></script>
<?= $this->endSection() ?>
