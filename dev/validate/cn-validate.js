#!/usr/bin/env node
/* =============================================================================
 * cn-validate.js — THE correctness gate for the Cross-Number Crossword engine.
 * -----------------------------------------------------------------------------
 * Runs window.TP_CN.generate() over hundreds of puzzles across every band/year/op
 * combo and asserts:
 *   (a) every clue evaluates to its entry's number;
 *   (b) every entry value re-derived from the grid matches its clue value
 *       (so intersection digits agree by construction);
 *   (c) no multi-digit entry starts with 0;
 *   (d) clues are year-appropriate (×/÷ factors on-curriculum or 2-digit long
 *       for exceeding, ÷ exact, fraction/% integer wholes, − never negative);
 *   (e) variety across runs (multiple skeletons + distinct puzzle signatures).
 * Exits non-zero on the first failure.
 * ========================================================================== */
'use strict';

// Load the engine in a faux-browser so window.TP_CN is exposed, with the
// tp-tool.js curriculum helpers available (the engine reads window.TP_*).
global.window = {};
require('../../public_html/assets/js/tp-tool.js');
require('../../public_html/assets/js/cross-number.js');
var TP_CN = global.window.TP_CN;

if (!TP_CN || typeof TP_CN.generate !== 'function') {
  console.error('FAIL: window.TP_CN.generate not exposed');
  process.exit(1);
}

var fails = [];
function check(cond, msg) { if (!cond) { fails.push(msg); } }

// ---- parse a clue string and compute its value -----------------------------
// Supports:  a + b   a − b   a × b   a ÷ b   FRAC of W   P% of W
var FRACS = { '½': [1, 2], '¼': [1, 4], '¾': [3, 4], '⅓': [1, 3], '⅔': [2, 3], '⅕': [1, 5] };
function num(s) { return parseInt(String(s).replace(/[\s,]/g, ''), 10); }

function evalClue(clue) {
  var m;
  if ((m = clue.match(/^(.+?) \+ (.+)$/)))  return num(m[1]) + num(m[2]);
  if ((m = clue.match(/^(.+?) − (.+)$/)))   return num(m[1]) - num(m[2]);
  if ((m = clue.match(/^(.+?) × (.+)$/)))   return num(m[1]) * num(m[2]);
  if ((m = clue.match(/^(.+?) ÷ (.+)$/)))   return num(m[1]) / num(m[2]);
  if ((m = clue.match(/^([½¼¾⅓⅔⅕]) of (.+)$/))) { var f = FRACS[m[1]]; return num(m[2]) * f[0] / f[1]; }
  if ((m = clue.match(/^(\d+)% of (.+)$/)))  return num(m[2]) * num(m[1]) / 100;
  return NaN;
}

// classify a clue for the year-appropriateness checks
function clueOp(clue) {
  if (/ \+ /.test(clue)) return '+';
  if (/ − /.test(clue)) return '-';
  if (/ × /.test(clue)) return '×';
  if (/ ÷ /.test(clue)) return '÷';
  if (/of /.test(clue) && /%/.test(clue)) return '%';
  if (/of /.test(clue)) return 'f';
  return '?';
}

function valueFromGrid(ans, e) {
  var v = 0;
  for (var k = 0; k < e.cells.length; k++) {
    var d = ans[e.cells[k][0]][e.cells[k][1]];
    if (d == null) return NaN;
    v = v * 10 + Number(d);
  }
  return v;
}

var YEARS = [3, 4, 5, 6];
var BANDS = ['below', 'meeting', 'exceeding'];
var OPSETS = [
  ['+', '-'],
  ['×', '÷'],
  ['+', '-', '×', '÷'],
  ['×'],
  ['÷'],
  ['+', '-', '×', '÷', 'f', '%']
];

var RUNS = 600;
var skelHits = {};
var sigSet = {};
var opFormHits = {};

function yearTables(year) {
  if (year <= 2) return [2, 5, 10];
  if (year === 3) return [2, 3, 4, 5, 8, 10];
  return [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
}
// Max digits in any entry for the year (Year 3 column ± is "up to 3 digits").
function ceilingDigits(year) { return year <= 3 ? 3 : 4; }
// Curriculum METHOD limits for × and ÷ (mirror of the engine's helpers).
function mulMethodOk(sm, lg, year) {
  if (sm < 2 || lg < 2) return false;
  if (year <= 3) return sm <= 9 && lg <= 99;
  if (year === 4) return sm <= 9 && lg <= 999;
  if (year === 5) return (sm <= 9 && lg <= 9999) || (sm <= 99 && lg <= 99);
  return sm <= 99 && lg <= 9999;
}
function divDividendMax(year) { return year <= 3 ? 99 : year === 4 ? 999 : 9999; }

for (var run = 0; run < RUNS; run++) {
  var year = YEARS[run % YEARS.length];
  var band = BANDS[run % BANDS.length];
  var ops = OPSETS[run % OPSETS.length];
  var tables = yearTables(year);

  var p;
  try {
    p = TP_CN.generate({ year: year, band: band, ops: ops });
  } catch (err) {
    fails.push('run ' + run + ': generate threw ' + err.message);
    break;
  }

  if (!p || !p.entries || !p.entries.length) { fails.push('run ' + run + ': empty puzzle'); continue; }
  // Hard requirement: every puzzle has exactly 12 clues, in a BALANCED split
  // (each direction 5–7) so neither clue column overflows the printed page.
  check(p.entries.length === 12, 'run ' + run + ': ' + p.entries.length + ' entries, want exactly 12');
  var nA = p.entries.filter(function (x) { return x.dir === 'A'; }).length;
  var nD = p.entries.length - nA;
  check(nA >= 5 && nD >= 5, 'run ' + run + ': unbalanced split ' + nA + 'A/' + nD + 'D (each direction must be 5–7)');
  skelHits[band] = skelHits[band] || {};
  skelHits[band][p.id] = (skelHits[band][p.id] || 0) + 1;

  // variety signature: skeleton id + all clue strings
  var sigParts = [p.id];

  for (var i = 0; i < p.entries.length; i++) {
    var e = p.entries[i];

    // value/clue sanity
    check(e.value != null && !isNaN(e.value), 'run ' + run + ' entry ' + e.n + e.dir + ': bad value ' + e.value);
    check(typeof e.clue === 'string' && e.clue.length > 0, 'run ' + run + ' entry ' + e.n + e.dir + ': empty clue');

    // (a) clue evaluates to the entry value
    var cv = evalClue(e.clue);
    check(!isNaN(cv), 'run ' + run + ' entry ' + e.n + e.dir + ': unparseable clue "' + e.clue + '"');
    check(cv === e.value, 'run ' + run + ' entry ' + e.n + e.dir + ': clue "' + e.clue + '" = ' + cv + ' != value ' + e.value);

    // (b) value re-derived from the answer grid matches clue value (intersection agreement)
    var gv = valueFromGrid(p.ans, e);
    check(gv === e.value, 'run ' + run + ' entry ' + e.n + e.dir + ': grid value ' + gv + ' != ' + e.value);

    // (c) no leading zero on a multi-digit entry
    var firstDigit = p.ans[e.cells[0][0]][e.cells[0][1]];
    check(e.len < 2 || String(firstDigit) !== '0', 'run ' + run + ' entry ' + e.n + e.dir + ': leading zero');
    // entry length must produce a number with exactly len digits
    check(String(e.value).length === e.len, 'run ' + run + ' entry ' + e.n + e.dir + ': value ' + e.value + ' not ' + e.len + ' digits');
    // entry must not exceed the year's curriculum digit ceiling (Y3 ≤ 3 digits)
    check(e.len <= ceilingDigits(year), 'run ' + run + ' entry ' + e.n + e.dir + ': ' + e.len + '-digit entry exceeds year ' + year + ' ceiling of ' + ceilingDigits(year));
    // NO number anywhere in the clue (operands, dividends, wholes) may exceed the
    // year ceiling either — a Year 3 sheet must show no 4-digit number at all.
    var opDigits = e.clue.replace(/[^0-9,]/g, ' ').split(/\s+/).filter(Boolean).map(function (s) { return s.replace(/,/g, '').length; });
    var maxOpDigits = opDigits.length ? Math.max.apply(null, opDigits) : 0;
    check(maxOpDigits <= ceilingDigits(year), 'run ' + run + ' entry ' + e.n + e.dir + ': clue "' + e.clue + '" has a ' + maxOpDigits + '-digit number > year ' + year + ' ceiling');

    // (d) year-appropriateness
    var op = clueOp(e.clue);
    opFormHits[op] = (opFormHits[op] || 0) + 1;
    var m;
    if (op === '-') {
      m = e.clue.match(/^(.+?) − (.+)$/);
      check(num(m[1]) >= num(m[2]), 'run ' + run + ' entry ' + e.n + e.dir + ': subtraction goes negative "' + e.clue + '"');
    } else if (op === '×') {
      // The × METHOD must be on the year's curriculum (Y3 2-digit×1-digit … Y6
      // long multiplication) — see mulMethodOk. Both operands ≥ 2.
      m = e.clue.match(/^(.+?) × (.+)$/);
      var a = num(m[1]), b = num(m[2]);
      var smX = Math.min(a, b), lgX = Math.max(a, b);
      check(mulMethodOk(smX, lgX, year), 'run ' + run + ' entry ' + e.n + e.dir + ': × method off-curriculum for year ' + year + ' "' + e.clue + '"');
    } else if (op === '÷') {
      m = e.clue.match(/^(.+?) ÷ (.+)$/);
      var dd = num(m[1]), dv = num(m[2]);
      check(dd % dv === 0, 'run ' + run + ' entry ' + e.n + e.dir + ': non-exact ÷ "' + e.clue + '"');
      // Any 2-digit divisor is LONG division — a Year 5+ method. A 1-digit
      // divisor (or ÷10) is short/table division: the divisor must be a year
      // table fact and the dividend within the year's written method.
      if (dv >= 11) {
        check(year >= 5, 'run ' + run + ' entry ' + e.n + e.dir + ': long division below year 5 "' + e.clue + '" (year ' + year + ')');
        check(dv <= 25 && dd <= 9999, 'run ' + run + ' entry ' + e.n + e.dir + ': long-division operands out of range "' + e.clue + '"');
      } else {
        check(tables.indexOf(dv) !== -1, 'run ' + run + ' entry ' + e.n + e.dir + ': off-curriculum ÷ divisor "' + e.clue + '" (year ' + year + ')');
        check(dd <= divDividendMax(year), 'run ' + run + ' entry ' + e.n + e.dir + ': ÷ dividend ' + dd + ' exceeds year ' + year + ' method max ' + divDividendMax(year) + ' "' + e.clue + '"');
      }
    } else if (op === 'f' || op === '%') {
      // whole-number result already checked via cv === e.value (integer compare)
      check(Number.isInteger(cv), 'run ' + run + ' entry ' + e.n + e.dir + ': fraction/% non-integer "' + e.clue + '"');
    }

    sigParts.push(e.clue);
  }

  // solve() must reproduce the answer grid
  var solved = TP_CN.solve(p);
  check(solved === p.ans, 'run ' + run + ': solve() did not return the answer grid');

  sigSet[sigParts.join('|')] = true;
}

// (e) variety
var distinctSigs = Object.keys(sigSet).length;
check(distinctSigs > RUNS * 0.9, 'low variety: only ' + distinctSigs + ' distinct puzzle signatures of ' + RUNS);
// Shapes are RANDOMISED per puzzle (fresh valid mask each generate, fixed
// skeletons only as a rare fallback), so each band should show many distinct
// grids over ~200 runs. A regression to fixed-only shapes would collapse this.
BANDS.forEach(function (b) {
  var hit = Object.keys(skelHits[b] || {}).length;
  check(hit >= 25, 'band ' + b + ' only hit ' + hit + ' distinct shape(s) (want >=25 — shapes must be randomised)');
});

// ---- report ----------------------------------------------------------------
console.log('runs: ' + RUNS);
console.log('distinct puzzle signatures: ' + distinctSigs);
console.log('skeletons hit per band: ' + JSON.stringify(Object.keys(skelHits).reduce(function (o, b) { o[b] = Object.keys(skelHits[b]).length; return o; }, {})));
console.log('clue op-form distribution: ' + JSON.stringify(opFormHits));

if (fails.length) {
  console.error('\nFAILED (' + fails.length + ' issues). First 20:');
  fails.slice(0, 20).forEach(function (f) { console.error('  - ' + f); });
  process.exit(1);
}
console.log('\nPASS: all ' + RUNS + ' puzzles valid (clues correct, intersections agree, no leading zeros, year-appropriate, varied).');
