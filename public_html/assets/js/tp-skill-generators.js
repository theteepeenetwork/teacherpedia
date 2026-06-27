/* =============================================================================
   tp-skill-generators.js — skill-level question generators
   -----------------------------------------------------------------------------
   The worksheet builder atomises each White Rose block into its individual
   SKILLS (e.g. "count in steps of 2", "count in steps of 3", … each its own
   row). Every skill needs its own generator so a teacher can pick exactly how
   many questions of each they want, at any year and any attainment band.

   This pack registers those skill generators onto window.TP_GEN (built by
   tp-generators.js, which must load first). Keys here MUST match the "key"
   field assigned to each skill in app/Database/data/framework_skills.json.

   Generators take a difficulty 1..5 (band: below=2, meeting=3, exceeding=4)
   and return { qtn, ans } — and optionally a `qhtml` SVG for visual skills
   (handled via the existing clock/shape generators in tp-generators.js, reused
   by key, so this file stays text-only).
   ========================================================================== */
(function () {
  'use strict';
  var G = window.TP_GEN;
  var H = window.TP_H;
  if (!G || !H) { return; }

  var ri = H.ri, pick = H.pick, gcd = H.gcd, fmt = H.fmt, frac = H.frac,
      words = H.words, toRoman = H.toRoman;
  var shuffle = function (a) { a = a.slice(); for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; } return a; };

  // -------------------------------------------------------------------------
  // COUNTING — fill-in-the-missing-term sequences
  // -------------------------------------------------------------------------
  function seqGen(step, opts) {
    opts = opts || {};
    return function () {
      var start;
      if (opts.neg) { start = ri(-4, 6); }
      else if (opts.fromAny) { start = ri(1, 9) + (step >= 100 ? ri(1, 9) * step : 0); }
      else { start = 0; }
      var n = 6, dir = pick([1, -1]);
      var first = dir > 0 ? start : start + step * (n - 1);
      var terms = [];
      for (var i = 0; i < n; i++) { terms.push(first + dir * step * i); }
      var blank = ri(1, n - 2);
      var shown = terms.map(function (t, i) { return i === blank ? '___' : fmt(t); });
      return { qtn: 'Fill in the missing number:  ' + shown.join(',  '), ans: fmt(terms[blank]) };
    };
  }
  [2, 3, 4, 5, 6, 7, 8, 9, 10, 25, 50, 100, 1000].forEach(function (s) {
    G['count_' + s] = seqGen(s);
  });
  G.count_10_any = seqGen(10, { fromAny: true });
  G.count_across100 = function () {
    var start = ri(94, 98), n = 6, dir = pick([1, -1]);
    var first = dir > 0 ? start : start + (n - 1);
    var terms = []; for (var i = 0; i < n; i++) { terms.push(first + dir * i); }
    var b = ri(1, n - 2), shown = terms.map(function (t, i) { return i === b ? '___' : fmt(t); });
    return { qtn: 'Count across 100. Fill in the gap:  ' + shown.join(',  '), ans: fmt(terms[b]) };
  };
  // Always counts DOWN through zero into negatives, so every sequence actually
  // crosses zero (e.g. 6, 3, 0, ___, -6, -9).
  function negThroughZero() {
    var step = pick([1, 2, 3, 5, 10]);
    var n = 6;
    var startK = ri(2, 3);                 // how many terms above/at zero
    var first = step * startK;             // a positive multiple of step
    var terms = []; for (var i = 0; i < n; i++) { terms.push(first - step * i); }  // descends through 0 to negatives
    var b = ri(1, n - 2);
    var shown = terms.map(function (t, i) { return i === b ? '___' : fmt(t); });
    return { qtn: 'Count backwards through zero. Fill in the gap:  ' + shown.join(',  '), ans: fmt(terms[b]) };
  }
  G.count_negatives = negThroughZero;
  G.count_neg_through0 = negThroughZero;
  // Negative numbers IN CONTEXT, calculating an interval across zero (temperature).
  G.neg_intervals = function () {
    return pick([
      function () { var hi = ri(2, 12), lo = -ri(1, 10); return { qtn: 'The temperature was ' + hi + '°C. It fell to ' + lo + '°C. By how many degrees did it fall?', ans: fmt(hi - lo) + '°C' }; },
      function () { var lo = -ri(1, 9), rise = ri(3, 14); return { qtn: 'At dawn it was ' + lo + '°C. It rose by ' + rise + '°C. What is the new temperature?', ans: fmt(lo + rise) + '°C' }; },
      function () { var a = -ri(1, 8), b = ri(1, 9); return { qtn: 'What is the difference between ' + a + ' and ' + b + '?', ans: fmt(b - a) }; }
    ])();
  };
  G.count_powers10 = function () { var s = pick([10, 100, 1000, 10000, 100000]); return seqGen(s, { fromAny: true })(); };
  G.count_tenths = function () {
    var start = ri(0, 5), n = 6, dir = pick([1, -1]);
    var first = dir > 0 ? start : start + n - 1, terms = [];
    for (var i = 0; i < n; i++) { terms.push(((first + dir * i) / 10).toFixed(1)); }
    var b = ri(1, n - 2), shown = terms.map(function (t, i) { return i === b ? '___' : t; });
    return { qtn: 'Count in tenths. Fill in the gap:  ' + shown.join(',  '), ans: terms[b] };
  };
  G.count_hundredths = function () {
    var start = ri(1, 40), n = 6, terms = [];
    for (var i = 0; i < n; i++) { terms.push(((start + i) / 100).toFixed(2)); }
    var b = ri(1, n - 2), shown = terms.map(function (t, i) { return i === b ? '___' : t; });
    return { qtn: 'Count in hundredths. Fill in the gap:  ' + shown.join(',  '), ans: terms[b] };
  };

  // -------------------------------------------------------------------------
  // MORE / LESS
  // -------------------------------------------------------------------------
  function moreLess(deltas, lo, hi) {
    return function () {
      var d = pick(deltas), more = pick([true, false]), n = ri(lo, hi);
      if (!more && n - d < 0) { more = true; }
      return { qtn: fmt(d) + (more ? ' more than ' : ' less than ') + fmt(n) + ' =', ans: fmt(more ? n + d : n - d) };
    };
  }
  // Band-aware: below stays within 10, exceeding crosses a tens boundary.
  G.one_more = function (d) {
    var n = d <= 2 ? ri(0, 9) : d >= 4 ? ri(1, 9) * 10 - 1 : ri(10, 98);
    return { qtn: '1 more than ' + n + ' =', ans: fmt(n + 1) };
  };
  G.one_less = function (d) {
    var n = d <= 2 ? ri(1, 9) : d >= 4 ? ri(2, 10) * 10 : ri(11, 99);
    return { qtn: '1 less than ' + n + ' =', ans: fmt(n - 1) };
  };
  G.more_less_10_100 = moreLess([10, 100], 100, 900);
  G.more_less_1000 = moreLess([1000], 1000, 9000);

  // -------------------------------------------------------------------------
  // REPRESENT — numerals <-> words, Roman numerals
  // -------------------------------------------------------------------------
  function numeralsGen(max) {
    return function () {
      var n = max <= 20 ? ri(1, 20) : ri(Math.floor(max / 4), max);
      return { qtn: 'Write this number in figures:  ' + words(n), ans: fmt(n) };
    };
  }
  function wordsGen(max) {
    return function () {
      var n = max <= 20 ? ri(1, 20) : ri(Math.floor(max / 4), max);
      return { qtn: 'Write this number in words:  ' + fmt(n), ans: words(n) };
    };
  }
  G.numerals_100 = numeralsGen(100); G.words_20 = wordsGen(20);
  G.numerals_1000 = numeralsGen(1000); G.words_100 = wordsGen(100); G.words_1000 = wordsGen(1000);
  G.roman_to_100 = function () {
    var n = ri(1, 100);
    return pick([true, false])
      ? { qtn: 'Write ' + n + ' in Roman numerals.', ans: toRoman(n) }
      : { qtn: 'Write this Roman numeral as a number:  ' + toRoman(n), ans: fmt(n) };
  };
  G.roman_to_1000 = function () {
    var n = pick([ri(1, 1000), pick([1990, 1999, 2000, 2012, 2024, 1066, 1485])]);
    return pick([true, false])
      ? { qtn: 'Write ' + n + ' in Roman numerals.', ans: toRoman(n) }
      : { qtn: 'Write this Roman numeral as a number:  ' + toRoman(n), ans: fmt(n) };
  };

  // -------------------------------------------------------------------------
  // PLACE VALUE — value of a digit in an N-digit number
  // -------------------------------------------------------------------------
  // "What is the value of the digit X?" only has a single answer when X appears
  // exactly once, so we always pick a digit that is non-zero AND unique in the
  // number (regenerating if a draw has no such digit). lo/hi bound the size so
  // each year stays within its curriculum ceiling.
  function placeValue(lo, hi) {
    return function () {
      var n, s, counts, candidates, guard = 0;
      do {
        n = ri(lo, hi); s = String(n); counts = {};
        for (var k = 0; k < s.length; k++) { counts[s[k]] = (counts[s[k]] || 0) + 1; }
        candidates = [];
        for (var i = 0; i < s.length; i++) { if (s[i] !== '0' && counts[s[i]] === 1) { candidates.push(i); } }
        guard++;
      } while (candidates.length === 0 && guard < 40);
      var idx = candidates.length ? pick(candidates) : s.length - 1;
      var digit = Number(s[idx]);
      var place = digit * Math.pow(10, s.length - 1 - idx);
      return { qtn: 'In ' + fmt(n) + ', what is the value of the digit ' + digit + '?', ans: fmt(place) };
    };
  }
  G.place_2 = placeValue(10, 99); G.place_3 = placeValue(100, 999); G.place_4 = placeValue(1000, 9999);
  G.place_1m = placeValue(100000, 999999);      // Y5: read/write/value to 1,000,000
  G.place_10m = placeValue(1000000, 9999999);   // Y6: read/write/value up to 10,000,000

  // -------------------------------------------------------------------------
  // COMPARE & ORDER
  // -------------------------------------------------------------------------
  function compareGen(hi) {
    return function () {
      var a = ri(0, hi), b = pick([true, false]) ? a : ri(0, hi);
      var sym = a < b ? '<' : a > b ? '>' : '=';
      return { qtn: 'Insert <, > or = :  ' + fmt(a) + ' ___ ' + fmt(b), ans: sym };
    };
  }
  G.compare_100 = compareGen(100); G.compare_1000 = compareGen(1000);
  G.compare_big = compareGen(1000000); G.compare_10m = compareGen(10000000);
  G.order_numbers = function () {
    var hi = pick([9999, 99999]), set = [];   // "beyond 1000": 4- and 5-digit numbers
    while (set.length < 4) { var v = ri(1001, hi); if (set.indexOf(v) === -1) { set.push(v); } }
    var asc = pick([true, false]);
    var sorted = set.slice().sort(function (a, b) { return asc ? a - b : b - a; });
    return { qtn: 'Put these in ' + (asc ? 'ascending' : 'descending') + ' order:  ' + set.map(fmt).join(', '), ans: sorted.map(fmt).join(', ') };
  };

  // -------------------------------------------------------------------------
  // ROUNDING
  // -------------------------------------------------------------------------
  function roundGen(places) {
    return function () {
      var p = pick(places), hi = Math.max(p * 50, 9999);
      var n = ri(p, hi), r = Math.round(n / p) * p;
      return { qtn: 'Round ' + fmt(n) + ' to the nearest ' + fmt(p) + ':', ans: fmt(r) };
    };
  }
  G.round_10_100_1000 = roundGen([10, 100, 1000]);
  G.round_multi = roundGen([10, 100, 1000, 10000, 100000]);
  G.round_accuracy = roundGen([10, 100, 1000, 10000, 100000, 1000000]);
  G.round_dp1 = function () { var n = ri(1, 199) / 10; return { qtn: 'Round ' + n.toFixed(1) + ' to the nearest whole number:', ans: fmt(Math.round(n)) }; };
  G.round_dp2 = function () {
    var n = ri(1, 1999) / 100;
    // Objective covers rounding 2 d.p. to the nearest whole AND to 1 d.p.
    return pick([true, false])
      ? { qtn: 'Round ' + n.toFixed(2) + ' to the nearest whole number:', ans: fmt(Math.round(n)) }
      : { qtn: 'Round ' + n.toFixed(2) + ' to 1 decimal place:', ans: (Math.round(n * 10) / 10).toFixed(1) };
  };

  // -------------------------------------------------------------------------
  // ADDITION & SUBTRACTION
  // -------------------------------------------------------------------------
  // genB is often structurally constrained (a multiple of ten, a single digit).
  // For subtraction we must NOT swap a and b to avoid a negative — that would
  // turn "78 − 30" into "30 − 78"→swap→"78 − 30" fine, but "18 − 50" would swap
  // to "50 − 18", destroying the "and tens" structure. Instead we re-draw the
  // minuend until it is >= the subtrahend, preserving genB's shape.
  function addSub(genA, genB, opts) {
    opts = opts || {};
    return function () {
      var op = opts.add === true ? '+' : opts.add === false ? '−' : pick(['+', '−']);
      var a = genA(), b = genB();
      if (op === '−' && a < b) {
        var guard = 0;
        while (a < b && guard < 50) { a = genA(); guard++; }
        if (a < b) { a = b + (a % 10); }   // last-resort: keep b's shape, a >= b
      }
      // Optional cap on an addition result (e.g. keep "2-digit + tens" within 100).
      if (op === '+' && opts.cap) {
        var g2 = 0;
        while (a + b > opts.cap && g2 < 50) { a = genA(); b = genB(); g2++; }
        if (a + b > opts.cap) { b = Math.max(0, opts.cap - a); }
      }
      return { qtn: fmt(a) + ' ' + op + ' ' + fmt(b) + ' =', ans: fmt(op === '+' ? a + b : a - b) };
    };
  }
  // "to 20": total within 20 (within 10 at the below band).
  G.add_to_20 = function (d) { var cap = d <= 2 ? 10 : 20; var a = ri(0, cap), b = ri(0, cap - a); return { qtn: a + ' + ' + b + ' =', ans: fmt(a + b) }; };
  G.sub_to_20 = function (d) { var cap = d <= 2 ? 10 : 20; var a = ri(0, cap), b = ri(0, a); return { qtn: a + ' − ' + b + ' =', ans: fmt(a - b) }; };
  G.addsub_2d_ones = addSub(function () { return ri(11, 99); }, function () { return ri(1, 9); }, { cap: 99 });
  G.addsub_2d_tens = addSub(function () { return ri(11, 89); }, function () { return ri(1, 9) * 10; }, { cap: 99 });
  G.addsub_2d_2d = addSub(function () { return ri(11, 99); }, function () { return ri(11, 99); }, { cap: 99 });
  G.add_three_1d = function () { var a = ri(1, 9), b = ri(1, 9), c = ri(1, 9); return { qtn: a + ' + ' + b + ' + ' + c + ' =', ans: fmt(a + b + c) }; };
  G.addsub_3d_ones = addSub(function () { return ri(101, 999); }, function () { return ri(1, 9); });
  G.addsub_3d_tens = addSub(function () { return ri(101, 899); }, function () { return ri(1, 9) * 10; });
  G.addsub_3d_hundreds = addSub(function () { return ri(101, 899); }, function () { return ri(1, 9) * 100; });
  G.col_addsub_3d = addSub(function () { return ri(100, 999); }, function () { return ri(100, 999); });
  G.col_add_4d = addSub(function () { return ri(1000, 9999); }, function () { return ri(1000, 9999); }, { add: true });
  G.col_sub_4d = addSub(function () { return ri(1000, 9999); }, function () { return ri(1000, 9999); }, { add: false });
  G.formal_addsub_5d = addSub(function () { return ri(10000, 999999); }, function () { return ri(10000, 999999); });
  G.mental_addsub_large = function () { var a = ri(1000, 9000), b = ri(100, 900), c = ri(100, 900); return { qtn: fmt(a) + ' + ' + fmt(b) + ' − ' + fmt(c) + ' =', ans: fmt(a + b - c) }; };
  G.bonds_10 = function () { var n = ri(0, 10); return { qtn: n + ' + ___ = 10', ans: fmt(10 - n) }; };
  G.bonds_20 = function () { var n = ri(0, 20); return { qtn: n + ' + ___ = 20', ans: fmt(20 - n) }; };
  G.bonds_100 = function () { var n = ri(0, 10) * 10; return { qtn: fmt(n) + ' + ___ = 100', ans: fmt(100 - n) }; };

  // missing-number / inverse. Difficulty scales the size: below (d<=2) stays
  // within 10, meeting/exceeding grow into 2- and 3-digit place-value problems.
  G.missing_number = function (d) {
    if (d <= 2) { var total = ri(5, 10), a = ri(1, total - 1); return { qtn: a + ' + ___ = ' + total, ans: fmt(total - a) }; }   // within 10
    if (d >= 4) { var a4 = ri(100, 300), b4 = ri(20, 90); return { qtn: fmt(a4) + ' + ___ = ' + fmt(a4 + b4), ans: fmt(b4) }; }
    var am = ri(10, 50), bm = ri(5, 40); return { qtn: am + ' + ___ = ' + fmt(am + bm), ans: fmt(bm) };
  };
  // × / ÷ missing-number (Multiplication & division block objective).
  // Year 3 facts: the 3, 4, 8 (and 2, 5, 10) tables — no 11/12.
  G.missing_number_md = function () {
    var f = pick([2, 3, 4, 5, 8, 10]), m = ri(2, 10), p = f * m;
    return pick([
      function () { return { qtn: f + ' × ___ = ' + fmt(p), ans: fmt(m) }; },
      function () { return { qtn: '___ × ' + m + ' = ' + fmt(p), ans: fmt(f) }; },
      function () { return { qtn: fmt(p) + ' ÷ ___ = ' + f, ans: fmt(m) }; },
      function () { return { qtn: fmt(p) + ' ÷ ' + f + ' = ___', ans: fmt(m) }; }
    ])();
  };
  G.missing_number_sub = function () { var a = ri(5, 20), b = ri(1, a); return { qtn: '___ − ' + b + ' = ' + (a - b), ans: fmt(a) }; };
  // Year 2 inverse-to-check: keep totals within 100.
  G.inverse_check = function () { var c = ri(20, 99), b = ri(1, c - 1), a = c - b; return { qtn: 'If ' + a + ' + ' + b + ' = ' + c + ', what is ' + c + ' − ' + b + '?', ans: fmt(a) }; };

  // word problems
  G.word_one_step = function () {
    return pick([
      function () { var a = ri(5, 30), b = ri(3, 20); return { qtn: 'Sam has ' + a + ' stickers and gets ' + b + ' more. How many now?', ans: fmt(a + b) }; },
      function () { var a = ri(12, 40), b = ri(3, a - 1); return { qtn: 'There are ' + a + ' apples. ' + b + ' are eaten. How many are left?', ans: fmt(a - b) }; }
    ])();
  };
  G.word_two_step = function () {
    var a = ri(20, 60), b = ri(10, 30), c = ri(5, 20);
    return { qtn: 'A box has ' + a + ' pens. ' + b + ' more are added, then ' + c + ' are taken out. How many pens now?', ans: fmt(a + b - c) };
  };
  G.word_multi_step = function () {
    var packs = ri(3, 8), per = ri(4, 9), used = ri(2, 10);
    return { qtn: packs + ' packs of ' + per + ' cakes are bought and ' + used + ' cakes are eaten. How many cakes are left?', ans: fmt(packs * per - used) };
  };

  // -------------------------------------------------------------------------
  // MULTIPLICATION & DIVISION
  // -------------------------------------------------------------------------
  function tableGen(t) {
    return function () {
      var m = ri(1, 12);
      return pick([true, false])
        ? { qtn: t + ' × ' + m + ' =', ans: fmt(t * m) }
        : { qtn: (t * m) + ' ÷ ' + t + ' =', ans: fmt(m) };
    };
  }
  [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].forEach(function (t) { G['table_' + t] = tableGen(t); });
  G.tables_to_12 = function () { return tableGen(ri(2, 12))(); };
  // Year 1 division: small sharing/grouping within the 2, 5 and 10 counts
  // (dividend kept within ~20).
  G.divide_simple = function () { var t = pick([2, 5, 10]), m = ri(1, Math.floor(20 / t)); return { qtn: (t * m) + ' ÷ ' + t + ' =', ans: fmt(m) }; };
  // Year 1 multiplication: small equal groups of 2, 5 or 10.
  G.mult_groups = function () { var t = pick([2, 5, 10]), m = ri(1, t === 10 ? 3 : t === 5 ? 6 : 10); return { qtn: m + ' × ' + t + ' =', ans: fmt(m * t) }; };
  G.odd_even = function () { var n = ri(1, 99); return { qtn: 'Is ' + n + ' odd or even?', ans: n % 2 === 0 ? 'even' : 'odd' }; };
  // Distinct factors so swapping the order is a real commutativity step.
  G.commutativity = function () { var a = ri(2, 9), b = ri(2, 9); while (b === a) { b = ri(2, 9); } return { qtn: a + ' × ' + b + ' = ' + b + ' × ___', ans: fmt(a) }; };
  // "Known tables" at KS1 = the 2, 5 and 10 times tables.
  G.mult_statements = function () { var t = pick([2, 5, 10]), m = ri(1, 12); return { qtn: t + ' × ' + m + ' =', ans: fmt(t * m) }; };
  G.mult_2dx1d = function () { var a = ri(11, 99), b = ri(2, 9); return { qtn: a + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.mult_3dx1d = function () { var a = ri(100, 999), b = ri(2, 9); return { qtn: fmt(a) + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.mult_three = function () { var a = ri(2, 6), b = ri(2, 6), c = ri(2, 5); return { qtn: a + ' × ' + b + ' × ' + c + ' =', ans: fmt(a * b * c) }; };
  // Mental ×/÷ from known facts — mostly real table facts, with the occasional
  // ×0 / ×1 / ÷1 special case (named explicitly in the Year 4 objective).
  G.mental_md = function () {
    return pick([
      function () { var a = ri(2, 12), b = ri(2, 12); return { qtn: a + ' × ' + b + ' =', ans: fmt(a * b) }; },
      function () { var a = ri(2, 12), b = ri(2, 12); return { qtn: (a * b) + ' ÷ ' + a + ' =', ans: fmt(b) }; },
      function () { var a = ri(2, 12), b = ri(2, 9); return { qtn: (a * 10) + ' × ' + b + ' =', ans: fmt(a * 10 * b) }; },
      function () { var a = ri(2, 12), s = pick([0, 1]); return { qtn: a + ' × ' + s + ' =', ans: fmt(a * s) }; }
    ])();
  };
  G.long_mult = function () { var a = ri(1000, 9999), b = ri(11, 99); return { qtn: fmt(a) + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.long_mult_4x2 = G.long_mult;
  G.short_div = function () { var b = ri(2, 9), q = ri(100, 999), r = ri(0, b - 1); return { qtn: fmt(b * q + r) + ' ÷ ' + b + ' =', ans: r ? fmt(q) + ' r ' + r : fmt(q) }; };
  G.short_div_2digit = function () { var b = ri(11, 25), q = ri(50, 400); return { qtn: fmt(b * q) + ' ÷ ' + b + ' =', ans: fmt(q) }; };
  G.long_div = function () { var b = ri(11, 40), q = ri(50, 250), r = ri(0, b - 1); return { qtn: fmt(b * q + r) + ' ÷ ' + b + ' =', ans: r ? fmt(q) + ' r ' + r : fmt(q) }; };
  G.md_10_100_1000 = function () { var p = pick([10, 100, 1000]), a = ri(2, 99); return pick([true, false]) ? { qtn: fmt(a) + ' × ' + p + ' =', ans: fmt(a * p) } : { qtn: fmt(a * p) + ' ÷ ' + p + ' =', ans: fmt(a) }; };

  G.factors = function () { var n = ri(12, 48); var f = []; for (var i = 1; i <= n; i++) { if (n % i === 0) { f.push(i); } } return { qtn: 'List all the factors of ' + n + '.', ans: f.join(', ') }; };
  G.multiples = function () { var n = ri(3, 9), k = ri(3, 6); var m = []; for (var i = 1; i <= k; i++) { m.push(n * i); } return { qtn: 'Write the first ' + k + ' multiples of ' + n + '.', ans: m.join(', ') }; };
  // Build both numbers from a shared factor so the pair has real common factors
  // (not just 1) to find.
  G.common_factors = function () {
    var g = ri(2, 6), a = g * ri(2, 6), b = g * ri(2, 6);
    while (b === a) { b = g * ri(2, 6); }   // two DISTINCT numbers
    var f = []; for (var i = 1; i <= Math.min(a, b); i++) { if (a % i === 0 && b % i === 0) { f.push(i); } }
    return { qtn: 'Find the common factors of ' + a + ' and ' + b + '.', ans: f.join(', ') };
  };
  var isPrime = function (x) { if (x < 2) { return false; } for (var i = 2; i * i <= x; i++) { if (x % i === 0) { return false; } } return true; };
  // Balance prime and composite candidates so "always no" can't score full marks.
  G.primes_test = function () {
    var primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97];
    var n = pick([true, false]) ? pick(primes) : ri(4, 100);
    return { qtn: 'Is ' + n + ' a prime number? (yes / no)', ans: isPrime(n) ? 'yes' : 'no' };
  };
  G.prime_vocab = function () { var n = pick([4, 6, 8, 9, 10, 12, 14, 15, 16, 18, 20, 21, 22, 24, 25]); var f = []; var m = n; for (var d = 2; d <= m; d++) { while (m % d === 0) { f.push(d); m /= d; } } return { qtn: 'Write ' + n + ' as a product of its prime factors.', ans: f.join(' × ') }; };
  G.square_numbers = function () { var n = ri(2, 12); return { qtn: n + '² =', ans: fmt(n * n) }; };
  G.cube_numbers = function () { var n = ri(2, 6); return { qtn: n + '³ =', ans: fmt(n * n * n) }; };
  G.hcf = function () { var a = ri(8, 40), b = ri(8, 40); return { qtn: 'Find the highest common factor (HCF) of ' + a + ' and ' + b + '.', ans: fmt(gcd(a, b)) }; };
  G.lcm = function () { var a = ri(2, 12), b = ri(2, 12); return { qtn: 'Find the lowest common multiple (LCM) of ' + a + ' and ' + b + '.', ans: fmt(a * b / gcd(a, b)) }; };
  G.scaling = function () { var a = ri(2, 12), s = ri(2, 6); return { qtn: 'A ribbon is ' + a + ' cm. A second ribbon is ' + s + ' times as long. How long is it?', ans: fmt(a * s) + ' cm' }; };
  G.correspondence = function () { var a = ri(2, 5), b = ri(2, 5); return { qtn: a + ' hats and ' + b + ' scarves. How many different hat-and-scarf combinations?', ans: fmt(a * b) }; };
  G.distributive = function () { var a = ri(2, 9), b = ri(10, 20); return { qtn: a + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.four_ops = function () { var a = ri(4, 12), b = ri(2, 9), c = ri(2, 9); return { qtn: a + ' × ' + b + ' + ' + c + ' =', ans: fmt(a * b + c) }; };
  G.order_of_operations = function () { var a = ri(2, 9), b = ri(2, 9), c = ri(2, 9); return { qtn: a + ' + ' + b + ' × ' + c + ' =', ans: fmt(a + b * c) }; };
  G.estimate_calc = function () { var a = ri(100, 9999), b = ri(100, 9999); var ra = Math.round(a / 100) * 100, rb = Math.round(b / 100) * 100; return { qtn: 'Estimate by rounding to the nearest 100:  ' + fmt(a) + ' + ' + fmt(b) + ' ≈', ans: fmt(ra + rb) }; };

  // -------------------------------------------------------------------------
  // FRACTIONS
  // -------------------------------------------------------------------------
  function fracOf(n, d) {
    return function () { var k = ri(2, 12) * d; return { qtn: frac(n, d) + ' of ' + k + ' =', ans: fmt(k * n / d) }; };
  }
  G.frac_half = fracOf(1, 2); G.frac_quarter = fracOf(1, 4); G.frac_third = fracOf(1, 3);
  G.frac_threeq = fracOf(3, 4);
  // 2/4 must be named and used (not collapsed to 1/2) per the Year 2 objective.
  G.frac_twoq = function () { var k = ri(2, 12) * 4; return { qtn: '2/4 of ' + k + ' =', ans: fmt(k * 2 / 4) }; };
  // Year 2 equivalence is specifically 2/4 = 1/2.
  G.frac_equiv_half = function () {
    return pick([
      function () { return { qtn: 'Complete:  2/4 = ___/2', ans: '1' }; },
      function () { return { qtn: 'Complete:  1/2 = ___/4', ans: '2' }; },
      function () { var k = ri(2, 10) * 2; return { qtn: 'True or false?  2/4 of ' + k + ' is the same as 1/2 of ' + k + '.', ans: 'true' }; }
    ])();
  };
  G.frac_unit_set = function () { var d = pick([2, 3, 4, 5, 6, 10]); var k = d * ri(2, 8); return { qtn: '1/' + d + ' of ' + k + ' =', ans: fmt(k / d) }; };
  G.frac_nonunit_set = function () { var d = pick([3, 4, 5, 6, 8, 10]); var n = ri(2, d - 1); var k = d * ri(2, 8); return { qtn: frac(n, d) + ' of ' + k + ' =', ans: fmt(k * n / d) }; };
  G.frac_simple = function () { var d = pick([2, 3, 4, 5]); var k = d * ri(2, 6); return { qtn: '1/' + d + ' of ' + k + ' =', ans: fmt(k / d) }; };
  G.frac_equiv = function () { var d = pick([2, 3, 4, 5]); var k = ri(2, 4); return { qtn: 'Complete the equivalent fraction:  1/' + d + ' = ___/' + (d * k), ans: fmt(k) }; };
  G.frac_equiv_family = function () { var d = pick([2, 3, 4, 5]), n = 1, k = ri(2, 5); return { qtn: 'Write a fraction equivalent to ' + n + '/' + d + '.', ans: (n * k) + '/' + (d * k) }; };
  G.frac_simplify = function () { var g = ri(2, 6), d = ri(2, 6), n = ri(1, d - 1); return { qtn: 'Simplify the fraction ' + (n * g) + '/' + (d * g) + '.', ans: frac(n * g, d * g) }; };
  // Same denominator, "within one whole". The answer keeps the SAME denominator
  // (the natural result of same-denominator working), so there is no
  // simplified-vs-unsimplified ambiguity.
  G.frac_addsub_same = function () {
    var d = pick([4, 5, 6, 8, 10]);
    var op = pick(['+', '−']);
    var a, b;
    if (op === '+') { a = ri(1, d - 1); b = ri(1, d - a); }   // a + b <= d
    else { a = ri(1, d - 1); b = ri(1, a); }                   // a - b >= 0
    var num = op === '+' ? a + b : a - b;
    var ans = num === 0 ? '0' : num === d ? '1' : num + '/' + d;
    return { qtn: a + '/' + d + ' ' + op + ' ' + b + '/' + d + ' =', ans: ans };
  };
  G.frac_addsub_related = function () { var d = pick([2, 3, 4]); var d2 = d * pick([2, 3]); var a = ri(1, d - 1), b = ri(1, d2 - 1); return { qtn: a + '/' + d + ' + ' + b + '/' + d2 + ' =', ans: frac(a * (d2 / d) + b, d2) }; };
  G.frac_addsub_diff = function () { var d1 = pick([2, 3, 4]), d2 = pick([3, 5, 6]); var a = ri(1, d1 - 1), b = ri(1, d2 - 1); var L = d1 * d2 / gcd(d1, d2); return { qtn: a + '/' + d1 + ' + ' + b + '/' + d2 + ' =', ans: frac(a * (L / d1) + b * (L / d2), L) }; };
  // Express a fraction over a common denominator (Year 6 "Common denomination").
  G.frac_common_denom = function () {
    var d1 = pick([2, 3, 4]), mult = pick([2, 3, 4]), d2 = d1 * mult;
    var n = ri(1, d1 - 1);
    return { qtn: 'Write ' + n + '/' + d1 + ' with a denominator of ' + d2 + ':  ' + n + '/' + d1 + ' = ___/' + d2, ans: fmt(n * mult) + '/' + d2 };
  };
  G.frac_mult_whole = function () { var d = pick([2, 3, 4, 5]); var n = ri(1, d - 1), w = ri(2, 6); return { qtn: n + '/' + d + ' × ' + w + ' =', ans: frac(n * w, d) }; };
  G.frac_mult_pair = function () { var d1 = pick([2, 3, 4]), d2 = pick([2, 3, 5]); var n1 = ri(1, d1 - 1), n2 = ri(1, d2 - 1); return { qtn: n1 + '/' + d1 + ' × ' + n2 + '/' + d2 + ' =', ans: frac(n1 * n2, d1 * d2) }; };
  G.frac_div_whole = function () { var d = pick([2, 3, 4]); var n = ri(1, d - 1), w = ri(2, 5); return { qtn: '(' + n + '/' + d + ') ÷ ' + w + ' =', ans: frac(n, d * w) }; };
  G.mixed_improper = function () { var d = pick([2, 3, 4, 5]); var whole = ri(1, 4), n = ri(1, d - 1); var imp = whole * d + n; return pick([true, false]) ? { qtn: 'Write ' + whole + ' ' + n + '/' + d + ' as an improper fraction.', ans: imp + '/' + d } : { qtn: 'Write ' + imp + '/' + d + ' as a mixed number.', ans: whole + ' ' + n + '/' + d }; };
  // Compare fractions with DIFFERENT (related) denominators, sometimes > 1.
  G.frac_compare = function () {
    var base = pick([2, 3, 4, 5]);
    var d1 = base, d2 = base * pick([2, 3]);
    var hi = pick([true, false]) ? d1 : d1 + 2;            // sometimes improper (> 1)
    var n1 = ri(1, hi), n2 = ri(1, d2);
    var v1 = n1 / d1, v2 = n2 / d2;
    var sym = v1 < v2 ? '<' : v1 > v2 ? '>' : '=';
    return { qtn: 'Insert <, > or = :  ' + n1 + '/' + d1 + ' ___ ' + n2 + '/' + d2, ans: sym };
  };
  // Year 3 "fractions as numbers" — compare/order on the number line, not decimals.
  var FRAC_WORD = { 2: 'halves', 3: 'thirds', 4: 'quarters', 5: 'fifths', 6: 'sixths', 8: 'eighths', 10: 'tenths' };
  G.frac_as_number = function () {
    var d = pick([3, 4, 5, 6, 8, 10]);
    return pick([
      function () { var a = ri(1, d - 1), b = ri(1, d - 1); while (b === a) { b = ri(1, d - 1); } var sym = a < b ? '<' : '>'; return { qtn: 'Insert < or > :  ' + a + '/' + d + ' ___ ' + b + '/' + d, ans: sym }; },
      function () { var k = ri(1, d - 2); return { qtn: 'Counting in ' + FRAC_WORD[d] + ', what comes next?  ' + k + '/' + d + ', ___', ans: (k + 1) + '/' + d }; }
    ])();
  };
  // Year 3 compare: unit fractions, or fractions with the SAME denominator (within 1).
  G.frac_compare_simple = function () {
    return pick([
      function () { var a = pick([2, 3, 4, 5, 6, 8]), b = pick([2, 3, 4, 5, 6, 8]); while (b === a) { b = pick([2, 3, 4, 5, 6, 8]); } var sym = (1 / a) < (1 / b) ? '<' : '>'; return { qtn: 'Insert < or > :  1/' + a + ' ___ 1/' + b, ans: sym }; },
      function () { var d = pick([4, 5, 6, 8, 10]); var a = ri(1, d - 1), b = ri(1, d - 1); while (b === a) { b = ri(1, d - 1); } var sym = a < b ? '<' : '>'; return { qtn: 'Insert < or > :  ' + a + '/' + d + ' ___ ' + b + '/' + d, ans: sym }; }
    ])();
  };

  // -------------------------------------------------------------------------
  // DECIMALS & PERCENTAGES
  // -------------------------------------------------------------------------
  G.dec_tenths_hundredths = function () { var n = ri(1, 99); return { qtn: 'Write ' + n + ' hundredths as a decimal.', ans: fmt(n / 100) }; };
  G.dec_equiv_quarter = function () { var f = pick([[1, 4], [1, 2], [3, 4]]); return { qtn: 'Write ' + f[0] + '/' + f[1] + ' as a decimal.', ans: fmt(f[0] / f[1]) }; };
  G.dec_as_fraction = function () { var n = ri(1, 99); return { qtn: 'Write ' + (n / 100).toFixed(2) + ' as a fraction (over 100).', ans: n + '/100' }; };
  G.dec_thousandths = function () { var n = ri(1, 999); return { qtn: 'Write ' + n + ' thousandths as a decimal.', ans: (n / 1000).toFixed(3) }; };
  // Mostly distinct decimals (only occasionally equal) so the answer isn't almost always "=".
  G.dec_compare = function () { var a = ri(1, 999) / 100, b = (Math.random() < 0.15) ? a : ri(1, 999) / 100; var sym = a < b ? '<' : a > b ? '>' : '='; return { qtn: 'Insert <, > or = :  ' + a.toFixed(2) + ' ___ ' + b.toFixed(2), ans: sym }; };
  G.dec_order_3dp = function () { var set = []; while (set.length < 3) { var v = ri(1, 9999) / 1000; if (set.indexOf(v) === -1) { set.push(v); } } var sorted = set.slice().sort(function (a, b) { return a - b; }); return { qtn: 'Put in order, smallest first:  ' + set.map(function (x) { return x.toFixed(3); }).join(', '), ans: sorted.map(function (x) { return x.toFixed(3); }).join(', ') }; };
  G.digit_value_3dp = function () { var n = ri(1, 9) + ri(1, 999) / 1000; var s = n.toFixed(3); var places = [{ name: 'tenths', i: 2, v: 0.1 }, { name: 'hundredths', i: 3, v: 0.01 }, { name: 'thousandths', i: 4, v: 0.001 }]; var p = pick(places); return { qtn: 'In ' + s + ', what is the value of the digit in the ' + p.name + ' place?', ans: fmt(Number(s[p.i]) * p.v) }; };
  G.percent_understand = function () { var n = pick([10, 20, 25, 40, 50, 60, 75]); return { qtn: 'Write ' + n + '% as a fraction over 100 (simplified).', ans: frac(n, 100) }; };
  G.percent_of = function () { var p = pick([10, 20, 25, 50]); var base = pick([20, 40, 60, 80, 100, 200]); return { qtn: 'Find ' + p + '% of ' + base + '.', ans: fmt(base * p / 100) }; };
  G.fdp_equiv = function () { var f = pick([[1, 2, 0.5, 50], [1, 4, 0.25, 25], [3, 4, 0.75, 75], [1, 10, 0.1, 10], [1, 5, 0.2, 20]]); return { qtn: 'Write ' + f[0] + '/' + f[1] + ' as a decimal and a percentage.', ans: fmt(f[2]) + ' = ' + f[3] + '%' }; };
  G.fraction_to_decimal = function () { var d = pick([2, 4, 5, 8, 10]); var n = ri(1, d - 1); return { qtn: 'Use division to write ' + n + '/' + d + ' as a decimal.', ans: fmt(n / d) }; };

  // -------------------------------------------------------------------------
  // MONEY
  // -------------------------------------------------------------------------
  G.money_change = function () { var price = ri(15, 95), paid = pick([100, 200, 500]); return { qtn: 'You buy an item for ' + price + 'p and pay with ' + (paid === 100 ? '£1' : '£' + paid / 100) + '. How much change?', ans: fmt(paid - price) + 'p' }; };
  G.money_combine = function () { var a = pick([5, 10, 20, 50]), b = pick([1, 2, 5, 10, 20]); return { qtn: 'What is the total of a ' + a + 'p coin and a ' + b + 'p coin?', ans: fmt(a + b) + 'p' }; };
  // "Different combinations of coins for the same amount" — a single-answer proxy.
  G.coin_combinations = function () { var small = pick([1, 2, 5, 10]), big = small * pick([2, 3, 4, 5]); return { qtn: 'How many ' + small + 'p coins have the same value as ' + big + 'p?', ans: fmt(big / small) }; };
  // Change can never be negative: pick the smallest note that covers the cost.
  G.money_problems = function () {
    var items = ri(2, 6), price = ri(2, 9), total = items * price;
    var notes = [5, 10, 20, 50];
    var paid = notes.filter(function (x) { return x >= total; })[0] || (Math.ceil(total / 10) * 10);
    return { qtn: items + ' pens cost £' + price + ' each. Pay with £' + paid + '. How much change?', ans: '£' + fmt(paid - total) };
  };
  G.money_pounds = function () { var p = ri(120, 980); return { qtn: 'Write ' + p + 'p in pounds.', ans: '£' + (p / 100).toFixed(2) }; };
  // Money problems involving decimals to 2 d.p. (Year 4 FDP measure/money).
  G.money_decimal = function () {
    return pick([
      function () { var items = ri(2, 5), price = (ri(120, 480) / 100); var total = +(items * price).toFixed(2); var paid = Math.ceil(total / 5) * 5; return { qtn: items + ' pens cost £' + price.toFixed(2) + ' each. Pay with £' + paid + '. How much change?', ans: '£' + (paid - total).toFixed(2) }; },
      function () { var a = ri(150, 950) / 100, b = ri(50, 400) / 100; return { qtn: '£' + a.toFixed(2) + ' + £' + b.toFixed(2) + ' =', ans: '£' + (a + b).toFixed(2) }; }
    ])();
  };

  // -------------------------------------------------------------------------
  // TIME FACTS / CONVERSIONS (clock-reading skills reuse the SVG keys)
  // -------------------------------------------------------------------------
  G.time_facts = function () { return pick([function () { return { qtn: 'How many minutes are there in an hour?', ans: '60' }; }, function () { return { qtn: 'How many hours are there in a day?', ans: '24' }; }, function () { var h = ri(2, 6); return { qtn: 'How many minutes are there in ' + h + ' hours?', ans: fmt(h * 60) }; }])(); };
  G.time_facts3 = function () { return pick([function () { return { qtn: 'How many seconds are there in a minute?', ans: '60' }; }, function () { return { qtn: 'How many days are there in a leap year?', ans: '366' }; }, function () { var m = pick(['September', 'April', 'June', 'November']); return { qtn: 'How many days are there in ' + m + '?', ans: '30' }; }])(); };
  G.time_convert = function () { return pick([function () { var h = ri(2, 6); return { qtn: 'Convert ' + h + ' hours to minutes.', ans: fmt(h * 60) }; }, function () { var m = ri(2, 8); return { qtn: 'Convert ' + m + ' minutes to seconds.', ans: fmt(m * 60) }; }, function () { var y = ri(2, 6); return { qtn: 'Convert ' + y + ' years to months.', ans: fmt(y * 12) }; }])(); };

  // -------------------------------------------------------------------------
  // MEASUREMENT — metric conversions, perimeter / area / volume
  // -------------------------------------------------------------------------
  var litre = function (n) { return n === 1 ? '1 litre' : n + ' litres'; };
  // Year 3 measures objective: measure, COMPARE, ADD and SUBTRACT (not only convert).
  G.convert_length = function () {
    return pick([
      function () { var m = ri(1, 9); return { qtn: 'Convert ' + m + ' m to cm.', ans: fmt(m * 100) + ' cm' }; },
      function () { var cm = ri(2, 9); return { qtn: 'Convert ' + cm + ' cm to mm.', ans: fmt(cm * 10) + ' mm' }; },
      function () { var a = ri(20, 80), b = ri(10, 60); return { qtn: a + ' cm + ' + b + ' cm =', ans: fmt(a + b) + ' cm' }; },
      function () { var a = ri(50, 95), b = ri(10, 45); return { qtn: a + ' cm − ' + b + ' cm =', ans: fmt(a - b) + ' cm' }; },
      function () { var a = ri(2, 9), b = ri(2, 9); var sym = a < b ? '<' : a > b ? '>' : '='; return { qtn: 'Insert <, > or = :  ' + a + ' m ___ ' + b + ' m', ans: sym }; }
    ])();
  };
  G.convert_mass = function () {
    return pick([
      function () { var kg = ri(1, 9); return { qtn: 'Convert ' + kg + ' kg to g.', ans: fmt(kg * 1000) + ' g' }; },
      function () { var a = ri(100, 600), b = ri(100, 350); return { qtn: a + ' g + ' + b + ' g =', ans: fmt(a + b) + ' g' }; },
      function () { var a = ri(400, 900), b = ri(100, 350); return { qtn: a + ' g − ' + b + ' g =', ans: fmt(a - b) + ' g' }; }
    ])();
  };
  G.convert_capacity = function () {
    return pick([
      function () { var l = ri(1, 9); return { qtn: 'Convert ' + litre(l) + ' to ml.', ans: fmt(l * 1000) + ' ml' }; },
      function () { var a = ri(100, 600), b = ri(100, 350); return { qtn: a + ' ml + ' + b + ' ml =', ans: fmt(a + b) + ' ml' }; },
      function () { var a = ri(400, 900), b = ri(100, 350); return { qtn: a + ' ml − ' + b + ' ml =', ans: fmt(a - b) + ' ml' }; }
    ])();
  };
  G.convert_metric = function () { return pick([function () { var m = ri(1, 9); return { qtn: 'Convert ' + m + ' m to cm.', ans: fmt(m * 100) + ' cm' }; }, function () { var kg = ri(1, 9); return { qtn: 'Convert ' + kg + ' kg to g.', ans: fmt(kg * 1000) + ' g' }; }, function () { var l = ri(1, 9); return { qtn: 'Convert ' + litre(l) + ' to ml.', ans: fmt(l * 1000) + ' ml' }; }])(); };
  // Imperial equivalences named in the objective: inches, pounds, pints (plus miles/km).
  G.convert_miles_km = function () {
    return pick([
      function () { var mi = ri(1, 10) * 5; return { qtn: 'Using 5 miles ≈ 8 km, convert ' + mi + ' miles to km.', ans: fmt(mi / 5 * 8) + ' km' }; },
      function () { var inch = ri(2, 12); return { qtn: 'Using 1 inch ≈ 2.5 cm, about how many cm is ' + inch + ' inches?', ans: fmt(inch * 2.5) + ' cm' }; },
      function () { var lb = ri(2, 11); return { qtn: 'Using 1 pound ≈ 450 g, about how many grams is ' + lb + ' pounds?', ans: fmt(lb * 450) + ' g' }; },
      function () { var pt = ri(2, 8); return { qtn: 'Using 1 pint ≈ 568 ml, about how many ml is ' + pt + ' pints?', ans: fmt(pt * 568) + ' ml' }; }
    ])();
  };
  // Year 2 "choose and use sensible units" — estimate, not convert.
  G.estimate_length = function () { var o = pick([['a pencil', '15 cm'], ['a door', '2 m'], ['a football pitch', '100 m'], ['a finger', '6 cm'], ['a bus', '10 m']]); var wrong = o[1].indexOf('cm') > -1 ? o[1].replace('cm', 'm') : o[1].replace('m', 'cm'); var opts = shuffle([o[1], wrong]); return { qtn: 'Choose the sensible length of ' + o[0] + ':  ' + opts.join('  or  '), ans: o[1] }; };
  G.estimate_mass = function () { var o = pick([['an apple', '150 g'], ['a bag of sugar', '1 kg'], ['a car', '1,000 kg'], ['a feather', '1 g'], ['a cat', '4 kg']]); var wrong = o[1].indexOf('kg') > -1 ? o[1].replace('kg', 'g') : o[1].replace('g', 'kg'); var opts = shuffle([o[1], wrong]); return { qtn: 'Choose the sensible mass of ' + o[0] + ':  ' + opts.join('  or  '), ans: o[1] }; };
  G.estimate_capacity = function () { var o = pick([['a teaspoon', '5 ml'], ['a bucket', '10 litres'], ['a mug', '300 ml'], ['a bottle of water', '1 litre'], ['a bath', '80 litres']]); var wrong = o[1].indexOf('ml') > -1 ? o[1].replace('ml', 'litres') : o[1].replace(/litres?/, 'ml'); var opts = shuffle([o[1], wrong]); return { qtn: 'Choose the sensible capacity of ' + o[0] + ':  ' + opts.join('  or  '), ans: o[1] }; };
  // Compare two measures with the same unit (Year 2 "compare & order" for measures);
  // mostly distinct values so the answer isn't almost always "=".
  G.compare_measures = function () { var u = pick(['cm', 'm', 'kg', 'g', 'ml', 'litres']); var a = ri(2, 99), b = (Math.random() < 0.15) ? a : ri(2, 99); var sym = a < b ? '<' : a > b ? '>' : '='; return { qtn: 'Insert <, > or = :  ' + a + ' ' + u + ' ___ ' + b + ' ' + u, ans: sym }; };
  // Four-operations measure problems in decimal notation, including scaling.
  G.measure_decimal = function () {
    return pick([
      function () { var len = ri(105, 495) / 100, n = ri(2, 5); return { qtn: 'A plank is ' + len.toFixed(2) + ' m long. ' + n + ' planks are joined end to end. What is the total length?', ans: (len * n).toFixed(2) + ' m' }; },
      function () { var total = ri(200, 900) / 100, n = pick([2, 4, 5]); return { qtn: fmt(n) + ' bottles hold ' + total.toFixed(2) + ' litres altogether. How much is in each bottle?', ans: (total / n).toFixed(2) + ' litres' }; },
      function () { var a = ri(150, 950) / 100, b = ri(50, 140) / 100; return { qtn: 'A rope is ' + a.toFixed(2) + ' m. You cut off ' + b.toFixed(2) + ' m. How much is left?', ans: (a - b).toFixed(2) + ' m' }; }
    ])();
  };
  G.perimeter_rect = function () { var w = ri(2, 20), h = ri(2, 20); return { qtn: 'A rectangle is ' + w + ' cm by ' + h + ' cm. What is its perimeter?', ans: fmt(2 * (w + h)) + ' cm' }; };
  G.perimeter_2d = function () { var s = ri(2, 15), n = pick([3, 4, 5, 6]); return { qtn: 'A regular shape has ' + n + ' sides of ' + s + ' cm. What is its perimeter?', ans: fmt(n * s) + ' cm' }; };
  G.area_rect = function () { var w = ri(2, 20), h = ri(2, 20); return { qtn: 'A rectangle is ' + w + ' cm by ' + h + ' cm. What is its area?', ans: fmt(w * h) + ' cm²' }; };
  G.area_count = function () { var w = ri(2, 8), h = ri(2, 6); return { qtn: 'A rectangle covers ' + w + ' squares across and ' + h + ' squares down. How many squares is its area?', ans: fmt(w * h) }; };
  G.area_tri_para = function () { return pick([function () { var b = ri(4, 20), h = ri(2, 12); return { qtn: 'A triangle has base ' + b + ' cm and height ' + h + ' cm. What is its area?', ans: fmt(b * h / 2) + ' cm²' }; }, function () { var b = ri(3, 15), h = ri(2, 12); return { qtn: 'A parallelogram has base ' + b + ' cm and height ' + h + ' cm. What is its area?', ans: fmt(b * h) + ' cm²' }; }])(); };
  G.volume_cuboid = function () { var a = ri(2, 8), b = ri(2, 8), c = ri(2, 8); return { qtn: 'A cuboid is ' + a + ' cm × ' + b + ' cm × ' + c + ' cm. What is its volume?', ans: fmt(a * b * c) + ' cm³' }; };

  // -------------------------------------------------------------------------
  // STATISTICS — small data sets, drawn so the child reads the representation
  // (pictogram / tally chart / block diagram / table / bar chart) named by the
  // objective rather than being handed the totals as text.
  // -------------------------------------------------------------------------
  function dataset(cats) { var data = {}; cats.forEach(function (c) { data[c] = ri(2, 12); }); return data; }
  function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

  function pictogramHTML(d, title) {
    var rows = Object.keys(d).map(function (k) {
      return '<tr><td style="padding:2px 10px 2px 0;font-size:12px;">' + esc(k) + '</td><td style="font-size:13px;letter-spacing:2px;color:#1f8a4d;">' + new Array(d[k] + 1).join('●') + '</td></tr>';
    }).join('');
    return '<div class="tp-data"><div style="font-size:11px;color:#6c716a;margin-bottom:3px;">' + esc(title) + ' — key: ● = 1 child</div><table style="border-collapse:collapse;">' + rows + '</table></div>';
  }
  function tallyHTML(d, title) {
    function tally(n) {
      var full = Math.floor(n / 5), rem = n % 5, s = '';
      for (var i = 0; i < full; i++) { s += '<span style="display:inline-block;margin-right:6px;border-left:9px solid #26302a;height:14px;transform:skewX(-20deg);padding-left:1px;">||||</span>'; }
      if (rem) { s += '<span style="letter-spacing:2px;">' + new Array(rem + 1).join('|') + '</span>'; }
      return s || '0';
    }
    var rows = Object.keys(d).map(function (k) {
      return '<tr><td style="padding:2px 12px 2px 0;font-size:12px;">' + esc(k) + '</td><td style="font-family:monospace;font-size:13px;">' + new Array(d[k] + 1).join('|').replace(/(.{5})/g, '$1 ') + '</td></tr>';
    }).join('');
    return '<div class="tp-data"><div style="font-size:11px;color:#6c716a;margin-bottom:3px;">' + esc(title) + ' (tally chart)</div><table style="border-collapse:collapse;">' + rows + '</table></div>';
  }
  function barHTML(d, title, block) {
    var max = Math.max.apply(null, Object.keys(d).map(function (k) { return d[k]; }));
    var rows = Object.keys(d).map(function (k) {
      var w = Math.round(d[k] / max * 140);
      var bar = block
        ? new Array(d[k] + 1).join('<span style="display:inline-block;width:11px;height:11px;margin-right:1px;background:#2a6fdb;"></span>')
        : '<span style="display:inline-block;height:11px;width:' + w + 'px;background:#2a6fdb;"></span>';
      return '<tr><td style="padding:2px 8px 2px 0;font-size:12px;text-align:right;">' + esc(k) + '</td><td style="padding:2px 0;">' + bar + '</td></tr>';
    }).join('');
    return '<div class="tp-data"><div style="font-size:11px;color:#6c716a;margin-bottom:3px;">' + esc(title) + ' (' + (block ? 'block diagram' : 'bar chart') + ', each square/grid line = 1)</div><table style="border-collapse:collapse;">' + rows + '</table></div>';
  }
  function tableHTML(d, title, colHead) {
    var rows = Object.keys(d).map(function (k) {
      return '<tr><td style="border:1px solid #c2c6bd;padding:2px 10px;font-size:12px;">' + esc(k) + '</td><td style="border:1px solid #c2c6bd;padding:2px 10px;font-size:12px;text-align:center;">' + d[k] + '</td></tr>';
    }).join('');
    return '<div class="tp-data"><table style="border-collapse:collapse;"><tr><th style="border:1px solid #c2c6bd;padding:2px 10px;font-size:11px;">' + esc(title) + '</th><th style="border:1px solid #c2c6bd;padding:2px 10px;font-size:11px;">' + esc(colHead) + '</th></tr>' + rows + '</table></div>';
  }

  function total(d) { return Object.keys(d).reduce(function (s, k) { return s + d[k]; }, 0); }

  G.stats_total = function () {  // pictograms / tally charts
    var d = dataset(shuffle(['Red', 'Blue', 'Green', 'Yellow']).slice(0, 3));
    var asTally = pick([true, false]);
    return { qtn: 'How many children altogether?', qhtml: (asTally ? tallyHTML(d, 'Favourite colour') : pictogramHTML(d, 'Favourite colour')), ans: fmt(total(d)) };
  };
  G.stats_compare = function () {  // block diagrams
    var keys = shuffle(['Cats', 'Dogs', 'Fish', 'Birds']).slice(0, 3);
    var d = dataset(keys);
    var hi = keys.reduce(function (a, b) { return d[a] >= d[b] ? a : b; });
    var lo = keys.reduce(function (a, b) { return d[a] <= d[b] ? a : b; });
    // Ensure the two compared categories are distinct with a non-zero difference.
    var guard = 0;
    while (hi === lo && guard < 20) { d = dataset(keys); hi = keys.reduce(function (a, b) { return d[a] >= d[b] ? a : b; }); lo = keys.reduce(function (a, b) { return d[a] <= d[b] ? a : b; }); guard++; }
    return { qtn: 'How many more ' + hi + ' than ' + lo + '?', qhtml: barHTML(d, 'Pets', true), ans: fmt(d[hi] - d[lo]) };
  };
  G.stats_one_step = function () {  // bar charts / pictograms / tables
    var d = dataset(shuffle(['Mon', 'Tue', 'Wed', 'Thu']).slice(0, 4));
    var keys = Object.keys(d);
    var most = keys.reduce(function (a, b) { return d[a] >= d[b] ? a : b; });
    return { qtn: 'On which day were the most books read?', qhtml: barHTML(d, 'Books read', false), ans: most };
  };
  G.stats_two_step = function () {  // 'how many more/fewer?' comparison
    var keys = shuffle(['Class A', 'Class B', 'Class C']).slice(0, 3);
    var d = dataset(keys);
    var hi = keys.reduce(function (a, b) { return d[a] >= d[b] ? a : b; });
    var lo = keys.reduce(function (a, b) { return d[a] <= d[b] ? a : b; });
    var guard = 0;
    while (hi === lo && guard < 20) { d = dataset(keys); hi = keys.reduce(function (a, b) { return d[a] >= d[b] ? a : b; }); lo = keys.reduce(function (a, b) { return d[a] <= d[b] ? a : b; }); guard++; }
    return { qtn: 'How many more points did ' + hi + ' score than ' + lo + '?', qhtml: barHTML(d, 'Points scored', false), ans: fmt(d[hi] - d[lo]) };
  };
  G.stats_table = function () {  // simple tables / timetables
    var d = dataset(shuffle(['Apples', 'Pears', 'Plums', 'Grapes']).slice(0, 4));
    return { qtn: 'How many pieces of fruit were sold altogether?', qhtml: tableHTML(d, 'Fruit', 'Sold'), ans: fmt(total(d)) };
  };
  G.stats_mean = function () {
    var vals = [], n = ri(3, 5), sum = 0;
    while (vals.length < n) { var v = ri(2, 20); vals.push(v); sum += v; }
    if (sum % n !== 0) { vals[0] += n - (sum % n); sum += n - (sum % n); }
    return { qtn: 'Find the mean (average) of:  ' + vals.join(', '), ans: fmt(sum / n) };
  };

  // -------------------------------------------------------------------------
  // ALGEBRA / RATIO (Year 6)
  // -------------------------------------------------------------------------
  G.linear_sequence = function () { var a = ri(1, 6), d = ri(2, 6), n = 5; var terms = []; for (var i = 0; i < n; i++) { terms.push(a + d * i); } return { qtn: 'Write the next term:  ' + terms.join(', ') + ', ___', ans: fmt(a + d * n) }; };
  G.simple_formula = function () { var m = ri(2, 6), c = ri(1, 9), x = ri(2, 9); return { qtn: 'If y = ' + m + 'x + ' + c + ', find y when x = ' + x + '.', ans: fmt(m * x + c) }; };
  G.express_algebra = function () { var b = ri(2, 9); return { qtn: 'A number n is increased by ' + b + '. Write an expression for the result.', ans: 'n + ' + b }; };
  G.two_unknowns = function () { var s = ri(6, 14); var a = ri(1, s - 1), b = s - a; return { qtn: 'a + b = ' + s + '. If a = ' + a + ', what is b?', ans: fmt(b) }; };
  G.enumerate_combos = function () { var a = ri(2, 4), b = ri(2, 4); return { qtn: a + ' tops and ' + b + ' bottoms. How many different outfits?', ans: fmt(a * b) }; };
  G.ratio_relative = function () { var u = ri(2, 9), k = ri(2, 6); return { qtn: 'A recipe for 1 person uses ' + u + ' g of rice. How much for ' + k + ' people?', ans: fmt(u * k) + ' g' }; };
  G.ratio_percent = function () { var base = pick([40, 60, 80, 200]), p = pick([10, 25, 50]); return { qtn: 'Find ' + p + '% of ' + base + '.', ans: fmt(base * p / 100) }; };
  G.scale_factor = function () { var a = ri(2, 8), k = ri(2, 4); return { qtn: 'A shape is enlarged by scale factor ' + k + '. A side of ' + a + ' cm becomes…', ans: fmt(a * k) + ' cm' }; };
  G.unequal_sharing = function () { var part = ri(2, 8), ratio = pick([[1, 2], [1, 3], [2, 3]]); var total = part * (ratio[0] + ratio[1]); return { qtn: '£' + total + ' is shared in the ratio ' + ratio[0] + ':' + ratio[1] + '. What is the larger share?', ans: '£' + fmt(part * Math.max(ratio[0], ratio[1])) }; };
})();
