/* =============================================================================
 * digit-detectives.js — Digit Detectives (column-addition deduction puzzle).
 * -----------------------------------------------------------------------------
 * A grid of small column-ADDITION cards. Each card is a REAL, correct sum laid
 * out in the formal written method (addends stacked over a rule, total beneath)
 * with EXACTLY TWO digits blanked: the TOTAL's adjacent TENS and ONES columns.
 * The solver finds the total (run the algorithm forwards), then reads its last
 * two digits. Because the addends are all known, the two answer blanks are
 * FORCED to a single value — no guessing.
 *
 * Cipher (two-digit codebook):
 *   The total's last two digits read as a two-digit number 00-25 -> letter A-Z
 *   (00=A, 01=B, … 25=Z): total % 100 === code (FIX 4). The TENS blank is the
 *   left, the ONES blank the right. One letter per puzzle; puzzle position =
 *   message-letter position. Reading one letter per card in order spells a
 *   whole-sheet reveal (a joke punchline or praise word) — the self-mark. The
 *   revealed message has EXACTLY one letter per puzzle (FIX 1).
 *
 * Magnitude scales by YEAR (aligned to objectives.json addition keys); the 1-5
 * meter scales ADDEND magnitude WITHIN the year ceiling — NEVER the ceiling, and
 * NEVER the blank placement (always the total's last two digits, every year).
 *
 *   Y3 : single-digit addends (3-5) OR two numbers <=3 digits; total <=3 digits;
 *        3-digit numbers never appear with 3+ addends.
 *   Y4 : two numbers up to 4 digits; total <=4 digits.
 *   Y5 : two numbers 5-6 digits; total <=6 digits.
 *   Y6 : 2-3 numbers, 4-6 digits, mixed sizes; total <=6 digits; the ONLY year
 *        that mixes three large numbers.
 *
 * Determinism: a seedable PRNG (mulberry32) means a saved {year,difficulty,
 * count,source,message,seed} re-prints the IDENTICAL sheet.
 *
 * Pure engine exposed as window.TP_DD for Node tests; DOM wiring runs in-browser.
 * Self-contained per the engine rules. See dev/RESOURCE_WORKFLOW.md.
 * ========================================================================== */
(function () {
  'use strict';

  // ---- seedable PRNG (mulberry32) -----------------------------------------
  // A tiny deterministic generator so a saved seed reproduces the exact sheet.
  // All randomness in generation flows through a passed-in rng() in [0,1).
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

  // rng-driven helpers (deterministic). Math.random is NEVER used in generation.
  function ri(rng, a, b) { return a + Math.floor(rng() * (b - a + 1)); }
  function pick(rng, arr) { return arr[Math.floor(rng() * arr.length)]; }
  function shuffle(rng, arr) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(rng() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; }
    return a;
  }

  // ---- two-digit cipher: code N (0-25) <-> letter A-Z ----------------------
  // codeToDigits(N) = [tens, ones]; the LEFT blank holds tens, the RIGHT ones.
  var ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  function letterToCode(ch) { return ALPHABET.indexOf(ch); }     // 'A' -> 0 … 'Z' -> 25
  function codeToLetter(n) { return ALPHABET.charAt(n); }        // 0 -> 'A'
  function codeToDigits(n) { return [Math.floor(n / 10), n % 10]; }
  function digitsToCode(t, o) { return t * 10 + o; }

  // ---- reveal bank: jokes (Q + A) and praise words -------------------------
  // Jokes print the question at the TOP; the punchline is what the sheet spells.
  // Praise words are single tokens. Each cleaned to A-Z only.
  //
  // FIX 1: the revealed message must show EXACTLY one letter per puzzle, so every
  // bank entry is chosen by its LETTER count (spaces ignored). The supported
  // puzzle counts are 6 / 9 / 12; each must offer >= 3 entries at that length.
  var JOKES = [
    { q: 'Why is six afraid of seven?', a: 'SEVEN ATE NINE' }   // 12 letters
  ];
  var PRAISE = [
    // 6 letters
    'GENIUS', 'WIZARD', 'CLEVER', 'BRAINY', 'EXPERT',
    // 9 letters
    'EXCELLENT', 'BRILLIANT', 'FANTASTIC', 'WONDERFUL', 'SUPERSTAR', 'DETECTIVE',
    // 12 letters
    'MATHEMAGICAL', 'CONGRATULATE'
  ];

  // Keep only A-Z and spaces, upper-case, collapse spaces, trim.
  function cleanMessage(s) {
    return String(s == null ? '' : s)
      .toUpperCase()
      .replace(/[^A-Z ]+/g, '')
      .replace(/ {2,}/g, ' ')
      .replace(/^ +| +$/g, '');
  }
  // The letters that actually drive puzzles (spaces stripped). Pad/trim to count.
  function messageLetters(msg) { return cleanMessage(msg).replace(/ /g, '').split(''); }
  // Number of code-bearing letters in a message (spaces ignored).
  function letterCount(msg) { return messageLetters(msg).length; }

  // FIX 1: candidate reveals whose letter count EXACTLY equals `count`.
  // Jokes carry their question as the prompt; praise words have no prompt.
  function revealsForCount(count) {
    var out = [];
    for (var j = 0; j < JOKES.length; j++) {
      if (letterCount(JOKES[j].a) === count) { out.push({ msg: cleanMessage(JOKES[j].a), prompt: JOKES[j].q, kind: 'joke' }); }
    }
    for (var p = 0; p < PRAISE.length; p++) {
      if (letterCount(PRAISE[p]) === count) { out.push({ msg: cleanMessage(PRAISE[p]), prompt: '', kind: 'word' }); }
    }
    return out;
  }

  // ---- year magnitude bands ------------------------------------------------
  // ceilingDigits: the HARD per-number digit cap for the year (never exceeded,
  // never scaled by the meter). Aligned to objectives.json addition keys:
  //   Y3 HTU+HTU (<=3) | Y4 HTU+HTU bridging 1000 -> allow up to 4 for inverse |
  //   Y5 ThHTU+ThHTU (4) extending to 5-6 | Y6 beyond four digits (<=6).
  function ceilingDigits(year) {
    if (year <= 3) { return 3; }
    if (year === 4) { return 4; }
    return 6; // Y5, Y6
  }

  // Per-year shape, with the 1-5 meter (d) scaling magnitude WITHIN the ceiling.
  // Returns the menu of "forms" the year may take; build() picks one.
  //
  // FIX 4: EVERY puzzle is now FORWARD — the two blanks are always the total's
  // adjacent TENS and ONES columns (total % 100 === code). There are no addend
  // blanks. The year therefore only drives ADDEND magnitude; the blank placement
  // is fixed. Forms describe addend shape only.
  //   form.kind    : 'small' (several single-digit addends) | 'pair' | 'mixed'
  //   form.nAdd    : addend count (number, or 'small' = 3-5 single digits)
  //   form.widthMin/widthMax: per-number digit band (<= ceiling)
  function yearForms(year, d) {
    var ceil = ceilingDigits(year);

    if (year === 3) {
      // Two forms (magnitude <= 3 digits):
      //  - several SINGLE-DIGIT addends (3-5), total <= 3 digits;
      //  - TWO numbers up to 3 digits, total <= 3 digits.
      // 3-digit numbers NEVER appear with 3+ addends (small-addend form is 1-digit).
      return {
        choices: [
          { kind: 'small', nAdd: 'small', widthMin: 1, widthMax: 1 },
          { kind: 'pair', nAdd: 2, widthMin: 2, widthMax: 3 }
        ],
        ceil: ceil
      };
    }
    if (year === 4) {
      // Two numbers, 3-4 digits (<= ceiling 4). The meter raises the upper width.
      var hi4 = (d <= 2) ? 3 : 4;
      return {
        choices: [{ kind: 'pair', nAdd: 2, widthMin: 3, widthMax: hi4 }],
        ceil: ceil
      };
    }
    if (year === 5) {
      // 5-6 digit numbers, two addends.
      var lo5 = d <= 2 ? 4 : 5;
      var hi5 = d <= 1 ? 5 : 6;
      return {
        choices: [{ kind: 'pair', nAdd: 2, widthMin: lo5, widthMax: Math.max(lo5, hi5) }],
        ceil: ceil
      };
    }
    // year 6: 2-3 numbers, 4-6 digits, mixed sizes; ONLY year that mixes three.
    var nAdd6 = d >= 3 ? 3 : 2;
    return {
      choices: [{ kind: 'mixed', nAdd: nAdd6, widthMin: 4, widthMax: 6 }],
      ceil: ceil
    };
  }

  // ---- digit-array representation ------------------------------------------
  // Numbers are digit arrays, index 0 = MOST significant, length == grid width
  // (zero-padded on the left). Column c (0=leftmost) is place value 10^(w-1-c).
  function digitsOf(n, width) {
    var s = String(n), out = [], i;
    for (i = 0; i < s.length; i++) { out.push(s.charCodeAt(i) - 48); }
    while (out.length < width) { out.unshift(0); }
    return out;
  }
  function numOf(arr) { var v = 0; for (var i = 0; i < arr.length; i++) { v = v * 10 + arr[i]; } return v; }

  // ---- uniqueness oracle ---------------------------------------------------
  // assertUniqueSolution(puzzle): brute-force ALL 10^(#blanks) digit
  // substitutions and count assignments where every addend is a valid number
  // (no leading zero on any multi-digit number) AND sum(addends) === total.
  // Returns { count, unique } where unique === (count === 1). Exposed on TP_DD.
  //
  // puzzle shape (the generator's qtn):
  //   { addends:[[d|null,…],…], total:[d|null,…], width, nAdd, blanks:[{kind,row,col,…}] }
  // Blanks are cells whose displayed value is null.
  function assertUniqueSolution(puzzle) {
    var w = puzzle.width, nAdd = puzzle.nAdd;
    var blanks = puzzle.blanks;
    var nB = blanks.length;

    // Each number occupies grid columns [lead .. w-1]; columns left of `lead` are
    // structural padding (always 0, never blanked) and are NOT a leading zero.
    // The no-leading-zero rule applies only at the number's own `lead` column.
    // When a puzzle omits lead info, default to col 0 (every column significant).
    var addLead = puzzle.addLead || (function () { var a = []; for (var r = 0; r < nAdd; r++) { a.push(0); } return a; })();
    var totalLead = (puzzle.totalLead != null) ? puzzle.totalLead : 0;

    // Resolve a cell's value under a trial assignment (array of digits per blank).
    function cellVal(kind, row, col, trial) {
      for (var k = 0; k < nB; k++) {
        var b = blanks[k];
        if (b.kind === kind && b.col === col && (kind === 'total' || b.row === row)) { return trial[k]; }
      }
      var lead = kind === 'total' ? totalLead : addLead[row];
      if (col < lead) { return 0; }  // structural padding column
      return kind === 'total' ? puzzle.total[col] : puzzle.addends[row][col];
    }
    // Is `row` (addend) or the total a valid number (no leading zero)? The
    // significant leading digit sits at the number's `lead` column.
    function noLeadingZero(kind, row, trial) {
      var lead = kind === 'total' ? totalLead : addLead[row];
      if (lead >= w) { return true; }
      var d = cellVal(kind, row, lead, trial);
      return d !== 0;
    }
    function valOf(kind, row, trial) {
      var v = 0;
      for (var c = 0; c < w; c++) { v = v * 10 + cellVal(kind, row, c, trial); }
      return v;
    }

    var count = 0;
    var trial = new Array(nB);
    // odometer over nB digits 0..9
    function rec(k) {
      if (count >= 2) { return; } // we only need to distinguish 0/1/>=2 for the gate,
      // but we keep counting to give an honest count up to a small cap for tests.
      if (k === nB) {
        // validate
        var ok = true, c, r, sum = 0;
        for (r = 0; r < nAdd && ok; r++) {
          if (!noLeadingZero('addend', r, trial)) { ok = false; break; }
          sum += valOf('addend', r, trial);
        }
        if (ok && !noLeadingZero('total', 0, trial)) { ok = false; }
        if (ok && sum !== valOf('total', 0, trial)) { ok = false; }
        if (ok) { count++; }
        return;
      }
      for (var dgt = 0; dgt <= 9; dgt++) { trial[k] = dgt; rec(k + 1); if (count >= 2) { return; } }
    }

    // For an HONEST count (used by the ambiguous fixture test) we do NOT
    // short-circuit when the caller wants the true number; but to keep the gate
    // fast we cap at 2. The fixture in the validator only needs count>1, and the
    // generator only needs count===1, so a cap of 2 is sufficient and exact for
    // the {0,1,>=2} decision. We expose count capped at 2 as ">=2".
    rec(0);
    return { count: count, unique: count === 1 };
  }

  // Partition `total` into exactly `n` single-digit addends (each 1-9). Returns
  // an array or null if impossible (total outside [n, 9n]).
  function partitionSingleDigits(rng, total, n) {
    if (total < n || total > 9 * n) { return null; }
    for (var attempt = 0; attempt < 60; attempt++) {
      var parts = [], remaining = total, ok = true;
      for (var i = 0; i < n; i++) {
        var slotsLeft = n - i - 1;
        // each remaining slot must hold 1..9, so bound this pick accordingly.
        var lo = Math.max(1, remaining - 9 * slotsLeft);
        var hi = Math.min(9, remaining - 1 * slotsLeft);
        if (lo > hi) { ok = false; break; }
        var v = ri(rng, lo, hi);
        parts.push(v); remaining -= v;
      }
      if (ok && remaining === 0) { return shuffle(rng, parts); }
    }
    return null;
  }

  // ---- core builder: one card ----------------------------------------------
  // FIX 4: build a complete valid addition for the year whose TOTAL's last two
  // digits are exactly the code (total % 100 === code), then blank the total's
  // adjacent TENS and ONES columns. Every puzzle is forward: read the total, the
  // two answer blanks ARE the code. Uniqueness is trivial (both blanks in the
  // total, all addends known) but still asserted. Returns { qtn, ans } or null.
  //
  //   code: integer 0-25 for this card's message letter.
  //   form: a chosen form object from yearForms().
  function buildCard(rng, year, d, form, code) {
    var cd = codeToDigits(code); // [tens, ones]; tens is 0/1/2, ones 0-9
    var t = cd[0], o = cd[1];
    var ceil = ceilingDigits(year);

    // ---- SMALL form (Y3): several single-digit addends summing to a 2-digit
    // total whose tens=t and ones=o. Needs t>=1 (a 2-digit total can't have a
    // zero tens digit); codes 0-9 (t==0) fall back to the pair form.
    if (form.kind === 'small') {
      if (t < 1) { return null; }                 // need a real 2-digit total
      var targetTotal = t * 10 + o;               // 10..25 for valid letters
      for (var sa = 0; sa < 200; sa++) {
        var nA = ri(rng, 3, 5);
        var parts = partitionSingleDigits(rng, targetTotal, nA);
        if (!parts) { continue; }
        var gridS = 2;
        var addsS = [];
        for (var p = 0; p < parts.length; p++) { addsS.push(digitsOf(parts[p], gridS)); }
        var qaS = assembleCard({
          addends: addsS, total: digitsOf(targetTotal, gridS), width: gridS, nAdd: parts.length
        }, [
          { kind: 'total', row: 0, col: 0, place: 'tens' },
          { kind: 'total', row: 0, col: 1, place: 'ones' }
        ], code, t, o);
        if (assertUniqueSolution(qaS.qtn).unique) { return qaS; }
      }
      return null;
    }

    // ---- PAIR / MIXED form: nAdd numbers in the year's width band, whose sum's
    // last two digits are t (tens) and o (ones). We build the first nAdd-1
    // addends freely, then choose the LAST addend so the running total lands on
    // ...to. The last addend's low two digits are derived; its high digits are
    // free (so it still reads as a real year-magnitude number).
    var nAdd = form.nAdd;
    var code100 = t * 10 + o;                     // 0..25
    for (var attempt = 0; attempt < 400; attempt++) {
      // per-number widths within the band
      var widths = [];
      var maxW = 0;
      for (var i = 0; i < nAdd; i++) {
        var wi = ri(rng, form.widthMin, form.widthMax);
        widths.push(wi);
        if (wi > maxW) { maxW = wi; }
      }
      var gridW = maxW;

      // build the first nAdd-1 addends as random valid numbers in their width.
      var nums = [];
      var partial = 0, ok = true;
      for (i = 0; i < nAdd - 1; i++) {
        var loA = Math.pow(10, widths[i] - 1), hiA = Math.pow(10, widths[i]) - 1;
        if (widths[i] === 1) { loA = 1; hiA = 9; }
        var v = ri(rng, loA, hiA);
        nums.push(v); partial += v;
      }

      // The last addend `last` must make (partial + last) % 100 === code100, and
      // must itself be a valid number in widths[last] with no leading zero, and
      // the total must stay within the ceiling. last % 100 is fixed mod 100; we
      // pick the high part freely. lastLow = (code100 - partial) mod 100.
      var lastW = widths[nAdd - 1];
      var lastLow = ((code100 - partial) % 100 + 100) % 100;   // 0..99, the low 2 digits
      // choose the high part so `last` has lastW digits and no leading zero.
      var last = null;
      for (var hattempt = 0; hattempt < 40; hattempt++) {
        var highDigits = lastW - 2;
        var hi, lo;
        if (highDigits <= 0) {
          // 1- or 2-digit last addend: value IS lastLow (must be valid for width)
          if (lastW === 1) { if (lastLow < 1 || lastLow > 9) { break; } last = lastLow; }
          else { if (lastLow < 10) { break; } last = lastLow; } // 2-digit needs >=10
          break;
        } else {
          var hLo = Math.pow(10, highDigits - 1), hHi = Math.pow(10, highDigits) - 1;
          hi = ri(rng, hLo, hHi);
          lo = lastLow;
          last = hi * 100 + lo;
          if (last >= Math.pow(10, lastW - 1)) { break; } // valid lastW-digit number
        }
      }
      if (last == null) { continue; }
      nums.push(last);

      var sum = partial + last;
      if (sum % 100 !== code100) { continue; }      // safety (carries don't change %100)
      var sumStr = String(sum);
      if (sumStr.length < 2) { continue; }          // need >=2 total digits for two blanks
      if (sumStr.length > ceil) { continue; }       // total within year ceiling

      gridW = Math.max(gridW, sumStr.length);
      var addends = [];
      for (i = 0; i < nAdd; i++) { addends.push(digitsOf(nums[i], gridW)); }
      var totalArr = digitsOf(sum, gridW);

      // the two blanks are the total's TENS (col gridW-2) and ONES (col gridW-1).
      var qa = assembleCard({
        addends: addends, total: totalArr, width: gridW, nAdd: nAdd
      }, [
        { kind: 'total', row: 0, col: gridW - 2, place: 'tens' },
        { kind: 'total', row: 0, col: gridW - 1, place: 'ones' }
      ], code, t, o);

      if (assertUniqueSolution(qa.qtn).unique) { return qa; }
    }
    return null;
  }

  // ---- assemble a card from a full sum + the two blank cells ----------------
  // Produces { qtn, ans }. qtn has display grids (null at blanks); ans has the
  // full solved grids, the per-blank digits, the code, and the decoded letter.
  function assembleCard(sum, blankCells, code, t, o) {
    var w = sum.width, nAdd = sum.nAdd;
    // copy full grids
    var fullAddends = [];
    for (var r = 0; r < nAdd; r++) { fullAddends.push(sum.addends[r].slice()); }
    var fullTotal = sum.total.slice();

    // display grids with null at the blanked cells
    var dispAdd = [];
    for (r = 0; r < nAdd; r++) { dispAdd.push(sum.addends[r].slice()); }
    var dispTotal = sum.total.slice();

    var blanks = [];
    for (var b = 0; b < blankCells.length; b++) {
      var bc = blankCells[b];
      var dig = bc.kind === 'total' ? fullTotal[bc.col] : fullAddends[bc.row][bc.col];
      blanks.push({ kind: bc.kind, row: bc.row, col: bc.col, place: bc.place, digit: dig });
      if (bc.kind === 'total') { dispTotal[bc.col] = null; }
      else { dispAdd[bc.row][bc.col] = null; }
    }

    // left blank (tens) first by column, right (ones) second — guaranteed by
    // construction, but sort defensively so render reads left->right.
    blanks.sort(function (a, b2) { return a.col - b2.col; });
    var tensDig = blanks[0].digit, onesDig = blanks[1].digit;
    var decoded = codeToLetter(digitsToCode(tensDig, onesDig));

    // Per-number lead column: where each number's significant digits begin in the
    // shared grid. Columns left of `lead` are structural padding (rendered blank,
    // value 0, never blanked). This lets the oracle apply the no-leading-zero
    // rule at the right place and lets a 2-wide grid host single-digit addends.
    function leadOf(arr) {
      for (var c = 0; c < w; c++) { if (arr[c] !== 0) { return c; } }
      return w - 1; // an all-zero number is just "0" in the last column
    }
    var addLead = [];
    for (r = 0; r < nAdd; r++) { addLead.push(leadOf(fullAddends[r])); }
    var totalLead = leadOf(fullTotal);

    return {
      qtn: {
        addends: dispAdd, total: dispTotal, width: w, nAdd: nAdd, blanks: blanks,
        addLead: addLead, totalLead: totalLead
      },
      ans: {
        fullAddends: fullAddends, fullTotal: fullTotal,
        code: code, tens: tensDig, ones: onesDig, letter: decoded,
        addLead: addLead.slice(), totalLead: totalLead
      }
    };
  }

  // ---- whole-sheet generation ----------------------------------------------
  // opts = { year, difficulty(1-5), count, source('joke'|'word'|'custom'),
  //          message(custom text), seed }.
  // Returns { items:[{qtn,ans},…], message, prompt, source, seed }.
  function generateSheet(opts) {
    opts = opts || {};
    var year = Math.max(3, Math.min(6, (opts.year || 4) | 0));
    var meter = Math.max(1, Math.min(5, (opts.difficulty || 3) | 0));
    var count = Math.max(1, (opts.count || 6) | 0);
    var seed = (opts.seed != null) ? (opts.seed >>> 0) : (Math.floor(Math.random() * 0xffffffff) >>> 0);
    var rng = makeRng(seed);
    var d = (typeof window !== 'undefined' && window.TP_effDifficulty)
      ? window.TP_effDifficulty(year, meter)
      : meter;

    // ---- resolve the reveal message -> EXACTLY `count` letters (FIX 1) ------
    // The revealed message must show one letter per puzzle and never more letters
    // than puzzles. For built-in reveals we only OFFER bank entries whose letter
    // count === count. For a custom message we SNAP the puzzle count to the
    // message's letter length (clamped to a sane 4-14), so message.length===count.
    var source = opts.source || 'joke';
    var prompt = '';
    var rawMsg = '';

    if (source === 'custom' && cleanMessage(opts.message)) {
      rawMsg = cleanMessage(opts.message);
      // SNAP count to the custom message's letter length (clamp 4-14).
      var customLen = letterCount(rawMsg);
      count = Math.max(4, Math.min(14, customLen));
      // If the typed message is longer/shorter than the clamp, trim/pad later to
      // `count` so we never display more letters than puzzles.
    } else {
      // Built-in: choose a reveal whose letter count EXACTLY equals `count`.
      var pool = revealsForCount(count);
      if (source === 'word') {
        pool = pool.filter(function (r) { return r.kind === 'word'; });
        if (!pool.length) { pool = revealsForCount(count); }
      } else {
        // joke preferred; fall back to whatever fits the count.
        var jokes = pool.filter(function (r) { return r.kind === 'joke'; });
        if (jokes.length) { pool = jokes; } // keep joke if one fits this count
        source = 'joke';
      }
      if (!pool.length) {
        // No bank entry of exactly this length: fall back to ALL reveals for the
        // count; if STILL none, build a generic word of the right length.
        pool = revealsForCount(count);
      }
      if (pool.length) {
        var chosen = pick(rng, pool);
        rawMsg = chosen.msg;
        prompt = chosen.prompt;
        source = chosen.kind;
      } else {
        // last resort: repeat a praise word's letters to the count (rare; only if
        // a non-standard count is requested with no matching bank entry).
        rawMsg = cleanMessage(PRAISE[0]);
        source = 'word';
      }
    }

    // letters that drive puzzles (spaces removed), trimmed/padded to `count`.
    var letters = messageLetters(rawMsg);
    if (!letters.length) { letters = messageLetters(PRAISE[0]); }
    var seq = [];
    for (var i = 0; i < count; i++) { seq.push(letters[i % letters.length]); }

    // ---- build one card per letter -----------------------------------------
    var items = [];
    for (i = 0; i < count; i++) {
      var code = letterToCode(seq[i]);
      var item = buildOne(rng, year, d, code);
      items.push(item);
    }

    // fullMessage is what the page DISPLAYS as the answer; it must contain no more
    // letters than puzzles. When the source message's letters exactly fill the
    // count we keep its spacing; otherwise show the spelled sequence (FIX 1).
    var displayMessage = (letterCount(rawMsg) === count) ? rawMsg : seq.join('');

    return {
      items: items,
      message: seq.join(''),       // exactly what the sheet spells (count letters)
      fullMessage: displayMessage, // human-readable; letters === count
      prompt: prompt,
      source: source,
      year: year, difficulty: meter, count: count, seed: seed
    };
  }

  // Build a single card for a given code, with a liveness fallback so a puzzle
  // is ALWAYS produced (never null). Picks a year form, retries forms, and as a
  // last resort uses a guaranteed-constructible inverse/forward minimal card.
  function buildOne(rng, year, d, code) {
    var forms = yearForms(year, d).choices;
    var tries = shuffle(rng, forms.slice());
    for (var f = 0; f < tries.length; f++) {
      var got = buildCard(rng, year, d, tries[f], code);
      if (got) { return got; }
    }
    // retry the whole menu a few more times (different randomness)
    for (var r = 0; r < 8; r++) {
      var form = pick(rng, forms);
      var got2 = buildCard(rng, year, d, form, code);
      if (got2) { return got2; }
    }
    // GUARANTEED fallback: a minimal forward card that can host any code 0-25.
    return fallbackCard(rng, year, code);
  }

  // A deterministic, always-constructible card: pick two addends so the total
  // contains both code digits in distinct interior columns. We brute-force small
  // totals; this always succeeds for the year-3 forward shape and is valid for
  // any year (digits within ceiling).
  function fallbackCard(rng, year, code) {
    var cd = codeToDigits(code), t = cd[0], o = cd[1];
    var ceil = ceilingDigits(year);
    // We want a 3-digit total whose tens digit = t and ones digit = o, with the
    // leading digit nonzero, expressed as a + b. Choose total = h*100 + t*10 + o.
    for (var attempt = 0; attempt < 100; attempt++) {
      var h = ri(rng, 1, Math.min(9, Math.pow(10, Math.min(ceil, 3) - 1) - 1) || 1);
      var total = h * 100 + t * 10 + o;
      if (String(total).length > ceil) { continue; }
      var a = ri(rng, 1, total - 1);
      var bnum = total - a;
      if (bnum < 1) { continue; }
      var gw = String(total).length;
      var addends = [digitsOf(a, gw), digitsOf(bnum, gw)];
      var totalArr = digitsOf(total, gw);
      if (addends[0][0] === 0 || addends[1][0] === 0) { continue; }
      // blanks: the tens (col gw-2) and ones (col gw-1) of the total.
      var qa = assembleCard({ addends: addends, total: totalArr, width: gw, nAdd: 2 }, [
        { kind: 'total', row: 0, col: gw - 2, place: 'tens' },
        { kind: 'total', row: 0, col: gw - 1, place: 'ones' }
      ], code, t, o);
      if (qa.qtn.blanks[0].col !== qa.qtn.blanks[1].col && assertUniqueSolution(qa.qtn).unique) { return qa; }
    }
    // ultra-last-resort: total = 100 + t*10 + o, a=50, b=rest.
    var tot = 100 + t * 10 + o;
    var aa = Math.max(1, Math.min(tot - 1, 50));
    var addends2 = [digitsOf(aa, 3), digitsOf(tot - aa, 3)];
    return assembleCard({ addends: addends2, total: digitsOf(tot, 3), width: 3, nAdd: 2 }, [
      { kind: 'total', row: 0, col: 1, place: 'tens' },
      { kind: 'total', row: 0, col: 2, place: 'ones' }
    ], code, t, o);
  }

  // ---- expose engine -------------------------------------------------------
  if (typeof window !== 'undefined') {
    window.TP_DD = {
      generateSheet: generateSheet,
      assertUniqueSolution: assertUniqueSolution,
      codeToDigits: codeToDigits,
      digitsToCode: digitsToCode,
      letterToCode: letterToCode,
      codeToLetter: codeToLetter,
      cleanMessage: cleanMessage,
      messageLetters: messageLetters,
      ceilingDigits: ceilingDigits,
      makeRng: makeRng,
      JOKES: JOKES, PRAISE: PRAISE, ALPHABET: ALPHABET
    };
  }

  /* ====== DOM (browser only) ============================================== */
  if (typeof document === 'undefined') { return; }

  var ACCENT = '#34507a';
  function $(id) { return document.getElementById(id); }

  var state = {
    year: 4,
    difficulty: 3,
    count: 9,
    tab: 'sheet',                 // 'sheet' | 'answers'
    source: 'joke',               // 'joke' | 'word' | 'custom'
    message: '',                  // custom text (when source==='custom')
    seed: (Math.floor(Math.random() * 0xffffffff) >>> 0),
    sheet: null
  };
  var els = {};

  function rebuild(opts) {
    opts = opts || {};
    if (opts.newSeed) { state.seed = (Math.floor(Math.random() * 0xffffffff) >>> 0); }
    state.sheet = generateSheet({
      year: state.year, difficulty: state.difficulty, count: state.count,
      source: state.source, message: state.message, seed: state.seed
    });
    render();
  }

  function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

  // ---- codebook: a clean 2-row A-Z grid, 00=A explicit ---------------------
  function codebookHTML() {
    var html = '<div class="dd-cb-title">Detective’s Codebook</div><div class="dd-cb-grid">';
    for (var n = 0; n < 26; n++) {
      var d2 = (n < 10 ? '0' : '') + n;
      html += '<span class="dd-cb-cell"><b>' + d2 + '</b><i>' + codeToLetter(n) + '</i></span>';
    }
    html += '</div>';
    return html;
  }

  // ---- one card ------------------------------------------------------------
  // `revealed` = answer-key tab (all cards solved). `isExample` = the worked
  // example card (FIX 3): pre-solved even on the worksheet tab, with an "Example"
  // tag. `solved` = this card shows its filled digits + completed strip.
  function cardHTML(item, idx, revealed, isExample) {
    var p = item.qtn, w = p.width, nAdd = p.nAdd;
    var solved = revealed || isExample;
    var carries = new Array(w + 1).fill(0);
    if (solved) {
      var carry = 0;
      for (var col = w - 1; col >= 0; col--) {
        var s = carry;
        for (var rr = 0; rr < nAdd; rr++) { s += item.ans.fullAddends[rr][col]; }
        carry = Math.floor(s / 10);
        carries[col] = carry;
      }
    }

    function labelAt(kind, row, col) {
      for (var b = 0; b < p.blanks.length; b++) {
        var bl = p.blanks[b];
        if (bl.kind === kind && bl.col === col && (kind === 'total' || bl.row === row)) { return bl; }
      }
      return null;
    }

    // FIX 2: blanks inside the sum are PLAIN missing-digit boxes — no in-sum
    // "tens"/"ones" word-tags (they read as column headers and confuse). The
    // only labelled decode area is the bottom strip. On the worked Example card
    // (or the answer key) the digit is filled in.
    function cellSpan(val, blank, isPad) {
      if (isPad) { return '<span class="dd-cell"></span>'; } // structural padding: blank
      if (val === null && blank) {
        if (solved) {
          return '<span class="dd-cell dd-blank dd-solved">' + blank.digit + '</span>';
        }
        return '<span class="dd-cell dd-blank"></span>';
      }
      return '<span class="dd-cell">' + val + '</span>';
    }

    var gridStyle = 'grid-template-columns: 16px repeat(' + w + ', 1fr);';
    var html = '<div class="dd-sum" style="' + gridStyle + '">';

    if (solved) {
      html += '<span class="dd-op"></span>';
      for (var c = 0; c < w; c++) {
        var cin = c < w - 1 ? carries[c + 1] : 0;
        html += '<span class="dd-carry">' + (cin ? '<small>' + cin + '</small>' : '') + '</span>';
      }
    }

    var addLead = p.addLead || [];
    for (var r = 0; r < nAdd; r++) {
      html += (r === nAdd - 1) ? '<span class="dd-op">+</span>' : '<span class="dd-op"></span>';
      var lead = (addLead[r] != null) ? addLead[r] : 0;
      for (c = 0; c < w; c++) {
        html += cellSpan(p.addends[r][c], labelAt('addend', r, c), c < lead);
      }
    }
    html += '<span class="dd-rule" style="grid-column: 1 / ' + (w + 2) + ';"></span>';
    html += '<span class="dd-op"></span>';
    var tLead = (p.totalLead != null) ? p.totalLead : 0;
    for (c = 0; c < w; c++) {
      html += cellSpan(p.total[c], labelAt('total', 0, c), c < tLead);
    }
    html += '</div>';

    // FIX 2: the ONE labelled decode strip — [tens][ones] -> [letter], with the
    // words printed UNDER each box. Filled on the worked example / answer key.
    var strip = '<div class="dd-strip-wrap">' +
      '<div class="dd-strip">' +
        '<span class="dd-strip-cell"><span class="dd-strip-box">' + (solved ? item.ans.tens : '') + '</span><span class="dd-strip-lab">tens</span></span>' +
        '<span class="dd-strip-cell"><span class="dd-strip-box">' + (solved ? item.ans.ones : '') + '</span><span class="dd-strip-lab">ones</span></span>' +
        '<span class="dd-strip-arrow">→</span>' +
        '<span class="dd-strip-cell"><span class="dd-strip-box dd-strip-ltr">' + (solved ? item.ans.letter : '') + '</span><span class="dd-strip-lab">letter</span></span>' +
      '</div>' +
      '<div class="dd-strip-cue">Write the two digits you found, then use the codebook to find the letter.</div>' +
      '</div>';

    // FIX 3: puzzle 1 is a worked Example on every sheet (worksheet + answer key).
    var tag = isExample ? '<div class="dd-card-tag">Example</div>' : '';
    var cls = 'dd-card' + (isExample ? ' dd-card-example' : '');
    return '<figure class="' + cls + '"><div class="dd-card-no">' + (idx + 1) + '</div>' + tag + html + strip + '</figure>';
  }

  // ---- reveal footer: the assembled message --------------------------------
  function revealHTML(revealed) {
    var sheet = state.sheet;
    var html = '<div class="dd-reveal">';
    if (sheet.prompt) {
      html += '<div class="dd-reveal-prompt">' + esc(sheet.prompt) + '</div>';
    }
    html += '<div class="dd-reveal-cap">Decode one letter per puzzle, in order, to reveal the ' +
      (sheet.source === 'joke' ? 'punchline' : (sheet.source === 'word' ? 'secret word' : 'message')) + '.</div>';
    html += '<div class="dd-reveal-row">';
    for (var i = 0; i < sheet.items.length; i++) {
      var ltr = revealed ? sheet.items[i].ans.letter : '';
      html += '<span class="dd-rslot"><span class="dd-rslot-no">' + (i + 1) + '</span>' +
        '<span class="dd-rslot-ltr">' + ltr + '</span></span>';
    }
    html += '</div>';
    if (revealed) {
      html += '<div class="dd-reveal-answer">' + esc(sheet.fullMessage) + '</div>';
    }
    html += '</div>';
    return html;
  }

  function render() {
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    // FIX 5: key-stage label tracks the selected year (Y3-6 are all KS2).
    var ks = state.year <= 2 ? 'KS1' : 'KS2';
    if (els.eyebrowKs) { els.eyebrowKs.textContent = ks + ' · Year ' + state.year; }
    if (els.context) { els.context.textContent = ks + ' · Numeracy'; }
    var revealed = state.tab === 'answers';

    if (els.codebook) { els.codebook.innerHTML = codebookHTML(); }

    var sheet = state.sheet;
    // Use the sheet's ACTUAL puzzle count for layout, not the requested count: a
    // custom message snaps the count to its letter length (FIX 1), so the grid
    // must size to what was generated (slots === puzzles always).
    var actualCount = sheet.items.length;
    var cols = actualCount >= 6 ? 3 : 2;
    els.grid.style.setProperty('--dd-cols', cols);
    var rows = Math.ceil(actualCount / cols);
    // Card height shrinks as rows grow so the codebook + grid + reveal footer all
    // fit one A4 (gotcha 4). 4-row (12-up) sheets need the most compact cards.
    var cardH = rows <= 2 ? 220 : (rows === 3 ? 150 : 100);
    els.grid.style.setProperty('--dd-cardh', cardH + 'px');
    // tighten grid for the densest sheet
    els.grid.style.setProperty('--dd-gap', rows >= 4 ? '8px' : '12px');

    // On the densest (4-row / 12-up) sheets the per-card cue is dropped to keep
    // ONE A4 page; the cue still shows on the worked Example card and in the
    // intro line, so the instruction is never lost.
    els.grid.classList.toggle('dd-dense', rows >= 4);

    var html = '';
    sheet.items.forEach(function (it, i) { html += cardHTML(it, i, revealed, i === 0); });
    els.grid.innerHTML = html;

    if (els.reveal) { els.reveal.innerHTML = revealHTML(revealed); }
    if (els.intro) {
      els.intro.textContent = sheet.prompt
        ? 'Solve each addition, decode the two digits, then read the punchline.'
        : 'Solve each addition, decode the two digits, then read the secret message.';
    }
  }

  // ---- toolbar wiring ------------------------------------------------------
  function setOnState(wrap, attr, val) {
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
    moveThumb(els.diffThumb, $('dd-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }
  function setTab(tab) {
    state.tab = tab;
    moveThumb(els.tabThumb, $('dd-tabs'), '[data-tab]', tab === 'answers' ? 1 : 0);
    render();
  }
  function regen() {
    if (els.spin) { els.spin.style.transform = 'rotate(360deg)'; setTimeout(function () { els.spin.style.transform = 'rotate(0deg)'; }, 500); }
    rebuild({ newSeed: true });
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
    form.append('title', 'Digit Detectives');
    form.append('activity', 'digit-detectives');
    form.append('config', JSON.stringify({
      year: state.year, difficulty: state.difficulty, count: state.count,
      source: state.source, message: state.message, seed: state.seed
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
    if (cfg.year) { state.year = Math.max(3, Math.min(6, cfg.year | 0)); }
    if (cfg.difficulty) { state.difficulty = Math.max(1, Math.min(5, cfg.difficulty | 0)); }
    if (cfg.count) { state.count = cfg.count | 0; }
    if (cfg.source) { state.source = cfg.source; }
    if (cfg.message != null) { state.message = String(cfg.message); }
    if (cfg.seed != null) { state.seed = cfg.seed >>> 0; }
    return true;
  }

  function init() {
    els.grid = $('dd-grid');
    els.codebook = $('dd-codebook');
    els.reveal = $('dd-reveal');
    els.intro = $('dd-intro');
    els.diffThumb = $('dd-difficulty') ? $('dd-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('dd-diff-label');
    els.eyebrowDiff = $('dd-eyebrow-diff');
    els.eyebrowKs = $('dd-eyebrow-ks');
    els.context = $('dd-context');
    els.tabThumb = $('dd-tabs') ? $('dd-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('dd-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('dd-toast');
    els.msg = $('dd-message');
    els.source = $('dd-source');

    var yearEl = $('dd-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var restored = restoreFromSaved();

    var y0 = window.TP_wireYears ? window.TP_wireYears('dd', function (y) { state.year = y; rebuild(); }) : null;
    if (y0 && !restored) { state.year = y0; }
    // reflect restored year on the chip row
    if (restored) {
      var yWrap = $('dd-years');
      if (yWrap) {
        Array.prototype.forEach.call(yWrap.querySelectorAll('[data-yr]'), function (b) {
          b.classList.toggle('chip-on', Number(b.getAttribute('data-yr')) === state.year);
        });
      }
    }

    var cnt = $('dd-count');
    if (cnt) {
      Array.prototype.forEach.call(cnt.querySelectorAll('[data-count]'), function (b) {
        b.addEventListener('click', function () { state.count = Number(b.getAttribute('data-count')); setOnState(cnt, 'data-count', state.count); rebuild(); });
      });
      setOnState(cnt, 'data-count', state.count);
    }

    // reveal source: joke / word + custom message input
    if (els.source) {
      Array.prototype.forEach.call(els.source.querySelectorAll('[data-source]'), function (b) {
        b.addEventListener('click', function () {
          state.source = b.getAttribute('data-source');
          setOnState(els.source, 'data-source', state.source);
          // switching off custom clears the input requirement
          rebuild({ newSeed: true });
        });
      });
      setOnState(els.source, 'data-source', state.source);
    }
    if (els.msg) {
      els.msg.value = state.message;
      els.msg.addEventListener('input', function () {
        state.message = els.msg.value;
        state.source = cleanMessage(state.message) ? 'custom' : state.source;
        if (els.source) { setOnState(els.source, 'data-source', state.source); }
        rebuild();
      });
    }
    var newJoke = $('dd-newjoke');
    if (newJoke) { newJoke.addEventListener('click', function () { rebuild({ newSeed: true }); }); }

    Array.prototype.forEach.call($('dd-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('dd-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('dd-save').addEventListener('click', onSave);
    $('dd-print').addEventListener('click', function () { window.print(); });
    $('dd-regen').addEventListener('click', regen);

    // reflect restored difficulty/count on controls
    moveThumb(els.diffThumb, $('dd-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    if (cnt) { setOnState(cnt, 'data-count', state.count); }

    setTab('sheet');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
