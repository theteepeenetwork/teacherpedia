/* =============================================================================
 * arithmagons.js — Arithmagon Triangles puzzle.
 * -----------------------------------------------------------------------------
 * A triangle with three CORNER circles (the vertices) and three EDGE boxes. Each
 * edge equals its two touching corners combined by the chosen operation:
 *      edge = corner ∘ corner      (∘ is + or ×)
 * Because all three edges are derived from one true set of corners, the figure
 * is over-constrained: with the edges given, the corners are uniquely fixed and
 * a wrong value breaks two edges at once — so the puzzle self-checks structurally.
 *
 * Challenge (maps to the attainment bands):
 *   Forward  (Below)     corners given, edges blank   → just combine.
 *   Inverse  (Meeting)   edges given, corners blank    → true inverse reasoning.
 *   Mixed    (Exceeding) one corner + two edges given  → find corners, then an edge.
 *
 * Puzzle tab — blank arithmagons to solve.   Answer key — every value filled in.
 * Prints clean. Save POSTs to /account/save. The pure puzzle engine is exposed
 * as window.AG for tests; DOM wiring only runs in a browser.
 * ========================================================================== */
(function () {
  'use strict';

  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function pick(a) { return a[Math.floor(Math.random() * a.length)]; }
  function fmt(n) { return Number(n).toLocaleString('en-GB'); }

  // Corner-value range per effective difficulty (1-5), per operation. Add uses
  // bigger corners; multiply keeps corners small so edges stay sensible products.
  var ADD_RANGE = [[1, 9], [2, 15], [3, 25], [5, 40], [8, 60]];
  var MUL_RANGE = [[2, 5], [2, 6], [2, 9], [2, 10], [2, 12]];

  function edgeVal(op, a, b) { return op === '×' ? a * b : a + b; }

  // Generate one arithmagon. Returns:
  //   { op, v:[v0,v1,v2], e:[e0,e1,e2], shownV:[bool×3], shownE:[bool×3], pattern }
  // edges: e0 = v0∘v1, e1 = v1∘v2, e2 = v2∘v0.
  function generate(op, pattern, eff) {
    eff = Math.max(1, Math.min(5, eff | 0));
    var range = (op === '×' ? MUL_RANGE : ADD_RANGE)[eff - 1];
    var v = [ri(range[0], range[1]), ri(range[0], range[1]), ri(range[0], range[1])];
    var e = [edgeVal(op, v[0], v[1]), edgeVal(op, v[1], v[2]), edgeVal(op, v[2], v[0])];

    var shownV, shownE;
    if (pattern === 'forward') { shownV = [true, true, true]; shownE = [false, false, false]; }
    else if (pattern === 'mixed') { shownV = [true, false, false]; shownE = [true, false, true]; }
    else { shownV = [false, false, false]; shownE = [true, true, true]; } // inverse

    return { op: op, v: v, e: e, shownV: shownV, shownE: shownE, pattern: pattern };
  }

  // Independently SOLVE a puzzle from only its shown cells, to prove it is
  // uniquely solvable (used by the answer key and by tests). Returns the full
  // {v:[],e:[]} or null if it cannot be solved with whole numbers.
  function solve(p) {
    var op = p.op;
    var inv = op === '×'
      ? function (whole, part) { return (part !== 0 && whole % part === 0) ? whole / part : null; } // corner = edge ÷ corner
      : function (whole, part) { return whole - part; };
    var combine = function (a, b) { return edgeVal(op, a, b); };
    var v = [null, null, null], e = [null, null, null], i;
    for (i = 0; i < 3; i++) { if (p.shownV[i]) { v[i] = p.v[i]; } if (p.shownE[i]) { e[i] = p.e[i]; } }

    if (p.pattern === 'inverse') {
      // all three edges known, corners unknown.
      if (op === '×') {
        // v0 = √(e0·e2 / e1), etc. (exact by construction)
        var n0 = e[0] * e[2], r0 = n0 % e[1] === 0 ? Math.sqrt(n0 / e[1]) : NaN;
        if (!Number.isInteger(r0)) { return null; }
        v[0] = r0; v[1] = inv(e[0], v[0]); v[2] = inv(e[2], v[0]);
      } else {
        // v0 = (e0 + e2 − e1)/2, etc.
        var s0 = e[0] + e[2] - e[1];
        if (s0 < 0 || s0 % 2 !== 0) { return null; }
        v[0] = s0 / 2; v[1] = e[0] - v[0]; v[2] = e[2] - v[0];
      }
    }
    // Constraint propagation for forward / mixed (and to fill any remaining cell):
    // each relation links v[i], v[(i+1)%3], e[i]; with any two known, derive the third.
    var changed = true, guard = 0;
    while (changed && guard < 20) {
      changed = false; guard++;
      for (i = 0; i < 3; i++) {
        var j = (i + 1) % 3;
        if (v[i] != null && v[j] != null && e[i] == null) { e[i] = combine(v[i], v[j]); changed = true; }
        else if (e[i] != null && v[i] != null && v[j] == null) { var x = inv(e[i], v[i]); if (x == null) { return null; } v[j] = x; changed = true; }
        else if (e[i] != null && v[j] != null && v[i] == null) { var y = inv(e[i], v[j]); if (y == null) { return null; } v[i] = y; changed = true; }
      }
    }
    for (i = 0; i < 3; i++) { if (v[i] == null || e[i] == null || v[i] < 0) { return null; } }
    return { v: v, e: e };
  }

  if (typeof window !== 'undefined') { window.AG = { generate: generate, solve: solve, edgeVal: edgeVal }; }

  /* ---- DOM (browser only) ------------------------------------------------- */
  if (typeof document === 'undefined') { return; }

  var ACCENT = '#7b4cc4';
  function $(id) { return document.getElementById(id); }

  var state = {
    year: 4,
    difficulty: 3,
    op: '+',                 // '+' | '×'
    pattern: 'inverse',      // 'forward' | 'inverse' | 'mixed'
    count: 6,                // puzzles per sheet
    tab: 'puzzle',
    puzzles: []
  };

  var els = {};

  function eff() {
    return window.TP_effDifficulty ? window.TP_effDifficulty(state.year, state.difficulty) : state.difficulty;
  }

  function rebuild() {
    state.puzzles = [];
    var e = eff();
    for (var i = 0; i < state.count; i++) {
      var p = null, guard = 0;
      // generate + verify uniquely solvable; regen on the rare reject.
      do { p = generate(state.op, state.pattern, e); guard++; } while (!solve(p) && guard < 30);
      state.puzzles.push(p);
    }
    render();
  }

  // SVG for one triangle. `revealed` true on the answer-key tab fills blanks.
  function triangleSVG(p, revealed) {
    var W = 210, H = 196;
    var corners = [[105, 26], [182, 165], [28, 165]];     // v0 top, v1 br, v2 bl
    var mids = [
      [(corners[0][0] + corners[1][0]) / 2 + 14, (corners[0][1] + corners[1][1]) / 2], // e0 right
      [(corners[1][0] + corners[2][0]) / 2, (corners[1][1] + corners[2][1]) / 2 + 4],   // e1 bottom
      [(corners[2][0] + corners[0][0]) / 2 - 14, (corners[2][1] + corners[0][1]) / 2]   // e2 left
    ];
    var s = '<svg viewBox="0 0 ' + W + ' ' + H + '" width="' + W + '" height="' + H + '" xmlns="http://www.w3.org/2000/svg" class="ag-svg">';
    // triangle sides
    s += '<polygon points="' + corners.map(function (c) { return c[0] + ',' + c[1]; }).join(' ') + '" fill="none" stroke="#c9cdd6" stroke-width="2"/>';
    // edge boxes (rectangles)
    for (var k = 0; k < 3; k++) {
      var m = mids[k], shown = p.shownE[k];
      var val = shown ? fmt(p.e[k]) : (revealed ? fmt(p.e[k]) : '');
      var blankReveal = revealed && !shown;
      s += '<g>';
      s += '<rect x="' + (m[0] - 21) + '" y="' + (m[1] - 16) + '" width="42" height="32" rx="7" fill="' + (shown ? '#fff' : (blankReveal ? 'rgba(123,76,196,.10)' : '#fff')) + '" stroke="' + (shown ? '#9aa0ad' : ACCENT) + '" stroke-width="' + (shown ? 1.5 : 2) + '" ' + (shown ? '' : 'stroke-dasharray="' + (blankReveal ? '0' : '5 4') + '"') + '/>';
      if (val !== '') { s += '<text x="' + m[0] + '" y="' + (m[1] + 5) + '" text-anchor="middle" font-size="16" font-weight="700" fill="' + (blankReveal ? ACCENT : '#26302a') + '" font-family="system-ui,sans-serif">' + val + '</text>'; }
      s += '</g>';
    }
    // corner circles
    for (var c = 0; c < 3; c++) {
      var pt = corners[c], sv = p.shownV[c];
      var cval = sv ? fmt(p.v[c]) : (revealed ? fmt(p.v[c]) : '');
      var cReveal = revealed && !sv;
      s += '<circle cx="' + pt[0] + '" cy="' + pt[1] + '" r="22" fill="' + (sv ? '#f3f0fb' : (cReveal ? 'rgba(123,76,196,.12)' : '#fff')) + '" stroke="' + (sv ? '#9aa0ad' : ACCENT) + '" stroke-width="' + (sv ? 1.5 : 2) + '" ' + (sv ? '' : (cReveal ? '' : 'stroke-dasharray="5 4"')) + '/>';
      if (cval !== '') { s += '<text x="' + pt[0] + '" y="' + (pt[1] + 6) + '" text-anchor="middle" font-size="17" font-weight="800" fill="' + (cReveal ? ACCENT : '#26302a') + '" font-family="system-ui,sans-serif">' + cval + '</text>'; }
    }
    s += '</svg>';
    return s;
  }

  function render() {
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    if (els.opSymbol) { els.opSymbol.textContent = state.op === '×' ? 'multiply (×)' : 'add (+)'; }
    var revealed = state.tab === 'answers';
    var html = '<div class="ag-grid" style="--ag-cols:' + (state.count >= 9 ? 3 : 2) + ';">';
    state.puzzles.forEach(function (p, i) {
      html += '<figure class="ag-card">' + triangleSVG(p, revealed) +
        '<figcaption class="ag-cap">Puzzle ' + (i + 1) + '</figcaption></figure>';
    });
    html += '</div>';
    els.grid.innerHTML = html;
  }

  // ---- toolbar wiring -------------------------------------------------------
  function setOnState(wrap, attr, val) {
    Array.prototype.forEach.call(wrap.querySelectorAll('[' + attr + ']'), function (b) {
      b.classList.toggle('chip-on', b.getAttribute(attr) === String(val));
    });
  }

  function setDiff(d) {
    state.difficulty = Math.max(1, Math.min(5, d));
    if (els.diffThumb) { els.diffThumb.style.left = ((state.difficulty - 1) / 4 * 100) + '%'; }
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }

  function setTab(tab) {
    state.tab = tab;
    if (els.tabThumb) { els.tabThumb.style.left = tab === 'answers' ? '50%' : '0%'; }
    Array.prototype.forEach.call($('ag-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.classList.toggle('seg-on', b.getAttribute('data-tab') === tab);
    });
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
    form.append('tool', 'arithmagons');
    form.append('title', 'Arithmagon Triangles');
    form.append('config_json', JSON.stringify({
      year: state.year, difficulty: state.difficulty, op: state.op, pattern: state.pattern, count: state.count
    }));
    fetch(window.TP_SAVE_URL || '/account/save', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form, credentials: 'same-origin', redirect: 'follow'
    }).then(function (res) {
      if (res.status === 401 || res.status === 403 || (res.redirected && /\/login/.test(res.url))) {
        window.location.href = window.TP_LOGIN_URL || '/login';
        return;
      }
      showToast(res.ok ? '✓ Saved' : 'Could not save');
    }).catch(function () { showToast('Could not save'); });
  }

  function init() {
    els.grid = $('ag-grid');
    els.diffThumb = $('ag-difficulty') ? $('ag-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('ag-diff-label');
    els.eyebrowDiff = $('ag-eyebrow-diff');
    els.tabThumb = $('ag-tabs') ? $('ag-tabs').querySelector('.seg-thumb') : null;
    els.opSymbol = $('ag-op-symbol');
    els.spin = $('ag-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('ag-toast');

    var yearEl = $('ag-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var y0 = window.TP_wireYears ? window.TP_wireYears('ag', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    var opWrap = $('ag-ops');
    Array.prototype.forEach.call(opWrap.querySelectorAll('[data-op]'), function (b) {
      b.addEventListener('click', function () { state.op = b.getAttribute('data-op'); setOnState(opWrap, 'data-op', state.op); rebuild(); });
    });
    setOnState(opWrap, 'data-op', state.op);

    var patWrap = $('ag-pattern');
    Array.prototype.forEach.call(patWrap.querySelectorAll('[data-pat]'), function (b) {
      b.addEventListener('click', function () { state.pattern = b.getAttribute('data-pat'); setOnState(patWrap, 'data-pat', state.pattern); rebuild(); });
    });
    setOnState(patWrap, 'data-pat', state.pattern);

    var cntWrap = $('ag-count');
    Array.prototype.forEach.call(cntWrap.querySelectorAll('[data-count]'), function (b) {
      b.addEventListener('click', function () { state.count = Number(b.getAttribute('data-count')); setOnState(cntWrap, 'data-count', state.count); rebuild(); });
    });
    setOnState(cntWrap, 'data-count', state.count);

    Array.prototype.forEach.call($('ag-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('ag-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('ag-save').addEventListener('click', onSave);
    $('ag-print').addEventListener('click', function () { window.print(); });
    $('ag-regen').addEventListener('click', regen);

    setDiff(state.difficulty);
    setTab('puzzle');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
