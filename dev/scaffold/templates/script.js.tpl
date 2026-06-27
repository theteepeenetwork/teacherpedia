/* =============================================================================
 * __SLUG__.js — __NAME__.
 * -----------------------------------------------------------------------------
 * TODO: describe the activity and how it self-marks.
 *
 * The pure item engine is exposed as window.__NS__ for Node tests; DOM wiring
 * only runs in a browser. See dev/RESOURCE_WORKFLOW.md.
 * ========================================================================== */
(function () {
  'use strict';

  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function pick(a) { return a[Math.floor(Math.random() * a.length)]; }
  function fmt(n) { return Number(n).toLocaleString('en-GB'); }

  // --- PURE ENGINE ---------------------------------------------------------
  // TODO: generate one item. MUST return a single objectively-correct answer.
  //   eff: effective difficulty 1..5.  Returns whatever your renderItem needs.
  function generate(eff) {
    eff = Math.max(1, Math.min(5, eff | 0));
    var a = ri(1, 9 + eff * 3), b = ri(1, 9 + eff * 3);
    return { a: a, b: b, ans: a + b };           // <-- replace with real logic
  }

  // Optional: independently verify an item is uniquely solvable (used by tests
  // and, if relevant, the answer key). Return null if not solvable.
  function solve(item) { return item; }           // <-- replace if applicable

  if (typeof window !== 'undefined') { window.__NS__ = { generate: generate, solve: solve }; }

  /* ---- DOM (browser only) ------------------------------------------------- */
  if (typeof document === 'undefined') { return; }

  var ACCENT = '__ACCENT__';
  function $(id) { return document.getElementById(id); }

  var state = {
    year: 4,
    difficulty: 3,
    count: 6,
    tab: 'sheet',          // 'sheet' | 'answers'
    items: []
  };
  var els = {};

  function eff() {
    return window.TP_effDifficulty ? window.TP_effDifficulty(state.year, state.difficulty) : state.difficulty;
  }

  function rebuild() {
    state.items = [];
    var e = eff(), seen = {}, attempts = 0, cap = state.count * 80 + 300;
    while (state.items.length < state.count && attempts < cap) {
      attempts++;
      var it = generate(e);
      if (!solve(it)) { continue; }                    // never push an unsolvable item
      var sig = JSON.stringify(it);
      if (seen[sig]) { continue; }                     // de-dupe within the sheet
      seen[sig] = true;
      state.items.push(it);
    }
    while (state.items.length < state.count && state.items.length) { state.items.push(state.items[0]); }
    render();
  }

  // TODO: render ONE item. For a visual, return an inline SVG string sized by a
  // viewBox (the CSS scales it). `revealed` is true on the answer-key tab.
  function renderItem(item, revealed) {
    var ans = revealed ? fmt(item.ans) : '____';
    return '<svg viewBox="0 0 210 150" class="__PREFIX__-svg" xmlns="http://www.w3.org/2000/svg">' +
      '<text x="105" y="80" text-anchor="middle" font-size="22" font-family="system-ui,sans-serif" fill="#26302a">' +
      fmt(item.a) + ' + ' + fmt(item.b) + ' = ' + ans + '</text></svg>';
  }

  function render() {
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    if (els.intro) { els.intro.innerHTML = 'TODO: one short task line.'; }
    var revealed = state.tab === 'answers';
    // Render cards DIRECTLY into #__PREFIX__-grid (it IS the grid) — do NOT nest
    // another grid inside it, or row distribution silently breaks.
    els.grid.style.setProperty('--__PREFIX__-cols', state.count >= 9 ? 3 : 2);
    var html = '';
    state.items.forEach(function (it, i) {
      html += '<figure class="__PREFIX__-card">' + renderItem(it, revealed) +
        '<figcaption class="__PREFIX__-cap">Q' + (i + 1) + '</figcaption></figure>';
    });
    els.grid.innerHTML = html;
  }

  // ---- toolbar wiring -------------------------------------------------------
  function setOnState(wrap, attr, val) {
    Array.prototype.forEach.call(wrap.querySelectorAll('[' + attr + ']'), function (b) {
      b.classList.toggle('chip-on', b.getAttribute(attr) === String(val));
    });
  }

  // Slide a slider/segmented thumb under its active button (measured in px —
  // the shared .diff-thumb/.seg-thumb carry no width).
  function moveThumb(thumb, wrap, selector, index) {
    if (!thumb || !wrap) { return; }
    var active = wrap.querySelectorAll(selector)[index];
    if (active) { thumb.style.left = active.offsetLeft + 'px'; thumb.style.width = active.offsetWidth + 'px'; }
  }

  function setDiff(d) {
    state.difficulty = Math.max(1, Math.min(5, d));
    moveThumb(els.diffThumb, $('__PREFIX__-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }

  function setTab(tab) {
    state.tab = tab;
    moveThumb(els.tabThumb, $('__PREFIX__-tabs'), '[data-tab]', tab === 'answers' ? 1 : 0);
    render();
  }

  function regen() {
    if (els.spin) { els.spin.style.transform = 'rotate(360deg)'; setTimeout(function () { els.spin.style.transform = 'rotate(0deg)'; }, 500); }
    rebuild();
  }

  function showToast(msg) {
    if (!els.toast) { return; }
    els.toast.textContent = msg;
    els.toast.classList.remove('hide');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function () { els.toast.classList.add('hide'); }, 1900);
  }

  function onSave() {
    var form = new FormData();
    form.append('title', '__NAME__');
    form.append('activity', '__SLUG__');             // must be in Account::ALLOWED_ACTIVITIES
    form.append('config', JSON.stringify({ year: state.year, difficulty: state.difficulty, count: state.count }));
    fetch(window.TP_SAVE_URL || '/account/save', {
      method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form, credentials: 'same-origin', redirect: 'follow'
    }).then(function (res) {
      if (res.status === 401 || res.status === 403 || (res.redirected && /\/login/.test(res.url))) {
        window.location.href = window.TP_LOGIN_URL || '/login'; return;
      }
      showToast(res.ok ? '✓ Saved' : 'Could not save');
    }).catch(function () { showToast('Could not save'); });
  }

  function init() {
    els.grid = $('__PREFIX__-grid');
    els.diffThumb = $('__PREFIX__-difficulty') ? $('__PREFIX__-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('__PREFIX__-diff-label');
    els.eyebrowDiff = $('__PREFIX__-eyebrow-diff');
    els.tabThumb = $('__PREFIX__-tabs') ? $('__PREFIX__-tabs').querySelector('.seg-thumb') : null;
    els.intro = $('__PREFIX__-intro');
    els.spin = $('__PREFIX__-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('__PREFIX__-toast');

    var yearEl = $('__PREFIX__-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var y0 = window.TP_wireYears ? window.TP_wireYears('__PREFIX__', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    var cnt = $('__PREFIX__-count');
    if (cnt) {
      Array.prototype.forEach.call(cnt.querySelectorAll('[data-count]'), function (b) {
        b.addEventListener('click', function () { state.count = Number(b.getAttribute('data-count')); setOnState(cnt, 'data-count', state.count); rebuild(); });
      });
      setOnState(cnt, 'data-count', state.count);
    }

    Array.prototype.forEach.call($('__PREFIX__-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('__PREFIX__-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('__PREFIX__-save').addEventListener('click', onSave);
    $('__PREFIX__-print').addEventListener('click', function () { window.print(); });
    $('__PREFIX__-regen').addEventListener('click', regen);

    setDiff(state.difficulty);
    setTab('sheet');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
