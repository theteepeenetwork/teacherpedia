#!/usr/bin/env node
/* =============================================================================
 * dd-validate.js — THE correctness gate for the Digit Detectives engine.
 * -----------------------------------------------------------------------------
 * Drives window.TP_DD across EVERY offered year (3-6) x difficulty (1-5) and
 * asserts the full acceptance spec:
 *
 *  (a) UNIQUENESS — assertUniqueSolution(qtn).count === 1 for every generated
 *      puzzle; the ambiguous FIXTURE (two blanks in the SAME column) is caught
 *      (count > 1); and NO generated puzzle ever has two blanks in one column.
 *  (b) YEAR MAGNITUDE — Y3 numbers <= 3 digits, 3-digit numbers NEVER appear
 *      with 3+ addends, and single-digit-addend puzzles DO occur at Y3 with
 *      answer-only blanks; Y4 <= 4 digits; only Y6 mixes 3 large numbers;
 *      Y3 is FORWARD only (both blanks in the answer row); Y4+ allows an addend
 *      blank (inverse).
 *  (c) DECODE ROUND-TRIP — for a known custom message, the recovered blanks ->
 *      digits -> letters spell exactly the input (trimmed/padded to count).
 *  (d) SELF-CHECK — mutating ANY single blank to a wrong value changes the
 *      decoded message (so a wrong digit cannot read the right word).
 *  (e) VARIETY — many distinct puzzle signatures and several distinct reveals.
 *
 * Plus structural checks: arithmetic correctness on the full grid, no leading
 * zeros on any number's significant leading digit, displayed givens match the
 * solution, and the cipher mapping is exact (letter == codeToLetter(code)).
 *
 * Exits non-zero on the first batch of failures. Prints a PASS summary.
 * ========================================================================== */
'use strict';

global.window = {};
require('../../public_html/assets/js/tp-tool.js');
require('../../public_html/assets/js/digit-detectives.js');
var DD = global.window.TP_DD;

if (!DD || typeof DD.generateSheet !== 'function' || typeof DD.assertUniqueSolution !== 'function') {
  console.error('FAIL: window.TP_DD.generateSheet / .assertUniqueSolution not exposed');
  process.exit(1);
}

var fails = [];
function check(cond, msg) { if (!cond && fails.length < 60) { fails.push(msg); } }
function numOf(arr) { var v = 0; for (var i = 0; i < arr.length; i++) { v = v * 10 + arr[i]; } return v; }
function ceilingDigits(year) { return year <= 3 ? 3 : (year === 4 ? 4 : 6); }

// ---------------------------------------------------------------------------
// (a.1) AMBIGUOUS FIXTURE — a deliberately under-constrained puzzle: a blanked
// ADDEND digit AND a blanked ANSWER digit in the SAME column. The spec example:
// addends 16 + 15 = total 31, blank addend[0] units (the 6) AND total units
// (the 1) — both in the units column. The units column then reads
//   x + 5 (+carry) === y  with x,y free  => MANY solutions. assertUniqueSolution
// must report count > 1.
(function ambiguousFixture() {
  var puzzle = {
    // 16 + 15 = 31, grid width 2
    addends: [[1, null], [1, 5]],   // blank the UNITS of the first addend (was 6)
    total: [3, null],               // blank the UNITS of the total (was 1)
    width: 2, nAdd: 2,
    addLead: [0, 0], totalLead: 0,
    blanks: [
      { kind: 'addend', row: 0, col: 1 },
      { kind: 'total', row: 0, col: 1 }
    ]
  };
  var res = DD.assertUniqueSolution(puzzle);
  check(res.count > 1, 'ambiguous fixture NOT caught: assertUniqueSolution reported count=' + res.count + ' (expected > 1)');

  // A second fixture: blanked addend + blanked answer in the same column with a
  // tens digit elsewhere — still multi-solution.
  var puzzle2 = {
    addends: [[2, null], [1, 4]],   // 2_ + 14, blank addend units
    total: [null, 8],               // _8, blank total tens
    width: 2, nAdd: 2,
    addLead: [0, 0], totalLead: 0,
    blanks: [
      { kind: 'addend', row: 0, col: 1 },
      { kind: 'total', row: 0, col: 0 }
    ]
  };
  // here the units column 2nd-row gives 4; units col: addendUnits + 4 = 8 => addendUnits=4 forced.
  // tens: 2 + 1 + carry(0) = totalTens => totalTens = 3 forced. This one IS unique;
  // used only to confirm the oracle does not blindly call everything ambiguous.
  var r2 = DD.assertUniqueSolution(puzzle2);
  check(r2.count === 1, 'control fixture should be unique but oracle reported count=' + r2.count);
})();

// ---------------------------------------------------------------------------
// Main sweep across years x difficulties.
var YEARS = [3, 4, 5, 6];
var DIFFS = [1, 2, 3, 4, 5];
var SEEDS = 40;                 // sheets per (year, difficulty)
var COUNT = 9;

var sigSet = {}, msgSet = {}, totalPuzzles = 0;
var y3SmallAnswerOnly = false;  // (b) Y3 single-digit-addend with answer-only blanks occurs
var y3Forward = true;           // (b) Y3 must be forward only
var y4PlusInverse = false;      // (b) Y4+ allows an addend blank
var y6ThreeBig = false;         // (b) only Y6 mixes 3 large numbers
var nonY6ThreeBig = false;      // must stay false

YEARS.forEach(function (year) {
  var ceil = ceilingDigits(year);
  DIFFS.forEach(function (d) {
    for (var s = 0; s < SEEDS; s++) {
      var sheet;
      try { sheet = DD.generateSheet({ year: year, difficulty: d, count: COUNT, source: 'joke', seed: s * 31 + d * 7 + year }); }
      catch (e) { fails.push('Y' + year + ' d' + d + ' seed' + s + ': generateSheet threw ' + e.message); return; }

      check(sheet && sheet.items && sheet.items.length === COUNT, 'Y' + year + ' d' + d + ': wrong item count');
      if (!sheet || !sheet.items) { continue; }

      sheet.items.forEach(function (it, idx) {
        totalPuzzles++;
        var q = it.qtn, a = it.ans, w = q.width, nAdd = q.nAdd;

        // ---- structural sanity
        check(q.blanks.length === 2, 'Y' + year + ' d' + d + ' #' + idx + ': expected exactly 2 blanks, got ' + q.blanks.length);

        // ---- (a) uniqueness: exactly one solution
        var res = DD.assertUniqueSolution(q);
        check(res.count === 1, 'Y' + year + ' d' + d + ' #' + idx + ': not unique (count=' + res.count + ')');

        // ---- (a) at most one blank per column
        var colSeen = {};
        var twoInCol = false;
        q.blanks.forEach(function (b) { if (colSeen[b.col]) { twoInCol = true; } colSeen[b.col] = 1; });
        check(!twoInCol, 'Y' + year + ' d' + d + ' #' + idx + ': two blanks share a column');

        // ---- arithmetic correctness on the full solved grid
        var sum = 0;
        for (var r = 0; r < nAdd; r++) {
          var num = numOf(a.fullAddends[r]);
          sum += num;
          // (b) digit ceiling on each addend
          check(String(num).length <= ceil, 'Y' + year + ' d' + d + ' #' + idx + ': addend ' + num + ' exceeds ceiling ' + ceil);
          // no leading zero at the number's significant lead column
          check(a.fullAddends[r][a.addLead[r]] !== 0, 'Y' + year + ' d' + d + ' #' + idx + ': addend leading zero');
        }
        var total = numOf(a.fullTotal);
        check(sum === total, 'Y' + year + ' d' + d + ' #' + idx + ': sum ' + sum + ' != total ' + total);
        check(String(total).length <= ceil, 'Y' + year + ' d' + d + ' #' + idx + ': total ' + total + ' exceeds ceiling ' + ceil);
        check(a.fullTotal[a.totalLead] !== 0, 'Y' + year + ' d' + d + ' #' + idx + ': total leading zero');

        // ---- displayed givens equal the solution; blanks shown as null
        for (r = 0; r < nAdd; r++) {
          for (var c = a.addLead[r]; c < w; c++) {
            var shown = q.addends[r][c];
            if (shown !== null) { check(shown === a.fullAddends[r][c], 'Y' + year + ' d' + d + ' #' + idx + ': shown addend digit disagrees with solution'); }
          }
        }
        for (c = a.totalLead; c < w; c++) {
          if (q.total[c] !== null) { check(q.total[c] === a.fullTotal[c], 'Y' + year + ' d' + d + ' #' + idx + ': shown total digit disagrees'); }
        }
        q.blanks.forEach(function (b) {
          var shown = b.kind === 'total' ? q.total[b.col] : q.addends[b.row][b.col];
          check(shown === null, 'Y' + year + ' d' + d + ' #' + idx + ': blanked cell not null in display');
        });

        // ---- cipher exactness: tens/ones digits -> code -> letter
        var blanksByCol = q.blanks.slice().sort(function (x, y) { return x.col - y.col; });
        check(blanksByCol[0].col < blanksByCol[1].col, 'Y' + year + ' d' + d + ' #' + idx + ': tens blank not left of ones blank');
        var code = DD.digitsToCode(a.tens, a.ones);
        check(code >= 0 && code <= 25, 'Y' + year + ' d' + d + ' #' + idx + ': code ' + code + ' out of A-Z range');
        check(a.letter === DD.codeToLetter(code), 'Y' + year + ' d' + d + ' #' + idx + ': cipher mismatch ' + code + ' -> ' + a.letter);

        // ---- (b) year-shape facts
        if (year === 3) {
          var allTotal = q.blanks.every(function (b) { return b.kind === 'total'; });
          if (!allTotal) { y3Forward = false; }
          // single-digit-addend (nAdd>=3, every addend 1 digit) with answer-only blanks
          if (nAdd >= 3) {
            var allSingle = a.fullAddends.every(function (arr) { return String(numOf(arr)).length === 1; });
            if (allSingle && allTotal) { y3SmallAnswerOnly = true; }
            // 3-digit ADDENDS must NEVER appear with 3+ addends (a 3-digit TOTAL
            // is fine — e.g. several single digits summing into the hundreds is
            // not produced here, but the rule guards the addends specifically).
            var anyThreeDigitAddend = a.fullAddends.some(function (arr) { return String(numOf(arr)).length >= 3; });
            check(!anyThreeDigitAddend, 'Y3 #' + idx + ': a 3-digit ADDEND appears with ' + nAdd + ' addends');
          }
        } else {
          if (q.blanks.some(function (b) { return b.kind === 'addend'; })) { y4PlusInverse = true; }
        }
        // three large numbers (3 addends, all >= 4 digits)
        if (nAdd >= 3) {
          var bigCount = a.fullAddends.filter(function (arr) { return String(numOf(arr)).length >= 4; }).length;
          if (bigCount >= 3) { if (year === 6) { y6ThreeBig = true; } else { nonY6ThreeBig = true; } }
        }

        // ---- variety signatures
        var sig = year + ':' + a.fullAddends.map(function (arr) { return numOf(arr); }).join('+') + '=' + total;
        sigSet[sig] = true;
      });

      msgSet[sheet.message] = true;
    }
  });
});

// ---------------------------------------------------------------------------
// (c) DECODE ROUND-TRIP for a known custom message, every year x difficulty.
(function decodeRoundTrip() {
  var MSG = 'WELL DONE';
  YEARS.forEach(function (year) {
    DIFFS.forEach(function (d) {
      var sheet = DD.generateSheet({ year: year, difficulty: d, count: 12, source: 'custom', message: MSG, seed: 4242 });
      var expected = DD.messageLetters(MSG);              // letters, spaces stripped
      var seq = [];
      for (var i = 0; i < 12; i++) { seq.push(expected[i % expected.length]); }
      var decoded = sheet.items.map(function (it) { return it.ans.letter; }).join('');
      check(decoded === seq.join(''), 'decode round-trip Y' + year + ' d' + d + ': "' + decoded + '" != "' + seq.join('') + '"');
      check(sheet.message === seq.join(''), 'sheet.message mismatch Y' + year + ' d' + d);
    });
  });
})();

// ---------------------------------------------------------------------------
// (d) SELF-CHECK: mutating any ONE blank to a wrong value breaks the message.
(function selfCheck() {
  var sheet = DD.generateSheet({ year: 4, difficulty: 3, count: 9, source: 'word', seed: 777 });
  var ok = true;
  sheet.items.forEach(function (it, idx) {
    var a = it.ans, q = it.qtn;
    var blanksByCol = q.blanks.slice().sort(function (x, y) { return x.col - y.col; });
    // tens then ones true digits
    var trueT = a.tens, trueO = a.ones;
    // try every wrong tens digit and wrong ones digit -> different letter
    for (var wt = 0; wt <= 9; wt++) {
      if (wt === trueT) { continue; }
      var lt = DD.codeToLetter(DD.digitsToCode(wt, trueO));
      if (lt === a.letter && DD.digitsToCode(wt, trueO) <= 25) { ok = false; }
    }
    for (var wo = 0; wo <= 9; wo++) {
      if (wo === trueO) { continue; }
      var lo = DD.codeToLetter(DD.digitsToCode(trueT, wo));
      if (lo === a.letter && DD.digitsToCode(trueT, wo) <= 25) { ok = false; }
    }
  });
  check(ok, 'self-check failed: a wrong blank digit could still read the correct letter');

  // And the deductive self-check: the blanks ARE forced (only the true digits
  // satisfy the arithmetic), so any single wrong digit makes the column wrong.
  sheet.items.forEach(function (it, idx) {
    var r = DD.assertUniqueSolution(it.qtn);
    check(r.count === 1, 'self-check: puzzle #' + idx + ' not uniquely forced (count ' + r.count + ')');
  });
})();

// ---------------------------------------------------------------------------
// (a/b) aggregate assertions
check(y3Forward, 'Y3 produced a non-forward puzzle (a blank outside the answer row)');
check(y3SmallAnswerOnly, 'Y3 never produced a single-digit-addend puzzle with answer-only blanks');
check(y4PlusInverse, 'Y4+ never produced an inverse puzzle (an addend blank)');
check(y6ThreeBig, 'Y6 never mixed three large (>=4-digit) numbers');
check(!nonY6ThreeBig, 'a year other than Y6 mixed three large numbers');

// (e) variety
var distinctSigs = Object.keys(sigSet).length;
var distinctMsgs = Object.keys(msgSet).length;
check(distinctSigs > totalPuzzles * 0.85, 'low variety: only ' + distinctSigs + ' distinct signatures of ' + totalPuzzles);
check(distinctMsgs >= 5, 'low reveal variety: only ' + distinctMsgs + ' distinct messages');

// ---------------------------------------------------------------------------
// (extra) determinism — same seed reproduces the identical sheet.
(function determinism() {
  var a = DD.generateSheet({ year: 5, difficulty: 4, count: 9, source: 'joke', seed: 9001 });
  var b = DD.generateSheet({ year: 5, difficulty: 4, count: 9, source: 'joke', seed: 9001 });
  check(JSON.stringify(a) === JSON.stringify(b), 'determinism: same seed produced different sheets');
})();

// ---- report ---------------------------------------------------------------
console.log('puzzles generated: ' + totalPuzzles);
console.log('distinct signatures: ' + distinctSigs);
console.log('distinct reveal messages: ' + distinctMsgs);
console.log('Y3 forward-only: ' + y3Forward);
console.log('Y3 single-digit-addend answer-only puzzles occur: ' + y3SmallAnswerOnly);
console.log('Y4+ inverse (addend-blank) puzzles occur: ' + y4PlusInverse);
console.log('only Y6 mixes three large numbers: ' + (y6ThreeBig && !nonY6ThreeBig));
console.log('ambiguous fixture caught (count>1) + generator always count===1: ' + (fails.length === 0 || 'see failures'));

if (fails.length) {
  console.error('\nFAILED (' + fails.length + ' issues). First 25:');
  fails.slice(0, 25).forEach(function (f) { console.error('  - ' + f); });
  process.exit(1);
}
console.log('\nPASS: all acceptance criteria met across Y3-6 x difficulty 1-5 — unique solutions, ambiguous fixture caught, year-appropriate magnitudes, decode round-trip, self-check, variety, determinism.');
