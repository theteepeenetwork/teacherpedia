<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Teacherpedia — Worksheets that never run out<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
<style>
  .subj{ transition:transform .18s ease, box-shadow .18s ease; }
  .subj:hover{ transform:translateY(-4px); box-shadow:0 18px 36px -18px rgba(28,36,32,.34) !important; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ============ HERO ============ -->
<section style="max-width:1200px; margin:0 auto; padding:78px 32px 70px; display:grid; grid-template-columns:1.05fr .95fr; gap:48px; align-items:center;">
  <div>
    <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 13px; border-radius:999px; background:#fff; border:1px solid rgba(28,36,32,.1); font-size:12.5px; font-weight:700; color:#206e40; margin-bottom:24px;">
      <span style="width:7px; height:7px; border-radius:50%; background:#1f8a4d;"></span>Aligned to the English National Curriculum
    </div>
    <h1 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:62px; line-height:1.0; letter-spacing:-.03em;">Worksheets that<br>never run out.</h1>
    <p style="margin:22px 0 0; font-size:18.5px; line-height:1.55; color:#545b51; max-width:480px;">Pick a curriculum objective, set the difficulty, and Teacherpedia builds a fresh, self-marking worksheet in seconds. Need another? Hit regenerate — same skill, brand-new questions, every single time.</p>
    <div style="display:flex; gap:13px; margin-top:30px;">
      <a href="<?= base_url('build') ?>" class="btn" style="background:#1f8a4d; color:#fff; font-weight:700; font-size:15.5px; padding:14px 26px; border-radius:999px; box-shadow:0 12px 26px -8px rgba(31,138,77,.6);">Build a worksheet</a>
      <a href="<?= base_url('browse') ?>" class="btn" style="background:#fff; color:#1c2420; font-weight:700; font-size:15.5px; padding:14px 24px; border-radius:999px; border:1px solid rgba(28,36,32,.16);">Browse resources</a>
    </div>
    <div style="display:flex; align-items:center; gap:18px; margin-top:34px; font-size:13px; color:#7c8278; font-weight:600;">
      <span>&#10003; Free to use</span><span>&#10003; Print or PDF</span><span>&#10003; Answer key included</span>
    </div>
  </div>

  <!-- hero paper preview (live regenerate) -->
  <div style="position:relative; height:430px;">
    <div style="position:absolute; right:24px; top:18px; width:300px; height:380px; background:#fff; border-radius:8px; box-shadow:0 20px 50px -22px rgba(28,36,32,.4); transform:rotate(4deg); border:1px solid rgba(28,36,32,.07);"></div>
    <div style="position:absolute; left:8px; top:0; width:320px; background:#fffdf7; border-radius:8px; box-shadow:0 30px 60px -24px rgba(28,36,32,.45); border:1px solid rgba(28,36,32,.08); padding:26px 28px 22px; animation:floaty 6s ease-in-out infinite;">
      <div style="font-size:9.5px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#1f8a4d;">Year 6 &middot; Numeracy</div>
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:21px; margin-top:5px; letter-spacing:-.02em;">Mental Starter</div>
      <div style="height:2px; width:34px; background:#1f8a4d; border-radius:2px; margin:11px 0 16px;"></div>
      <ul id="heroQs" style="list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:11px;">
        <!-- populated by home.js -->
      </ul>
    </div>
    <button id="heroRegen" type="button" class="btn" style="position:absolute; right:8px; bottom:6px; z-index:3; background:#1c2420; color:#fff; font-weight:700; font-size:13.5px; padding:11px 18px; border:none; border-radius:999px; cursor:pointer; box-shadow:0 12px 26px -8px rgba(28,36,32,.6); display:inline-flex; align-items:center; gap:8px;">
      <span id="heroSpin" style="display:inline-block; font-size:15px; transition:transform .5s ease;">&#10227;</span> Regenerate
    </button>
  </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section style="max-width:1200px; margin:0 auto; padding:46px 32px 40px;">
  <div style="text-align:center; margin-bottom:42px;">
    <div style="font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#1f8a4d;">How it works</div>
    <h2 style="margin:9px 0 0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:38px; letter-spacing:-.025em;">Three steps to a perfect sheet</h2>
  </div>
  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:22px;">
    <div style="background:#fff; border-radius:16px; padding:30px 26px; border:1px solid rgba(28,36,32,.07);">
      <div style="width:44px; height:44px; border-radius:12px; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:20px;">1</div>
      <h3 style="margin:18px 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Pick your objectives</h3>
      <p style="margin:0; font-size:15px; line-height:1.55; color:#5c6159;">Search the whole curriculum by year and strand. Mix objectives from different year groups into one differentiated sheet.</p>
    </div>
    <div style="background:#fff; border-radius:16px; padding:30px 26px; border:1px solid rgba(28,36,32,.07);">
      <div style="width:44px; height:44px; border-radius:12px; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:20px;">2</div>
      <h3 style="margin:18px 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Set difficulty &amp; amount</h3>
      <p style="margin:0; font-size:15px; line-height:1.55; color:#5c6159;">Choose how many questions per objective and dial the difficulty from foundation to greater depth. The sheet updates live as you go.</p>
    </div>
    <div style="background:#fff; border-radius:16px; padding:30px 26px; border:1px solid rgba(28,36,32,.07);">
      <div style="width:44px; height:44px; border-radius:12px; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:20px;">3</div>
      <h3 style="margin:18px 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Print, or regenerate</h3>
      <p style="margin:0; font-size:15px; line-height:1.55; color:#5c6159;">Print, save as PDF, or grab the answer key. Need a fresh set for tomorrow? One click re-rolls every question.</p>
    </div>
  </div>
</section>

<!-- ============ REGENERATE STORY ============ -->
<section style="background:#1c2420; color:#f3ede1; margin-top:50px;">
  <div style="max-width:1200px; margin:0 auto; padding:72px 32px; display:grid; grid-template-columns:1fr 1fr; gap:56px; align-items:center;">
    <div>
      <div style="font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#6cc78e;">The Teacherpedia difference</div>
      <h2 style="margin:12px 0 0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:42px; line-height:1.05; letter-spacing:-.03em;">Same objective.<br>Fresh questions.<br><span style="color:#6cc78e;">Every time.</span></h2>
      <p style="margin:22px 0 0; font-size:17px; line-height:1.6; color:#c3c8be; max-width:440px;">No more hunting for "another sheet on long division." Every resource regenerates on demand — perfect for a re-test, an extra practice set, or a whole term of starters from a single objective.</p>
      <div style="display:flex; gap:34px; margin-top:34px;">
        <div><div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:34px; color:#fff;">185+</div><div style="font-size:13.5px; color:#a7ad9f; font-weight:600;">auto-generating objectives</div></div>
        <div><div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:34px; color:#fff;">&infin;</div><div style="font-size:13.5px; color:#a7ad9f; font-weight:600;">unique worksheets</div></div>
        <div><div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:34px; color:#fff;">Y3&ndash;6</div><div style="font-size:13.5px; color:#a7ad9f; font-weight:600;">KS2 numeracy, live now</div></div>
      </div>
    </div>
    <div style="display:flex; flex-direction:column; gap:14px;">
      <div style="background:#26302a; border:1px solid rgba(255,255,255,.09); border-radius:14px; padding:20px 22px;">
        <div style="font-size:11.5px; font-weight:700; color:#6cc78e; letter-spacing:.04em; text-transform:uppercase; margin-bottom:12px;">Monday's sheet</div>
        <div style="display:flex; flex-direction:column; gap:9px; font-size:15px; color:#e7e9e2; font-variant-numeric:tabular-nums;">
          <span>1)&nbsp;&nbsp;4,820 + 1,376 =</span><span>2)&nbsp;&nbsp;312 &times; 27 =</span><span>3)&nbsp;&nbsp;&#8535; of 250 =</span>
        </div>
      </div>
      <div style="display:flex; justify-content:center; color:#6cc78e; font-size:22px;">&darr; regenerate</div>
      <div style="background:#26302a; border:1px solid rgba(108,199,142,.3); border-radius:14px; padding:20px 22px;">
        <div style="font-size:11.5px; font-weight:700; color:#6cc78e; letter-spacing:.04em; text-transform:uppercase; margin-bottom:12px;">Tuesday's sheet — same skills</div>
        <div style="display:flex; flex-direction:column; gap:9px; font-size:15px; color:#e7e9e2; font-variant-numeric:tabular-nums;">
          <span>1)&nbsp;&nbsp;7,008 + 2,594 =</span><span>2)&nbsp;&nbsp;486 &times; 35 =</span><span>3)&nbsp;&nbsp;&frac34; of 180 =</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ SUBJECTS / ACTIVITIES ============ -->
<section style="max-width:1200px; margin:0 auto; padding:72px 32px 40px;">
  <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:32px;">
    <div>
      <div style="font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#1f8a4d;">Browse the library</div>
      <h2 style="margin:9px 0 0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:38px; letter-spacing:-.025em;">Pick an activity</h2>
    </div>
    <a class="navlink" href="<?= base_url('browse') ?>" style="font-size:15px; font-weight:700; color:#1f8a4d;">See all activities &rarr;</a>
  </div>
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px;">
    <a href="<?= base_url('build') ?>" class="subj" style="display:block; background:#fff; border-radius:16px; padding:26px; border:1px solid rgba(28,36,32,.07); box-shadow:0 1px 2px rgba(28,36,32,.04);">
      <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div style="width:46px; height:46px; border-radius:12px; background:#e7f5ed; display:flex; align-items:center; justify-content:center; font-size:23px;">&#128221;</div>
        <span style="font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#206e40; background:#e7f5ed; padding:4px 9px; border-radius:6px;">Live</span>
      </div>
      <h3 style="margin:18px 0 5px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:21px;">Worksheet Generator</h3>
      <p style="margin:0; font-size:14px; color:#5c6159; line-height:1.5;">Combine any objectives across Years 3&ndash;6 into one differentiated, printable practice sheet.</p>
      <div style="margin-top:16px; font-size:13px; font-weight:700; color:#1f8a4d;">Open &rarr;</div>
    </a>
    <a href="<?= base_url('code-breaker') ?>" class="subj" style="display:block; background:#fff; border-radius:16px; padding:26px; border:1px solid rgba(28,36,32,.07); box-shadow:0 1px 2px rgba(28,36,32,.04);">
      <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div style="width:46px; height:46px; border-radius:12px; background:#e8eefb; display:flex; align-items:center; justify-content:center; font-size:23px;">&#128269;</div>
        <span style="font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#206e40; background:#e7f5ed; padding:4px 9px; border-radius:6px;">Live</span>
      </div>
      <h3 style="margin:18px 0 5px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:21px;">Code Breaker</h3>
      <p style="margin:0; font-size:14px; color:#5c6159; line-height:1.5;">Solve calculations to crack a cipher and reveal a hidden message. A self-marking puzzle.</p>
      <div style="margin-top:16px; font-size:13px; font-weight:700; color:#1f8a4d;">Open &rarr;</div>
    </a>
    <a href="<?= base_url('maths-maze') ?>" class="subj" style="display:block; background:#fff; border-radius:16px; padding:26px; border:1px solid rgba(28,36,32,.07); box-shadow:0 1px 2px rgba(28,36,32,.04);">
      <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div style="width:46px; height:46px; border-radius:12px; background:#e3f3f3; display:flex; align-items:center; justify-content:center; font-size:23px;">&#129513;</div>
        <span style="font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#206e40; background:#e7f5ed; padding:4px 9px; border-radius:6px;">Live</span>
      </div>
      <h3 style="margin:18px 0 5px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:21px;">Maths Maze</h3>
      <p style="margin:0; font-size:14px; color:#5c6159; line-height:1.5;">Solve a calculation to unlock each step through the grid &mdash; a wrong turn is a dead end. Self-marking.</p>
      <div style="margin-top:16px; font-size:13px; font-weight:700; color:#1f8a4d;">Open &rarr;</div>
    </a>
    <a href="<?= base_url('browse') ?>" class="subj" style="display:block; background:#fff; border-radius:16px; padding:26px; border:1px solid rgba(28,36,32,.07);">
      <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div style="width:46px; height:46px; border-radius:12px; background:#f0ede5; color:#a8a294; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; font-family:'Bricolage Grotesque',sans-serif;">&#65291;</div>
        <span style="font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; color:#a8a294; background:#f0ede5; padding:4px 9px; border-radius:6px;">3 soon</span>
      </div>
      <h3 style="margin:18px 0 5px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:21px;">More activities</h3>
      <p style="margin:0; font-size:14px; color:#5c6159; line-height:1.5;">Times-table grids, bingo and true-or-false cards are on the way.</p>
      <div style="margin-top:16px; font-size:13px; font-weight:700; color:#1f8a4d;">See all &rarr;</div>
    </a>
  </div>
</section>

<!-- ============ FEATURED RESOURCE ============ -->
<section style="max-width:1200px; margin:0 auto; padding:42px 32px 70px;">
  <div style="background:linear-gradient(135deg,#e7f5ed,#f3ede1); border:1px solid rgba(31,138,77,.18); border-radius:22px; padding:44px 48px; display:grid; grid-template-columns:1fr auto; gap:40px; align-items:center;">
    <div>
      <div style="font-size:12.5px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#206e40;">Featured &middot; most popular</div>
      <h2 style="margin:10px 0 0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:34px; letter-spacing:-.025em;">Year 6 Multi-Generator</h2>
      <p style="margin:14px 0 0; font-size:16px; line-height:1.55; color:#41483f; max-width:520px;">The all-in-one numeracy builder. Combine any objectives across Years 3&ndash;6, set the difficulty, and print a differentiated arithmetic sheet with its answer key — then regenerate as many versions as you need.</p>
      <a href="<?= base_url('build') ?>" class="btn" style="display:inline-block; margin-top:24px; background:#1f8a4d; color:#fff; font-weight:700; font-size:15px; padding:13px 24px; border-radius:999px; box-shadow:0 12px 26px -8px rgba(31,138,77,.55);">Open the generator &rarr;</a>
    </div>
    <div style="width:180px; height:230px; background:#fffdf7; border-radius:8px; box-shadow:0 20px 44px -18px rgba(28,36,32,.4); border:1px solid rgba(28,36,32,.08); padding:18px 16px; transform:rotate(3deg);">
      <div style="font-size:8px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#1f8a4d;">Year 6 &middot; Numeracy</div>
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:14px; margin-top:3px;">Arithmetic</div>
      <div style="height:2px; width:22px; background:#1f8a4d; border-radius:2px; margin:8px 0 11px;"></div>
      <div style="display:flex; flex-direction:column; gap:8px; font-size:10px; color:#39423b; font-variant-numeric:tabular-nums;">
        <span>1)&nbsp; 4,820 + 1,376</span><span>2)&nbsp; 312 &times; 27</span><span>3)&nbsp; 84 &divide; 7</span><span>4)&nbsp; &#8535; of 250</span><span>5)&nbsp; 45% of 60</span><span>6)&nbsp; 13&sup2;</span>
      </div>
    </div>
  </div>
</section>

<!-- ============ FINAL CTA ============ -->
<section style="max-width:1000px; margin:0 auto; padding:30px 32px 84px; text-align:center;">
  <h2 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:44px; letter-spacing:-.03em; line-height:1.05;">Less time photocopying.<br>More time teaching.</h2>
  <p style="margin:18px auto 0; font-size:18px; color:#545b51; max-width:520px; line-height:1.5;">Build your first worksheet free. No sign-up needed to try it.</p>
  <a href="<?= base_url('build') ?>" class="btn" style="display:inline-block; margin-top:28px; background:#1f8a4d; color:#fff; font-weight:700; font-size:16px; padding:16px 34px; border-radius:999px; box-shadow:0 14px 30px -8px rgba(31,138,77,.6);">Build a worksheet &rarr;</a>
</section>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url('assets/js/home.js') ?>"></script>
<?= $this->endSection() ?>
