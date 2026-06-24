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

<!-- ACTIVITY GRID -->
<section style="max-width:1200px; margin:0 auto; width:100%; padding:20px 32px 70px; flex:1;">
  <div id="activityGrid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:22px;">
    <?php foreach ($activities as $a):
      $live      = ($a['status'] ?? '') === 'live';
      $tags      = array_filter(array_map('trim', explode(',', (string) ($a['tags'] ?? ''))));
      $href      = $live && ! empty($a['route']) ? base_url(ltrim($a['route'], '/')) : base_url('browse');
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
    ?>
    <a class="rcard activity-card" data-search="<?= esc($haystack, 'attr') ?>" href="<?= esc($href, 'attr') ?>" style="display:block; background:#fff; border-radius:18px; padding:24px; border:1px solid rgba(28,36,32,.08); box-shadow:0 1px 2px rgba(28,36,32,.04); <?= $cardOpacity ?>">
      <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:18px;">
        <div style="width:50px; height:50px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:24px; background:<?= $iconBg ?>;"><?= esc($a['icon'] ?? '') ?></div>
        <span style="<?= $badgeStyle ?>"><?= $live ? 'Live' : 'Soon' ?></span>
      </div>
      <h3 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:21px; line-height:1.1; letter-spacing:-.015em; color:<?= $titleCol ?>;"><?= esc($a['name'] ?? '') ?></h3>
      <p style="margin:9px 0 0; font-size:13.5px; color:<?= $descCol ?>; line-height:1.5;"><?= esc($a['description'] ?? '') ?></p>
      <div style="display:flex; align-items:center; gap:7px; margin-top:16px; flex-wrap:wrap;">
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
<?= $this->endSection() ?>
