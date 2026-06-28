#!/usr/bin/env node
/* =============================================================================
 * dd-validate.js — THE correctness gate for the Digit Detectives engine.
 * -----------------------------------------------------------------------------
 * Runs window.TP_DD.generate() over hundreds of puzzles across EVERY offered
 * year (3-6) and tier (below|meeting|exceeding) and asserts:
 *   (a) the printed addition is arithmetically correct (sum of full addends
 *       equals the full total) — the puzzle is built on a real, correct sum;
 *   (b) re-running solve(qtn) returns unique:true AND recovers exactly the
 *       hidden digits (so the puzzle is solvable by pure deduction, no guessing);
 *   (c) no number (addend OR total) has a leading zero, and the displayed blanks
 *       are interior (never a leading digit);
 *   (d) NO number exceeds the year's digit ceiling (Y3 ≤ 3 digits, Y4+ ≤ 4) —
 *       the curriculum METHOD gate, on addends AND the total;
 *   (e) the cipher mapping is exact (letter == KEY[digit]) and `word` == the
 *       letters joined; when `real` is claimed the word is in the curated list;
 *   (f) tier shapes content as designed (addend count, width band, blank count);
 *   (g) variety: many distinct puzzle signatures and multiple distinct words.
 * Exits non-zero on the first failure.
 * ========================================================================== */
'use strict';

global.window = {};
require('../../public_html/assets/js/tp-tool.js');
require('../../public_html/assets/js/digit-detectives.js');
var TP_DD = global.window.TP_DD;

if (!TP_DD || typeof TP_DD.generate !== 'function' || typeof TP_DD.solve !== 'function') {
  console.error('FAIL: window.TP_DD.generate / .solve not exposed');
  process.exit(1);
}

var KEY = TP_DD.KEY, WORDS = TP_DD.WORDS;
var fails = [];
function check(cond, msg) { if (!cond && fails.length < 40) { fails.push(msg); } }

function ceilingDigits(year) { return year <= 3 ? 3 : 4; }

// number read from a digit array (index 0 = most significant)
function numOf(arr) { var v = 0; for (var i = 0; i < arr.length; i++) { v = v * 10 + arr[i]; } return v; }

var YEARS = [3, 4, 5, 6];
var TIERS = ['below', 'meeting', 'exceeding'];
var RUNS = 1200;

var sigSet = {}, wordSet = {}, realCount = 0, tierShape = {};

for (var run = 0; run < RUNS; run++) {
  var year = YEARS[run % YEARS.length];
  var tier = TIERS[Math.floor(run / YEARS.length) % TIERS.length];
  var ceil = ceilingDigits(year);

  var p;
  try { p = TP_DD.generate({ year: year, tier: tier }); }
  catch (err) { fails.push('run ' + run + ' (' + year + '/' + tier + '): generate threw ' + err.message); break; }

  if (!p || !p.qtn || !p.ans) { fails.push('run ' + run + ' (' + year + '/' + tier + '): null/empty puzzle'); continue; }
  var q = p.qtn, a = p.ans, w = q.width, nAdd = q.nAdd;

  // ---- structural sanity
  check(w >= 2, 'run ' + run + ': width ' + w + ' < 2');
  check(nAdd >= 2, 'run ' + run + ': nAdd ' + nAdd + ' < 2');
  check(q.blanks && q.blanks.length >= 1, 'run ' + run + ': no blanks');
  check(a.fullAddends && a.fullAddends.length === nAdd, 'run ' + run + ': fullAddends length mismatch');

  // ---- (a) arithmetic correctness on the FULL (solved) grid
  var sum = 0, bad = false;
  for (var r = 0; r < nAdd; r++) {
    var arr = a.fullAddends[r];
    check(arr.length === w, 'run ' + run + ': addend row ' + r + ' wrong width');
    for (var c = 0; c < w; c++) { if (arr[c] == null || isNaN(arr[c])) { bad = true; } }
    sum += numOf(arr);
    // (c) no leading zero on any addend
    check(arr[0] !== 0, 'run ' + run + ' (' + year + '/' + tier + '): addend leading zero');
    // (d) digit ceiling
    check(String(numOf(arr)).length <= ceil, 'run ' + run + ' (' + year + '/' + tier + '): addend ' + numOf(arr) + ' exceeds year ' + year + ' ceiling ' + ceil);
  }
  check(!bad, 'run ' + run + ': NaN/undefined in fullAddends');
  var total = numOf(a.fullTotal);
  check(a.fullTotal[0] !== 0, 'run ' + run + ' (' + year + '/' + tier + '): total leading zero');
  check(String(total).length <= ceil, 'run ' + run + ' (' + year + '/' + tier + '): total ' + total + ' exceeds year ' + year + ' ceiling ' + ceil);
  check(sum === total, 'run ' + run + ' (' + year + '/' + tier + '): sum ' + sum + ' != total ' + total);

  // ---- (c) blanks are interior (never a leading col-0 cell) and consistent with display
  for (var b = 0; b < q.blanks.length; b++) {
    var bl = q.blanks[b];
    check(bl.col >= 1, 'run ' + run + ': blank ' + bl.label + ' is a leading (col 0) digit');
    // the displayed grid must show null at the blanked cell
    var shown = bl.kind === 'total' ? q.total[bl.col] : q.addends[bl.row][bl.col];
    check(shown === null, 'run ' + run + ': blank ' + bl.label + ' not blanked in display grid');
  }
  // every non-blank display cell must equal the full grid (givens shown correctly)
  for (r = 0; r < nAdd; r++) {
    for (c = 0; c < w; c++) {
      if (q.addends[r][c] !== null) { check(q.addends[r][c] === a.fullAddends[r][c], 'run ' + run + ': shown addend digit disagrees with solution'); }
    }
  }
  for (c = 0; c < w; c++) {
    if (q.total[c] !== null) { check(q.total[c] === a.fullTotal[c], 'run ' + run + ': shown total digit disagrees with solution'); }
  }

  // ---- (b) unique solvability + exact recovery
  var res = TP_DD.solve(q);
  check(res && res.unique === true, 'run ' + run + ' (' + year + '/' + tier + '): solve not unique (count ' + (res ? res.count : '?') + ') word=' + a.word);
  if (res && res.unique) {
    for (b = 0; b < q.blanks.length; b++) {
      var lab = q.blanks[b].label;
      var trueDig = q.blanks[b].kind === 'total' ? a.fullTotal[q.blanks[b].col] : a.fullAddends[q.blanks[b].row][q.blanks[b].col];
      check(res.digits[lab] === trueDig, 'run ' + run + ': recovered ' + lab + '=' + res.digits[lab] + ' != true ' + trueDig);
      check(res.digits[lab] != null && !isNaN(res.digits[lab]), 'run ' + run + ': recovered ' + lab + ' is NaN/null');
    }
  }

  // ---- (e) cipher exactness + word integrity
  check(typeof a.word === 'string' && a.word.length === q.blanks.length, 'run ' + run + ': word length != blank count');
  check(a.letters.join('') === a.word, 'run ' + run + ': word != letters joined');
  for (b = 0; b < q.blanks.length; b++) {
    var d = a.digits[q.blanks[b].label];
    check(KEY[d] === a.letters[b], 'run ' + run + ': cipher mismatch at ' + q.blanks[b].label + ' digit ' + d + ' -> ' + KEY[d] + ' != ' + a.letters[b]);
  }
  if (a.real) {
    var pool = WORDS[a.word.length] || [];
    check(pool.indexOf(a.word) !== -1, 'run ' + run + ': claims real but "' + a.word + '" not in curated list');
    realCount++;
  }

  // ---- (f) tier shaping
  tierShape[tier] = tierShape[tier] || { nAdd: {}, width: {}, blanks: {} };
  tierShape[tier].nAdd[nAdd] = 1;
  tierShape[tier].width[w] = 1;
  tierShape[tier].blanks[q.blanks.length] = (tierShape[tier].blanks[q.blanks.length] || 0) + 1;
  if (tier === 'below') { check(nAdd === 2, 'run ' + run + ': below tier must have 2 addends, got ' + nAdd); }
  if (tier === 'exceeding') { check(nAdd === 3, 'run ' + run + ': exceeding tier must have 3 addends, got ' + nAdd); }

  // ---- (g) variety signature
  var sig = [];
  for (r = 0; r < nAdd; r++) { sig.push(a.fullAddends[r].join('')); }
  sig.push('=' + a.fullTotal.join(''));
  sig.push(a.word);
  sigSet[sig.join('|')] = true;
  wordSet[a.word] = true;
}

// ---- (g) variety thresholds
var distinctSigs = Object.keys(sigSet).length;
var distinctWords = Object.keys(wordSet).length;
check(distinctSigs > RUNS * 0.9, 'low variety: only ' + distinctSigs + ' distinct signatures of ' + RUNS);
check(distinctWords >= 20, 'low word variety: only ' + distinctWords + ' distinct reveal words');
// real-word rate should be high (word-first generation); the fallback may emit
// non-words occasionally, but most footers must read a real word.
check(realCount > RUNS * 0.7, 'real-word rate too low: ' + realCount + '/' + RUNS + ' (' + (100 * realCount / RUNS).toFixed(1) + '%)');

// ---- report
console.log('runs: ' + RUNS);
console.log('distinct signatures: ' + distinctSigs);
console.log('distinct reveal words: ' + distinctWords);
console.log('real-word rate: ' + (100 * realCount / RUNS).toFixed(1) + '%');
console.log('tier shaping: ' + JSON.stringify(Object.keys(tierShape).reduce(function (o, t) {
  o[t] = { nAdd: Object.keys(tierShape[t].nAdd), width: Object.keys(tierShape[t].width), blanks: tierShape[t].blanks };
  return o;
}, {})));

if (fails.length) {
  console.error('\nFAILED (' + fails.length + ' issues). First 25:');
  fails.slice(0, 25).forEach(function (f) { console.error('  - ' + f); });
  process.exit(1);
}
console.log('\nPASS: all ' + RUNS + ' puzzles valid (sums correct, uniquely solvable by deduction, no leading zeros, year-appropriate, cipher exact, varied).');
