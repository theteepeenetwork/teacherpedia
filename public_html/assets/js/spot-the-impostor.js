/* =============================================================================
 * spot-the-impostor.js — Spot the Impostor.
 * -----------------------------------------------------------------------------
 * A grid of PRE-WORKED calculations. Some are deliberately WRONG using realistic
 * pupil misconceptions (forgotten carry, smaller-from-larger, off-by-one table
 * fact …). Pupils JUDGE each cell (✓/✗), correct the impostors, then add the
 * corrected board and self-check against an honest-total footer. The one resource
 * that asks pupils to EVALUATE, not compute.
 *
 * CORE MECHANIC — correct-FIRST-then-sabotage (per cell):
 *   1. generate operands valid for (year, op, effDifficulty);
 *   2. compute trueAnswer (EXACT integer arithmetic; decimals held as scaled
 *      integers so honestTotal/answers never drift);
 *   3. mark ~impostorCount cells as impostors;
 *   4. for each impostor pick a misconception VALID for (op, year) from the
 *      REGISTRY and apply its pure transform (operands,trueAnswer)=>wrongAnswer,
 *      recording {bugId,label}; displayedAnswer = wrongAnswer;
 *   5. GUARD RAIL: if displayedAnswer === trueAnswer (e.g. no-carry bug on a sum
 *      needing no carry) re-pick the bug; if none can produce a VISIBLE error
 *      re-roll the operands. An impostor identical to the truth is a silent bug
 *      and must never ship;
 *   6. honestTotal = Σ TRUE answers over ALL cells (never the displayed values).
 *
 * Years 1-6 (a JUDGING task across the whole range). yearCaps() gates the
 * operations / number ranges / methods; misconceptions self-gate by
 * appliesFromYear + operation so the teacher's (year, op) choice always yields a
 * valid bug set. Engine clamps year to 1..6 defensively.
 *
 * Determinism: a seedable PRNG (mulberry32) means a saved config re-prints
 * IDENTICALLY. Pure engine exposed as window.TP_SI for Node tests; DOM wiring
 * runs in-browser. Self-contained per the engine rules. See RESOURCE_WORKFLOW.md.
 * ========================================================================== */
(function () {
  'use strict';

  // ---- seedable PRNG (mulberry32) -----------------------------------------
  function mulberry32(seed) {
    var a = seed >>> 0;
    return function () {
      a |= 0; a = (a + 0x6D2B79F5) | 0;
      var t = Math.imul(a ^ (a >>> 15), 1 | a);
      t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
      return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
  }
  function makeRng(seed) { return mulberry32((seed >>> 0) || 1); }
  function ri(rng, a, b) { return a + Math.floor(rng() * (b - a + 1)); }
  function pick(rng, arr) { return arr[Math.floor(rng() * arr.length)]; }
  function shuffle(rng, arr) {
    var out = arr.slice();
    for (var i = out.length - 1; i > 0; i--) {
      var j = Math.floor(rng() * (i + 1)); var t = out[i]; out[i] = out[j]; out[j] = t;
    }
    return out;
  }
  function clampYear(y) { return Math.max(1, Math.min(6, y | 0)); }

  function digitsOf(n) { // least-significant first
    n = Math.abs(n); var d = []; if (n === 0) { return [0]; }
    while (n > 0) { d.push(n % 10); n = Math.floor(n / 10); }
    return d;
  }
  function gcd(a, b) { a = Math.abs(a); b = Math.abs(b); while (b) { var t = b; b = a % b; a = t; } return a || 1; }
  function lcm(a, b) { return Math.abs(a / gcd(a, b) * b); }

  /* =========================================================================
   * yearCaps(year) — the per-year curriculum gate. Drives BOTH the engine and
   * which toolbar op-chips are enabled.
   * ===================================================================== */
  function yearCaps(year) {
    year = clampYear(year);
    var T = (typeof window !== 'undefined' && window.TP_yearTables) ? window.TP_yearTables(year) : null;
    var tables = T || (year <= 2 ? [2, 5, 10] : year === 3 ? [2, 3, 4, 5, 8, 10] : [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
    switch (year) {
      case 1: return { ops: ['+', '-'], numMax: 20, tables: [], allowRemainder: false, allowDecimals: false, allowRounding: false, dpMax: 0, roundUnits: [] };
      case 2: return { ops: ['+', '-', '×', '÷'], numMax: 100, tables: [2, 5, 10], allowRemainder: false, allowDecimals: false, allowRounding: false, dpMax: 0, roundUnits: [] };
      case 3: return { ops: ['+', '-', '×', '÷', 'round'], numMax: 1000, tables: [2, 3, 4, 5, 8, 10], allowRemainder: false, allowDecimals: false, allowRounding: true, dpMax: 0, roundUnits: [10, 100] };
      case 4: return { ops: ['+', '-', '×', '÷', 'round', 'dec'], numMax: 9999, tables: tables, allowRemainder: true, allowDecimals: true, allowRounding: true, dpMax: 2, roundUnits: [10, 100, 1000] };
      case 5: return { ops: ['+', '-', '×', '÷', 'round', 'dec'], numMax: 99999, tables: tables, allowRemainder: true, allowDecimals: true, allowRounding: true, dpMax: 3, roundUnits: [10, 100, 1000, 10000] };
      default: return { ops: ['+', '-', '×', '÷', 'round', 'dec'], numMax: 999999, tables: tables, allowRemainder: true, allowDecimals: true, allowRounding: true, dpMax: 3, roundUnits: [10, 100, 1000, 10000, 100000] };
    }
  }

  // ---- exact decimal helpers (scaled-integer space) ------------------------
  function fmtScaled(value, scale) {
    if (scale === 1) { return fmtInt(value); }
    var neg = value < 0; var v = Math.abs(value);
    var dp = Math.round(Math.log(scale) / Math.log(10));
    var whole = Math.floor(v / scale);
    var frac = v % scale;
    var fs = String(frac); while (fs.length < dp) { fs = '0' + fs; }
    return (neg ? '-' : '') + fmtInt(whole) + '.' + fs;
  }
  function fmtInt(n) {
    var s = String(Math.abs(n)); var out = '';
    for (var i = 0; i < s.length; i++) { if (i > 0 && (s.length - i) % 3 === 0) { out += ','; } out += s[i]; }
    return (n < 0 ? '-' : '') + out;
  }

  // ---- column-arithmetic bug primitives (exact integers) -------------------
  function addNoCarry(a, b) { // no_carry(47,38)=75
    var da = digitsOf(a), db = digitsOf(b), len = Math.max(da.length, db.length), out = 0, mul = 1;
    for (var i = 0; i < len; i++) { out += (((da[i] || 0) + (db[i] || 0)) % 10) * mul; mul *= 10; }
    return out;
  }
  function subSmallerFromLarger(a, b) { // subtract_smaller_from_larger(52,27)=35
    var da = digitsOf(a), db = digitsOf(b), len = Math.max(da.length, db.length), out = 0, mul = 1;
    for (var i = 0; i < len; i++) { out += Math.abs((da[i] || 0) - (db[i] || 0)) * mul; mul *= 10; }
    return out;
  }
  function subNoExchange(a, b) { // floor each column to >=0 (no borrow)
    var da = digitsOf(a), db = digitsOf(b), len = da.length, out = 0, mul = 1;
    for (var i = 0; i < len; i++) { var d = (da[i] || 0) - (db[i] || 0); if (d < 0) { d = 0; } out += d * mul; mul *= 10; }
    return out;
  }

  /* =========================================================================
   * MISCONCEPTION REGISTRY — the heart.
   * Each entry { id, label, operations:[...], appliesFromYear,
   *   transform(operands, trueAnswer, scale, rng) -> wrongAnswer | null }.
   * Transforms are PURE and MODEL the named bug. Return null when inapplicable
   * to these operands; sabotage() then tries the next valid bug.
   * ===================================================================== */
  var REGISTRY = [
    // ---- addition ('+') ----
    { id: 'no_carry', label: 'Forgot to carry', operations: ['+'], appliesFromYear: 1,
      transform: function (o) { return addNoCarry(o[0], o[1]); } },
    { id: 'place_value_misalign', label: 'Misaligned the place value', operations: ['+'], appliesFromYear: 2,
      transform: function (o) {
        // ragged addition: the smaller addend is shifted one place left (lined up
        // under the wrong column), so it contributes 10× its value.
        var a = o[0], b = o[1], small = Math.min(a, b), big = Math.max(a, b);
        if (small < 10) { return null; }
        return big + small * 10; } },
    { id: 'off_by_ten', label: 'Lost ten in the carry', operations: ['+', '-'], appliesFromYear: 2,
      transform: function (o, t) { return t + 10; } },

    // ---- subtraction ('-') ----
    { id: 'subtract_smaller_from_larger', label: 'Took the smaller digit from the larger', operations: ['-'], appliesFromYear: 1,
      transform: function (o) { return subSmallerFromLarger(o[0], o[1]); } },
    { id: 'no_exchange', label: 'Subtracted without exchanging', operations: ['-'], appliesFromYear: 3,
      transform: function (o) { return subNoExchange(o[0], o[1]); } },
    { id: 'off_by_one', label: 'Borrow slip (off by one)', operations: ['-'], appliesFromYear: 1,
      transform: function (o, t) { return t >= 1 ? t - 1 : t + 1; } },

    // ---- multiplication ('×') ----
    { id: 'times_table_slip', label: 'Slipped a row in the times table', operations: ['×'], appliesFromYear: 2,
      transform: function (o, t, scale, rng) {
        // an ADJACENT fact: nudge ONE operand by ±1 (a real neighbouring product).
        // times_table_slip(7,8): 6×8=48, 8×8=64, 7×7=49, 7×9=63 — all "one row off".
        var a = o[0], b = o[1];
        var opts = [];
        if (a - 1 >= 1) { opts.push((a - 1) * b); }
        opts.push((a + 1) * b);
        if (b - 1 >= 1) { opts.push(a * (b - 1)); }
        opts.push(a * (b + 1));
        return rng ? pick(rng, opts) : opts[0]; } },
    { id: 'add_instead_of_multiply', label: 'Added instead of multiplying', operations: ['×'], appliesFromYear: 2,
      transform: function (o) { return o[0] + o[1]; } },
    { id: 'missing_placeholder_zero', label: 'Forgot the placeholder zero', operations: ['×'], appliesFromYear: 5,
      transform: function (o) {
        // long mult: the tens partial product loses its trailing zero (÷10 worth).
        var a = o[0], b = o[1]; if (b < 10) { return null; }
        var units = b % 10, tens = Math.floor(b / 10);
        return a * units + a * tens; } },
    { id: 'carry_dropped', label: 'Dropped a carry in the columns', operations: ['×'], appliesFromYear: 4,
      transform: function (o, t) { return t - 10; } },

    // ---- division ('÷') ----
    { id: 'remainder_dropped', label: 'Ignored the remainder', operations: ['÷'], appliesFromYear: 4,
      transform: function (o) {
        var q = Math.floor(o[0] / o[1]); if (q * o[1] === o[0]) { return null; } return q; } },
    { id: 'quotient_off_by_one', label: 'Quotient out by one', operations: ['÷'], appliesFromYear: 2,
      transform: function (o, t) { return t + 1; } },
    { id: 'inverse_confusion', label: 'Multiplied instead of dividing', operations: ['÷'], appliesFromYear: 2,
      transform: function (o) { return o[0] * o[1]; } },

    // ---- rounding ('round') — operands [n, unit]; scale 1 ----
    { id: 'round_wrong_place', label: 'Rounded to the wrong place', operations: ['round'], appliesFromYear: 3,
      transform: function (o) {
        var n = o[0], unit = o[1];
        var wrongUnit = unit >= 100 ? unit / 10 : unit * 10;
        return Math.round(n / wrongUnit) * wrongUnit; } },
    { id: 'round_half_down', label: 'Rounded a half down', operations: ['round'], appliesFromYear: 3,
      transform: function (o) {
        var n = o[0], unit = o[1];
        // floor when the remainder is exactly half, round-down the boundary
        return Math.floor((n + unit / 2 - 1) / unit) * unit; } },
    { id: 'truncate_not_round', label: 'Chopped the digits instead of rounding', operations: ['round'], appliesFromYear: 3,
      transform: function (o) { return Math.floor(o[0] / o[1]) * o[1]; } },
    { id: 'cascade_round', label: 'Failed the 9-carry cascade', operations: ['round'], appliesFromYear: 5,
      transform: function (o) {
        // 7,995 -> should be 8,000; cascade-fail rounds DOWN (no 9-carry up).
        var n = o[0], unit = o[1];
        return Math.floor(n / unit) * unit; } },

    // ---- decimals ('dec') — scaled-integer arithmetic ----
    { id: 'decimal_as_whole', label: 'Treated the decimal as a whole number', operations: ['dec'], appliesFromYear: 4,
      // The child adds the fractional columns as whole numbers and FAILS to carry the
      // resulting ten into the units (0.5 + 0.5 -> "0.10" read as 0.1; 2.7 + 0.5 ->
      // "2.12" with the units untouched). Modelled exactly: when the fractional parts
      // sum to >= one whole (a carry the child drops), the answer is the true total
      // minus that uncarried whole. Stays in the CELL's scale, genuinely != true.
      // Returns null when no fractional carry occurs (bug would be invisible).
      transform: function (o, t, scale) {
        var af = o[0].value % scale, bf = o[1].value % scale;
        if (af + bf < scale) { return null; }   // no carry to drop -> bug invisible here
        return t - scale; } },
    { id: 'point_misalign', label: 'Misplaced the decimal point', operations: ['dec'], appliesFromYear: 4,
      // Column add with the points misaligned: the child right-aligns the digits they
      // actually wrote (decimal point ignored, trailing zeros dropped as a child omits
      // them) and adds those, e.g. 2.5 + 0.75 -> writes 25 and 75, gets 100 -> "1.00".
      // The wrong total is reinterpreted at the CELL's scale, genuinely != true.
      // Returns null when the misaligned sum happens to coincide with the truth.
      transform: function (o, t, scale) {
        function strip(v) { var s = String(v).replace(/0+$/, ''); return s === '' ? 0 : parseInt(s, 10); }
        var w = strip(o[0].value) + strip(o[1].value);
        if (w === t) { return null; }
        return w; } },
    { id: 'place_value_tenths_hundredths', label: 'Confused tenths and hundredths', operations: ['dec'], appliesFromYear: 4,
      transform: function (o, t, scale) {
        if (scale < 100) { return null; }
        var tenths = Math.floor((t % scale) / (scale / 10));
        var huns = Math.floor((t % (scale / 10)) / (scale / 100));
        if (tenths === huns) { return null; }
        return t - tenths * (scale / 10) - huns * (scale / 100) + huns * (scale / 10) + tenths * (scale / 100); } }
  ];

  function bugsFor(op, year) {
    year = clampYear(year);
    var out = [];
    for (var i = 0; i < REGISTRY.length; i++) {
      var r = REGISTRY[i];
      if (r.operations.indexOf(op) !== -1 && r.appliesFromYear <= year) { out.push(r); }
    }
    return out;
  }

  /* =========================================================================
   * Operand generation per (op, year, eff) -> cell descriptor.
   *   { op, operands, trueAnswer, scale, display, working, remainder? }
   * trueAnswer is in scaled-integer units (scale = 10^dp; 1 for whole numbers).
   * ===================================================================== */
  function magnitudeFor(year, eff) {
    var caps = yearCaps(year);
    var t = (Math.max(1, Math.min(5, eff)) - 1) / 4;
    var lo = Math.max(10, Math.round(caps.numMax * 0.15));
    var hi = Math.round(lo + (caps.numMax - lo) * (0.4 + 0.6 * t));
    if (year === 1) { lo = 1; hi = Math.min(20, 9 + Math.round(11 * t)); }
    return { lo: lo, hi: Math.min(caps.numMax, hi) };
  }

  function genAdd(rng, year, eff) {
    var m = magnitudeFor(year, eff);
    var a, b;
    if (year === 1) { a = ri(rng, 1, 12); b = ri(rng, 1, 20 - a); }
    else { a = ri(rng, m.lo, m.hi); b = ri(rng, m.lo, m.hi); }
    var t = a + b;
    return { op: '+', operands: [a, b], trueAnswer: t, scale: 1, display: fmtInt(a) + ' + ' + fmtInt(b), working: a + ' + ' + b };
  }
  function genSub(rng, year, eff) {
    var m = magnitudeFor(year, eff);
    var a, b;
    if (year === 1) { a = ri(rng, 6, 20); b = ri(rng, 1, a - 1); }
    else { a = ri(rng, m.lo, m.hi); b = ri(rng, m.lo, m.hi); if (b > a) { var tmp = a; a = b; b = tmp; } }
    var t = a - b;
    return { op: '-', operands: [a, b], trueAnswer: t, scale: 1, display: fmtInt(a) + ' − ' + fmtInt(b), working: a + ' − ' + b };
  }
  function genMul(rng, year, eff) {
    var caps = yearCaps(year);
    var tables = caps.tables.length ? caps.tables : [2, 5, 10];
    var f = pick(rng, tables);
    var a, b;
    if (year >= 5 && eff >= 4) { a = f * ri(rng, 2, 9); b = ri(rng, 12, 39); } // long mult: 2-digit ×
    else { a = f; b = ri(rng, 2, 12); }
    if (b > a) { var tmp = a; a = b; b = tmp; }
    var t = a * b;
    // working: a partial-product / repeated-grouping hint (not an echo of a × b).
    var work;
    if (b >= 10) { var bu = b % 10, bt = Math.floor(b / 10); work = '(' + a + '×' + bt + '0) + (' + a + '×' + bu + ')'; }
    else { work = a + ' added ' + b + ' times'; }
    return { op: '×', operands: [a, b], trueAnswer: t, scale: 1, display: fmtInt(a) + ' × ' + fmtInt(b), working: work };
  }
  function genDiv(rng, year, eff) {
    var caps = yearCaps(year);
    var tables = (caps.tables.length ? caps.tables : [2, 5, 10]).filter(function (x) { return x > 1; });
    var divisor = pick(rng, tables) || 2;
    var q = ri(rng, 2, 12);
    if (year >= 6 && eff >= 4) { q = ri(rng, 12, 60); } // long division (larger quotient)
    // EXACT divisions only. A printed honest remainder ('10 r 2') cannot be added
    // into a single running total that lands on honestTotal, so the additive
    // self-check the footer advertises would be unfulfillable on paper. Keeping
    // every quotient exact makes Σ corrected answers === honest total coherent.
    var dividend = divisor * q;
    var t = dividend / divisor; // exact by construction
    return { op: '÷', operands: [dividend, divisor], trueAnswer: t, remainder: 0, scale: 1,
      display: fmtInt(dividend) + ' ÷ ' + fmtInt(divisor),
      working: 'how many ' + divisor + 's make ' + fmtInt(dividend) + '?' };
  }
  function genRound(rng, year, eff) {
    var caps = yearCaps(year);
    var unit = pick(rng, caps.roundUnits);
    var n, tries = 0;
    do { n = ri(rng, unit + 1, Math.min(caps.numMax, unit * 99)); tries++; }
    while (n % unit === 0 && tries < 20);
    var t = Math.round(n / unit) * unit;
    var unitName = fmtInt(unit);
    var low = Math.floor(n / unit) * unit, high = low + unit;
    return { op: 'round', operands: [n, unit], trueAnswer: t, scale: 1,
      display: 'Round ' + fmtInt(n) + ' to the nearest ' + unitName,
      working: 'between ' + fmtInt(low) + ' and ' + fmtInt(high) };
  }
  function genDec(rng, year, eff) {
    var caps = yearCaps(year);
    var dp = (year === 4) ? 2 : (eff >= 4 ? 3 : 2);
    if (dp > caps.dpMax) { dp = caps.dpMax; }
    if (dp < 1) { dp = 1; }
    var scale = Math.pow(10, dp);
    var av = ri(rng, scale, scale * 20 - 1);
    var bv = ri(rng, scale, scale * 20 - 1);
    var a = { value: av, scale: scale }, b = { value: bv, scale: scale };
    var t = av + bv;
    return { op: 'dec', operands: [a, b], trueAnswer: t, scale: scale,
      display: fmtScaled(av, scale) + ' + ' + fmtScaled(bv, scale),
      working: 'line up the decimal points' };
  }

  function generateCell(op, year, eff, rng) {
    year = clampYear(year);
    switch (op) {
      case '+': return genAdd(rng, year, eff);
      case '-': return genSub(rng, year, eff);
      case '×': return genMul(rng, year, eff);
      case '÷': return genDiv(rng, year, eff);
      case 'round': return genRound(rng, year, eff);
      case 'dec': return genDec(rng, year, eff);
      default: return genAdd(rng, year, eff);
    }
  }

  // solve() — recompute the true answer from operands ALONE (uniqueness contract).
  function solve(cell) {
    var o = cell.operands;
    switch (cell.op) {
      case '+': return o[0] + o[1];
      case '-': return o[0] - o[1];
      case '×': return o[0] * o[1];
      case '÷': return Math.floor(o[0] / o[1]);
      case 'round': return Math.round(o[0] / o[1]) * o[1];
      case 'dec': return o[0].value + o[1].value;
      default: return cell.trueAnswer;
    }
  }

  // sabotage() — pick a valid bug, apply with the guard rail. Returns
  // { displayedAnswer, bugId, label } or null if unsabotageable here.
  function sabotage(cell, year, rng) {
    var bugs = shuffle(rng, bugsFor(cell.op, year));
    for (var i = 0; i < bugs.length; i++) {
      var bug = bugs[i], w;
      // Every transform returns a scaled-integer wrong answer IN THE CELL'S OWN scale
      // (the same numeric space as trueAnswer and the space pupilTotal sums in), or
      // null when inapplicable to these operands.
      try { w = bug.transform(cell.operands, cell.trueAnswer, cell.scale, rng); }
      catch (e) { w = null; }
      if (w == null || isNaN(w)) { continue; }
      w = Math.round(w);
      if (w < 0) { continue; }                  // never a nonsense negative answer
      // GUARD RAIL (NUMERIC): the impostor must differ from the truth in the cell's
      // scaled-integer space — the SAME space pupilTotal sums in. A value-equal
      // "impostor" is silently defeated (a pupil who misses it still lands on the
      // honest total), so reject it and try the next bug. Applies to ALL ops/bugs.
      if (w === cell.trueAnswer) { continue; }
      return { displayedAnswer: w, bugId: bug.id, label: bug.label };
    }
    return null;
  }

  /* =========================================================================
   * build(config) -> { cells, honestTotal, honestScale, honestTotalText, config }
   * honestTotal = Σ trueAnswer over ALL cells (scaled-integer space, exact).
   * ===================================================================== */
  var PUPIL_NAMES = ['Priya', 'Sam', 'Aisha', 'Tom', 'Mei', 'Leo', 'Zara', 'Noah', 'Iris', 'Kai', 'Amara', 'Finn'];

  // assembleBoard(seed, …) -> { cells, honestTotal, honestScale } | builds ONE board
  // for a given seed. Pure; build() may call it with re-derived seeds (see below).
  function assembleBoard(seed, year, eff, ops, gridSize, impostorCount, pupilNames, showWorking) {
    var rng = makeRng(seed >>> 0 || 1);
    var idx = []; for (var k = 0; k < gridSize; k++) { idx.push(k); }
    var impostorIdx = {};
    var chosen = shuffle(rng, idx).slice(0, impostorCount);
    for (var c = 0; c < chosen.length; c++) { impostorIdx[chosen[c]] = true; }

    var cells = [];
    for (var n = 0; n < gridSize; n++) {
      var op = ops[n % ops.length];
      var cell = null, sab = null, attempts = 0;
      var wantImpostor = !!impostorIdx[n];
      do {
        cell = generateCell(op, year, eff, rng);
        if (wantImpostor) { sab = sabotage(cell, year, rng); }
        attempts++;
      } while (wantImpostor && !sab && attempts < 30);

      if (wantImpostor && !sab) { wantImpostor = false; } // demote: no visible bug found

      cell.isImpostor = wantImpostor;
      if (wantImpostor) {
        cell.displayedAnswer = sab.displayedAnswer; cell.bugId = sab.bugId; cell.label = sab.label;
      } else {
        cell.displayedAnswer = cell.trueAnswer; cell.bugId = null; cell.label = null;
      }
      if (pupilNames) { cell.name = pick(rng, PUPIL_NAMES); }
      cell.showWorking = !!showWorking;
      cells.push(cell);
    }

    // honestTotal — Σ trueAnswer over ALL cells, exact in a common scale.
    var commonScale = 1;
    for (var s = 0; s < cells.length; s++) { commonScale = lcm(commonScale, cells[s].scale); }
    var honestTotal = 0, displayedTotal = 0, impostorN = 0;
    for (var u = 0; u < cells.length; u++) {
      var f = commonScale / cells[u].scale;
      honestTotal += cells[u].trueAnswer * f;
      displayedTotal += cells[u].displayedAnswer * f; // "missed-all" pupil total
      if (cells[u].isImpostor) { impostorN++; }
    }
    return { cells: cells, honestTotal: honestTotal, honestScale: commonScale,
      displayedTotal: displayedTotal, impostorN: impostorN };
  }

  function build(config) {
    config = config || {};
    var year = clampYear(config.year || 1);
    var caps = yearCaps(year);
    var meter = config.difficulty || 3;
    var eff = (typeof window !== 'undefined' && window.TP_effDifficulty) ? window.TP_effDifficulty(year, meter) : Math.max(1, Math.min(5, meter));

    var ops = (config.operations && config.operations.length ? config.operations : caps.ops.slice())
      .filter(function (op) { return caps.ops.indexOf(op) !== -1; });
    if (!ops.length) { ops = [caps.ops[0]]; }

    var gridSize = Math.max(4, Math.min(12, config.gridSize || 9));
    var impostorCount = Math.max(1, Math.min(gridSize - 1, config.impostorCount || 3));

    // BOARD-LEVEL SELF-CHECK GUARD: each impostor is individually wrong, but a
    // COMBINATION of impostor errors can CANCEL (e.g. one +9 and one −9), so a pupil
    // who misses them all still lands on the honest total — the running-total check
    // is silently defeated. Re-roll with a deterministically re-derived seed until
    // the "missed-all" displayed total differs from the honest total (or there are
    // no impostors). Deterministic: same config -> same accepted board.
    var baseSeed = (config.seed >>> 0) || 1;
    var board = null;
    for (var attempt = 0; attempt < 40; attempt++) {
      var seed = attempt === 0 ? baseSeed : ((baseSeed + Math.imul(attempt, 0x9E3779B1)) >>> 0) || 1;
      board = assembleBoard(seed, year, eff, ops, gridSize, impostorCount, !!config.pupilNames, !!config.showWorking);
      // Accept when no impostors (nothing to defeat) or the displayed total is
      // genuinely off the honest total (missing impostors WILL break the running sum).
      if (board.impostorN === 0 || board.displayedTotal !== board.honestTotal) { break; }
    }

    var cells = board.cells;
    var commonScale = board.honestScale;
    var scaledSum = board.honestTotal;

    return {
      cells: cells,
      honestTotal: scaledSum,
      honestScale: commonScale,
      honestTotalText: fmtScaled(scaledSum, commonScale),
      config: { year: year, operations: ops, gridSize: gridSize, impostorCount: impostorCount,
        showWorking: !!config.showWorking, pupilNames: !!config.pupilNames,
        seed: (config.seed >>> 0) || 1, difficulty: meter }
    };
  }

  // ---- expose engine -------------------------------------------------------
  if (typeof window !== 'undefined') {
    window.TP_SI = {
      REGISTRY: REGISTRY, bugsFor: bugsFor, yearCaps: yearCaps,
      generateCell: generateCell, sabotage: sabotage, solve: solve, build: build,
      makeRng: makeRng, fmtScaled: fmtScaled, fmtInt: fmtInt
    };
  }

  /* ====== DOM (browser only) ============================================== */
  if (typeof document === 'undefined') { return; }

  function $(id) { return document.getElementById(id); }
  function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

  var state = {
    year: 1, difficulty: 3, ops: ['+', '-'], gridSize: 9, impostorCount: 3,
    showWorking: true, pupilNames: false, tab: 'worksheet',
    seed: (Math.floor(Math.random() * 0xffffffff) >>> 0),
    sheet: null, marks: {}
  };
  var els = {};
  var GRID_COLS = { 6: 2, 9: 3, 12: 3 };

  // ---- localStorage (live pupil state survives a refresh) ------------------
  function lsKey() { return 'tp_si_marks_' + (window.TP_SAVED && window.TP_SAVED.id ? window.TP_SAVED.id + '_' : '') + state.seed; }
  function saveMarks() { try { localStorage.setItem(lsKey(), JSON.stringify(state.marks)); } catch (e) {} }
  function loadMarks() { try { var raw = localStorage.getItem(lsKey()); state.marks = raw ? JSON.parse(raw) : {}; } catch (e) { state.marks = {}; } }
  function clearMarks() { try { localStorage.removeItem(lsKey()); } catch (e) {} state.marks = {}; }

  function rebuild(opts) {
    opts = opts || {};
    if (opts.newSeed) { state.seed = (Math.floor(Math.random() * 0xffffffff) >>> 0); clearMarks(); }
    state.sheet = build({
      year: state.year, operations: state.ops, gridSize: state.gridSize,
      impostorCount: state.impostorCount, showWorking: state.showWorking,
      pupilNames: state.pupilNames, seed: state.seed, difficulty: state.difficulty
    });
    if (!opts.newSeed) { loadMarks(); }
    render();
  }

  function answerText(cell, val) { return fmtScaled(val, cell.scale); }
  // Divisions are generated EXACT (genDiv), so answers never carry a printed
  // remainder — every displayed/true answer is a single addable number, keeping
  // the footer's additive self-check coherent on paper.
  // displayedAnswer always lives in the cell's own scale (the same numeric space as
  // trueAnswer and pupilTotal), so the wrong decimal the pupil would write renders
  // straight from fmtScaled at the cell scale.
  function dispAnswerText(cell) { return fmtScaled(cell.displayedAnswer, cell.scale); }
  function trueAnswerText(cell) { return answerText(cell, cell.trueAnswer); }

  function cardHTML(cell, i, revealed) {
    var mark = state.marks[i] || {};
    var nameEyebrow = cell.name ? '<div class="si-name">' + esc(cell.name) + '’s working</div>' : '';
    // The working line only adds value for the multi-step methods (×, ÷, round,
    // decimals); for +/- it would merely echo the calculation, so it is omitted
    // even when the toggle is on (keeps the simplest sheets uncluttered).
    var showW = cell.showWorking && (cell.op === '×' || cell.op === '÷' || cell.op === 'round' || cell.op === 'dec');
    var working = showW ? '<div class="si-working">' + esc(cell.working) + '</div>' : '';
    var calc = '<div class="si-calc"><span class="si-q">' + esc(cell.display) + '</span> '
      + '<span class="si-eq">=</span> <span class="si-ans">' + esc(dispAnswerText(cell)) + '</span></div>';

    if (revealed) {
      var verdict = cell.isImpostor
        ? '<div class="si-verdict si-imp"><span class="si-ic">✗</span> <span class="si-vtxt">Impostor</span></div>'
          + '<div class="si-bug">' + esc(cell.label) + '</div>'
          + '<div class="si-correct">Correct answer: <strong>' + esc(trueAnswerText(cell)) + '</strong></div>'
        : '<div class="si-verdict si-ok"><span class="si-ic">✓</span> <span class="si-vtxt">Correct</span></div>';
      return '<div class="si-card' + (cell.isImpostor ? ' si-card-imp' : ' si-card-ok') + '" role="group" aria-label="Cell ' + (i + 1) + '">'
        + '<div class="si-card-head"><span class="si-no">' + (i + 1) + '</span>' + nameEyebrow + '</div>'
        + calc + working + verdict + '</div>';
    }

    var who = cell.name ? esc(cell.name) + '’s' : 'this';
    var corr = (mark.v === 'x')
      ? '<div class="si-corr"><label class="si-corr-lbl" for="si-corr-' + i + '">Correct answer</label>'
        + '<input class="si-corr-in" id="si-corr-' + i + '" type="text" inputmode="numeric" '
        + 'value="' + (mark.c != null ? esc(mark.c) : '') + '" data-i="' + i + '" '
        + 'aria-label="Correct answer for cell ' + (i + 1) + '"></div>'
      : '';
    return '<div class="si-card" role="group" aria-label="Cell ' + (i + 1) + '">'
      + '<div class="si-card-head"><span class="si-no">' + (i + 1) + '</span>' + nameEyebrow + '</div>'
      + calc + working
      + '<div class="si-judge" role="group" aria-label="Is ' + who + ' answer correct?">'
      + '<button type="button" class="si-btn si-tick' + (mark.v === 'v' ? ' on' : '') + '" data-i="' + i + '" data-v="v" '
      + 'aria-pressed="' + (mark.v === 'v' ? 'true' : 'false') + '" aria-label="Mark ' + who + ' answer correct">✓</button>'
      + '<button type="button" class="si-btn si-cross' + (mark.v === 'x' ? ' on' : '') + '" data-i="' + i + '" data-v="x" '
      + 'aria-pressed="' + (mark.v === 'x' ? 'true' : 'false') + '" aria-label="Mark ' + who + ' answer an impostor">✗</button>'
      + '</div>' + corr
      + '</div>';
  }

  function parseCorrection(str, scale) {
    str = String(str).replace(/\s*r.*$/i, '').replace(/[^0-9.\-]/g, '');
    if (str === '' || str === '-' || str === '.') { return null; }
    var f = parseFloat(str); if (isNaN(f)) { return null; }
    return scale === 1 ? Math.round(f) : Math.round(f * scale);
  }
  function pupilTotal() {
    if (!state.sheet) { return null; }
    var cells = state.sheet.cells, commonScale = state.sheet.honestScale, sum = 0, allJudged = true;
    for (var i = 0; i < cells.length; i++) {
      var cell = cells[i], mark = state.marks[i] || {}, contribute;
      if (mark.v === 'x') {
        if (mark.c == null || mark.c === '') { allJudged = false; contribute = 0; }
        else { contribute = parseCorrection(mark.c, cell.scale); if (contribute == null) { allJudged = false; contribute = 0; } }
      } else if (mark.v === 'v') { contribute = cell.displayedAnswer; }
      else { allJudged = false; contribute = 0; }
      sum += contribute * (commonScale / cell.scale);
    }
    return { sum: sum, scale: commonScale, allJudged: allJudged };
  }

  function render() {
    if (!state.sheet) { return; }
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    if (els.eyebrowKs) { els.eyebrowKs.textContent = (state.year <= 2 ? 'KS1' : 'KS2') + ' · Year ' + state.year; }
    var revealed = state.tab === 'answerkey';
    var sheet = state.sheet;

    els.grid.style.setProperty('--si-cols', GRID_COLS[state.gridSize] || 3);
    els.grid.setAttribute('data-grid', state.gridSize);
    els.grid.setAttribute('data-rev', revealed ? '1' : '0');

    // Page-fill is handled entirely in CSS (grid-auto-rows:1fr + align-self:center
    // on 6/9-up; content-sized rows on 12-up). No per-board sparse heuristic needed.

    var html = '';
    for (var i = 0; i < sheet.cells.length; i++) { html += cardHTML(sheet.cells[i], i, revealed); }
    els.grid.innerHTML = html;

    if (els.honest) { els.honest.textContent = sheet.honestTotalText; }
    if (els.intro) {
      els.intro.textContent = revealed
        ? 'Answer key — each impostor is named with the mistake behind it.'
        : 'Some answers are wrong. Tick the right ones, cross the impostors and write the correction.';
    }
    if (els.footTask) {
      els.footTask.innerHTML = 'Fix the impostors, then add up all the correct answers — you should land on the honest total: <strong>' + esc(sheet.honestTotalText) + '</strong>.';
    }
    if (els.pupilWrap) { els.pupilWrap.style.display = revealed ? 'none' : ''; }

    bindCardEvents();
    updatePupilTotal();
  }

  function updatePupilTotal() {
    if (!els.pupilTotal) { return; }
    var pt = pupilTotal(); if (!pt) { return; }
    els.pupilTotal.textContent = fmtScaled(pt.sum, pt.scale);
    var match = pt.allJudged && (pt.sum === state.sheet.honestTotal);
    if (els.pupilWrap) {
      els.pupilWrap.classList.toggle('si-match', match);
      els.pupilWrap.classList.toggle('si-pending', !pt.allJudged);
    }
    if (els.pupilNote) {
      els.pupilNote.textContent = !pt.allJudged
        ? 'Judge every cell to compare.'
        : (match ? 'Matches the honest total — likely correct (a strong check, not proof).'
                 : 'Not a match yet — check your judgements and corrections.');
    }
  }

  function bindCardEvents() {
    Array.prototype.forEach.call(els.grid.querySelectorAll('.si-btn'), function (b) {
      b.addEventListener('click', function () {
        var i = b.getAttribute('data-i'), v = b.getAttribute('data-v');
        var mark = state.marks[i] || {};
        mark.v = (mark.v === v) ? null : v;
        state.marks[i] = mark; saveMarks(); render();
      });
    });
    Array.prototype.forEach.call(els.grid.querySelectorAll('.si-corr-in'), function (inp) {
      inp.addEventListener('input', function () {
        var i = inp.getAttribute('data-i');
        var mark = state.marks[i] || {}; mark.c = inp.value; state.marks[i] = mark;
        saveMarks(); updatePupilTotal();
      });
    });
  }

  // ---- toolbar wiring ------------------------------------------------------
  function setOnState(wrap, attr, val) {
    if (!wrap) { return; }
    Array.prototype.forEach.call(wrap.querySelectorAll('[' + attr + ']'), function (b) {
      b.classList.toggle('chip-on', b.getAttribute(attr) === String(val));
    });
  }
  function moveThumb(thumb, wrap, selector, index) {
    if (!thumb || !wrap) { return; }
    var active = wrap.querySelectorAll(selector)[index];
    if (active) { thumb.style.left = active.offsetLeft + 'px'; thumb.style.width = active.offsetWidth + 'px'; }
  }
  function setDiff(d) {
    state.difficulty = Math.max(1, Math.min(5, d));
    moveThumb(els.diffThumb, $('si-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }
  function setTab(tab) {
    state.tab = tab;
    moveThumb(els.tabThumb, $('si-tabs'), '[data-tab]', tab === 'answerkey' ? 1 : 0);
    render();
  }
  function regen() {
    if (els.spin) { els.spin.style.transform = 'rotate(360deg)'; setTimeout(function () { els.spin.style.transform = 'rotate(0deg)'; }, 500); }
    rebuild({ newSeed: true });
  }

  function syncOpChips() {
    var caps = yearCaps(state.year);
    var wrap = $('si-ops'); if (!wrap) { return; }
    Array.prototype.forEach.call(wrap.querySelectorAll('[data-op]'), function (b) {
      var op = b.getAttribute('data-op');
      var allowed = caps.ops.indexOf(op) !== -1;
      b.disabled = !allowed;
      b.style.display = allowed ? '' : 'none';
      if (!allowed) { var k = state.ops.indexOf(op); if (k !== -1) { state.ops.splice(k, 1); } }
    });
    state.ops = state.ops.filter(function (op) { return caps.ops.indexOf(op) !== -1; });
    if (!state.ops.length) { state.ops = [caps.ops[0]]; }
    Array.prototype.forEach.call(wrap.querySelectorAll('[data-op]'), function (b) {
      b.classList.toggle('chip-on', state.ops.indexOf(b.getAttribute('data-op')) !== -1);
    });
  }
  function syncImpostorChips() {
    var wrap = $('si-impostors'); if (!wrap) { return; }
    Array.prototype.forEach.call(wrap.querySelectorAll('[data-imp]'), function (b) {
      var v = Number(b.getAttribute('data-imp'));
      var allowed = v < state.gridSize;
      b.disabled = !allowed; b.style.opacity = allowed ? '' : '.35';
    });
    if (state.impostorCount >= state.gridSize) { state.impostorCount = state.gridSize - 1; }
    setOnState(wrap, 'data-imp', state.impostorCount);
  }

  function showToast(msg) {
    if (!els.toast) { return; }
    els.toast.textContent = msg; els.toast.classList.remove('hide');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function () { els.toast.classList.add('hide'); }, 1900);
  }
  function onSave() {
    var form = new FormData();
    form.append('title', 'Spot the Impostor');
    form.append('activity', 'spot-the-impostor');
    form.append('config', JSON.stringify({
      year: state.year, difficulty: state.difficulty, operations: state.ops, gridSize: state.gridSize,
      impostorCount: state.impostorCount, showWorking: state.showWorking,
      pupilNames: state.pupilNames, seed: state.seed
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

  function restoreFromSaved() {
    if (typeof window === 'undefined' || !window.TP_SAVED || !window.TP_SAVED.config) { return false; }
    var cfg = window.TP_SAVED.config;
    if (cfg.year) { state.year = clampYear(cfg.year); }
    if (cfg.difficulty) { state.difficulty = Math.max(1, Math.min(5, cfg.difficulty | 0)); }
    if (cfg.operations && cfg.operations.length) { state.ops = cfg.operations.slice(); }
    if (cfg.gridSize) { state.gridSize = Math.max(4, Math.min(12, cfg.gridSize | 0)); }
    if (cfg.impostorCount) { state.impostorCount = Math.max(1, cfg.impostorCount | 0); }
    if (cfg.showWorking != null) { state.showWorking = !!cfg.showWorking; }
    if (cfg.pupilNames != null) { state.pupilNames = !!cfg.pupilNames; }
    if (cfg.seed != null) { state.seed = cfg.seed >>> 0; }
    return true;
  }

  function defaultOpsFor(year) {
    var caps = yearCaps(year);
    if (year === 1) { return ['+', '-']; }
    return ['+', '-', '×', '÷'].filter(function (op) { return caps.ops.indexOf(op) !== -1; });
  }
  function onYearChange(y) {
    state.year = clampYear(y);
    var caps = yearCaps(state.year);
    state.ops = state.ops.filter(function (op) { return caps.ops.indexOf(op) !== -1; });
    if (!state.ops.length) { state.ops = defaultOpsFor(state.year); }
    syncOpChips();
    rebuild();
  }

  function init() {
    els.grid = $('si-grid');
    els.diffThumb = $('si-difficulty') ? $('si-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('si-diff-label');
    els.eyebrowDiff = $('si-eyebrow-diff');
    els.eyebrowKs = $('si-eyebrow-ks');
    els.tabThumb = $('si-tabs') ? $('si-tabs').querySelector('.seg-thumb') : null;
    els.intro = $('si-intro');
    els.spin = $('si-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('si-toast');
    els.honest = $('si-honest');
    els.footTask = $('si-foot-task');
    els.pupilTotal = $('si-pupil-total');
    els.pupilWrap = $('si-pupil-wrap');
    els.pupilNote = $('si-pupil-note');

    var yearEl = $('si-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var restored = restoreFromSaved();
    if (!restored) { state.ops = defaultOpsFor(state.year); }

    var y0 = window.TP_wireYears ? window.TP_wireYears('si', onYearChange) : null;
    if (y0 && !restored) { state.year = clampYear(y0); state.ops = defaultOpsFor(state.year); }
    if (restored) {
      var yWrap = $('si-years');
      if (yWrap) {
        Array.prototype.forEach.call(yWrap.querySelectorAll('[data-yr]'), function (b) {
          b.classList.toggle('chip-on', Number(b.getAttribute('data-yr')) === state.year);
        });
      }
    }

    var opsWrap = $('si-ops');
    if (opsWrap) {
      Array.prototype.forEach.call(opsWrap.querySelectorAll('[data-op]'), function (b) {
        b.addEventListener('click', function () {
          if (b.disabled) { return; }
          var op = b.getAttribute('data-op'), k = state.ops.indexOf(op);
          if (k !== -1) { if (state.ops.length > 1) { state.ops.splice(k, 1); } }
          else { state.ops.push(op); }
          b.classList.toggle('chip-on', state.ops.indexOf(op) !== -1);
          rebuild();
        });
      });
    }

    var gridWrap = $('si-grid-size');
    if (gridWrap) {
      Array.prototype.forEach.call(gridWrap.querySelectorAll('[data-grid]'), function (b) {
        b.addEventListener('click', function () {
          state.gridSize = Number(b.getAttribute('data-grid'));
          setOnState(gridWrap, 'data-grid', state.gridSize);
          syncImpostorChips(); rebuild();
        });
      });
      setOnState(gridWrap, 'data-grid', state.gridSize);
    }

    var impWrap = $('si-impostors');
    if (impWrap) {
      Array.prototype.forEach.call(impWrap.querySelectorAll('[data-imp]'), function (b) {
        b.addEventListener('click', function () {
          if (b.disabled) { return; }
          state.impostorCount = Number(b.getAttribute('data-imp'));
          setOnState(impWrap, 'data-imp', state.impostorCount);
          rebuild();
        });
      });
    }

    var workTog = $('si-working');
    if (workTog) {
      workTog.addEventListener('click', function () {
        state.showWorking = !state.showWorking;
        workTog.classList.toggle('chip-on', state.showWorking);
        workTog.setAttribute('aria-pressed', state.showWorking ? 'true' : 'false');
        rebuild();
      });
      workTog.classList.toggle('chip-on', state.showWorking);
      workTog.setAttribute('aria-pressed', state.showWorking ? 'true' : 'false');
    }
    var nameTog = $('si-names');
    if (nameTog) {
      nameTog.addEventListener('click', function () {
        state.pupilNames = !state.pupilNames;
        nameTog.classList.toggle('chip-on', state.pupilNames);
        nameTog.setAttribute('aria-pressed', state.pupilNames ? 'true' : 'false');
        rebuild();
      });
      nameTog.classList.toggle('chip-on', state.pupilNames);
      nameTog.setAttribute('aria-pressed', state.pupilNames ? 'true' : 'false');
    }

    Array.prototype.forEach.call($('si-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('si-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('si-save').addEventListener('click', onSave);
    $('si-print').addEventListener('click', function () { window.print(); });
    $('si-regen').addEventListener('click', regen);

    syncOpChips();
    syncImpostorChips();

    moveThumb(els.diffThumb, $('si-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }

    setTab('worksheet');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
