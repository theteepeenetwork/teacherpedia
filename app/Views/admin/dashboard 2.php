<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard — Teacherpedia Admin<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
<style>
  /* ---- Admin chrome (dashboard + studio share the dark sidebar idiom) ---- */
  .adm-shell { display:flex; height:100vh; background:#f3ede1; color:#1c2420; overflow:hidden; }
  .adm-side { width:230px; flex-shrink:0; background:#1c2420; color:#c3c8be; display:flex; flex-direction:column; padding:20px 16px; }
  .adm-brand { display:flex; align-items:baseline; gap:1px; font-family:var(--font-head); font-weight:800; font-size:20px; color:#fff; letter-spacing:-.02em; padding:6px 10px 18px; }
  .adm-brand .dot-mark { color:#6cc78e; font-size:25px; line-height:0; }
  .adm-side-label { font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:#6f766b; padding:8px 12px 6px; }
  .adm-nav { display:flex; flex-direction:column; gap:2px; }
  .navitem { display:flex; align-items:center; gap:11px; padding:10px 12px; border-radius:9px; font-size:14px; font-weight:600; cursor:pointer; color:#c3c8be; transition:background .12s, color .12s; }
  .navitem:hover { background:rgba(255,255,255,.06); }
  .navitem .ndot { width:7px; height:7px; border-radius:2px; background:#4a524a; }
  .navitem.active { font-weight:700; color:#fff; background:rgba(108,199,142,.16); }
  .navitem.active .ndot { background:#6cc78e; }
  .nav-badge { margin-left:auto; background:#c0563a; color:#fff; font-size:10.5px; font-weight:800; border-radius:999px; padding:1px 7px; }
  .adm-back { font-size:13px; font-weight:600; color:#9fa79b; }
  .adm-user { display:flex; align-items:center; gap:10px; padding:12px 10px 4px; border-top:1px solid rgba(255,255,255,.08); margin-top:8px; }
  .adm-user .av { width:32px; height:32px; border-radius:50%; background:linear-gradient(140deg,#3a4a3f,#6cc78e); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; font-family:var(--font-head); }
  .adm-logout { font-size:11px; color:#7f867b; }
  .adm-logout:hover { color:#fff; text-decoration:underline; }

  .adm-main { flex:1; overflow-y:auto; }
  .adm-main::-webkit-scrollbar { width:10px; }
  .adm-main::-webkit-scrollbar-thumb { background:rgba(28,36,32,.16); border-radius:8px; border:3px solid transparent; background-clip:padding-box; }
  .adm-topbar { display:flex; align-items:center; gap:16px; padding:22px 34px; border-bottom:1px solid rgba(28,36,32,.09); position:sticky; top:0; background:rgba(243,237,225,.86); backdrop-filter:blur(10px); z-index:5; }
  .adm-h1 { margin:0; font-family:var(--font-head); font-weight:800; font-size:26px; letter-spacing:-.025em; }
  .adm-sub { font-size:13.5px; color:#7c8278; margin-top:2px; }
  .adm-newbtn { display:inline-flex; align-items:center; gap:8px; background:#1f8a4d; color:#fff; font-weight:700; font-size:14px; padding:11px 18px; border-radius:11px; box-shadow:0 8px 18px -6px rgba(31,138,77,.55); transition:transform .12s ease; }
  .adm-newbtn:hover { transform:translateY(-1px); }
  .adm-content { padding:28px 34px 60px; max-width:1100px; }

  .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
  .stat-card { background:#fff; border:1px solid rgba(28,36,32,.08); border-radius:15px; padding:20px; }
  .stat-card .lbl { font-size:12.5px; font-weight:700; color:#8a8f86; }
  .stat-card .num { font-family:var(--font-head); font-weight:800; font-size:34px; letter-spacing:-.02em; margin-top:6px; }
  .stat-card .cap { font-size:12px; font-weight:600; margin-top:4px; color:#8a8f86; }

  .panel-grid { display:grid; grid-template-columns:1.25fr 1fr; gap:22px; }
  .adm-panel { background:#fff; border:1px solid rgba(28,36,32,.08); border-radius:16px; overflow:hidden; }
  .panel-head { display:flex; align-items:center; justify-content:space-between; padding:18px 20px 14px; }
  .panel-head h2 { margin:0; font-family:var(--font-head); font-weight:700; font-size:17px; }
  .panel-link { font-size:12.5px; font-weight:700; color:#1f8a4d; }

  .act-head, .act-row { display:grid; grid-template-columns:1fr auto auto; gap:14px; align-items:center; }
  .act-head { padding:8px 20px; font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:#a8a294; border-bottom:1px solid rgba(28,36,32,.07); }
  .act-row { padding:13px 20px; border-bottom:1px solid rgba(28,36,32,.05); transition:background .12s; }
  .act-row:hover { background:#faf8f3; }
  .act-ico { width:30px; height:30px; border-radius:8px; background:#f3ede1; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
  .act-name { font-size:14px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .act-type { font-size:12.5px; color:#7c8278; font-weight:600; }
  .stat-badge { font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; padding:3px 9px; border-radius:6px; }
  .badge-live { color:#206e40; background:#e7f5ed; }
  .badge-draft { color:#b8742e; background:#fbf3d6; }
  .badge-soon { color:#a8a294; background:#f0ede5; }

  .sub-row { padding:14px 20px; border-top:1px solid rgba(28,36,32,.06); }
  .sub-row .ttl { font-size:13.5px; font-weight:700; }
  .sub-tag { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; padding:2px 7px; border-radius:5px; color:#2a6fdb; background:#e8eefb; }
  .sub-tag.activity { color:#7a4fbf; background:#efe9f9; }
  .sub-meta { font-size:12px; color:#8a8f86; margin-top:3px; }
  .sub-actions { display:flex; gap:8px; margin-top:10px; }
  .sub-btn { font-size:12px; font-weight:700; border-radius:8px; padding:6px 12px; cursor:pointer; font-family:inherit; transition:transform .12s ease; }
  .sub-btn:hover { transform:translateY(-1px); }
  .sub-review { color:#1c2420; background:#fff; border:1px solid rgba(28,36,32,.16); }
  .sub-approve { color:#fff; background:#1f8a4d; border:none; }

  .cov-row { display:flex; align-items:center; gap:14px; }
  .cov-label { font-size:13px; font-weight:700; width:54px; flex-shrink:0; }
  .cov-track { flex:1; height:10px; background:#f0ede5; border-radius:6px; overflow:hidden; }
  .cov-fill { height:100%; background:#1f8a4d; border-radius:6px; }
  .cov-ratio { font-size:12.5px; font-weight:700; color:#7c8278; width:70px; text-align:right; flex-shrink:0; }

  .adm-flash { margin:0 0 18px; padding:11px 16px; border-radius:10px; font-size:13.5px; font-weight:600; }
  .adm-flash.ok { background:#e7f5ed; color:#206e40; }
  .adm-flash.err { background:#fbe9e4; color:#c0563a; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
  helper('text');
  $initials = strtoupper(substr((string) $firstName, 0, 2));
?>
<div class="adm-shell">

  <!-- SIDEBAR -->
  <aside class="adm-side">
    <a href="/" class="adm-brand">teacherpedia<span class="dot-mark">.</span></a>
    <div class="adm-side-label">Admin</div>
    <nav class="adm-nav">
      <div class="navitem active"><span class="ndot"></span>Dashboard</div>
      <div class="navitem"><span class="ndot"></span>Activities</div>
      <div class="navitem"><span class="ndot"></span>Generators</div>
      <div class="navitem"><span class="ndot"></span>Curriculum</div>
      <div class="navitem"><span class="ndot"></span>Submissions
        <?php if ($pendingReview > 0): ?><span class="nav-badge"><?= esc($pendingReview) ?></span><?php endif; ?>
      </div>
      <div class="navitem"><span class="ndot"></span>Users</div>
    </nav>
    <div style="flex:1;"></div>
    <a href="/" class="navitem adm-back">&larr; Back to site</a>
    <div class="adm-user">
      <div class="av"><?= esc($initials) ?></div>
      <div style="min-width:0;">
        <div style="font-size:13px; font-weight:700; color:#fff;"><?= esc($firstName) ?></div>
        <div style="font-size:11px; color:#7f867b;"><?= esc($role) ?> &middot; <a href="/logout" class="adm-logout">Log out</a></div>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="adm-main">
    <div class="adm-topbar">
      <div>
        <h1 class="adm-h1">Dashboard</h1>
        <div class="adm-sub">Manage activities, generators and submissions</div>
      </div>
      <div style="flex:1;"></div>
      <a href="/admin/studio" class="adm-newbtn"><span style="font-size:17px; line-height:0;">+</span> New resource</a>
    </div>

    <div class="adm-content">

      <?php if (session('success')): ?>
        <div class="adm-flash ok">&#10003; <?= esc(session('success')) ?></div>
      <?php endif; ?>
      <?php if (session('error')): ?>
        <div class="adm-flash err"><?= esc(session('error')) ?></div>
      <?php endif; ?>

      <!-- STAT CARDS -->
      <div class="stat-grid">
        <div class="stat-card">
          <div class="lbl">Live activities</div>
          <div class="num"><?= esc($liveActivities) ?></div>
          <div class="cap" style="color:#1f8a4d; font-weight:700;"><?= esc(implode(' &middot; ', $liveNames) ?: '—') ?></div>
        </div>
        <div class="stat-card">
          <div class="lbl">Generators</div>
          <div class="num"><?= esc($generators) ?></div>
          <div class="cap">across <?= esc($strandCount) ?> strands</div>
        </div>
        <div class="stat-card">
          <div class="lbl">Need a generator</div>
          <div class="num" style="color:#b8742e;"><?= esc($needGenerator) ?></div>
          <div class="cap" style="color:#b8742e; font-weight:700;">objectives to build &rarr;</div>
        </div>
        <div class="stat-card">
          <div class="lbl">Pending review</div>
          <div class="num" style="color:#c0563a;"><?= esc($pendingReview) ?></div>
          <div class="cap" style="color:#c0563a; font-weight:700;">awaiting approval</div>
        </div>
      </div>

      <div class="panel-grid">
        <!-- ACTIVITIES TABLE -->
        <div class="adm-panel">
          <div class="panel-head">
            <h2>Activities</h2>
            <a href="/admin/studio" class="panel-link">Manage &rarr;</a>
          </div>
          <div class="act-head"><span>Activity</span><span>Type</span><span>Status</span></div>
          <?php foreach ($activities as $a):
            $status = strtolower((string) ($a['status'] ?? ''));
            $badge  = $status === 'live' ? 'badge-live' : ($status === 'draft' ? 'badge-draft' : 'badge-soon');
            $type   = explode(',', (string) ($a['tags'] ?? ''))[0] ?? '';
          ?>
            <div class="act-row">
              <div style="display:flex; align-items:center; gap:11px; min-width:0;">
                <span class="act-ico"><?= esc($a['icon'] ?? '') ?></span>
                <span class="act-name"><?= esc($a['name'] ?? '') ?></span>
              </div>
              <span class="act-type"><?= esc($type) ?></span>
              <span class="stat-badge <?= $badge ?>"><?= esc(ucfirst($status)) ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- REVIEW QUEUE -->
        <div class="adm-panel">
          <div class="panel-head">
            <h2>Submission queue</h2>
            <span style="font-size:12.5px; font-weight:700; color:#c0563a;"><?= esc($pendingReview) ?> new</span>
          </div>
          <?php if (empty($submissions)): ?>
            <div style="padding:22px 20px; font-size:13px; color:#8a8f86;">No submissions in the queue.</div>
          <?php else: ?>
            <?php foreach ($submissions as $s):
              $kind    = (strtolower((string) ($s['type'] ?? '')) === 'activity') ? 'Activity' : 'Generator';
              $tagCls  = $kind === 'Activity' ? 'sub-tag activity' : 'sub-tag';
              $when    = ! empty($s['created_at']) ? date('j M Y', strtotime((string) $s['created_at'])) : 'Just now';
              $author  = $s['author_id'] ? ('Author #' . $s['author_id']) : 'Unknown';
            ?>
              <div class="sub-row">
                <div style="display:flex; align-items:center; gap:8px;">
                  <span class="ttl"><?= esc($s['name'] ?? 'Untitled') ?></span>
                  <span class="<?= $tagCls ?>"><?= esc($kind) ?></span>
                </div>
                <div class="sub-meta"><?= esc($author) ?> &middot; <?= esc($when) ?></div>
                <div class="sub-actions">
                  <a href="/admin/studio/<?= esc($s['id']) ?>" class="sub-btn sub-review">Review</a>
                  <a href="/admin/submissions/approve/<?= esc($s['id']) ?>" class="sub-btn sub-approve">Approve</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- COVERAGE -->
      <div class="adm-panel" style="padding:20px 22px; margin-top:22px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
          <h2 style="margin:0; font-family:var(--font-head); font-weight:700; font-size:17px;">Generator coverage by year</h2>
          <a href="/admin/studio" class="panel-link">Build a generator &rarr;</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:13px;">
          <?php foreach ($coverage as $c):
            $pct = (int) $c['pct'];
          ?>
            <div class="cov-row">
              <span class="cov-label"><?= esc($c['label']) ?></span>
              <div class="cov-track"><div class="cov-fill" style="width:<?= esc($pct) ?>%;"></div></div>
              <span class="cov-ratio"><?= esc($c['auto']) ?> / <?= esc($c['total']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </main>
</div>
<?= $this->endSection() ?>
