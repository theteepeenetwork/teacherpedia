/* =============================================================================
 * maths-maze.js — KS2 Numeracy "Find the Path" maze puzzle.
 * -----------------------------------------------------------------------------
 * A grid of calculations. A single winding path runs from START (top-left) to
 * FINISH (bottom-right). Every square on the path has an answer that matches
 * the puzzle RULE (all even, or all odd); every off-path square breaks the rule
 * so it is a dead end. Solve a square to unlock the step onto it — a wrong turn
 * is a dead end.
 *
 * The path is a monotone lattice path (only right/down moves), which guarantees
 * the rule-matching squares form exactly one unambiguous, connected route.
 *
 * Puzzle tab    — blank grid of calculations; self-marking as you click a path.
 * Answer key    — reveals the correct route and every answer.
 * Prints as a clean puzzle. Save POSTs to /account/save.
 * ========================================================================== */
(function () {
  'use strict';

  var DIFF_LABELS = ['Foundation', 'Emerging', 'Expected', 'Greater depth', 'Challenge'];
  // Answer magnitude range per difficulty (index = difficulty - 1).
  var RANGES = [[3, 12], [4, 20], [6, 40], [10, 80], [15, 150]];

  var state = {
    difficulty: 3,
    ops: ['+', '-', '×'],
    size: 5,
    rule: 'even',          // 'even' | 'odd'  (decided per puzzle)
    tab: 'puzzle',         // 'puzzle' | 'answers'
    puzzle: null,
    current: null,         // {r,c} the student has correctly reached
    reached: {}            // "r,c" -> true for correctly stepped cells
  };

  // ---- helpers --------------------------------------------------------------
  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function pick(a) { return a[Math.floor(Math.random() * a.length)]; }
  function fmt(n) { return n.toLocaleString('en-GB'); }
  function key(r, c) { return r + ',' + c; }

  // A target value in the difficulty range with the requested parity.
  function targetWithParity(d, wantEven) {
    var range = RANGES[d - 1];
    var v, guard = 0;
    do { v = ri(range[0], range[1]); guard++; } while (((v % 2 === 0) !== wantEven) && guard < 50);
    if ((v % 2 === 0) !== wantEven) { v += (v + 1 <= range[1] ? 1 : -1); } // last-resort nudge
    return v;
  }

  // Build a calculation string that evaluates to `target` for op + difficulty.
  function calcFor(target, op, d) {
    if (op === '+') {
      var a = ri(1, target - 1);
      return fmt(a) + ' + ' + fmt(target - a);
    }
    if (op === '-') {
      var span = d <= 2 ? 20 : d <= 4 ? 80 : 300;
      var extra = target + ri(1, span);
      return fmt(extra) + ' − ' + fmt(extra - target);
    }
    if (op === '×') {
      var factors = [];
      for (var i = 2; i <= Math.min(12, target); i++) { if (target % i === 0) { factors.push(i); } }
      if (factors.length) { var f = pick(factors); return f + ' × ' + fmt(target / f); }
      var ax = ri(1, target - 1);
      return fmt(ax) + ' + ' + fmt(target - ax); // fallback when prime
    }
    if (op === '÷') {
      var b = ri(2, d <= 2 ? 5 : 9);
      return fmt(target * b) + ' ÷ ' + b;
    }
    var af = ri(1, target - 1);
    return fmt(af) + ' + ' + fmt(target - af);
  }

  // Monotone lattice path (right/down only) from (0,0) to (n-1,n-1).
  function buildPath(n) {
    var moves = [];
    for (var i = 0; i < n - 1; i++) { moves.push('R'); }
    for (var j = 0; j < n - 1; j++) { moves.push('D'); }
    // Fisher-Yates shuffle
    for (var k = moves.length - 1; k > 0; k--) {
      var m = Math.floor(Math.random() * (k + 1));
      var t = moves[k]; moves[k] = moves[m]; moves[m] = t;
    }
    var r = 0, c = 0, path = [{ r: 0, c: 0 }];
    moves.forEach(function (mv) { if (mv === 'R') { c++; } else { r++; } path.push({ r: r, c: c }); });
    return path;
  }

  function build() {
    var d = state.difficulty;
    var n = state.size;
    var ops = state.ops.length ? state.ops : ['+'];
    var rule = pick(['even', 'odd']);
    state.rule = rule;
    var wantEven = rule === 'even';

    var path = buildPath(n);
    var onPath = {};
    path.forEach(function (p) { onPath[key(p.r, p.c)] = true; });

    var startK = key(0, 0);
    var finishK = key(n - 1, n - 1);

    var cells = [];
    for (var r = 0; r < n; r++) {
      for (var c = 0; c < n; c++) {
        var k = key(r, c);
        var isPath = !!onPath[k];
        var isStart = k === startK;
        var isFinish = k === finishK;
        var cell = { r: r, c: c, onPath: isPath, start: isStart, finish: isFinish, q: '', answer: null };
        if (!isStart) {
          // Path cells obey the rule; off-path cells break it (dead ends).
          var target = targetWithParity(d, isPath ? wantEven : !wantEven);
          cell.answer = target;
          cell.q = calcFor(target, pick(ops), d);
        }
        cells.push(cell);
      }
    }

    return { size: n, rule: rule, cells: cells, path: path };
  }

  // ---- DOM ------------------------------------------------------------------
  var els = {};
  function $(id) { return document.getElementById(id); }

  function cellAt(p, r, c) { return p.cells[r * p.size + c]; }

  // ---- rendering ------------------------------------------------------------
  function renderGrid() {
    var p = state.puzzle;
    var answersOn = state.tab === 'answers';
    var grid = els.grid;
    grid.style.setProperty('--mm-n', p.size);
    grid.innerHTML = '';

    p.cells.forEach(function (cell) {
      var div = document.createElement('div');
      div.className = 'mm-cell';
      div.setAttribute('data-r', cell.r);
      div.setAttribute('data-c', cell.c);

      if (cell.start || cell.finish) { div.classList.add('mm-endpoint'); }

      if (cell.start) {
        var st = document.createElement('span'); st.className = 'mm-tag'; st.textContent = 'Start';
        div.appendChild(st);
      } else {
        var q = document.createElement('span'); q.className = 'mm-q'; q.textContent = cell.q;
        div.appendChild(q);
        if (cell.finish) {
          var ft = document.createElement('span'); ft.className = 'mm-tag'; ft.textContent = 'Finish';
          div.appendChild(ft);
        }
        if (answersOn) {
          var a = document.createElement('span'); a.className = 'mm-a'; a.textContent = '= ' + fmt(cell.answer);
          div.appendChild(a);
        }
      }

      if (answersOn) {
        if (cell.onPath) { div.classList.add('mm-path'); }
      } else {
        // puzzle tab: reflect the student's progress
        if (state.reached[key(cell.r, cell.c)]) { div.classList.add('mm-correct'); }
        div.addEventListener('click', function () { onCellClick(cell); });
      }

      grid.appendChild(div);
    });
  }

  function setStatus(msg) { if (els.statusText) { els.statusText.textContent = msg; } }

  function renderControls() {
    // operation chips
    Array.prototype.forEach.call(els.ops.querySelectorAll('[data-op]'), function (c) {
      c.classList.toggle('chip-on', state.ops.indexOf(c.getAttribute('data-op')) !== -1);
    });
    // size chips
    Array.prototype.forEach.call(els.size.querySelectorAll('[data-size]'), function (c) {
      c.classList.toggle('chip-on', Number(c.getAttribute('data-size')) === state.size);
    });
    // difficulty thumb + labels (measure the active button)
    var diffWrap = $('mm-difficulty');
    var active = diffWrap ? diffWrap.querySelectorAll('[data-diff]')[state.difficulty - 1] : null;
    if (els.diffThumb && active) {
      els.diffThumb.style.left = active.offsetLeft + 'px';
      els.diffThumb.style.width = active.offsetWidth + 'px';
    }
    var label = DIFF_LABELS[state.difficulty - 1];
    if (els.diffLabel) { els.diffLabel.textContent = label; }
    if (els.eyebrowDiff) { els.eyebrowDiff.textContent = label; }
    // tab thumb (measure the active segment)
    var tabsWrap = $('mm-tabs');
    var activeTab = tabsWrap ? tabsWrap.querySelectorAll('[data-tab]')[state.tab === 'answers' ? 1 : 0] : null;
    if (els.tabThumb && activeTab) {
      els.tabThumb.style.left = activeTab.offsetLeft + 'px';
      els.tabThumb.style.width = activeTab.offsetWidth + 'px';
    }
    // rule text
    if (els.ruleText) {
      els.ruleText.textContent = 'step only on squares with an ' + state.rule + ' answer';
    }
  }

  function render() {
    renderControls();
    renderGrid();
    if (state.tab === 'answers') {
      setStatus('Answer key — the highlighted squares are the correct path.');
    } else {
      // Restore a progress-appropriate prompt (e.g. when switching back from
      // the answer key) without clobbering live click feedback.
      var p = state.puzzle;
      var cur = state.current;
      if (p && cur && cur.r === p.size - 1 && cur.c === p.size - 1) {
        setStatus('🎉 Solved! You found the path to FINISH.');
      } else if (Object.keys(state.reached).length > 1) {
        setStatus('Correct so far — keep going!');
      } else {
        setStatus('Click a square next to START to begin.');
      }
    }
  }

  // ---- interaction ----------------------------------------------------------
  function onCellClick(cell) {
    if (state.tab !== 'puzzle') { return; }
    var cur = state.current;
    if (cell.start) { setStatus('You are at START — step to a touching square.'); return; }
    if (cur && cell.r === cur.r && cell.c === cur.c) { return; }

    var adjacent = cur && (Math.abs(cell.r - cur.r) + Math.abs(cell.c - cur.c) === 1);
    if (!adjacent) {
      setStatus('That square isn’t next to where you are.');
      return;
    }

    if (cell.onPath) {
      state.reached[key(cell.r, cell.c)] = true;
      state.current = { r: cell.r, c: cell.c };
      var div = els.grid.querySelector('[data-r="' + cell.r + '"][data-c="' + cell.c + '"]');
      if (div) { div.classList.remove('mm-dead'); div.classList.add('mm-correct'); }
      if (cell.finish) {
        setStatus('🎉 Solved! You found the path to FINISH.');
      } else {
        setStatus('Correct — keep going!');
      }
    } else {
      var dead = els.grid.querySelector('[data-r="' + cell.r + '"][data-c="' + cell.c + '"]');
      if (dead) { dead.classList.add('mm-dead'); }
      setStatus('Dead end! Go back and try another square.');
    }
  }

  function resetProgress() {
    state.current = { r: 0, c: 0 };
    state.reached = {};
    state.reached[key(0, 0)] = true;
    setStatus('Click a square next to START to begin.');
  }

  // ---- actions --------------------------------------------------------------
  function rebuild() {
    state.puzzle = build();
    resetProgress();
    render();
  }

  function setDiff(d) { state.difficulty = d; rebuild(); }
  function setSize(n) { state.size = n; rebuild(); }
  function setTab(tab) { state.tab = tab; render(); }

  function toggleOp(op) {
    var i = state.ops.indexOf(op);
    if (i === -1) { state.ops.push(op); } else { state.ops.splice(i, 1); }
    if (!state.ops.length) { state.ops = [op]; } // at least one op always on
    rebuild();
  }

  var spinT;
  function regen() {
    if (els.spin) {
      els.spin.style.transform = 'rotate(360deg)';
      clearTimeout(spinT);
      spinT = setTimeout(function () { els.spin.style.transform = 'none'; }, 500);
    }
    rebuild();
  }

  var toastT;
  function showToast(msg) {
    if (!els.toast) { return; }
    els.toast.textContent = msg;
    els.toast.classList.remove('hide');
    clearTimeout(toastT);
    toastT = setTimeout(function () { els.toast.classList.add('hide'); }, 1900);
  }

  function onSave() {
    var p = state.puzzle;
    var config = {
      difficulty: state.difficulty,
      operations: state.ops.slice(),
      size: state.size,
      rule: p ? p.rule : state.rule
    };
    var form = new FormData();
    form.append('title', 'Maths Maze');
    form.append('activity', 'maths-maze');
    form.append('config', JSON.stringify(config));

    fetch(window.TP_SAVE_URL || '/account/save', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form,
      credentials: 'same-origin',
      redirect: 'follow'
    }).then(function (res) {
      if (res.status === 401 || res.status === 403 || (res.redirected && /\/login/.test(res.url))) {
        window.location.href = window.TP_LOGIN_URL || '/login';
        return;
      }
      showToast(res.ok ? '✓ Saved' : 'Could not save');
    }).catch(function () { showToast('Could not save'); });
  }

  // ---- wire up --------------------------------------------------------------
  function init() {
    els.ops = $('mm-ops');
    els.size = $('mm-size');
    els.grid = $('mm-grid');
    els.diffThumb = $('mm-difficulty') ? $('mm-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('mm-diff-label');
    els.eyebrowDiff = $('mm-eyebrow-diff');
    els.tabThumb = $('mm-tabs') ? $('mm-tabs').querySelector('.seg-thumb') : null;
    els.ruleText = $('mm-rule-text');
    els.statusText = $('mm-status-text');
    els.spin = $('mm-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('mm-toast');

    var yearEl = $('mm-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    Array.prototype.forEach.call(els.ops.querySelectorAll('[data-op]'), function (c) {
      c.addEventListener('click', function () { toggleOp(c.getAttribute('data-op')); });
    });
    Array.prototype.forEach.call(els.size.querySelectorAll('[data-size]'), function (c) {
      c.addEventListener('click', function () { setSize(Number(c.getAttribute('data-size'))); });
    });
    Array.prototype.forEach.call($('mm-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('mm-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('mm-save').addEventListener('click', onSave);
    $('mm-print').addEventListener('click', function () { window.print(); });
    $('mm-regen').addEventListener('click', regen);

    rebuild();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
