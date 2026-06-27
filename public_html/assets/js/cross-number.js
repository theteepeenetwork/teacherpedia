/* =============================================================================
 * cross-number.js — Cross-Number Crossword.
 * -----------------------------------------------------------------------------
 * A small square grid where every white cell holds ONE digit. Across and Down
 * entries are whole numbers; each entry has a CLUE that is a calculation
 * (e.g. 24 × 3, 156 + 88, 600 ÷ 8) whose answer is that entry's number.
 * Across and Down entries share cells at intersections, so a wrong digit clashes
 * with the crossing answer and the grid won't close — it self-marks.
 *
 * RELIABLE ENGINE: a small set of FIXED grid skeletons (which cells are white
 * and the across/down entries). To generate: fill every white cell with a random
 * digit (no leading zero on any entry), read each entry's number off the grid,
 * then build a calculation clue whose result EQUALS that number. Intersections
 * are consistent by construction because Across and Down read the SAME cell.
 *
 * Pure engine exposed as window.TP_CN for Node tests; DOM wiring runs in-browser.
 * See dev/RESOURCE_WORKFLOW.md.
 * ========================================================================== */
(function () {
  'use strict';

  // ---- self-contained helpers ---------------------------------------------
  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function pick(a) { return a[Math.floor(Math.random() * a.length)]; }
  function fmt(n) { return Number(n).toLocaleString('en-GB'); }
  function shuffle(arr) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; }
    return a;
  }
  function yearTables(year) {
    return (typeof window !== 'undefined' && window.TP_yearTables) ? window.TP_yearTables(year)
      : (year <= 2 ? [2, 5, 10] : year === 3 ? [2, 3, 4, 5, 8, 10] : [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
  }
  function digitsOf(n, len) {
    var s = String(n), out = [];
    for (var i = 0; i < s.length; i++) { out.push(s.charCodeAt(i) - 48); }
    while (out.length < len) { out.unshift(0); }
    return out;
  }

  // ---- fixed grid skeletons -------------------------------------------------
  // Each skeleton is an ASCII grid MASK keyed by tier (below|meeting|exceeding).
  // Entries (Across/Down) and crossword numbering are DERIVED from the mask, so
  // every grid is a well-formed, dense crossword (6 Across + 6+ Down, many
  // crossings). The masks were found by a validated search (6/6 entries, runs in
  // the tier's digit range, fully connected, every entry crossing). See
  // dev/RESOURCE_WORKFLOW.md.
  // Derive crossword entries from an ASCII grid mask ('.'=white, '#'=black):
  // each entry is a maximal run of >=2 white cells (bounded by a black cell or
  // the edge), so the grid is ALWAYS a well-formed crossword and dense 6-across/
  // 6-down grids are easy to draw. See dev/RESOURCE_WORKFLOW.md.
  function entriesFromGrid(grid) {
    var R = grid.length, C = grid[0].length;
    function w(r, c) { return r >= 0 && r < R && c >= 0 && c < C && grid[r].charAt(c) !== '#'; }
    var es = [];
    for (var r = 0; r < R; r++) {
      for (var c = 0; c < C; c++) {
        if (!w(r, c)) { continue; }
        if (!w(r, c - 1) && w(r, c + 1)) { var la = 0; while (w(r, c + la)) { la++; } es.push({ dir: 'A', r: r, c: c, len: la }); }
        if (!w(r - 1, c) && w(r + 1, c)) { var ld = 0; while (w(r + ld, c)) { ld++; } es.push({ dir: 'D', r: r, c: c, len: ld }); }
      }
    }
    return es;
  }

  var SKELETONS = [
    { id: 'b1', tier: 'below', grid: [
      '###...',
      '#...#.',
      '#.#..#',
      '#..#.#',
      '.#...#',
      '...###'
    ] },
    { id: 'b2', tier: 'below', grid: [
      '...##.',
      '#.#...',
      '#...#.',
      '.#...#',
      '...#.#',
      '.##...'
    ] },
    { id: 'b3', tier: 'below', grid: [
      '#.#...',
      '#..#.#',
      '##...#',
      '#...##',
      '#.#..#',
      '...#.#'
    ] },
    { id: 'm1', tier: 'meeting', grid: [
      '..###.#',
      '#.###.#',
      '...#...',
      '##...##',
      '...#...',
      '#.###.#',
      '#.###..'
    ] },
    { id: 'm2', tier: 'meeting', grid: [
      '...###.',
      '##.#...',
      '##...##',
      '###.###',
      '##...##',
      '...#.##',
      '.###...'
    ] },
    { id: 'm3', tier: 'meeting', grid: [
      '..#####',
      '...####',
      '...####',
      '##...##',
      '####...',
      '####...',
      '#####..'
    ] },
    { id: 'e1', tier: 'exceeding', grid: [
      '##.#####',
      '...#.###',
      '#....###',
      '#.##....',
      '....##.#',
      '###....#',
      '###.#...',
      '#####.##'
    ] },
    { id: 'e2', tier: 'exceeding', grid: [
      '.#######',
      '.##...##',
      '....####',
      '.#....##',
      '##....#.',
      '####....',
      '##...##.',
      '#######.'
    ] },
    { id: 'e3', tier: 'exceeding', grid: [
      '####...#',
      '####.###',
      '.#....##',
      '.#.#....',
      '....#.#.',
      '##....#.',
      '###.####',
      '#...####'
    ] },
  ];
  // Derive rows/cols/entries from each grid mask once at load.
  for (var _si = 0; _si < SKELETONS.length; _si++) {
    var _sk = SKELETONS[_si];
    _sk.rows = _sk.grid.length;
    _sk.cols = _sk.grid[0].length;
    _sk.entries = entriesFromGrid(_sk.grid);
  }

  // Derive white cells + crossword numbering for a skeleton. Returns a fresh
  // model: { rows, cols, white(set), starts(map key->number), entries(numbered) }.
  // Numbering: scan cells row-major; a cell that begins ANY entry gets the next
  // number; Across+Down sharing a start share that number (standard crossword).
  function layout(sk) {
    var startSet = {};   // "r,c" -> true (a cell that begins some entry)
    var i, e;
    for (i = 0; i < sk.entries.length; i++) {
      e = sk.entries[i];
      startSet[e.r + ',' + e.c] = true;
    }
    var number = {};     // "r,c" -> printed number
    var n = 0;
    for (var r = 0; r < sk.rows; r++) {
      for (var c = 0; c < sk.cols; c++) {
        if (startSet[r + ',' + c]) { n++; number[r + ',' + c] = n; }
      }
    }
    var white = {};
    var entries = [];
    for (i = 0; i < sk.entries.length; i++) {
      e = sk.entries[i];
      var cells = [];
      for (var k = 0; k < e.len; k++) {
        var cr = e.dir === 'A' ? e.r : e.r + k;
        var cc = e.dir === 'A' ? e.c + k : e.c;
        white[cr + ',' + cc] = true;
        cells.push([cr, cc]);
      }
      entries.push({ n: number[e.r + ',' + e.c], dir: e.dir, r: e.r, c: e.c, len: e.len, cells: cells });
    }
    return { id: sk.id, tier: sk.tier, rows: sk.rows, cols: sk.cols, white: white, number: number, entries: entries };
  }

  // ---- reverse clue builders ------------------------------------------------
  // Each returns a calculation STRING that evaluates to `value`, or null if it
  // cannot represent the value for the given difficulty/year. Operators use the
  // glyphs ×  ÷  − (U+2212) to match the rest of the suite.
  function clueBuilders(value, d, year, tier) {
    var tables = yearTables(year);

    function add() {
      if (value < 2) { return null; }
      // keep both operands sensible (away from trivial 0 / 1) for bigger values
      var lo = value >= 100 ? Math.floor(value * 0.2) : 1;
      var hi = value - lo;
      if (hi < lo) { lo = 1; hi = value - 1; }
      var a = ri(lo, hi);
      return fmt(a) + ' + ' + fmt(value - a);
    }
    function sub() {
      if (value < 1) { return null; }
      var span = d <= 2 ? 30 : d <= 4 ? 200 : 900;
      var extra = value + ri(1, span);
      return fmt(extra) + ' − ' + fmt(extra - value);
    }
    function mul() {
      if (tier === 'exceeding') {
        // long multiplication: 2-digit × 2-digit (or × small) = value
        var pairs = [];
        for (var a = 11; a <= 99; a++) {
          if (value % a === 0) {
            var b = value / a;
            if (b >= 2 && b <= 99 && a >= b) { pairs.push([a, b]); }
          }
        }
        if (!pairs.length) { return null; }
        var p = pick(pairs);
        return fmt(p[0]) + ' × ' + fmt(p[1]);
      }
      // table fact: f (a year table) × m, with multiplier m = 2..12 (a real
      // table fact, never × 1, which isn't on-curriculum practice).
      var fs = [];
      for (var i = 0; i < tables.length; i++) {
        var m = value / tables[i];
        if (value % tables[i] === 0 && m >= 2 && m <= 12) { fs.push(tables[i]); }
      }
      if (!fs.length) { return null; }
      var f = pick(fs);
      return fmt(f) + ' × ' + fmt(value / f);
    }
    function div() {
      if (value < 1) { return null; }
      if (tier === 'exceeding') {
        // long division: value × b ÷ b, b a 2-digit divisor — exact by build
        var b = ri(11, 25);
        return fmt(value * b) + ' ÷ ' + fmt(b);
      }
      var dv = pick(tables);
      return fmt(value * dv) + ' ÷ ' + fmt(dv);
    }
    function fracOf() {
      // ¾ of W, ½ of W, etc. — only when W is a clean whole and value < W.
      if (value < 2) { return null; }
      var fracs = [[1, 2, '½'], [1, 4, '¼'], [3, 4, '¾'], [1, 3, '⅓'], [2, 3, '⅔'], [1, 5, '⅕']];
      var opts = shuffle(fracs);
      for (var i = 0; i < opts.length; i++) {
        var num = opts[i][0], den = opts[i][1], glyph = opts[i][2];
        // value = whole * num / den  =>  whole = value * den / num
        if ((value * den) % num !== 0) { continue; }
        var whole = value * den / num;
        if (whole <= value) { continue; }          // result must be a part
        if (whole > 9999) { continue; }
        return glyph + ' of ' + fmt(whole);
      }
      return null;
    }
    function pctOf() {
      // P% of W — clean percentages only.
      if (value < 1) { return null; }
      var pcts = [10, 25, 50, 75, 20, 5];
      var opts = shuffle(pcts);
      for (var i = 0; i < opts.length; i++) {
        var pc = opts[i];
        // value = whole * pc / 100  =>  whole = value * 100 / pc
        if ((value * 100) % pc !== 0) { continue; }
        var whole = value * 100 / pc;
        if (whole <= value) { continue; }
        if (whole > 9999) { continue; }
        return pc + '% of ' + fmt(whole);
      }
      return null;
    }
    return { '+': add, '-': sub, '×': mul, '÷': div, 'f': fracOf, '%': pctOf };
  }

  // Build a clue for `value`. Tries the selected ops (shuffled), then falls back
  // to +/- which can always represent any value — so generation never fails.
  function clueFor(value, ops, d, year, tier) {
    var make = clueBuilders(value, d, year, tier);
    var order = shuffle(ops.slice());
    for (var i = 0; i < order.length; i++) {
      var fn = make[order[i]];
      if (fn) { var s = fn(); if (s) { return s; } }
    }
    return make['-']() || make['+']();    // guaranteed for any value ≥ 1
  }

  // ---- generate -------------------------------------------------------------
  // opts = { year, band, meter, ops }.  Returns { grid, entries, qtn, ans }.
  function generate(opts) {
    opts = opts || {};
    var year = opts.year || 4;
    var band = opts.band || 'meeting';
    var tier = band;   // 'below' | 'meeting' | 'exceeding'
    var d;
    if (opts.meter && (typeof window !== 'undefined') && window.TP_effDifficulty) {
      d = window.TP_effDifficulty(year, opts.meter);
    } else if ((typeof window !== 'undefined') && window.TP_bandDifficulty) {
      d = window.TP_bandDifficulty(year, band);
    } else {
      d = band === 'below' ? 2 : band === 'exceeding' ? 4 : 3;
    }
    // Gate the available ops by TIER so differentiation matches the curriculum:
    //   Below     -> +/- only (mostly small entries)
    //   Meeting   -> adds ×/÷ (table facts / exact division)
    //   Exceeding -> adds long ×/÷ and fraction/percentage-of-amount
    // The UI op-chips are the teacher's *preference*; the tier is the ceiling, so
    // a Below sheet never prints an off-curriculum × or ÷ regardless of chips.
    var allowed = tier === 'below' ? ['+', '-']
      : tier === 'meeting' ? ['+', '-', '×', '÷']
      : ['+', '-', '×', '÷', 'f', '%'];
    var ops = (opts.ops && opts.ops.length) ? opts.ops.slice() : allowed.slice();
    ops = ops.filter(function (o) { return allowed.indexOf(o) !== -1; });
    if (!ops.length) { ops = tier === 'below' ? ['+', '-'] : allowed.slice(); }

    // pick a skeleton for the tier
    var pool = SKELETONS.filter(function (s) { return s.tier === tier; });
    if (!pool.length) { pool = SKELETONS.filter(function (s) { return s.tier === 'meeting'; }); }
    var lay = layout(pick(pool));

    // 1) fill every white cell with a random digit. Entry-start cells must be
    //    1–9 (no leading zero); interior cells may be 0–9. Start cells are
    //    filled first so an intersecting interior never overwrites them.
    var digit = {};            // "r,c" -> 0..9
    var startKeys = {};
    var i, e;
    for (i = 0; i < lay.entries.length; i++) { startKeys[lay.entries[i].r + ',' + lay.entries[i].c] = true; }
    var wk = Object.keys(lay.white);
    for (i = 0; i < wk.length; i++) {
      digit[wk[i]] = startKeys[wk[i]] ? ri(1, 9) : ri(0, 9);
    }

    // 2) read each entry's number off the grid; 3) build its clue.
    var numbered = [];
    for (i = 0; i < lay.entries.length; i++) {
      e = lay.entries[i];
      var value = 0;
      for (var k = 0; k < e.cells.length; k++) {
        value = value * 10 + digit[e.cells[k][0] + ',' + e.cells[k][1]];
      }
      var clue = clueFor(value, ops, d, year, tier);
      numbered.push({ n: e.n, dir: e.dir, r: e.r, c: e.c, len: e.len, cells: e.cells, value: value, clue: clue });
    }
    numbered.sort(function (a, b) { return a.n - b.n || (a.dir === 'A' ? -1 : 1); });

    // 4) build grids: ans = filled, grid = blank mask.
    var grid = [], ans = [];
    for (var r = 0; r < lay.rows; r++) {
      grid.push([]); ans.push([]);
      for (var c = 0; c < lay.cols; c++) {
        if (lay.white[r + ',' + c]) {
          grid[r].push('');                  // blank white cell
          ans[r].push(String(digit[r + ',' + c]));
        } else {
          grid[r].push(null);                // black
          ans[r].push(null);
        }
      }
    }

    var across = numbered.filter(function (x) { return x.dir === 'A'; });
    var down = numbered.filter(function (x) { return x.dir === 'D'; });

    return {
      id: lay.id, rows: lay.rows, cols: lay.cols,
      white: lay.white, number: lay.number,
      grid: grid, ans: ans,
      entries: numbered,
      qtn: { rows: lay.rows, cols: lay.cols, white: lay.white, number: lay.number, across: across, down: down }
    };
  }

  // The unique solution is simply the filled grid produced above.
  function solve(puzzle) { return puzzle ? puzzle.ans : null; }

  if (typeof window !== 'undefined') {
    window.TP_CN = { SKELETONS: SKELETONS, layout: layout, generate: generate, solve: solve, clueFor: clueFor };
  }

  /* ---- DOM (browser only) ------------------------------------------------- */
  if (typeof document === 'undefined') { return; }

  function $(id) { return document.getElementById(id); }

  var BANDS = ['below', 'meeting', 'exceeding'];
  var state = {
    year: 4,
    difficulty: 3,      // 1..5 meter -> band via 1-2 below, 3 meeting, 4-5 exceeding
    ops: ['+', '-', '×', '÷'],
    tab: 'sheet',       // 'sheet' | 'answers'
    puzzle: null
  };
  var els = {};

  function bandFromMeter(m) { return m <= 2 ? 'below' : m >= 4 ? 'exceeding' : 'meeting'; }

  function rebuild() {
    // This is a Year 3-6 resource (the selector disables Y1/Y2); clamp defensively
    // so clue generation never falls back to an off-curriculum year.
    var yr = Math.max(3, Math.min(6, state.year | 0));
    state.puzzle = generate({ year: yr, band: bandFromMeter(state.difficulty), ops: state.ops });
    render();
  }

  function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

  // Render the grid as a single inline SVG directly into #cn-grid.
  function gridSVG(p, revealed) {
    var CELL = 40, PAD = 1;
    var w = p.cols * CELL + PAD * 2, h = p.rows * CELL + PAD * 2;
    var s = '<svg viewBox="0 0 ' + w + ' ' + h + '" class="cn-svg" xmlns="http://www.w3.org/2000/svg" role="img">';
    for (var r = 0; r < p.rows; r++) {
      for (var c = 0; c < p.cols; c++) {
        var x = PAD + c * CELL, y = PAD + r * CELL;
        var isWhite = !!p.white[r + ',' + c];
        var fill = isWhite ? '#ffffff' : '#1a1a1a';
        s += '<rect x="' + x + '" y="' + y + '" width="' + CELL + '" height="' + CELL +
          '" fill="' + fill + '" stroke="#1a1a1a" stroke-width="1.4"/>';
        if (isWhite) {
          var num = p.number[r + ',' + c];
          if (num) {
            s += '<text x="' + (x + 3) + '" y="' + (y + 10) + '" font-size="9" font-family="system-ui,Arial,sans-serif" fill="#1a1a1a">' + num + '</text>';
          }
          if (revealed && p.solved && p.solved[r] && p.solved[r][c] != null) {
            s += '<text x="' + (x + CELL / 2) + '" y="' + (y + CELL / 2 + 8) + '" text-anchor="middle" font-size="20" font-weight="700" font-family="system-ui,Arial,sans-serif" fill="#1a1a1a">' + p.solved[r][c] + '</text>';
          }
        }
      }
    }
    s += '</svg>';
    return s;
  }

  function clueList(list, revealed) {
    var html = '';
    for (var i = 0; i < list.length; i++) {
      var e = list[i];
      html += '<li class="cn-clue"><span class="cn-num">' + e.n + '.</span> <span class="cn-calc">' + esc(e.clue) + '</span>' +
        (revealed ? ' <span class="cn-val">= ' + fmt(e.value) + '</span>' : '') + '</li>';
    }
    return html;
  }

  function render() {
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    var p = state.puzzle;
    var revealed = state.tab === 'answers';
    p.solved = revealed ? p.ans : null;

    // Size the grid per tier so the page fills at every setting: a small Below
    // grid prints large; the big Exceeding grid is capped so it still fits one A4
    // (keeps roughly constant cell size, ~52px, across grid sizes).
    var gridMax = Math.min(520, Math.max(430, p.cols * 64));
    els.grid.style.setProperty('--cn-grid-max', gridMax + 'px');

    // grid (inline SVG) directly into #cn-grid
    els.grid.innerHTML = gridSVG(p, revealed);

    // clue columns
    if (els.across) { els.across.innerHTML = clueList(p.qtn.across, revealed); }
    if (els.down) { els.down.innerHTML = clueList(p.qtn.down, revealed); }
  }

  // ---- toolbar wiring -------------------------------------------------------
  function moveThumb(thumb, wrap, selector, index) {
    if (!thumb || !wrap) { return; }
    var active = wrap.querySelectorAll(selector)[index];
    if (active) { thumb.style.left = active.offsetLeft + 'px'; thumb.style.width = active.offsetWidth + 'px'; }
  }

  function setDiff(d) {
    state.difficulty = Math.max(1, Math.min(5, d));
    moveThumb(els.diffThumb, $('cn-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }

  function setTab(tab) {
    state.tab = tab;
    moveThumb(els.tabThumb, $('cn-tabs'), '[data-tab]', tab === 'answers' ? 1 : 0);
    render();
  }

  function toggleOp(op) {
    var i = state.ops.indexOf(op);
    if (i === -1) { state.ops.push(op); } else { state.ops.splice(i, 1); }
    if (!state.ops.length) { state.ops = [op]; }       // at least one op always on
    Array.prototype.forEach.call(els.ops.querySelectorAll('[data-op]'), function (b) {
      b.classList.toggle('chip-on', state.ops.indexOf(b.getAttribute('data-op')) !== -1);
    });
    rebuild();
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
    form.append('title', 'Cross-Number Crossword');
    form.append('activity', 'cross-number');
    form.append('config', JSON.stringify({
      year: state.year, difficulty: state.difficulty,
      band: bandFromMeter(state.difficulty), ops: state.ops.slice()
    }));
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
    els.grid = $('cn-grid');
    els.across = $('cn-across');
    els.down = $('cn-down');
    els.ops = $('cn-ops');
    els.diffThumb = $('cn-difficulty') ? $('cn-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('cn-diff-label');
    els.eyebrowDiff = $('cn-eyebrow-diff');
    els.tabThumb = $('cn-tabs') ? $('cn-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('cn-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('cn-toast');

    var yearEl = $('cn-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var y0 = window.TP_wireYears ? window.TP_wireYears('cn', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    if (els.ops) {
      Array.prototype.forEach.call(els.ops.querySelectorAll('[data-op]'), function (b) {
        b.classList.toggle('chip-on', state.ops.indexOf(b.getAttribute('data-op')) !== -1);
        b.addEventListener('click', function () { toggleOp(b.getAttribute('data-op')); });
      });
    }

    Array.prototype.forEach.call($('cn-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('cn-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('cn-save').addEventListener('click', onSave);
    $('cn-print').addEventListener('click', function () { window.print(); });
    $('cn-regen').addEventListener('click', regen);

    setDiff(state.difficulty);
    setTab('sheet');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
