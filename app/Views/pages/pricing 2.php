<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Pricing — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HERO -->
<section style="max-width:840px; margin:0 auto; padding:70px 32px 36px; text-align:center;">
  <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 13px; border-radius:999px; background:#fff; border:1px solid rgba(28,36,32,.1); font-size:12.5px; font-weight:700; color:#206e40; margin-bottom:22px;">Fair, transparent pricing</div>
  <h1 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:50px; line-height:1.02; letter-spacing:-.03em;">No marketplace.<br>No per-sheet fees.</h1>
  <p style="margin:20px auto 0; font-size:18px; line-height:1.55; color:#545b51; max-width:560px;">The core builder is free, forever. If you'd like to support the project and unlock a few extras, our paid plans are priced for teachers — not for profit.</p>
</section>

<!-- PLANS -->
<section style="max-width:1080px; margin:0 auto; padding:24px 32px 30px;">
  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; align-items:stretch;">

    <!-- Free -->
    <div style="background:#fff; border-radius:20px; border:1px solid rgba(28,36,32,.08); padding:32px 28px; display:flex; flex-direction:column;">
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Free</div>
      <div style="font-size:13.5px; color:#7c8278; margin-top:4px;">For every teacher, always</div>
      <div style="display:flex; align-items:baseline; gap:4px; margin:22px 0 4px;"><span style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:46px; letter-spacing:-.02em;">&pound;0</span></div>
      <div style="font-size:13px; color:#9a9f95; margin-bottom:22px;">No card. No sign-up to try.</div>
      <a href="<?= base_url('browse') ?>" class="btn" style="text-align:center; background:#f0ede5; color:#1c2420; font-weight:700; font-size:14.5px; padding:12px; border-radius:11px; border:1px solid rgba(28,36,32,.1);">Start building</a>
      <div style="height:1px; background:rgba(28,36,32,.08); margin:24px 0;"></div>
      <div style="display:flex; flex-direction:column; gap:11px; font-size:14px; color:#3a423b;">
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> All 185+ KS2 numeracy objectives</div>
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Unlimited regeneration</div>
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Difficulty &amp; answer keys</div>
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Print &amp; PDF export</div>
      </div>
    </div>

    <!-- Pro (highlighted) -->
    <div style="background:#1c2420; color:#f3ede1; border-radius:20px; border:1px solid #1c2420; padding:32px 28px; display:flex; flex-direction:column; position:relative; box-shadow:0 24px 50px -22px rgba(28,36,32,.5);">
      <div style="position:absolute; top:-12px; left:50%; transform:translateX(-50%); background:#1f8a4d; color:#fff; font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; padding:5px 12px; border-radius:999px;">Most popular</div>
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px; color:#fff;">Teacher Pro</div>
      <div style="font-size:13.5px; color:#9fb6a6; margin-top:4px;">For your whole classroom</div>
      <div style="display:flex; align-items:baseline; gap:4px; margin:22px 0 4px;"><span style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:46px; color:#fff; letter-spacing:-.02em;">&pound;3</span><span style="font-size:15px; color:#9fb6a6;">/ month</span></div>
      <div style="font-size:13px; color:#9fb6a6; margin-bottom:22px;">Billed annually, or &pound;4 monthly</div>
      <a href="<?= base_url('login') ?>" class="btn" style="text-align:center; background:#1f8a4d; color:#fff; font-weight:700; font-size:14.5px; padding:12px; border-radius:11px; box-shadow:0 12px 24px -8px rgba(31,138,77,.6);">Start free trial</a>
      <div style="height:1px; background:rgba(255,255,255,.12); margin:24px 0;"></div>
      <div style="display:flex; flex-direction:column; gap:11px; font-size:14px; color:#dfe3da;">
        <div style="display:flex; gap:10px;"><span style="color:#6cc78e; font-weight:800;">&#10003;</span> Everything in Free</div>
        <div style="display:flex; gap:10px;"><span style="color:#6cc78e; font-weight:800;">&#10003;</span> Save &amp; organise your sheets</div>
        <div style="display:flex; gap:10px;"><span style="color:#6cc78e; font-weight:800;">&#10003;</span> Assign worksheets to a class</div>
        <div style="display:flex; gap:10px;"><span style="color:#6cc78e; font-weight:800;">&#10003;</span> Branded headers &amp; logos</div>
        <div style="display:flex; gap:10px;"><span style="color:#6cc78e; font-weight:800;">&#10003;</span> Early access to new subjects</div>
      </div>
    </div>

    <!-- School -->
    <div style="background:#fff; border-radius:20px; border:1px solid rgba(28,36,32,.08); padding:32px 28px; display:flex; flex-direction:column;">
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">School</div>
      <div style="font-size:13.5px; color:#7c8278; margin-top:4px;">For your whole staffroom</div>
      <div style="display:flex; align-items:baseline; gap:4px; margin:22px 0 4px;"><span style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:46px; letter-spacing:-.02em;">&pound;149</span><span style="font-size:15px; color:#9a9f95;">/ year</span></div>
      <div style="font-size:13px; color:#9a9f95; margin-bottom:22px;">Whole-school, unlimited staff</div>
      <a href="<?= base_url('contact') ?>" class="btn" style="text-align:center; background:#f0ede5; color:#1c2420; font-weight:700; font-size:14.5px; padding:12px; border-radius:11px; border:1px solid rgba(28,36,32,.1);">Talk to us</a>
      <div style="height:1px; background:rgba(28,36,32,.08); margin:24px 0;"></div>
      <div style="display:flex; flex-direction:column; gap:11px; font-size:14px; color:#3a423b;">
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Everything in Pro, for all staff</div>
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Shared resource library</div>
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Single invoice &amp; admin</div>
        <div style="display:flex; gap:10px;"><span style="color:#1f8a4d; font-weight:800;">&#10003;</span> Priority support</div>
      </div>
    </div>
  </div>
  <p style="text-align:center; font-size:13.5px; color:#8a8f86; margin-top:24px;">Teacherpedia is independently run. Prefer to chip in instead? <a href="<?= base_url('contact') ?>" style="color:#1f8a4d; font-weight:700;">A one-off donation</a> keeps the free tier free.</p>
</section>

<!-- FAQ -->
<section style="max-width:820px; margin:0 auto; padding:40px 32px 70px;">
  <h2 style="margin:0 0 22px; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:30px; letter-spacing:-.02em; text-align:center;">Questions, answered</h2>
  <div style="display:flex; flex-direction:column; gap:12px;">
    <div style="background:#fff; border-radius:14px; border:1px solid rgba(28,36,32,.07); padding:20px 24px;">
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:16px;">Is the free plan really free?</div>
      <p style="margin:8px 0 0; font-size:14.5px; color:#5c6159; line-height:1.55;">Yes. Every numeracy generator, unlimited regeneration, answer keys and printing are free with no card required. Paid plans only add convenience features.</p>
    </div>
    <div style="background:#fff; border-radius:14px; border:1px solid rgba(28,36,32,.07); padding:20px 24px;">
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:16px;">Why isn't this a marketplace?</div>
      <p style="margin:8px 0 0; font-size:14.5px; color:#5c6159; line-height:1.55;">We don't think teachers should pay per resource out of their own pocket. One fair price unlocks everything — no &agrave; la carte fees, no upsells.</p>
    </div>
    <div style="background:#fff; border-radius:14px; border:1px solid rgba(28,36,32,.07); padding:20px 24px;">
      <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:16px;">Can I cancel anytime?</div>
      <p style="margin:8px 0 0; font-size:14.5px; color:#5c6159; line-height:1.55;">Of course. Cancel in a click and you'll keep Pro until the end of your billing period, then drop back to the free plan — your account and saved sheets stay put.</p>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
