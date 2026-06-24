<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?><?= ($mode ?? 'login') === 'admin' ? 'Admin sign-in' : 'Sign in' ?> — Teacherpedia<?= $this->endSection() ?>

<?= $this->section('pageHead') ?>
<style>
  /* Auth split-screen — scoped to .auth-screen so it never leaks to other pages. */
  .auth-screen{ min-height:calc(100vh - 140px); display:flex; background:#f3ede1; color:#1c2420; overflow:hidden; }
  .auth-brand{ width:46%; flex-shrink:0; background:linear-gradient(155deg,#26332b,#1c2420); color:#f3ede1; padding:44px 52px; display:flex; flex-direction:column; position:relative; overflow:hidden; }
  .auth-brand h1{ margin:0; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:40px; line-height:1.08; letter-spacing:-.03em; }
  .auth-brand .lede{ margin:18px 0 0; font-size:16.5px; line-height:1.55; color:#bcc4ba; max-width:380px; }
  .auth-brand-inner{ flex:1; display:flex; flex-direction:column; justify-content:center; position:relative; z-index:2; max-width:380px; }
  .auth-benefits{ display:flex; flex-direction:column; gap:13px; margin-top:30px; }
  .auth-benefit{ display:flex; gap:11px; align-items:center; font-size:15px; color:#dfe3da; }
  .auth-benefit span{ color:#6cc78e; font-weight:800; }
  .auth-logo{ display:flex; align-items:baseline; gap:1px; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:23px; color:#fff; letter-spacing:-.025em; position:relative; z-index:2; }
  .auth-logo .dot{ color:#6cc78e; font-size:28px; line-height:0; }
  .auth-sheet{ position:absolute; right:-50px; bottom:-40px; width:230px; background:#fffdf7; border-radius:8px; box-shadow:0 30px 60px -20px rgba(0,0,0,.5); padding:20px; animation:auth-floaty 6s ease-in-out infinite; z-index:1; }
  @keyframes auth-floaty{ 0%,100%{ transform:rotate(-3deg) translateY(0); } 50%{ transform:rotate(-3deg) translateY(-9px); } }
  .auth-sheet-kicker{ font-size:8.5px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#1f8a4d; }
  .auth-sheet-title{ font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:15px; margin-top:3px; color:#1c2420; }
  .auth-sheet-rule{ height:2px; width:24px; background:#1f8a4d; border-radius:2px; margin:9px 0 12px; }
  .auth-sheet-qs{ display:flex; flex-direction:column; gap:9px; font-size:11px; color:#39423b; font-variant-numeric:tabular-nums; }

  .auth-form-side{ flex:1; display:flex; align-items:center; justify-content:center; padding:40px; }
  .auth-form-box{ width:100%; max-width:400px; }
  .auth-toggle{ position:relative; display:flex; background:rgba(28,36,32,.06); border-radius:12px; padding:4px; margin-bottom:30px; }
  .auth-toggle-pill{ position:absolute; top:4px; bottom:4px; left:4px; width:calc(50% - 4px); background:#fff; border-radius:9px; box-shadow:0 1px 4px rgba(28,36,32,.14); transition:left .2s ease; }
  .auth-screen[data-mode="register"] .auth-toggle-pill{ left:calc(50%); }
  .auth-toggle button{ flex:1; position:relative; z-index:1; border:none; background:none; cursor:pointer; font-family:'Hanken Grotesk',sans-serif; font-weight:700; font-size:14.5px; padding:10px; color:#1c2420; }
  .auth-heading{ margin:0 0 6px; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:27px; letter-spacing:-.02em; }
  .auth-sub{ margin:0 0 24px; font-size:14.5px; color:#7c8278; }
  .auth-label{ display:block; font-size:13px; font-weight:700; color:#5c6159; margin-bottom:7px; }
  .auth-fld{ width:100%; border:1px solid rgba(28,36,32,.16); border-radius:11px; padding:12px 14px; font-size:15px; background:#fbfaf6; transition:border-color .15s, box-shadow .15s; font-family:inherit; margin-bottom:16px; }
  .auth-fld:focus{ outline:none; border-color:#1f8a4d; box-shadow:0 0 0 3px rgba(31,138,77,.16); }
  .auth-cta{ display:block; width:100%; text-align:center; background:#1f8a4d; color:#fff; font-weight:700; font-size:15px; padding:14px; border-radius:12px; box-shadow:0 12px 26px -8px rgba(31,138,77,.55); border:none; cursor:pointer; font-family:inherit; transition:transform .12s ease; }
  .auth-cta:hover{ transform:translateY(-2px); }
  .auth-or{ display:flex; align-items:center; gap:12px; margin:22px 0; }
  .auth-or div{ flex:1; height:1px; background:rgba(28,36,32,.12); }
  .auth-or span{ font-size:12px; color:#9a9f95; font-weight:600; }
  .auth-google{ width:100%; background:#fff; border:1px solid rgba(28,36,32,.16); color:#1c2420; font-weight:700; font-size:14.5px; padding:12px; border-radius:12px; cursor:pointer; font-family:inherit; transition:transform .12s ease; }
  .auth-google:hover{ transform:translateY(-2px); }
  .auth-switch{ text-align:center; font-size:13.5px; color:#7c8278; margin-top:24px; }
  .auth-switch button{ border:none; background:none; color:#1f8a4d; font-weight:700; font-size:13.5px; cursor:pointer; font-family:inherit; padding:0; }
  .auth-staff{ text-align:center; font-size:12.5px; color:#a8a294; margin-top:14px; }
  .auth-staff a{ color:#5c6159; font-weight:700; }
  .auth-privacy{ margin:8px 0 18px; font-size:12.5px; color:#9a9f95; line-height:1.5; }
  .auth-privacy a{ color:#1f8a4d; font-weight:600; }
  .auth-forgot{ text-align:right; margin-bottom:18px; }
  .auth-forgot a{ font-size:13px; font-weight:600; color:#1f8a4d; }
  .auth-flash{ border-radius:11px; padding:12px 14px; font-size:13.5px; font-weight:600; margin-bottom:18px; }
  .auth-flash.is-error{ background:#fdecec; color:#b3261e; border:1px solid #f3c4c0; }
  .auth-flash.is-success{ background:#e7f6ec; color:#15703c; border:1px solid #bfe6cd; }
  .auth-reg-only{ display:none; }
  .auth-login-only{ display:block; }
  .auth-screen[data-mode="register"] .auth-reg-only{ display:block; }
  .auth-screen[data-mode="register"] .auth-login-only{ display:none; }
  @media (max-width:860px){ .auth-brand{ display:none; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $mode    = $mode ?? 'login';
    $isAdmin = $mode === 'admin';
    // For client-side toggling, admin behaves like the login layout but never toggles.
    $dataMode = $mode === 'register' ? 'register' : 'login';

    $formAction = $isAdmin ? base_url('admin/login') : base_url('login');
    $heading    = $isAdmin ? 'Admin sign-in' : 'Sign in';
    $sub        = $isAdmin ? 'Restricted area — Teacherpedia staff only.' : 'Welcome back — let’s get building.';
    $cta        = $isAdmin ? 'Sign in →' : 'Sign in →';
?>
<div class="auth-screen" data-mode="<?= esc($dataMode, 'attr') ?>"<?= $isAdmin ? ' data-admin="1"' : '' ?>>

  <!-- LEFT BRAND PANEL -->
  <div class="auth-brand">
    <a href="<?= base_url('/') ?>" class="auth-logo">teacherpedia<span class="dot">.</span></a>
    <div class="auth-brand-inner">
      <h1><?= $isAdmin ? 'Teacherpedia studio.' : 'Welcome back to the staffroom.' ?></h1>
      <p class="lede">
        <?= $isAdmin
            ? 'Review submissions, manage resources and keep the library curriculum-aligned.'
            : 'Save your worksheets, assign them to a class, and pick up right where you left off — on any device.' ?>
      </p>
      <div class="auth-benefits">
        <div class="auth-benefit"><span>✓</span> Save &amp; reuse every sheet you build</div>
        <div class="auth-benefit"><span>✓</span> Assign worksheets to your class</div>
        <div class="auth-benefit"><span>✓</span> Free to start — no card needed</div>
      </div>
    </div>
    <div class="auth-sheet">
      <div class="auth-sheet-kicker">Year 6 · Numeracy</div>
      <div class="auth-sheet-title">Mental Starter</div>
      <div class="auth-sheet-rule"></div>
      <div class="auth-sheet-qs">
        <span>1)&nbsp; 4,820 + 1,376 =</span>
        <span>2)&nbsp; 312 × 27 =</span>
        <span>3)&nbsp; ⅗ of 250 =</span>
        <span>4)&nbsp; 45% of 60 =</span>
      </div>
    </div>
  </div>

  <!-- RIGHT FORM -->
  <div class="auth-form-side">
    <div class="auth-form-box">

      <?php if (! empty($error)): ?>
        <div class="auth-flash is-error"><?= esc($error) ?></div>
      <?php endif; ?>
      <?php if (! empty($success)): ?>
        <div class="auth-flash is-success"><?= esc($success) ?></div>
      <?php endif; ?>

      <?php if (! $isAdmin): ?>
        <!-- Sign in / Create account toggle (vanilla JS) -->
        <div class="auth-toggle">
          <div class="auth-toggle-pill"></div>
          <button type="button" data-auth-mode="login">Sign in</button>
          <button type="button" data-auth-mode="register">Create account</button>
        </div>
      <?php endif; ?>

      <!-- SIGN IN heading -->
      <div class="auth-login-only">
        <h2 class="auth-heading"><?= esc($heading) ?></h2>
        <p class="auth-sub"><?= esc($sub) ?></p>
      </div>
      <?php if (! $isAdmin): ?>
        <!-- CREATE ACCOUNT heading -->
        <div class="auth-reg-only">
          <h2 class="auth-heading">Create your account</h2>
          <p class="auth-sub">Free forever. No card required.</p>
        </div>
      <?php endif; ?>

      <!-- ===================== SIGN IN FORM ===================== -->
      <form class="auth-login-only" method="post" action="<?= $formAction ?>" autocomplete="on">
        <label class="auth-label" for="login-email">Email</label>
        <input class="auth-fld" id="login-email" name="email" type="email"
               value="<?= esc(old('email'), 'attr') ?>" placeholder="you@school.uk" required>

        <label class="auth-label" for="login-password">Password</label>
        <input class="auth-fld" id="login-password" name="password" type="password"
               placeholder="••••••••" required>

        <?php if (! $isAdmin): ?>
          <div class="auth-forgot"><a href="<?= base_url('login') ?>">Forgot password?</a></div>
        <?php else: ?>
          <div style="height:8px"></div>
        <?php endif; ?>

        <button class="auth-cta" type="submit"><?= esc($cta) ?></button>
      </form>

      <?php if (! $isAdmin): ?>
      <!-- =================== CREATE ACCOUNT FORM =================== -->
      <form class="auth-reg-only" method="post" action="<?= base_url('register') ?>" autocomplete="on">
        <label class="auth-label" for="reg-name">Full name</label>
        <input class="auth-fld" id="reg-name" name="full_name" type="text"
               value="<?= esc(old('full_name'), 'attr') ?>" placeholder="Jane Smith" required>

        <label class="auth-label" for="reg-email">Email</label>
        <input class="auth-fld" id="reg-email" name="email" type="email"
               value="<?= esc(old('email'), 'attr') ?>" placeholder="you@school.uk" required>

        <label class="auth-label" for="reg-password">Password</label>
        <input class="auth-fld" id="reg-password" name="password" type="password"
               placeholder="At least 8 characters" minlength="8" required>

        <label class="auth-privacy" style="display:flex; gap:8px; align-items:flex-start; cursor:pointer;">
          <input type="checkbox" name="agree" value="1" required style="margin-top:2px;">
          <span>By creating an account you agree to our
            <a href="<?= base_url('privacy') ?>">privacy policy</a>.</span>
        </label>

        <button class="auth-cta" type="submit">Create account →</button>
      </form>
      <?php endif; ?>

      <!-- Divider + Google placeholder (non-functional) -->
      <div class="auth-or"><div></div><span>or</span><div></div></div>
      <button class="auth-google" type="button" onclick="return false;">Continue with Google</button>

      <?php if (! $isAdmin): ?>
        <p class="auth-switch">
          <span data-auth-text="prompt">New to Teacherpedia?</span>
          <button type="button" data-auth-toggle data-auth-text="link">Create an account</button>
        </p>
        <p class="auth-staff">Teacherpedia staff?
          <a href="<?= base_url('admin/login') ?>">Admin sign-in →</a></p>
      <?php else: ?>
        <p class="auth-staff">Not staff?
          <a href="<?= base_url('login') ?>">Back to teacher sign-in →</a></p>
      <?php endif; ?>

    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url('assets/js/auth.js') ?>" defer></script>
<?= $this->endSection() ?>
