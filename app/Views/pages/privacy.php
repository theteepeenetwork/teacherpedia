<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Privacy policy — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section style="max-width:760px; margin:0 auto; width:100%; padding:56px 32px 70px;">
  <div style="font-size:13px; font-weight:600; color:#8a8f86; margin-bottom:14px;"><a class="navlink" href="<?= base_url('/') ?>" style="color:#8a8f86;">Home</a> &nbsp;&rsaquo;&nbsp; <span style="color:#3a423b; font-weight:700;">Privacy policy</span></div>
  <h1 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:42px; letter-spacing:-.03em;">Privacy policy</h1>
  <p style="margin:14px 0 0; font-size:15px; color:#8a8f86;">Last updated <?= date('j M Y') ?></p>

  <div style="background:#fff; border-radius:18px; border:1px solid rgba(28,36,32,.08); padding:36px 38px; margin-top:30px;">
    <p style="margin:0 0 22px; font-size:16px; line-height:1.65; color:#3a423b;">Teacherpedia is built by a teacher, for teachers. We collect as little as possible, never sell your data, and use it only to run the service and improve our resources.</p>

    <h2 style="margin:0 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">What we collect</h2>
    <p style="margin:0 0 22px; font-size:15px; line-height:1.62; color:#5c6159;">If you create an account we store your name, email and the worksheets you choose to save. If you contact us, we keep your message so we can reply. We use privacy-respecting analytics to understand which resources are useful.</p>

    <h2 style="margin:0 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">How we use it</h2>
    <p style="margin:0 0 22px; font-size:15px; line-height:1.62; color:#5c6159;">To sign you in, save your sheets, respond to your messages, and decide which generators to build next. We never sell or rent personal data, and we don't show third-party advertising in your generated worksheets.</p>

    <h2 style="margin:0 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Cookies</h2>
    <p style="margin:0 0 22px; font-size:15px; line-height:1.62; color:#5c6159;">We use essential cookies to keep you signed in and remember your preferences. Analytics cookies are optional — you can decline them without losing any functionality.</p>

    <h2 style="margin:0 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Your rights</h2>
    <p style="margin:0 0 22px; font-size:15px; line-height:1.62; color:#5c6159;">Under UK GDPR you can ask us to access, correct or delete your data at any time. Email us and we'll action it promptly.</p>

    <h2 style="margin:0 0 8px; font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px;">Get in touch</h2>
    <p style="margin:0; font-size:15px; line-height:1.62; color:#5c6159;">Questions about your data? <a href="<?= base_url('contact') ?>" style="color:#1f8a4d; font-weight:700;">Contact us</a> any time.</p>
  </div>
</section>

<?= $this->endSection() ?>
