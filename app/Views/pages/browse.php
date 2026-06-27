<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Resource activities — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
<style>
  .rcard{ transition:transform .16s ease, box-shadow .16s ease, border-color .16s; }
  .rcard:hover{ transform:translateY(-5px); box-shadow:0 24px 46px -22px rgba(28,36,32,.34) !important; border-color:rgba(31,138,77,.4) !important; }
  .tp-input:focus{ outline:none; border-color:#1f8a4d; box-shadow:0 0 0 3px rgba(31,138,77,.16); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HEADER BLOCK -->
<section style="max-width:1200px; margin:0 auto; width:100%; padding:42px 32px 18px;">
  <div style="font-size:13px; font-weight:600; color:#8a8f86; margin-bottom:14px;">
    <a class="navlink" href="<?= base_url('/') ?>" style="color:#8a8f86;">Home</a> &nbsp;&rsaquo;&nbsp; <span style="color:#3a423b; font-weight:700;">Resources</span> &nbsp;&rsaquo;&nbsp; Key Stage 2 &nbsp;&rsaquo;&nbsp; Numeracy
  </div>
  <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:24px; flex-wrap:wrap;">
    <div>
      <h1 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:46px; letter-spacing:-.03em;">Resource activities</h1>
      <p style="margin:12px 0 0; font-size:17px; color:#545b51; max-width:580px; line-height:1.5;">Each activity is a different way to practise — worksheets, code breakers, board games and more. Every one pulls from the same curriculum engine, so all the questions auto-generate fresh on demand.</p>
    </div>
    <div style="position:relative; width:300px;">
      <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#a8a294; font-size:15px;">&#9013;</span>
      <input id="activitySearch" class="tp-input" type="text" placeholder="Search activities&hellip;" autocomplete="off" style="width:100%; border:1px solid rgba(28,36,32,.16); border-radius:999px; padding:11px 16px 11px 36px; font-size:14px; background:#fff; transition:border-color .15s, box-shadow .15s; font-family:inherit;" />
    </div>
  </div>
</section>

<!-- KEY STAGE TABS -->
<section style="max-width:1200px; margin:0 auto; width:100%; padding:8px 32px 0;">
  <div style="display:flex; gap:10px; border-bottom:1px solid rgba(28,36,32,.1); padding-bottom:2px;">
    <div style="position:relative; padding:12px 4px; font-weight:700; font-size:15px; color:#1c2420;">Key Stage 2<div style="position:absolute; left:0; right:0; bottom:-1px; height:2.5px; background:#1f8a4d; border-radius:2px;"></div></div>
    <div style="padding:12px 14px; font-weight:600; font-size:15px; color:#a8a294;">Key Stage 1 <span style="font-size:11px; background:#f0ede5; padding:2px 7px; border-radius:5px; margin-left:2px;">soon</span></div>
    <div style="padding:12px 14px; font-weight:600; font-size:15px; color:#a8a294;">Key Stage 3 <span style="font-size:11px; background:#f0ede5; padding:2px 7px; border-radius:5px; margin-left:2px;">soon</span></div>
  </div>
</section>

<!-- FILTER BAR -->
<section style="max-width:1200px; margin:0 auto; width:100%; padding:22px 32px 6px; display:flex; align-items:center; gap:26px; flex-wrap:wrap;">
  <div style="display:flex; align-items:center; gap:9px;">
    <span style="font-size:11.5px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95;">Subject</span>
    <span style="background:#1f8a4d; color:#fff; border-radius:999px; padding:6px 14px; font-size:13px; font-weight:700;">Numeracy</span>
    <span style="background:#fff; color:#a8a294; border:1px solid rgba(28,36,32,.13); border-radius:999px; padding:6px 14px; font-size:13px; font-weight:600;">Literacy &middot; soon</span>
    <span style="background:#fff; color:#a8a294; border:1px solid rgba(28,36,32,.13); border-radius:999px; padding:6px 14px; font-size:13px; font-weight:600;">Science &middot; soon</span>
  </div>
  <div style="flex:1;"></div>
  <div style="font-size:13.5px; font-weight:600; color:#8a8f86;"><?= (int) $liveCount ?> live &middot; <?= (int) $soonCount ?> coming soon</div>
</section>

<!-- YEAR FILTER CHIPS -->
<section style="max-width:1200px; margin:0 auto; width:100%; padding:6px 32px 0; display:flex; align-items:center; gap:9px; flex-wrap:wrap;">
  <span style="font-size:11.5px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#9a9f95;">Year</span>
  <button type="button" class="chip chip-on year-chip" data-year="" aria-pressed="true">All years</button>
  <?php for ($y = 1; $y <= 6; $y++): ?>
    <button type="button" class="chip year-chip" data-year="<?= $y ?>" aria-pressed="false">Year <?= $y ?></button>
  <?php endfor; ?>
</section>

<!-- ACTIVITY GRID -->
<section style="max-width:1200px; margin:0 auto; width:100%; padding:20px 32px 70px; flex:1;">
  <div id="activityGrid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:22px;">
    <?php foreach ($activities as $a):
      $live      = ($a['status'] ?? '') === 'live';
      $tags      = array_filter(array_map('trim', explode(',', (string) ($a['tags'] ?? ''))));
      $href      = $live && ! empty($a['slug']) ? base_url('resource/' . $a['slug']) : base_url('browse');
      $titleCol  = $live ? '#1c2420' : '#6c716a';
      $descCol   = $live ? '#5c6159' : '#9a9f95';
      $ctaCol    = $live ? '#1f8a4d' : '#b8bcb2';
      $cta       = $live ? 'Open &rarr;' : 'Soon';
      $iconBg    = $live ? '#e7f5ed' : '#f0ede5';
      $cardOpacity = $live ? '' : 'opacity:.72;';
      $badgeStyle = $live
        ? 'font-size:10.5px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#206e40; background:#e7f5ed; padding:4px 9px; border-radius:6px;'
        : 'font-size:10.5px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#a8a294; background:#f0ede5; padding:4px 9px; border-radius:6px;';
      // searchable haystack for browse.js
      $haystack = strtolower(trim(($a['name'] ?? '') . ' ' . ($a['description'] ?? '') . ' ' . ($a['tags'] ?? '')));
      // year coverage (inclusive); null = unspecified / coming-soon
      $minYear  = isset($a['min_year']) && $a['min_year'] !== null ? (int) $a['min_year'] : null;
      $maxYear  = isset($a['max_year']) && $a['max_year'] !== null ? (int) $a['max_year'] : null;
      $coverage = '';
      if ($minYear !== null && $maxYear !== null) {
        $coverage = $minYear === $maxYear ? 'Year ' . $minYear : 'Years ' . $minYear . '&ndash;' . $maxYear;
      }
    ?>
    <a class="rcard activity-card" data-search="<?= esc($haystack, 'attr') ?>" data-min-year="<?= $minYear !== null ? $minYear : '' ?>" data-max-year="<?= $maxYear !== null ? $maxYear : '' ?>" href="<?= esc($href) ?>" style="display:block; background:#fff; border-radius:18px; padding:24px; border:1px solid rgba(28,36,32,.08); box-shadow:0 1px 2px rgba(28,36,32,.04); <?= $cardOpacity ?>">
      <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:18px;">
        <div style="width:50px; height:50px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:24px; background:<?= $iconBg ?>;"><?= esc($a['icon'] ?? '') ?></div>
        <span style="<?= $badgeStyle ?>"><?= $live ? 'Live' : 'Soon' ?></span>
      </div>
      <h3 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:21px; line-height:1.1; letter-spacing:-.015em; color:<?= $titleCol ?>;"><?= esc($a['name'] ?? '') ?></h3>
      <p style="margin:9px 0 0; font-size:13.5px; color:<?= $descCol ?>; line-height:1.5;"><?= esc($a['description'] ?? '') ?></p>
      <div style="display:flex; align-items:center; gap:7px; margin-top:16px; flex-wrap:wrap;">
        <?php if ($coverage !== ''): ?>
          <span style="font-size:11px; font-weight:700; color:#206e40; background:#e7f5ed; border-radius:6px; padding:3px 8px;"><?= $coverage ?></span>
        <?php endif; ?>
        <?php foreach ($tags as $t): ?>
          <span style="font-size:11px; font-weight:700; color:#6c716a; background:rgba(28,36,32,.05); border-radius:6px; padding:3px 8px;"><?= esc($t) ?></span>
        <?php endforeach; ?>
      </div>
      <div style="display:flex; align-items:center; justify-content:space-between; margin-top:18px; padding-top:14px; border-top:1px solid rgba(28,36,32,.08);">
        <span style="font-size:12.5px; font-weight:700; color:#8a8f86;"><?= $live ? '185+ objectives' : 'In development' ?></span>
        <span style="font-size:13px; font-weight:700; color:<?= $ctaCol ?>;"><?= $cta ?></span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <div id="noResults" style="display:none; text-align:center; color:#9a9f95; font-size:15px; padding:70px 20px;">No activities match your search.</div>
</section>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url('assets/js/browse.js') ?>"></script>
<script>
/* Combined text-search + year filter for the activity grid.
 * Self-contained: owns the final card visibility so it stays consistent with
 * the external browse.js text search (both run on the same 'input' event). */
(function () {
  'use strict';
  function init() {
    var input = document.getElementById('activitySearch');
    var grid  = document.getElementById('activityGrid');
    var empty = document.getElementById('noResults');
    var chips = Array.prototype.slice.call(document.querySelectorAll('.year-chip'));
    if (!grid) { return; }

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.activity-card'));
    var selectedYear = ''; // '' = All years

    function apply() {
      var q = input ? input.value.trim().toLowerCase() : '';
      var visible = 0;

      cards.forEach(function (card) {
        var hay = card.getAttribute('data-search') || '';
        var textMatch = q === '' || hay.indexOf(q) !== -1;

        var yearMatch = true;
        if (selectedYear !== '') {
          var minAttr = card.getAttribute('data-min-year');
          var maxAttr = card.getAttribute('data-max-year');
          if (minAttr === '' || maxAttr === '' || minAttr === null || maxAttr === null) {
            yearMatch = false; // unknown coverage hidden when a year is picked
          } else {
            var y = parseInt(selectedYear, 10);
            yearMatch = parseInt(minAttr, 10) <= y && y <= parseInt(maxAttr, 10);
          }
        }

        var show = textMatch && yearMatch;
        card.style.display = show ? '' : 'none';
        if (show) { visible++; }
      });

      if (empty) { empty.style.display = visible === 0 ? 'block' : 'none'; }
    }

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        selectedYear = chip.getAttribute('data-year') || '';
        chips.forEach(function (c) {
          var on = c === chip;
          c.classList.toggle('chip-on', on);
          c.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        apply();
      });
    });

    if (input) { input.addEventListener('input', apply); }
    apply();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>
<?= $this->endSection() ?>
