#!/usr/bin/env node
/* =============================================================================
 * spot-the-impostor-validate.js — THE correctness gate for the Spot the
 * Impostor engine. Runs window.TP_SI.build() across the FULL offered year range
 * (Y1..Y6), every difficulty and every operation, and asserts:
 *   (a) solve(cell) === trueAnswer  (single, uniquely-determined correct answer);
 *   (b) honestTotal === Σ trueAnswer  (exact, scaled-integer; no float drift);
 *   (c) GUARD RAIL: every impostor's displayedAnswer !== trueAnswer;
 *   (d) every offered (year, op) yields >=1 valid bug (no empty bug set);
 *   (e) every impostor's bug is valid for its op+year (self-gating holds);
 *   (f) no NaN/null/undefined anywhere;
 *   (g) curriculum-appropriateness PER YEAR: Y1 add/sub-within-20 only, no
 *       ×÷/decimals/rounding offered; KS1 no rounding/decimals; Y<=3 no decimals;
 *       Y2 ×/÷ on the 2/5/10 tables; the offered-op set matches yearCaps;
 *   (h) every offered CONTROL changes the output (no silent no-op).
 * Exits non-zero on the first failure.
 * ========================================================================== */
'use strict';

global.window = {};
require('../../public_html/assets/js/tp-tool.js');
require('../../public_html/assets/js/spot-the-impostor.js');
var SI = global.window.TP_SI;

if (!SI || typeof SI.build !== 'function') {
  console.error('FAIL: window.TP_SI.build not exposed');
  process.exit(1);
}

var fails = [];
function check(c, m) { if (!c) { fails.push(m); } }

// ---- named-bug spot checks (the registry must MODEL the bug) ---------------
function bug(id) { for (var i = 0; i < SI.REGISTRY.length; i++) { if (SI.REGISTRY[i].id === id) { return SI.REGISTRY[i]; } } return null; }
check(bug('no_carry').transform([47, 38], 85) === 75, 'no_carry(47,38) must be 75');
check(bug('subtract_smaller_from_larger').transform([52, 27], 25) === 35, 'subtract_smaller_from_larger(52,27) must be 35');
check(bug('add_instead_of_multiply').transform([6, 4], 24) === 10, 'add_instead_of_multiply(6,4) must be 10');
var ttsSeen = {};
for (var ts = 0; ts < 300; ts++) { ttsSeen[bug('times_table_slip').transform([7, 8], 56, 1, SI.makeRng(ts + 1))] = 1; }
check(Object.keys(ttsSeen).map(Number).every(function (v) { return [48, 64, 49, 63].indexOf(v) !== -1; }),
  'times_table_slip(7,8) must land on an adjacent fact {48,64,49,63}, saw ' + Object.keys(ttsSeen).join(','));

// ---- full-matrix stress ----------------------------------------------------
var YEAR_NUM_MAX = { 1: 20, 2: 100, 3: 1000, 4: 9999, 5: 99999, 6: 999999 };
var runs = 0;

for (var year = 1; year <= 6; year++) {
  var caps = SI.yearCaps(year);

  // (d) every offered op yields >=1 bug
  for (var oi = 0; oi < caps.ops.length; oi++) {
    check(SI.bugsFor(caps.ops[oi], year).length >= 1, 'no valid bug for op ' + caps.ops[oi] + ' at Y' + year);
  }
  // (g) Y1 must offer ONLY + and -
  if (year === 1) { check(caps.ops.length === 2 && caps.ops.indexOf('+') !== -1 && caps.ops.indexOf('-') !== -1, 'Y1 must offer only + and -'); }
  // KS1 (Y1-2) must NOT offer rounding or decimals
  if (year <= 2) { check(caps.ops.indexOf('round') === -1 && caps.ops.indexOf('dec') === -1, 'KS1 must not offer round/dec'); }
  // Y<=3 must NOT offer decimals
  if (year <= 3) { check(caps.ops.indexOf('dec') === -1, 'Y<=3 must not offer decimals'); }

  for (var meter = 1; meter <= 5; meter++) {
    // exercise the all-ops board AND each op alone
    var sets = [caps.ops.slice()];
    for (var k = 0; k < caps.ops.length; k++) { sets.push([caps.ops[k]]); }
    for (var si = 0; si < sets.length; si++) {
      for (var grid = 0; grid < 3; grid++) {
        var gridSize = [6, 9, 12][grid];
        var seed = (year * 7919 + meter * 131 + si * 17 + grid * 3 + 1) >>> 0;
        var sheet = SI.build({ year: year, operations: sets[si], gridSize: gridSize,
          impostorCount: [2, 3, 4][grid], showWorking: true, pupilNames: true, seed: seed, difficulty: meter });
        runs++;

        // (b) honest total exact
        var manual = 0, cs = sheet.honestScale;
        check(sheet.cells.length === gridSize, 'grid size mismatch Y' + year);
        for (var i = 0; i < sheet.cells.length; i++) {
          var c = sheet.cells[i];
          // (a) solve recomputes truth
          check(SI.solve(c) === c.trueAnswer, 'Y' + year + ' ' + c.op + ' solve() != trueAnswer (' + c.display + ')');
          // (f) no bad values
          check(c.displayedAnswer != null && !isNaN(c.displayedAnswer), 'bad displayedAnswer: ' + c.display);
          check(c.trueAnswer != null && !isNaN(c.trueAnswer), 'bad trueAnswer: ' + c.display);
          check(typeof c.display === 'string' && c.display.length > 0, 'empty display');
          // op on-curriculum for the year
          check(caps.ops.indexOf(c.op) !== -1, 'Y' + year + ' produced off-curriculum op ' + c.op);
          // (c)+(e) impostor guard rail + bug self-gating. The guard is NUMERIC: the
          // impostor's displayedAnswer must differ from trueAnswer in the cell's
          // scaled-integer space — the SAME space pupilTotal sums in. A value-equal
          // impostor is silently defeated (a pupil who misses it still lands on the
          // honest total), so it must never ship. Both live in cell.scale now.
          if (c.isImpostor) {
            check(c.displayedAnswer !== c.trueAnswer,
              'GUARD RAIL broken (impostor numerically equals truth -> silent): ' + c.display + ' Y' + year + ' bug=' + c.bugId);
            // belt-and-braces: the printed forms must also differ (they follow from
            // the numeric difference now that both render at the cell scale).
            check(SI.fmtScaled(c.displayedAnswer, c.scale) !== SI.fmtScaled(c.trueAnswer, c.scale),
              'GUARD RAIL broken (impostor prints as truth): ' + c.display + ' Y' + year);
            check(!!c.label && !!c.bugId, 'impostor missing label/bugId: ' + c.display);
            check(SI.bugsFor(c.op, year).some(function (b) { return b.id === c.bugId; }), 'impostor bug ' + c.bugId + ' invalid for ' + c.op + ' Y' + year);
            check(c.displayedAnswer >= 0, 'negative displayed impostor answer: ' + c.display);
          } else {
            check(c.displayedAnswer === c.trueAnswer, 'honest cell display != truth: ' + c.display);
          }
          // SELF-CHECK COHERENCE: a printed answer must carry NO information that is
          // excluded from honestTotal. The footer tells pupils to add the corrected
          // answers and land on the honest total; a printed division remainder
          // ('10 r 2') would be addable text excluded from the floored-quotient sum,
          // making the additive self-check unfulfillable on paper. Divisions must be
          // EXACT (remainder 0) so every printed answer is one addable number.
          if (c.op === '÷') {
            check(c.operands[0] % c.operands[1] === 0, 'honest div not exact (printed remainder breaks additive self-check): ' + c.display);
            check(!c.remainder, 'division cell carries a remainder excluded from honestTotal: ' + c.display);
          }
          // curriculum magnitude / method ceilings
          if (c.op === '+' || c.op === '-') {
            check(c.operands[0] <= YEAR_NUM_MAX[year] && c.operands[1] <= YEAR_NUM_MAX[year], 'Y' + year + ' +/- operand over ceiling: ' + c.display);
          }
          if (year === 1) {
            check(c.op === '+' || c.op === '-', 'Y1 non +/- op: ' + c.op);
            check(c.operands[0] <= 20 && c.operands[1] <= 20, 'Y1 operand > 20: ' + c.display);
            if (c.op === '+') { check(c.trueAnswer <= 20, 'Y1 sum > 20: ' + c.display); }
          }
          if (year === 2 && (c.op === '×' || c.op === '÷')) {
            var hasTable = [2, 5, 10].some(function (t) { return c.operands.indexOf(t) !== -1; });
            check(hasTable, 'Y2 ×/÷ not on 2/5/10 tables: ' + c.display);
          }
          manual += c.trueAnswer * (cs / c.scale);
        }
        check(manual === sheet.honestTotal, 'Y' + year + ' honestTotal ' + sheet.honestTotal + ' != Σtrue ' + manual);
        check(/[0-9]/.test(sheet.honestTotalText), 'honestTotalText has no digits');

        // ---- SIMULATED PUPIL SELF-CHECK (the headline mechanic) --------------
        // The footer promises: correct the impostors, add the corrected board, and
        // land on the honest total. Two pupils, exact scaled-integer arithmetic
        // (LCM of scales == honestScale):
        //  1. "missed-all": ticks EVERY cell -> sums displayedAnswer. If any cell is
        //     an impostor this MUST NOT equal honestTotal (else the running-total
        //     check is silently defeated — the exact decimal defect we just fixed).
        //  2. "corrected-all": honest cells true, impostors corrected to true -> this
        //     is Σ trueAnswer and MUST equal honestTotal exactly.
        var impostorN = 0, missedAll = 0, correctedAll = 0;
        for (var pj = 0; pj < sheet.cells.length; pj++) {
          var pc = sheet.cells[pj], f = cs / pc.scale;
          if (pc.isImpostor) { impostorN++; }
          missedAll += pc.displayedAnswer * f;        // tick everything: take it as printed
          correctedAll += pc.trueAnswer * f;          // every cell judged & corrected to truth
        }
        check(correctedAll === sheet.honestTotal,
          'Y' + year + ' corrected-all total ' + correctedAll + ' != honestTotal ' + sheet.honestTotal);
        if (impostorN >= 1) {
          check(missedAll !== sheet.honestTotal,
            'SILENT SELF-CHECK: Y' + year + ' a pupil who ticks every cell (misses all ' + impostorN +
            ' impostor(s)) still lands on the honest total ' + sheet.honestTotal + ' (' + sets[si].join('') + ' g' + gridSize + ' seed ' + seed + ')');
        }
      }
    }
  }
}

// ---- (h) every CONTROL changes output --------------------------------------
function sig(cfg) { return SI.build(cfg).cells.map(function (c) { return c.display + '=' + c.displayedAnswer; }).join('|'); }
var base = { year: 4, operations: ['+', '-', '×', '÷'], gridSize: 9, impostorCount: 3, showWorking: true, pupilNames: false, seed: 4242, difficulty: 3 };
check(sig(base) !== sig(Object.assign({}, base, { year: 5 })), 'year control had no effect');
check(sig(base) !== sig(Object.assign({}, base, { difficulty: 5 })), 'difficulty control had no effect');
check(sig(base) !== sig(Object.assign({}, base, { operations: ['+'] })), 'operations control had no effect');
check(sig(base) !== sig(Object.assign({}, base, { gridSize: 12 })), 'grid control had no effect');
check(sig(base) !== sig(Object.assign({}, base, { impostorCount: 4, seed: 4242 })) || true, 'impostorCount checked below');
// impostor count: count impostors in the board
function impCount(cfg) { var s = SI.build(cfg); var n = 0; s.cells.forEach(function (c) { if (c.isImpostor) { n++; } }); return n; }
check(impCount(Object.assign({}, base, { impostorCount: 2 })) === 2, 'impostorCount=2 did not yield 2 impostors');
check(impCount(Object.assign({}, base, { impostorCount: 4 })) === 4, 'impostorCount=4 did not yield 4 impostors');
// show-working toggle changes whether cells carry a working line (rendered) — the
// engine flags it; assert the flag flips.
var withW = SI.build(Object.assign({}, base, { showWorking: true }));
var noW = SI.build(Object.assign({}, base, { showWorking: false }));
check(withW.cells[0].showWorking === true && noW.cells[0].showWorking === false, 'showWorking flag did not flip');
// pupil-names toggle attaches names
var withN = SI.build(Object.assign({}, base, { pupilNames: true }));
var noN = SI.build(Object.assign({}, base, { pupilNames: false }));
check(!!withN.cells[0].name && !noN.cells[0].name, 'pupilNames toggle did not attach/remove names');

// ---- determinism -----------------------------------------------------------
check(sig(base) === sig(base), 'same seed not deterministic');

console.log('spot-the-impostor: ' + runs + ' boards validated across Y1-Y6 × diff × ops × grid.');
if (fails.length) {
  console.error('\nFAIL (' + fails.length + '):');
  fails.slice(0, 40).forEach(function (m) { console.error('  - ' + m); });
  process.exit(1);
}
console.log('PASS: all curriculum, correctness, guard-rail and control checks held.');
