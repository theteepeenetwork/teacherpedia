/* =============================================================================
 * auth.js — sign-in / create-account toggle for auth/login.php
 * Ports the DCLogic toggle from Login.dc.html to vanilla JS.
 *
 * Drives everything off the .auth-screen[data-mode] attribute:
 *   data-mode="login"    -> show sign-in form + heading, pill on the left
 *   data-mode="register" -> show create-account form + heading, pill on right
 * The actual show/hide is done in CSS via [data-mode] selectors; this script
 * just flips the attribute and swaps the switch-prompt copy.
 * Admin mode (data-admin="1") has no toggle, so this is a no-op there.
 * ========================================================================== */
(function () {
  'use strict';

  var COPY = {
    login:    { prompt: 'New to Teacherpedia?',     link: 'Create an account' },
    register: { prompt: 'Already have an account?', link: 'Sign in' }
  };

  function init() {
    var screen = document.querySelector('.auth-screen');
    if (!screen || screen.getAttribute('data-admin') === '1') return;

    function setMode(mode) {
      if (mode !== 'login' && mode !== 'register') return;
      screen.setAttribute('data-mode', mode);

      var copy = COPY[mode];
      var promptEl = screen.querySelector('[data-auth-text="prompt"]');
      var linkEl   = screen.querySelector('[data-auth-text="link"]');
      if (promptEl) promptEl.textContent = copy.prompt;
      if (linkEl)   linkEl.textContent   = copy.link;
    }

    // Toggle-bar buttons ("Sign in" / "Create account").
    screen.querySelectorAll('[data-auth-mode]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        setMode(btn.getAttribute('data-auth-mode'));
      });
    });

    // Inline switch link at the bottom of the form.
    screen.querySelectorAll('[data-auth-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var current = screen.getAttribute('data-mode');
        setMode(current === 'login' ? 'register' : 'login');
      });
    });

    // Ensure the switch-prompt copy matches the server-rendered initial mode.
    setMode(screen.getAttribute('data-mode') || 'login');
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
