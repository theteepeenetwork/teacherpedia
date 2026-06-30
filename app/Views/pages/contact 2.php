<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Contact — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
  $success = session()->getFlashdata('success');
  $errors  = session()->getFlashdata('errors') ?? [];
?>

<section style="max-width:1080px; margin:0 auto; width:100%; padding:60px 32px 70px; display:grid; grid-template-columns:1fr 1.1fr; gap:56px; align-items:start;">
  <!-- left: copy + methods -->
  <div>
    <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 13px; border-radius:999px; background:#fff; border:1px solid rgba(28,36,32,.1); font-size:12.5px; font-weight:700; color:#206e40; margin-bottom:22px;">Contact</div>
    <h1 style="margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:46px; line-height:1.04; letter-spacing:-.03em;">Tell us your<br>thoughts.</h1>
    <p style="margin:18px 0 0; font-size:17px; line-height:1.55; color:#545b51; max-width:400px;">Found a bug, want a new objective, or fancy a subject we haven't built yet? Teacherpedia is shaped by teachers — so we'd love to hear from you.</p>

    <div style="display:flex; flex-direction:column; gap:16px; margin-top:34px;">
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:44px; height:44px; border-radius:12px; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-size:18px;">&#9993;</div>
        <div><div style="font-size:12px; color:#9a9f95; font-weight:700; text-transform:uppercase; letter-spacing:.04em;">Email</div><div style="font-size:15px; font-weight:600;">hello@teacherpedia.co.uk</div></div>
      </div>
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:44px; height:44px; border-radius:12px; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-size:18px;">&#9678;</div>
        <div><div style="font-size:12px; color:#9a9f95; font-weight:700; text-transform:uppercase; letter-spacing:.04em;">Social</div><div style="font-size:15px; font-weight:600;">@teacherpedia.co.uk</div></div>
      </div>
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:44px; height:44px; border-radius:12px; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-size:18px;">&#9046;</div>
        <div><div style="font-size:12px; color:#9a9f95; font-weight:700; text-transform:uppercase; letter-spacing:.04em;">Based in</div><div style="font-size:15px; font-weight:600;">North East England</div></div>
      </div>
    </div>
  </div>

  <!-- right: form card -->
  <div style="background:#fff; border-radius:20px; border:1px solid rgba(28,36,32,.08); box-shadow:0 18px 44px -24px rgba(28,36,32,.3); padding:34px 34px;">
    <?php if ($success): ?>
      <!-- SUCCESS STATE -->
      <div style="text-align:center; padding:36px 10px;">
        <div style="width:60px; height:60px; border-radius:50%; background:#e7f5ed; color:#1f8a4d; display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 18px;">&#10003;</div>
        <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:24px;">Message sent!</div>
        <p style="margin:10px auto 0; font-size:15px; color:#5c6159; max-width:300px; line-height:1.5;"><?= esc($success) ?></p>
        <a href="<?= base_url('contact') ?>" class="btn" style="display:inline-block; margin-top:22px; background:#f0ede5; border:1px solid rgba(28,36,32,.1); color:#1c2420; font-weight:700; font-size:14px; padding:11px 20px; border-radius:11px; text-align:center;">Send another</a>
      </div>
    <?php else: ?>
      <!-- FORM STATE -->
      <form method="post" action="<?= base_url('contact') ?>">
        <?= csrf_field() ?>
        <div style="font-family:'Bricolage Grotesque',sans-serif; font-weight:700; font-size:20px; margin-bottom:20px;">Send a message</div>

        <?php if (! empty($errors)): ?>
          <div style="background:#fbe9e7; border:1px solid rgba(192,86,58,.3); color:#a23c25; border-radius:11px; padding:12px 14px; font-size:13.5px; line-height:1.5; margin-bottom:16px;">
            <?php foreach ($errors as $err): ?>
              <div><?= esc($err) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <label style="display:block; font-size:13px; font-weight:700; color:#5c6159; margin-bottom:7px;">Your name</label>
        <input class="fld" type="text" name="name" value="<?= esc(old('name'), 'attr') ?>" placeholder="Jane Smith" style="width:100%; border:1px solid rgba(28,36,32,.16); border-radius:11px; padding:12px 14px; font-size:15px; background:#fbfaf6; transition:border-color .15s, box-shadow .15s; font-family:inherit; margin-bottom:16px;" />

        <label style="display:block; font-size:13px; font-weight:700; color:#5c6159; margin-bottom:7px;">Email</label>
        <input class="fld" type="email" name="email" value="<?= esc(old('email'), 'attr') ?>" placeholder="you@school.uk" style="width:100%; border:1px solid rgba(28,36,32,.16); border-radius:11px; padding:12px 14px; font-size:15px; background:#fbfaf6; transition:border-color .15s, box-shadow .15s; font-family:inherit; margin-bottom:16px;" />

        <label style="display:block; font-size:13px; font-weight:700; color:#5c6159; margin-bottom:7px;">Message</label>
        <textarea class="fld" name="message" placeholder="Tell us your thoughts&hellip;" rows="5" style="width:100%; border:1px solid rgba(28,36,32,.16); border-radius:11px; padding:12px 14px; font-size:15px; background:#fbfaf6; transition:border-color .15s, box-shadow .15s; font-family:inherit; resize:vertical; margin-bottom:20px;"><?= esc(old('message')) ?></textarea>

        <button type="submit" class="btn" style="width:100%; background:#1f8a4d; color:#fff; font-weight:700; font-size:15px; padding:14px; border:none; border-radius:12px; cursor:pointer; font-family:inherit; box-shadow:0 12px 26px -8px rgba(31,138,77,.55);">Send message</button>
      </form>
    <?php endif; ?>
  </div>
</section>

<?= $this->endSection() ?>
