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
  // Curriculum METHOD limits for × and ÷ by year — not just the digit count, but
  // which written method is on the curriculum:
  //   Y3  tables / 2-digit × 1-digit          ·  2-digit ÷ 1-digit (table facts)
  //   Y4  3-digit × 1-digit                    ·  3-digit ÷ 1-digit
  //   Y5  4-digit × 1-digit OR 2-digit × 2-digit ·  4-digit ÷ 1-digit, long ÷ begins
  //   Y6  long multiplication/division by a 2-digit number
  // A product/quotient that can't be written with the year's method returns null
  // from its builder, so the entry falls back to + / − instead.
  function mulMethodOk(sm, lg, year) {
    if (sm < 2 || lg < 2) { return false; }
    if (year <= 3) { return sm <= 9 && lg <= 99; }
    if (year === 4) { return sm <= 9 && lg <= 999; }
    if (year === 5) { return (sm <= 9 && lg <= 9999) || (sm <= 99 && lg <= 99); }
    return sm <= 99 && lg <= 9999;
  }
  function divDividendMax(year) { return year <= 3 ? 99 : year === 4 ? 999 : 9999; }

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
      '##.###',
      '...#.#',
      '.#...#',
      '..##..',
      '#...#.',
      '#.#...',
      '###.##'
    ] },
    { id: 'm2', tier: 'meeting', grid: [
      '...###',
      '.#.###',
      '.#...#',
      '..##..',
      '#...#.',
      '###.#.',
      '###...'
    ] },
    { id: 'm3', tier: 'meeting', grid: [
      '....##',
      '.#.#.#',
      '#....#',
      '..##..',
      '#....#',
      '#.#.#.',
      '##....'
    ] },
    { id: 'e1', tier: 'exceeding', grid: [
      '####.#',
      '#....#',
      '...###',
      '#....#',
      '#....#',
      '###...',
      '#....#',
      '#.####'
    ] },
    { id: 'e2', tier: 'exceeding', grid: [
      '###.##',
      '#...##',
      '..#...',
      '.##.#.',
      '.#.##.',
      '...#..',
      '##...#',
      '##.###'
    ] },
    { id: 'e3', tier: 'exceeding', grid: [
      '#####.',
      '##....',
      '###..#',
      '#....#',
      '#....#',
      '#..###',
      '....##',
      '.#####'
    ] },
  ];
  // Derive rows/cols/entries from each grid mask once at load.
  for (var _si = 0; _si < SKELETONS.length; _si++) {
    var _sk = SKELETONS[_si];
    _sk.rows = _sk.grid.length;
    _sk.cols = _sk.grid[0].length;
    _sk.entries = entriesFromGrid(_sk.grid);
  }

  // ---- random shape generation ---------------------------------------------
  // The fixed SKELETONS above guarantee a valid fallback, but using only 3 masks
  // per tier looks repetitive. So we GENERATE A FRESH random mask per puzzle and
  // accept it only if it is a well-formed, dense crossword: exactly 12 entries
  // (any Across/Down split), every run in the tier's digit range, every white
  // cell covered, enough crossings, every entry crossing another, fully
  // connected, no all-black row/col. Reject-sampling with 180°-rotational
  // symmetry on rectangular grids is reliable and fast for all three tiers
  // (below 6x6 ~100%, meeting 7x6 ~96%, exceeding 8x6 ~100% within budget — see
  // dev/validate + the shape benchmark). If a tier somehow misses its budget we
  // fall back to a RANDOM DIHEDRAL TRANSFORM of a fixed skeleton (still varied).
  //
  // Per-tier shape: rows×cols, black fraction, run-length range, min crossings,
  // and min entries per direction. Tiers differ by grid size (visible size step);
  // the harder NUMBERS/operations come from `tier`/`d` in clueBuilders, not from
  // forbidding short entries (which is what made big square grids unsearchable).
  // minPer 5 with total 12 keeps the Across/Down split balanced (each direction
  // 5–7), so neither clue column runs long enough to overflow the printed page —
  // a 4/8 or 3/9 split stacks 8–9 clues in one column and spills to a 2nd sheet.
  var SHAPE = {
    below:     { rows: 6, cols: 6, bf: 0.28, minL: 2, maxL: 3, minX: 6, minPer: 5, total: 12 },
    meeting:   { rows: 7, cols: 6, bf: 0.26, minL: 2, maxL: 4, minX: 5, minPer: 5, total: 12 },
    exceeding: { rows: 8, cols: 6, bf: 0.30, minL: 2, maxL: 4, minX: 5, minPer: 5, total: 12 }
  };

  // CURRICULUM CEILING on the number of DIGITS in any entry, by year. Year 3 adds
  // and subtracts numbers "up to three digits"; from Year 4 the column method
  // extends to four digits (Y5/Y6 go further, but the crossword caps at 4 to keep
  // the grid and the ×/÷ envelope sane — still within curriculum). The grid's run
  // length IS the entry's digit count, so the ceiling is enforced by choosing the
  // shape, not just by clamping clues.
  function ceilingDigits(year) { return year <= 3 ? 3 : 4; }

  // Map (year, band) to the SHAPE whose max run length respects the year ceiling.
  // A 3-digit ceiling (Year 3, or a "below" sheet) always uses the small 6x6
  // grid whatever the difficulty meter says; 4-digit years grow the grid by band.
  // The difficulty still scales operand magnitude and operations in clueBuilders.
  function shapeTier(year, band) {
    if (ceilingDigits(year) <= 3) { return 'below'; }
    if (band === 'below') { return 'below'; }
    return band;
  }

  function randMaskGrid(rows, cols, bf) {
    var g = [], r, c;
    for (r = 0; r < rows; r++) { var row = []; for (c = 0; c < cols; c++) { row.push('.'); } g.push(row); }
    for (r = 0; r < rows; r++) {
      for (c = 0; c < cols; c++) {
        if (Math.random() < bf) { g[r][c] = '#'; g[rows - 1 - r][cols - 1 - c] = '#'; }   // keep 180° symmetry
      }
    }
    return g.map(function (a) { return a.join(''); });
  }

  // Is `grid` a well-formed, dense crossword for params P? (the acceptance test)
  function maskValid(grid, P) {
    var R = grid.length, C = grid[0].length;
    function w(r, c) { return r >= 0 && r < R && c >= 0 && c < C && grid[r].charAt(c) !== '#'; }
    var es = entriesFromGrid(grid);
    var A = 0, D = 0, i, e, k, r, c;
    for (i = 0; i < es.length; i++) {
      e = es[i];
      if (e.len < P.minL || e.len > P.maxL) { return false; }
      if (e.dir === 'A') { A++; } else { D++; }
    }
    if (A + D !== P.total) { return false; }
    if (A < P.minPer || D < P.minPer) { return false; }
    for (r = 0; r < R; r++) { var ab = true; for (c = 0; c < C; c++) { if (w(r, c)) { ab = false; break; } } if (ab) { return false; } }
    for (c = 0; c < C; c++) { var ab2 = true; for (r = 0; r < R; r++) { if (w(r, c)) { ab2 = false; break; } } if (ab2) { return false; } }
    var cov = {}, inA = {}, inD = {};
    for (i = 0; i < es.length; i++) {
      e = es[i];
      for (k = 0; k < e.len; k++) {
        var rr = e.dir === 'A' ? e.r : e.r + k, cc = e.dir === 'A' ? e.c + k : e.c, key = rr + ',' + cc;
        cov[key] = 1; if (e.dir === 'A') { inA[key] = 1; } else { inD[key] = 1; }
      }
    }
    for (r = 0; r < R; r++) { for (c = 0; c < C; c++) { if (w(r, c) && !cov[r + ',' + c]) { return false; } } }
    var cross = 0, kk; for (kk in inA) { if (inD[kk]) { cross++; } }
    if (cross < P.minX) { return false; }
    for (i = 0; i < es.length; i++) {
      e = es[i]; var hit = false;
      for (k = 0; k < e.len; k++) {
        var r2 = e.dir === 'A' ? e.r : e.r + k, c2 = e.dir === 'A' ? e.c + k : e.c;
        if ((e.dir === 'A' ? inD : inA)[r2 + ',' + c2]) { hit = true; break; }
      }
      if (!hit) { return false; }
    }
    // fully connected white region
    var whites = []; for (r = 0; r < R; r++) { for (c = 0; c < C; c++) { if (w(r, c)) { whites.push(r + ',' + c); } } }
    if (!whites.length) { return false; }
    var seen = {}; seen[whites[0]] = 1; var st = [whites[0]];
    var nb = [[1, 0], [-1, 0], [0, 1], [0, -1]];
    while (st.length) {
      var p = st.pop().split(','), pr = +p[0], pc = +p[1];
      for (var d = 0; d < 4; d++) {
        var nr = pr + nb[d][0], nc = pc + nb[d][1];
        if (w(nr, nc)) { var nk = nr + ',' + nc; if (!seen[nk]) { seen[nk] = 1; st.push(nk); } }
      }
    }
    var cnt = 0, sk2; for (sk2 in seen) { cnt++; }
    return cnt === whites.length;
  }

  // Short stable id from a grid (so distinct shapes get distinct ids → the
  // validator's per-band variety check measures REAL shape variety).
  function hashGrid(grid) {
    var s = grid.join('/'), h = 0;
    for (var i = 0; i < s.length; i++) { h = (h * 31 + s.charCodeAt(i)) | 0; }
    return (h >>> 0).toString(36);
  }
  function skFromGrid(grid, tier, prefix) {
    return { id: prefix + hashGrid(grid), tier: tier, grid: grid, rows: grid.length, cols: grid[0].length, entries: entriesFromGrid(grid) };
  }

  // One of the 8 dihedral transforms of a grid (square skeletons only — all the
  // fixed ones are). Validity is isomorphism-invariant, so a transform of a valid
  // skeleton is valid — used to vary the fallback.
  function transformGrid(grid, op) {
    var R = grid.length, C = grid[0].length, out = [], r, c, row;
    function g(r, c) { return grid[r].charAt(c); }
    switch (op) {
      case 1: for (r = 0; r < R; r++) { row = ''; for (c = C - 1; c >= 0; c--) { row += g(r, c); } out.push(row); } return out; // flip H
      case 2: for (r = R - 1; r >= 0; r--) { out.push(grid[r]); } return out;                                                   // flip V
      case 3: for (r = R - 1; r >= 0; r--) { row = ''; for (c = C - 1; c >= 0; c--) { row += g(r, c); } out.push(row); } return out; // rot180
      case 4: for (c = 0; c < C; c++) { row = ''; for (r = 0; r < R; r++) { row += g(r, c); } out.push(row); } return out;        // transpose
      case 5: for (c = C - 1; c >= 0; c--) { row = ''; for (r = 0; r < R; r++) { row += g(r, c); } out.push(row); } return out;   // rot90
      case 6: for (c = 0; c < C; c++) { row = ''; for (r = R - 1; r >= 0; r--) { row += g(r, c); } out.push(row); } return out;   // rot-90
      case 7: for (c = C - 1; c >= 0; c--) { row = ''; for (r = R - 1; r >= 0; r--) { row += g(r, c); } out.push(row); } return out; // anti-transpose
      default: return grid.slice();
    }
  }

  // A fresh RANDOM valid skeleton for the tier, or null if the budget is missed.
  function randomSkeleton(tier) {
    var P = SHAPE[tier] || SHAPE.meeting;
    for (var t = 0; t < 20000; t++) {
      var g = randMaskGrid(P.rows, P.cols, P.bf);
      if (maskValid(g, P)) { return skFromGrid(g, tier, 'r'); }
    }
    return null;
  }
  // Guaranteed-valid fallback: a random dihedral transform of a fixed skeleton.
  function fallbackSkeleton(tier) {
    var poolF = SKELETONS.filter(function (s) { return s.tier === tier; });
    if (!poolF.length) { poolF = SKELETONS.filter(function (s) { return s.tier === 'meeting'; }); }
    var base = pick(poolF);
    return skFromGrid(transformGrid(base.grid, Math.floor(Math.random() * 8)), tier, 'f');
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
    var maxV = Math.pow(10, ceilingDigits(year)) - 1;   // biggest in-curriculum number for the year

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
      // The minuend must also stay within the year's digit ceiling (Year 3's
      // column ± is "up to three digits" — so no 4-digit minuend on a 3-digit
      // answer). Cap the headroom accordingly.
      var headroom = maxV - value;
      if (headroom < 1) { return null; }
      var span = d <= 2 ? 30 : d <= 4 ? 200 : 900;
      span = Math.min(span, headroom);
      var extra = value + ri(1, span);
      return fmt(extra) + ' − ' + fmt(extra - value);
    }
    function mul() {
      // Written multiplication whose METHOD is on the year's curriculum (see
      // mulMethodOk). Only factor pairs the year could actually be asked to
      // compute are kept; if none exist the entry falls back to + / −.
      var pairs = [];
      for (var a = 2; a <= 99; a++) {
        if (value % a !== 0) { continue; }
        var b = value / a;
        if (b < 2) { continue; }
        var sm = Math.min(a, b), lg = Math.max(a, b);
        if (!mulMethodOk(sm, lg, year)) { continue; }
        pairs.push([lg, sm]);   // [bigger, smaller]
      }
      if (!pairs.length) { return null; }
      // Prefer a tables-style product (smaller factor ≤ 12) for readability.
      var nice = pairs.filter(function (p) { return p[1] <= 12; });
      var p = pick(nice.length ? nice : pairs);
      return fmt(p[0]) + ' × ' + fmt(p[1]);
    }
    function div() {
      if (value < 1) { return null; }
      // The DIVIDEND (value × divisor) is shown in the clue, so it too must stay
      // within the year's digit ceiling — no 4-digit number on a Year 3 sheet,
      // even inside a division. Pick a divisor that keeps it in range, else null
      // (the entry falls back to another op). Long division by a 2-digit divisor
      // is a Year 5/6 method, so only use it for exceeding sheets at Y5+.
      var b, i;
      if (tier === 'exceeding' && year >= 5) {
        for (i = 0; i < 12; i++) { b = ri(11, 25); if (value * b <= maxV) { return fmt(value * b) + ' ÷ ' + fmt(b); } }
        return null;
      }
      // Short / table division: a 1-digit divisor (or ÷10 place value) with the
      // DIVIDEND inside the year's method (Y3 2-digit ÷ 1-digit … ). A 2-digit
      // divisor is LONG division — that only comes from the exceeding/Y5+ branch
      // above, never here, whatever tables the year knows.
      var lim = divDividendMax(year);
      var opts = shuffle(tables).filter(function (dv) { return dv <= 10; });
      for (i = 0; i < opts.length; i++) { if (value * opts[i] <= lim) { return fmt(value * opts[i]) + ' ÷ ' + fmt(opts[i]); } }
      return null;
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
        if (whole > maxV) { continue; }            // keep the whole within the year ceiling
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
        if (whole > maxV) { continue; }            // keep the whole within the year ceiling
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

  // Can `value` be clued by at least one of the SELECTED ops? Used to re-roll the
  // grid so every entry can use a chosen op (e.g. × selected -> avoid primes), so
  // the clues honour the selection without a +/- fallback.
  function cluable(value, ops, d, year, tier) {
    var make = clueBuilders(value, d, year, tier);
    for (var i = 0; i < ops.length; i++) {
      var fn = make[ops[i]];
      if (fn && fn() != null) { return true; }
    }
    return false;
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
    // The op-chips are AUTHORITATIVE — honour exactly what the teacher selected
    // (every chip shown must work at every difficulty). The tier still scales
    // entry size and operand magnitude via `tier`/`d`, and the YEAR gates the
    // ×/÷ tables in clueBuilders, so clues stay curriculum-appropriate. Default
    // when nothing is passed = + − × ÷.
    var VALID = ['+', '-', '×', '÷', 'f', '%'];
    var ops = (opts.ops && opts.ops.length)
      ? opts.ops.slice().filter(function (o) { return VALID.indexOf(o) !== -1; })
      : ['+', '-', '×', '÷'];
    if (!ops.length) { ops = ['+', '-']; }

    // generate a FRESH random shape (varied every puzzle) whose grid size keeps
    // every entry within the YEAR's digit ceiling; if the search misses its
    // budget, fall back to a transformed fixed skeleton of the same size.
    var st = shapeTier(year, tier);
    var sk = randomSkeleton(st) || fallbackSkeleton(st);
    var lay = layout(sk);

    // 1) fill every white cell with a random digit. Entry-start cells must be
    //    1–9 (no leading zero); interior cells may be 0–9. RE-ROLL the whole fill
    //    until every entry's value can be clued by a SELECTED op (so e.g. with
    //    only × selected we avoid prime values and every clue is a multiplication
    //    — no +/- fallback). Intersections stay consistent because the whole grid
    //    is re-filled together. Keep the best (fewest un-cluable) if a perfect
    //    fill isn't found within the budget.
    var i, e;
    var startKeys = {};
    for (i = 0; i < lay.entries.length; i++) { startKeys[lay.entries[i].r + ',' + lay.entries[i].c] = true; }
    var wk = Object.keys(lay.white);

    function fillOnce() {
      var dg = {};
      for (var j = 0; j < wk.length; j++) { dg[wk[j]] = startKeys[wk[j]] ? ri(1, 9) : ri(0, 9); }
      return dg;
    }
    function valueOf(dg, ent) {
      var v = 0;
      for (var k = 0; k < ent.cells.length; k++) { v = v * 10 + dg[ent.cells[k][0] + ',' + ent.cells[k][1]]; }
      return v;
    }

    var digit = null, bestMiss = Infinity;
    for (var attempt = 0; attempt < 600; attempt++) {
      var dg = fillOnce(), miss = 0;
      for (i = 0; i < lay.entries.length; i++) {
        if (!cluable(valueOf(dg, lay.entries[i]), ops, d, year, tier)) { miss++; }
      }
      if (miss < bestMiss) { bestMiss = miss; digit = dg; }
      if (miss === 0) { break; }
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

    // Size the grid so it fills the page yet always fits one A4. The grids share
    // a column count but differ in ROWS (below 6, meeting 7, exceeding 8); the SVG
    // is width-constrained with height:auto, so we derive the max-WIDTH from a
    // target HEIGHT (width = H · cols/rows). That keeps every tier ~constant grid
    // height (~500px) instead of letting the 8-row grid run off the page.
    var targetH = 500;
    var gridMax = Math.max(330, Math.min(430, Math.round(targetH * p.cols / p.rows)));
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
