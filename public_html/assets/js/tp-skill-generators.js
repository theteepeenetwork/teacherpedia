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

  /* =========================================================================
     RESOURCE-CREATOR TEAM — generators for the 40 previously-uncovered skills
     (Geometry, Measurement, Statistics & visual Place-Value skills). Each was
     designed against its White Rose Below/Meeting/Exceeding band, implemented,
     then stress-tested 600x+ in Node for crash-free, single-correct-answer output.
     Keys are wired to the matching rows in framework_skills.json.
     ====================================================================== */

  // Y1 · Place value › Objects & pictures  [pv_y1_count_objects]
  G['pv_y1_count_objects'] = function (d) {
    // d: 2=below, 3=meeting, 4=exceeding
    var max = d >= 4 ? 20 : (d <= 2 ? 8 : 10);
    var min = 1;
    var n = ri(min, max);

    var objects = [
      {name:'star', plural:'stars', fill:'#FFC93C', stroke:'#E0A800'},
      {name:'apple', plural:'apples', fill:'#E63946', stroke:'#A4161A'},
      {name:'counter', plural:'counters', fill:'#457B9D', stroke:'#1D3557'},
      {name:'circle', plural:'circles', fill:'#2A9D8F', stroke:'#1B6F63'},
      {name:'flower', plural:'flowers', fill:'#C77DFF', stroke:'#9D4EDD'}
    ];
    var obj = pick(objects);

    // SVG drawing helpers (self-contained)
    function star(cx, cy, r, fill, stroke) {
      var pts = [], i, ang, rr;
      for (i = 0; i < 10; i++) {
        ang = Math.PI / 2 + i * Math.PI / 5;
        rr = (i % 2 === 0) ? r : r * 0.4;
        pts.push((cx + rr * Math.cos(ang)).toFixed(1) + ',' + (cy - rr * Math.sin(ang)).toFixed(1));
      }
      return '<polygon points="' + pts.join(' ') + '" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5" stroke-linejoin="round"/>';
    }
    function apple(cx, cy, r, fill, stroke) {
      var s = '';
      s += '<circle cx="' + (cx - r*0.35).toFixed(1) + '" cy="' + cy + '" r="' + (r*0.7).toFixed(1) + '" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5"/>';
      s += '<circle cx="' + (cx + r*0.35).toFixed(1) + '" cy="' + cy + '" r="' + (r*0.7).toFixed(1) + '" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5"/>';
      s += '<rect x="' + (cx-1.2).toFixed(1) + '" y="' + (cy - r*1.05).toFixed(1) + '" width="2.4" height="' + (r*0.45).toFixed(1) + '" fill="#7B4B2A"/>';
      s += '<ellipse cx="' + (cx + r*0.45).toFixed(1) + '" cy="' + (cy - r*0.75).toFixed(1) + '" rx="' + (r*0.35).toFixed(1) + '" ry="' + (r*0.2).toFixed(1) + '" fill="#3A7d44"/>';
      return s;
    }
    function disc(cx, cy, r, fill, stroke) {
      return '<circle cx="' + cx + '" cy="' + cy + '" r="' + r.toFixed(1) + '" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5"/>';
    }
    function flower(cx, cy, r, fill, stroke) {
      var s = '', i, ang;
      for (i = 0; i < 6; i++) {
        ang = i * Math.PI / 3;
        s += '<circle cx="' + (cx + r*0.55*Math.cos(ang)).toFixed(1) + '" cy="' + (cy + r*0.55*Math.sin(ang)).toFixed(1) + '" r="' + (r*0.45).toFixed(1) + '" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.2"/>';
      }
      s += '<circle cx="' + cx + '" cy="' + cy + '" r="' + (r*0.4).toFixed(1) + '" fill="#FFD166" stroke="' + stroke + '" stroke-width="1.2"/>';
      return s;
    }
    function drawObj(name, cx, cy, r, fill, stroke) {
      if (name === 'star') return star(cx, cy, r, fill, stroke);
      if (name === 'apple') return apple(cx, cy, r, fill, stroke);
      if (name === 'flower') return flower(cx, cy, r, fill, stroke);
      return disc(cx, cy, r, fill, stroke);
    }

    var cell = 46, r = 17, pad = 12;
    var svg = '';

    // Layout: row for n<=10, else ten-frame style (two rows of up to 10)
    if (n <= 10) {
      var w = pad * 2 + n * cell;
      var h = pad * 2 + cell + 70;
      var yObj = pad + cell / 2;
      svg += '<svg width="' + w + '" height="' + h + '" viewBox="0 0 ' + w + ' ' + h + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="' + n + ' ' + obj.plural + '">';
      svg += '<rect x="0" y="0" width="' + w + '" height="' + h + '" fill="#ffffff"/>';
      for (var k = 0; k < n; k++) {
        var cx = pad + cell * k + cell / 2;
        svg += drawObj(obj.name, cx, yObj, r, obj.fill, obj.stroke);
      }
      // answer box centred under the row
      var bx = (w - 50) / 2, by = pad + cell + 14;
      svg += '<rect x="' + bx.toFixed(1) + '" y="' + by + '" width="50" height="50" fill="#ffffff" stroke="#222222" stroke-width="1.5"/>';
      svg += '</svg>';
    } else {
      // ten-frame style: two rows, 10 per row
      var perRow = 10;
      var rows = 2;
      var fw = pad * 2 + perRow * cell;
      var fh = pad * 2 + rows * cell + 70;
      svg += '<svg width="' + fw + '" height="' + fh + '" viewBox="0 0 ' + fw + ' ' + fh + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="' + n + ' ' + obj.plural + '">';
      svg += '<rect x="0" y="0" width="' + fw + '" height="' + fh + '" fill="#ffffff"/>';
      // grid outline for the frame
      var gx = pad, gy = pad;
      svg += '<rect x="' + gx + '" y="' + gy + '" width="' + (perRow * cell) + '" height="' + (rows * cell) + '" fill="none" stroke="#cccccc" stroke-width="1.5"/>';
      var c2;
      for (c2 = 1; c2 < perRow; c2++) {
        svg += '<line x1="' + (gx + c2 * cell) + '" y1="' + gy + '" x2="' + (gx + c2 * cell) + '" y2="' + (gy + rows * cell) + '" stroke="#dddddd" stroke-width="1"/>';
      }
      svg += '<line x1="' + gx + '" y1="' + (gy + cell) + '" x2="' + (gx + perRow * cell) + '" y2="' + (gy + cell) + '" stroke="#dddddd" stroke-width="1"/>';
      for (var m = 0; m < n; m++) {
        var rr2 = Math.floor(m / perRow);
        var cc2 = m % perRow;
        var ox = gx + cc2 * cell + cell / 2;
        var oy = gy + rr2 * cell + cell / 2;
        svg += drawObj(obj.name, ox, oy, r, obj.fill, obj.stroke);
      }
      var bx2 = (fw - 50) / 2, by2 = gy + rows * cell + 14;
      svg += '<rect x="' + bx2.toFixed(1) + '" y="' + by2 + '" width="50" height="50" fill="#ffffff" stroke="#222222" stroke-width="1.5"/>';
      svg += '</svg>';
    }

    var qtn = 'How many ' + obj.plural + ' are there? Write the number in the box.';
    return { qtn: qtn, ans: n, qhtml: svg };
  };

  // Y1 · Measurement › Length/height  [meas_y1_tallest_longest]
  G['meas_y1_tallest_longest'] = function (d) {
    function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    // Object types: vertical -> tallest, horizontal -> longest
    var vertical = [
      { name: 'tower', plural: 'towers', colour: '#bcd6f0' },
      { name: 'tree', plural: 'trees', colour: '#bfe3bf' },
      { name: 'candle', plural: 'candles', colour: '#f3e0b0' },
      { name: 'building', plural: 'buildings', colour: '#d9c7e8' }
    ];
    var horizontal = [
      { name: 'pencil', plural: 'pencils', colour: '#f3c9a0' },
      { name: 'ribbon', plural: 'ribbons', colour: '#f0bcd0' },
      { name: 'snake', plural: 'snakes', colour: '#bfe3bf' },
      { name: 'worm', plural: 'worms', colour: '#f3c9a0' }
    ];

    var orientation = pick(['vertical', 'horizontal']);
    var superlative, obj;
    if (orientation === 'vertical') { superlative = 'tallest'; obj = pick(vertical); }
    else { superlative = 'longest'; obj = pick(horizontal); }

    var letters = ['A', 'B', 'C'];

    // Generate 3 distinct sizes with a guaranteed clear gap (>=15px) between
    // the largest and every other value, and >=8px between any two so none look equal.
    var sizes;
    for (var attempt = 0; attempt < 200; attempt++) {
      var a = ri(40, 130), b = ri(40, 130), c = ri(40, 130);
      var arr = [a, b, c];
      var sorted = arr.slice().sort(function (x, y) { return x - y; });
      var gapTop = sorted[2] - sorted[1];        // gap to second largest
      var gapMid = sorted[1] - sorted[0];        // gap between two smaller
      if (gapTop >= 15 && gapMid >= 8) { sizes = arr; break; }
    }
    if (!sizes) sizes = [55, 80, 120]; // safe fallback (already valid)

    // Sizes are already randomised per-position, so the largest position is random.
    var maxV = Math.max(sizes[0], sizes[1], sizes[2]);
    var ansIdx = sizes.indexOf(maxV);
    var ans = letters[ansIdx];

    // Build SVG
    var pad = 20, gap = 50, slot = 60, baseY = 170, maxLen = 130;
    var svgW = pad * 2 + slot * 3 + gap * 2;
    var svgH = 230;
    var parts = [];
    parts.push('<svg xmlns="http://www.w3.org/2000/svg" width="' + svgW + '" height="' + svgH + '" viewBox="0 0 ' + svgW + ' ' + svgH + '" font-family="sans-serif">');

    if (orientation === 'vertical') {
      var baseLineY = baseY;
      parts.push('<line x1="' + (pad - 5) + '" y1="' + baseLineY + '" x2="' + (svgW - pad + 5) + '" y2="' + baseLineY + '" stroke="#333" stroke-width="2"/>');
      for (var i = 0; i < 3; i++) {
        var x = pad + i * (slot + gap);
        var w = 40;
        var h = sizes[i];
        var topY = baseLineY - h;
        parts.push('<rect x="' + (x + (slot - w) / 2) + '" y="' + topY + '" width="' + w + '" height="' + h + '" fill="' + obj.colour + '" stroke="#555" stroke-width="1.5"/>');
        var cx = x + slot / 2;
        parts.push('<text x="' + cx + '" y="' + (baseLineY + 22) + '" text-anchor="middle" font-size="18" font-weight="bold">' + letters[i] + '</text>');
        parts.push('<rect x="' + (cx - 9) + '" y="' + (baseLineY + 30) + '" width="18" height="18" fill="#fff" stroke="#333" stroke-width="1.5"/>');
      }
    } else {
      // horizontal: bars grow rightward from a common left baseline (start line)
      var startX = pad + 20;
      var rowGap = 50;
      var firstY = 20;
      parts.push('<line x1="' + startX + '" y1="' + (firstY - 8) + '" x2="' + startX + '" y2="' + (firstY + 2 * rowGap + 30) + '" stroke="#333" stroke-width="2"/>');
      for (var j = 0; j < 3; j++) {
        var ry = firstY + j * rowGap;
        var len = sizes[j];
        var bh = 22;
        parts.push('<rect x="' + startX + '" y="' + ry + '" width="' + len + '" height="' + bh + '" fill="' + obj.colour + '" stroke="#555" stroke-width="1.5"/>');
        parts.push('<text x="' + (startX - 12) + '" y="' + (ry + bh - 5) + '" text-anchor="middle" font-size="18" font-weight="bold">' + letters[j] + '</text>');
        parts.push('<rect x="' + (startX + maxLen + 18) + '" y="' + (ry + 2) + '" width="18" height="18" fill="#fff" stroke="#333" stroke-width="1.5"/>');
      }
    }
    parts.push('</svg>');

    var qtn = 'Here are three ' + esc(obj.plural) + '. Tick the ' + superlative + ' ' + esc(obj.name) + '.';

    return { qtn: qtn, ans: ans, qhtml: parts.join('') };
  };

  // Y1 · Measurement › Mass/weight  [meas_y1_balance_mass]
  G['meas_y1_balance_mass'] = function (d) {
    // self-contained helpers
    function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    // pool of everyday objects, each with an emoji icon and an indefinite article
    var pool = [
      { name: 'apple',   art: 'an', icon: '🍎' },
      { name: 'ball',    art: 'a',  icon: '⚽' },
      { name: 'book',    art: 'a',  icon: '📕' },
      { name: 'teddy',   art: 'a',  icon: '🧸' },
      { name: 'brick',   art: 'a',  icon: '🧱' },
      { name: 'feather', art: 'a',  icon: '🪶' },
      { name: 'cat',     art: 'a',  icon: '🐱' },
      { name: 'mouse',   art: 'a',  icon: '🐭' }
    ];

    // pick two distinct objects
    var two = shuffle(pool).slice(0, 2);
    var objA = two[0], objB = two[1];

    // which side is heavy: 'left' or 'right' (heavy pan sits LOWER)
    var heavySide = pick(['left', 'right']);

    // assign objects to sides
    var leftObj, rightObj;
    if (pick([true, false])) { leftObj = objA; rightObj = objB; }
    else { leftObj = objB; rightObj = objA; }

    var heavyObj = (heavySide === 'left') ? leftObj : rightObj;
    var lightObj = (heavySide === 'left') ? rightObj : leftObj;

    // prompt direction: heavier or lighter
    var dir = pick(['heavier', 'lighter']);
    var targetObj = (dir === 'heavier') ? heavyObj : lightObj;

    var qtn = 'Look at the balance scales. Which is ' + dir + ', the '
      + leftObj.name + ' or the ' + rightObj.name + '?';
    var ans = 'The ' + targetObj.name + ' is ' + dir
      + ' (its pan is ' + (dir === 'heavier' ? 'lower' : 'higher') + ').';

    // ---- build SVG balance ----
    // beam tilts so the heavy side is down. We rotate the beam about the pivot.
    var W = 260, Hh = 200, cx = W / 2, pivotY = 70;
    var tilt = (heavySide === 'left') ? 12 : -12; // degrees, positive => left down
    var rad = tilt * Math.PI / 180;
    var halfBeam = 90;
    // beam endpoints after rotation about (cx, pivotY)
    var lx = cx - halfBeam * Math.cos(rad), ly = pivotY - halfBeam * Math.sin(rad);
    var rx = cx + halfBeam * Math.cos(rad), ry = pivotY + halfBeam * Math.sin(rad);
    // pans hang straight down from each beam end
    var hangHigh = 30, hangLow = 30;
    var leftPanY = ly + hangHigh, rightPanY = ry + hangHigh;

    function pan(px, py, icon) {
      return '<line x1="' + px.toFixed(1) + '" y1="' + (py - 32).toFixed(1) + '" x2="' + px.toFixed(1) + '" y2="' + py.toFixed(1) + '" stroke="#555" stroke-width="2"/>'
        + '<path d="M ' + (px - 26).toFixed(1) + ' ' + py.toFixed(1) + ' Q ' + px.toFixed(1) + ' ' + (py + 22).toFixed(1) + ' ' + (px + 26).toFixed(1) + ' ' + py.toFixed(1) + ' Z" fill="#d9e6f2" stroke="#555" stroke-width="2"/>'
        + '<text x="' + px.toFixed(1) + '" y="' + (py + 6).toFixed(1) + '" font-size="22" text-anchor="middle">' + esc(icon) + '</text>';
    }

    function label(px, name) {
      return '<text x="' + px.toFixed(1) + '" y="195" font-size="13" text-anchor="middle" fill="#333">' + esc(name) + '</text>';
    }

    var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + W + ' ' + Hh + '" width="' + W + '" height="' + Hh + '" role="img">'
      // central post
      + '<line x1="' + cx + '" y1="' + pivotY + '" x2="' + cx + '" y2="160" stroke="#555" stroke-width="4"/>'
      // pivot triangle
      + '<path d="M ' + (cx - 22) + ' 160 L ' + (cx + 22) + ' 160 L ' + cx + ' ' + (pivotY - 4) + ' Z" fill="#bba" stroke="#555" stroke-width="2"/>'
      // base
      + '<line x1="' + (cx - 45) + '" y1="160" x2="' + (cx + 45) + '" y2="160" stroke="#555" stroke-width="4"/>'
      // beam
      + '<line x1="' + lx.toFixed(1) + '" y1="' + ly.toFixed(1) + '" x2="' + rx.toFixed(1) + '" y2="' + ry.toFixed(1) + '" stroke="#555" stroke-width="5"/>'
      + '<circle cx="' + cx + '" cy="' + pivotY + '" r="4" fill="#555"/>'
      // pans
      + pan(lx, leftPanY, leftObj.icon)
      + pan(rx, rightPanY, rightObj.icon)
      // labels
      + label(lx, leftObj.name)
      + label(rx, rightObj.name)
      + '</svg>';

    return { qtn: qtn, ans: ans, qhtml: svg };
  };

  // Y1 · Measurement › Capacity/volume  [meas_y1_compare_capacity]
  G['meas_y1_compare_capacity'] = function (d) {
    var esc = function (s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    };
    // Distinct fill levels (fraction of container height). Difference always clearly visible.
    var levels = [0.2, 0.4, 0.6, 0.8];
    var i1 = ri(0, levels.length - 1);
    var i2;
    do { i2 = ri(0, levels.length - 1); } while (i2 === i1);
    var fillLeft = levels[i1];
    var fillRight = levels[i2];

    // Container naming: either A/B or two named items.
    var namings = [
      { word: 'container', plural: 'containers', a: 'A', b: 'B', useLetters: true },
      { word: 'cup', plural: 'cups', a: 'Cup A', b: 'Cup B', useLetters: false },
      { word: 'glass', plural: 'glasses', a: 'Glass A', b: 'Glass B', useLetters: false },
      { word: 'jug', plural: 'jugs', a: 'Jug A', b: 'Jug B', useLetters: false }
    ];
    var naming = pick(namings);
    var labelLeft = naming.a, labelRight = naming.b;

    // Question word: more or less.
    var qword = pick(['MORE', 'LESS']);

    // Determine correct answer: larger fill for MORE, smaller for LESS.
    var leftMore = fillLeft > fillRight;
    var ans;
    if (qword === 'MORE') ans = leftMore ? labelLeft : labelRight;
    else ans = leftMore ? labelRight : labelLeft;

    // Build question stem.
    var noun = naming.plural;
    var qtn = 'Look at the two ' + noun + '. They are the same size. Which one holds ' + qword +
      ' water? (' + labelLeft + ' or ' + labelRight + '?)';

    // SVG drawing.
    var W = 320, Hh = 200;
    var cw = 70, ch = 130;          // container outline width / height
    var baseY = 40 + ch;            // bottom of containers
    var leftX = 50, rightX = 200;   // left edges of the two containers
    var topY = 40;

    function container(x, fill, label) {
      var fh = Math.round(ch * fill);
      var fy = baseY - fh;
      var s = '';
      // water (drawn first so outline sits on top)
      s += '<rect x="' + x + '" y="' + fy + '" width="' + cw + '" height="' + fh +
        '" fill="#4da6ff" stroke="none"/>';
      // container outline (no fill)
      s += '<rect x="' + x + '" y="' + topY + '" width="' + cw + '" height="' + ch +
        '" fill="none" stroke="#000" stroke-width="2"/>';
      // label underneath
      s += '<text x="' + (x + cw / 2) + '" y="' + (baseY + 22) +
        '" text-anchor="middle" font-family="sans-serif" font-size="16">' + esc(label) + '</text>';
      return s;
    }

    var qhtml = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + Hh +
      '" viewBox="0 0 ' + W + ' ' + Hh + '">';
    qhtml += '<text x="' + (W / 2) + '" y="22" text-anchor="middle" font-family="sans-serif" font-size="14">Which holds ' +
      qword + ' water?</text>';
    qhtml += container(leftX, fillLeft, labelLeft);
    qhtml += container(rightX, fillRight, labelRight);
    qhtml += '</svg>';

    return { qtn: qtn, ans: ans, qhtml: qhtml };
  };

  // Y1 · Measurement › Time  [meas_y1_time_compare_duration]
  G['meas_y1_time_compare_duration'] = function (d) {
    var bank = {
      SHORT: ['blinking your eyes', 'clapping once', 'clicking your fingers', 'saying "hello"', 'jumping once'],
      MEDIUM: ['brushing your teeth', 'singing Happy Birthday', 'running around the playground once', 'washing your hands', 'tidying up the toys'],
      LONG: ['watching a whole film', 'a whole school day', 'sleeping all night', 'a car journey to the seaside', 'growing a sunflower']
    };
    var rank = { SHORT: 0, MEDIUM: 1, LONG: 2 };
    var classes = ['SHORT', 'MEDIUM', 'LONG'];
    // pick two DIFFERENT classes (never same class -> always one unambiguously longer)
    var two = shuffle(classes).slice(0, 2);
    var c1 = two[0], c2 = two[1];
    var a1 = pick(bank[c1]);
    var a2 = pick(bank[c2]);
    // determine which is longer by duration class
    var longerAct, shorterAct;
    if (rank[c1] > rank[c2]) { longerAct = a1; shorterAct = a2; }
    else { longerAct = a2; shorterAct = a1; }
    // randomise presentation order
    var order = shuffle([a1, a2]);
    // randomise whether we ask for the longer or shorter one
    var askLonger = Math.random() < 0.5;
    var word = askLonger ? 'LONGER' : 'SHORTER';
    var ans = askLonger ? longerAct : shorterAct;
    var qtn = 'Circle the one that takes a ' + word + ' time:   ' +
              order[0] + '   /   ' + order[1];
    return { qtn: qtn, ans: ans };
  };

  // Y1 · Measurement › Measure & record  [meas_length_count_cubes]
  G['meas_length_count_cubes'] = function (d) {
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    var objs=[
      {name:'pencil', col:'#f2c200', tip:'point'},
      {name:'ribbon', col:'#e0457b', tip:'flat'},
      {name:'red bar', col:'#d23b3b', tip:'flat'},
      {name:'blue bar', col:'#3b6fd2', tip:'flat'},
      {name:'green bar', col:'#3aa856', tip:'flat'},
      {name:'crayon', col:'#8e44ad', tip:'point'},
      {name:'stick', col:'#a0703a', tip:'flat'},
      {name:'straw', col:'#ff7f27', tip:'flat'}
    ];
    var o=pick(objs);
    var N=ri(3,10);
    var extra=ri(1,3);
    var total=N+extra;
    var cube=30;
    var padL=10, padTop=10;
    var rowY=padTop+34;
    var objY=padTop+6;
    var objH=18;
    var w=padL*2+total*cube+70;
    var hgt=rowY+cube+22;

    function cubesRow(){
      var s='';
      for(var i=0;i<total;i++){
        var x=padL+i*cube;
        s+='<rect x="'+x+'" y="'+rowY+'" width="'+cube+'" height="'+cube+'" fill="#ffffff" stroke="#000000" stroke-width="1"/>';
      }
      return s;
    }
    function objectShape(){
      var x0=padL;
      var x1=padL+N*cube;
      var midY=objY+objH/2;
      var s='';
      if(o.tip==='point'){
        var bodyEnd=x1-10;
        s+='<rect x="'+x0+'" y="'+objY+'" width="'+(bodyEnd-x0)+'" height="'+objH+'" fill="'+o.col+'" stroke="#333" stroke-width="1"/>';
        s+='<polygon points="'+bodyEnd+','+objY+' '+x1+','+midY+' '+bodyEnd+','+(objY+objH)+'" fill="#e8b98a" stroke="#333" stroke-width="1"/>';
      }else{
        s+='<rect x="'+x0+'" y="'+objY+'" width="'+(x1-x0)+'" height="'+objH+'" rx="3" fill="'+o.col+'" stroke="#333" stroke-width="1"/>';
      }
      return s;
    }
    function answerBox(){
      var bx=padL+total*cube+18;
      var by=rowY+cube/2-14;
      return '<rect x="'+bx+'" y="'+by+'" width="40" height="28" fill="#ffffff" stroke="#000" stroke-width="1.5"/>'+
             '<text x="'+(bx+20)+'" y="'+(by+44)+'" font-family="sans-serif" font-size="11" text-anchor="middle">cubes</text>';
    }
    var svg='<svg xmlns="http://www.w3.org/2000/svg" width="'+w+'" height="'+hgt+'" viewBox="0 0 '+w+' '+hgt+'" font-family="sans-serif">'
      +objectShape()
      +cubesRow()
      +answerBox()
      +'</svg>';

    var qtn='How long is the '+esc(o.name)+'? Count the cubes and write the number in the box.';
    return { qtn:qtn, ans:N+' cubes', qhtml:svg };
  };

  // Y1 · Measurement › Sequence events  [meas_y1_sequence_events]
  G['meas_y1_sequence_events'] = function (d) {
    // escape helper (self-contained)
    function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    // Each template: fixed canonical chronological order (index 0 = first/earliest).
    // draw(x): returns SVG line art for the event inside a box of inner width ~110, height ~80.
    function soil(x){ return '<rect x="'+(x+10)+'" y="105" width="100" height="18" fill="#c8a06a" stroke="#000" stroke-width="2"/>'; }
    var TEMPLATES = [
      { name:'morning routine', events:[
        { label:'wake up', draw:function(x){ return '<rect x="'+(x+25)+'" y="70" width="70" height="40" rx="6" fill="#fff" stroke="#000" stroke-width="2"/><circle cx="'+(x+40)+'" cy="60" r="12" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+15)+'" y="90" width="90" height="10" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'get dressed', draw:function(x){ return '<rect x="'+(x+40)+'" y="55" width="40" height="35" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+30)+'" y="60" width="12" height="25" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+78)+'" y="60" width="12" height="25" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+47)+'" y="90" width="26" height="25" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'go to school', draw:function(x){ return '<rect x="'+(x+30)+'" y="75" width="60" height="40" fill="#fff" stroke="#000" stroke-width="2"/><polygon points="'+(x+30)+',75 '+(x+60)+',50 '+(x+90)+',75" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+52)+'" y="95" width="16" height="20" fill="#fff" stroke="#000" stroke-width="2"/>'; } }
      ]},
      { name:'a growing plant', events:[
        { label:'a seed', draw:function(x){ return soil(x)+'<ellipse cx="'+(x+60)+'" cy="98" rx="7" ry="5" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'a small shoot', draw:function(x){ return soil(x)+'<line x1="'+(x+60)+'" y1="105" x2="'+(x+60)+'" y2="70" stroke="#000" stroke-width="2"/><path d="M '+(x+60)+' 80 q 12 -6 18 4" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'a flower', draw:function(x){ return soil(x)+'<line x1="'+(x+60)+'" y1="105" x2="'+(x+60)+'" y2="60" stroke="#000" stroke-width="2"/><circle cx="'+(x+60)+'" cy="50" r="10" fill="#fff" stroke="#000" stroke-width="2"/><circle cx="'+(x+45)+'" cy="50" r="9" fill="#fff" stroke="#000" stroke-width="2"/><circle cx="'+(x+75)+'" cy="50" r="9" fill="#fff" stroke="#000" stroke-width="2"/><circle cx="'+(x+60)+'" cy="36" r="9" fill="#fff" stroke="#000" stroke-width="2"/>'; } }
      ]},
      { name:'pouring a drink', events:[
        { label:'empty cup', draw:function(x){ return '<rect x="'+(x+42)+'" y="65" width="36" height="48" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'pouring a drink', draw:function(x){ return '<rect x="'+(x+42)+'" y="65" width="36" height="48" fill="#fff" stroke="#000" stroke-width="2"/><line x1="'+(x+60)+'" y1="50" x2="'+(x+60)+'" y2="90" stroke="#000" stroke-width="2"/><rect x="'+(x+44)+'" y="90" width="32" height="23" fill="#cfe8ff" stroke="#000" stroke-width="2"/>'; } },
        { label:'a full cup', draw:function(x){ return '<rect x="'+(x+42)+'" y="65" width="36" height="48" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+44)+'" y="70" width="32" height="43" fill="#cfe8ff" stroke="#000" stroke-width="2"/>'; } }
      ]},
      { name:'a chicken hatching', events:[
        { label:'an egg', draw:function(x){ return '<ellipse cx="'+(x+60)+'" cy="88" rx="20" ry="26" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'a chick hatching', draw:function(x){ return '<path d="M '+(x+40)+' 90 l 8 -8 l 8 8 l 8 -8 l 8 8 l 8 -8" fill="#fff" stroke="#000" stroke-width="2"/><circle cx="'+(x+60)+'" cy="72" r="13" fill="#fff" stroke="#000" stroke-width="2"/><polygon points="'+(x+73)+',72 '+(x+82)+',69 '+(x+82)+',75" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'a hen', draw:function(x){ return '<ellipse cx="'+(x+58)+'" cy="90" rx="24" ry="18" fill="#fff" stroke="#000" stroke-width="2"/><circle cx="'+(x+80)+'" cy="70" r="12" fill="#fff" stroke="#000" stroke-width="2"/><polygon points="'+(x+91)+',70 '+(x+100)+',68 '+(x+100)+',74" fill="#fff" stroke="#000" stroke-width="2"/><line x1="'+(x+52)+'" y1="108" x2="'+(x+52)+'" y2="115" stroke="#000" stroke-width="2"/><line x1="'+(x+64)+'" y1="108" x2="'+(x+64)+'" y2="115" stroke="#000" stroke-width="2"/>'; } }
      ]},
      { name:'a sandcastle', events:[
        { label:'build a sandcastle', draw:function(x){ return '<rect x="'+(x+38)+'" y="95" width="44" height="20" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+48)+'" y="80" width="24" height="18" fill="#fff" stroke="#000" stroke-width="2"/><line x1="'+(x+72)+'" y1="80" x2="'+(x+72)+'" y2="68" stroke="#000" stroke-width="2"/><polygon points="'+(x+72)+',68 '+(x+84)+',71 '+(x+72)+',74" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'a finished castle', draw:function(x){ return '<rect x="'+(x+34)+'" y="90" width="52" height="25" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+34)+'" y="82" width="10" height="10" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+55)+'" y="82" width="10" height="10" fill="#fff" stroke="#000" stroke-width="2"/><rect x="'+(x+76)+'" y="82" width="10" height="10" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'waves wash it away', draw:function(x){ return '<path d="M '+(x+18)+' 95 q 12 -12 24 0 q 12 12 24 0 q 12 -12 24 0 q 12 12 24 0" fill="none" stroke="#000" stroke-width="2"/><path d="M '+(x+18)+' 108 q 12 -12 24 0 q 12 12 24 0 q 12 -12 24 0 q 12 12 24 0" fill="none" stroke="#000" stroke-width="2"/>'; } }
      ]},
      { name:'a burning candle', events:[
        { label:'light a candle', draw:function(x){ return '<rect x="'+(x+50)+'" y="55" width="20" height="60" fill="#fff" stroke="#000" stroke-width="2"/><line x1="'+(x+60)+'" y1="55" x2="'+(x+60)+'" y2="48" stroke="#000" stroke-width="2"/><ellipse cx="'+(x+60)+'" cy="42" rx="5" ry="8" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'half burned', draw:function(x){ return '<rect x="'+(x+50)+'" y="85" width="20" height="30" fill="#fff" stroke="#000" stroke-width="2"/><line x1="'+(x+60)+'" y1="85" x2="'+(x+60)+'" y2="78" stroke="#000" stroke-width="2"/><ellipse cx="'+(x+60)+'" cy="72" rx="5" ry="8" fill="#fff" stroke="#000" stroke-width="2"/>'; } },
        { label:'burned out', draw:function(x){ return '<rect x="'+(x+50)+'" y="108" width="20" height="7" fill="#fff" stroke="#000" stroke-width="2"/><line x1="'+(x+60)+'" y1="108" x2="'+(x+60)+'" y2="100" stroke="#000" stroke-width="2"/><path d="M '+(x+58)+' 100 q 4 -6 -2 -10" fill="none" stroke="#000" stroke-width="2"/>'; } }
      ]}
    ];

    var tpl = pick(TEMPLATES);
    // canonical order events
    var canonical = tpl.events; // index 0..2 = first..last
    var letters = ['A','B','C'];

    // build positions then shuffle, ensuring not already in chronological order
    var order;
    do {
      order = shuffle([0,1,2]); // order[i] = canonical index drawn in box i (left->right)
    } while (order[0]===0 && order[1]===1 && order[2]===2);

    var boxW=130, boxH=150, gap=20, padX=15;
    var svgW = padX*2 + boxW*3 + gap*2;
    var svgH = 190;
    var parts = [];
    parts.push('<svg xmlns="http://www.w3.org/2000/svg" width="'+svgW+'" height="'+svgH+'" viewBox="0 0 '+svgW+' '+svgH+'" font-family="sans-serif">');
    parts.push('<text x="'+padX+'" y="20" font-size="13">These three pictures are in the wrong order.</text>');

    var ansParts = [];
    for (var i=0;i<3;i++){
      var bx = padX + i*(boxW+gap);
      var ci = order[i];
      // outer box
      parts.push('<rect x="'+bx+'" y="35" width="'+boxW+'" height="'+boxH+'" fill="#fff" stroke="#000" stroke-width="2"/>');
      // event art shifted into the box via a group translate
      parts.push('<g transform="translate('+bx+',35)">'+canonical[ci].draw(0)+'</g>');
      // label
      parts.push('<text x="'+(bx+boxW/2)+'" y="'+(35+boxH-12)+'" font-size="12" text-anchor="middle">'+esc(canonical[ci].label)+'</text>');
      // empty answer square top-left
      parts.push('<rect x="'+(bx+6)+'" y="41" width="24" height="24" fill="#fff" stroke="#000" stroke-width="2"/>');
      // chronological number for this box = ci+1 (canonical index 0 -> "1")
      ansParts.push('Box '+letters[i]+' ('+canonical[ci].label+') = '+(ci+1));
    }
    parts.push('</svg>');

    var qtn = 'Look at the three pictures. They show ' + tpl.name + ' but they are in the wrong order. ' +
              'Write 1, 2, 3 in the boxes to show what happens first, next and last. ' +
              'The box that happens first gets 1; use the words first, next, last and before, after.';

    var seqOrder = canonical.map(function(e){ return e.label; }).join(' → ');
    var ans = ansParts.join('; ') + '. Order first→last: ' + seqOrder + '.';

    return { qtn: qtn, ans: ans, qhtml: parts.join('') };
  };

  // Y1 · Measurement › Date language  [meas_y1_date_language]
  G['meas_y1_date_language'] = function (d) {
    var days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function nextIn(seq, i){ return seq[(i + 1) % seq.length]; }
    function prevIn(seq, i){ return seq[(i - 1 + seq.length) % seq.length]; }

    // Item types chosen for a single, objectively-correct answer.
    // Difficulty: below=2 -> keep to simple "next day" / fact recall;
    //             meeting=3 -> days+months ordering by one step (default);
    //             exceeding=4 -> add before/gap frames and months.
    var pool;
    if (d <= 2) {
      pool = ['day_next','fact'];
    } else if (d >= 4) {
      pool = ['day_next','day_before','day_gap','month_next','month_before','fact'];
    } else {
      pool = ['day_next','day_before','day_gap','month_next','fact'];
    }
    var type = pick(pool);

    if (type === 'day_next') {
      var i = ri(0, 6);
      return { qtn: 'Which day comes after ' + days[i] + '? Write your answer on the line. ____________',
               ans: nextIn(days, i) };
    }
    if (type === 'day_before') {
      var i2 = ri(0, 6);
      return { qtn: 'Which day comes before ' + days[i2] + '? Write your answer on the line. ____________',
               ans: prevIn(days, i2) };
    }
    if (type === 'day_gap') {
      var i3 = ri(0, 6);
      var before = days[i3];
      var middle = nextIn(days, i3);
      var after = nextIn(days, (i3 + 1) % 7);
      return { qtn: 'Fill the gap: ' + before + ', __________, ' + after + '.',
               ans: middle };
    }
    if (type === 'month_next') {
      var m = ri(0, 11);
      return { qtn: 'Which month comes after ' + months[m] + '? Write your answer on the line. ____________',
               ans: nextIn(months, m) };
    }
    if (type === 'month_before') {
      var m2 = ri(0, 11);
      return { qtn: 'Which month comes before ' + months[m2] + '? Write your answer on the line. ____________',
               ans: prevIn(months, m2) };
    }
    // fact recall — single answer
    var facts = [
      { q: 'How many days are in one week? ____________', a: 7 },
      { q: 'How many months are in one year? ____________', a: 12 },
      { q: 'How many days are in two weeks? ____________', a: 14 }
    ];
    var f = pick(facts);
    return { qtn: f.q, ans: f.a };
  };

  // Y1 · Geometry › Position & turns  [geo_position_turns]
  G['geo_position_turns'] = function (d) {
    var dirs = ['up', 'right', 'down', 'left'];
    var turns = [
      { name: 'quarter', steps: 1 },
      { name: 'half', steps: 2 },
      { name: 'three-quarter', steps: 3 }
    ];
    var senses = [
      { word: 'clockwise (to the right)', dir: 1 },
      { word: 'anticlockwise (to the left)', dir: -1 }
    ];

    var startIdx = ri(0, 3);
    var start = dirs[startIdx];
    var turn = pick(turns);
    var sense = pick(senses);

    // clockwise advances up->right->down->left (index +1); anticlockwise reverses
    var endIdx = (((startIdx + sense.dir * turn.steps) % 4) + 4) % 4;
    var end = dirs[endIdx];

    // arrow direction vectors (SVG y-down): up=-y, right=+x, down=+y, left=-x
    var vecs = { up: [0, -1], right: [1, 0], down: [0, 1], left: [-1, 0] };
    var cx = 60, cy = 60, len = 42;
    var v = vecs[start];
    var tipX = cx + v[0] * len;
    var tipY = cy + v[1] * len;

    // arrowhead: build two barb points by rotating the back-of-shaft vector
    function rot(px, py, ang) {
      var c = Math.cos(ang), s = Math.sin(ang);
      return [px * c - py * s, px * s + py * c];
    }
    var bx = -v[0] * 12, by = -v[1] * 12; // back along shaft, head size 12
    var b1 = rot(bx, by, 0.5), b2 = rot(bx, by, -0.5);
    var h1x = tipX + b1[0], h1y = tipY + b1[1];
    var h2x = tipX + b2[0], h2y = tipY + b2[1];

    function r1(n) { return Math.round(n * 10) / 10; }

    // small curved turn-reminder icon in the corner (top-right); sweep reflects sense
    var sweep = sense.dir === 1 ? 1 : 0;
    var iconArc = 'M 104 14 A 10 10 0 1 ' + sweep + ' 114 24';
    var iconHead = sense.dir === 1
      ? '<polygon points="114,24 109,22 116,18" fill="#333"/>'
      : '<polygon points="104,14 106,9 110,16" fill="#333"/>';

    var svg =
      '<svg width="130" height="160" viewBox="0 0 130 160" xmlns="http://www.w3.org/2000/svg">' +
      '<circle cx="' + cx + '" cy="' + cy + '" r="50" fill="none" stroke="#333" stroke-width="2"/>' +
      '<circle cx="' + cx + '" cy="' + cy + '" r="3" fill="#333"/>' +
      '<line x1="' + cx + '" y1="' + cy + '" x2="' + r1(tipX) + '" y2="' + r1(tipY) + '" stroke="#000" stroke-width="5" stroke-linecap="round"/>' +
      '<polygon points="' + r1(tipX) + ',' + r1(tipY) + ' ' + r1(h1x) + ',' + r1(h1y) + ' ' + r1(h2x) + ',' + r1(h2y) + '" fill="#000"/>' +
      '<path d="' + iconArc + '" fill="none" stroke="#333" stroke-width="2"/>' + iconHead +
      '<text x="65" y="138" font-family="sans-serif" font-size="13" text-anchor="middle" fill="#000">up&#160;&#160;/&#160;&#160;right&#160;&#160;/&#160;&#160;down&#160;&#160;/&#160;&#160;left</text>' +
      '</svg>';

    var qtn = 'The arrow points ' + start.toUpperCase() +
      '. It makes a ' + turn.name + ' turn ' + sense.word +
      '. Which way does it point now? Ring one: up / right / down / left.';

    return { qtn: qtn, qhtml: svg, ans: end };
  };

  // Y2 · Measurement › Temperature (°C)  [meas_temperature_thermometer_y2]
  G['meas_temperature_thermometer_y2'] = function (d) {
    // choose scale max: 30, 40 or 50
    var maxC = pick([30, 40, 50]);
    // candidate target ticks: even numbers, avoid very top/bottom
    var lo = 4, hi = maxC - 4;
    var cands = [];
    for (var v = lo; v <= hi; v += 2) cands.push(v);
    var T = pick(cands);

    // ---- SVG geometry ----
    var W = 120, Hgt = 320;
    var topPad = 24, botPad = 40;     // pixel padding top / bottom of scale
    var yTop = topPad;                  // y for maxC
    var yBot = Hgt - botPad;            // y for 0
    var colX = 56;                      // centre x of tube
    var tubeW = 18;
    var bulbR = 22;
    var bulbCY = yBot + 18;

    function yFor(c) { return yBot - (c / maxC) * (yBot - yTop); }

    var parts = [];
    // outer tube
    parts.push('<rect x="' + (colX - tubeW / 2) + '" y="' + yTop + '" width="' + tubeW + '" height="' + (yBot - yTop) + '" rx="' + (tubeW / 2) + '" fill="#ffffff" stroke="#333" stroke-width="2"/>');
    // bulb
    parts.push('<circle cx="' + colX + '" cy="' + bulbCY + '" r="' + bulbR + '" fill="#d11" stroke="#333" stroke-width="2"/>');
    // mercury column from bulb up to T
    var yT = yFor(T);
    var mercW = tubeW - 6;
    parts.push('<rect x="' + (colX - mercW / 2) + '" y="' + yT + '" width="' + mercW + '" height="' + (bulbCY - yT) + '" fill="#d11"/>');
    parts.push('<rect x="' + (colX - mercW / 2) + '" y="' + yT + '" width="' + mercW + '" height="' + mercW + '" rx="' + (mercW / 2) + '" fill="#d11"/>');

    // ticks
    var tickX = colX + tubeW / 2;
    for (var c = 0; c <= maxC; c += 2) {
      var y = yFor(c);
      var major = (c % 10 === 0);
      var len = major ? 14 : 8;
      parts.push('<line x1="' + tickX + '" y1="' + y + '" x2="' + (tickX + len) + '" y2="' + y + '" stroke="#333" stroke-width="' + (major ? 2 : 1) + '"/>');
      if (major) {
        parts.push('<text x="' + (tickX + len + 4) + '" y="' + (y + 4) + '" font-family="sans-serif" font-size="13" fill="#333">' + c + '</text>');
      }
    }
    // unit label
    parts.push('<text x="' + colX + '" y="16" font-family="sans-serif" font-size="13" fill="#333" text-anchor="middle">°C</text>');

    var svg = '<svg class="tp-thermometer" width="' + W + '" height="' + Hgt + '" viewBox="0 0 ' + W + ' ' + Hgt + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="thermometer reading">' + parts.join('') + '</svg>';

    return {
      qtn: 'What temperature does this thermometer show? Write your answer in °C.',
      qhtml: svg,
      ans: T + ' °C'
    };
  };

  // Y2 · Measurement › Compare intervals  [meas_time_compare_intervals]
  G['meas_time_compare_intervals'] = function (d) {
    // Pool of everyday activities, each with a duration in a comparable unit.
    // mins is the duration converted to minutes (the common base for ordering).
    var pool = [
      { act: 'Brushing teeth', val: 2, unit: 'minutes', mins: 2 },
      { act: 'Brushing teeth', val: 3, unit: 'minutes', mins: 3 },
      { act: 'Eating a biscuit', val: 1, unit: 'minute', mins: 1 },
      { act: 'Singing a song', val: 4, unit: 'minutes', mins: 4 },
      { act: 'Boiling an egg', val: 5, unit: 'minutes', mins: 5 },
      { act: 'A short story', val: 10, unit: 'minutes', mins: 10 },
      { act: 'Playtime', val: 15, unit: 'minutes', mins: 15 },
      { act: 'Eating lunch', val: 30, unit: 'minutes', mins: 30 },
      { act: 'A school lesson', val: 1, unit: 'hour', mins: 60 },
      { act: 'Watching a film', val: 2, unit: 'hours', mins: 120 },
      { act: 'A school morning', val: 3, unit: 'hours', mins: 180 },
      { act: 'A school day', val: 6, unit: 'hours', mins: 360 },
      { act: 'Sleeping at night', val: 10, unit: 'hours', mins: 600 },
      { act: 'A whole day', val: 1, unit: 'day', mins: 1440 },
      { act: 'A weekend', val: 2, unit: 'days', mins: 2880 },
      { act: 'A holiday', val: 7, unit: 'days', mins: 10080 }
    ];

    // HTML-escape helper (self-contained).
    function esc(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }
    function durText(p) { return p.val + ' ' + p.unit; }

    // band: below=2 -> two intervals; meeting=3 / exceeding=4 -> three intervals.
    var n = (d <= 2) ? 2 : 3;

    // Pick n activities with distinct mins (no ties) and distinct activity names.
    var chosen = [];
    var usedNames = {};
    var usedMins = {};
    var guard = 0;
    while (chosen.length < n && guard < 500) {
      guard++;
      var p = pick(pool);
      if (usedNames[p.act] || usedMins[p.mins]) continue;
      usedNames[p.act] = true;
      usedMins[p.mins] = true;
      chosen.push(p);
    }
    // Fallback (should not trigger): deterministic distinct fill.
    if (chosen.length < n) {
      chosen = [];
      usedMins = {};
      for (var k = 0; k < pool.length && chosen.length < n; k++) {
        if (!usedMins[pool[k].mins]) { usedMins[pool[k].mins] = true; chosen.push(pool[k]); }
      }
    }

    // Display in shuffled order.
    var display = shuffle(chosen);

    // True ordering shortest -> longest by mins (all distinct, so unique).
    var ordered = chosen.slice().sort(function (a, b) { return a.mins - b.mins; });

    var qtn, ans;
    if (n === 2) {
      qtn = 'Which takes longer?\n' +
        display[0].act + ': ' + durText(display[0]) + '\n' +
        display[1].act + ': ' + durText(display[1]);
      var longer = ordered[1];
      ans = longer.act + ' (' + durText(longer) + ') takes longer.';
    } else {
      var lines = ['Put these times in order from shortest to longest. Write 1 (shortest), 2, 3 (longest) in the boxes.'];
      for (var i = 0; i < display.length; i++) {
        lines.push(display[i].act + ': ' + durText(display[i]) + ' [ ]');
      }
      qtn = lines.join('\n');
      var aParts = [];
      for (var j = 0; j < ordered.length; j++) {
        aParts.push(ordered[j].act + ' (' + durText(ordered[j]) + ') = ' + (j + 1));
      }
      ans = aParts.join(', ');
    }

    // Visual: a simple boxed table of the displayed activities.
    var rows = '';
    for (var r = 0; r < display.length; r++) {
      rows += '<tr><td style="border:1px solid #333;padding:4px 8px;">' + esc(display[r].act) +
        '</td><td style="border:1px solid #333;padding:4px 8px;">' + esc(durText(display[r])) +
        '</td><td style="border:1px solid #333;padding:4px 8px;width:32px;">&nbsp;</td></tr>';
    }
    var qhtml = '<table style="border-collapse:collapse;font-family:sans-serif;">' + rows + '</table>';

    return { qtn: qtn, ans: ans, qhtml: qhtml };
  };

  // Y2 · Geometry › 2-D on 3-D  [geo_2d_on_3d_faces]
  G['geo_2d_on_3d_faces'] = function (d) {
    // Catalogue of 3-D shapes with highlightable faces.
    // Each entry draws the 3-D shape in 2-D with ONE face shaded grey; the answer
    // is the name of the 2-D shape of that shaded face.
    function svgWrap(body, label) {
      return '<svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">' +
        '<style>.edge{fill:none;stroke:#222;stroke-width:2;}.face{stroke:#222;stroke-width:2;}.sh{fill:#bdbdbd;}.wh{fill:#ffffff;}</style>' +
        body +
        '<text x="100" y="190" font-family="sans-serif" font-size="14" text-anchor="middle" fill="#222">' + label + '</text>' +
        '</svg>';
    }

    // CUBE: front or top face shaded -> square
    function cube(which) {
      var f = which === 'front';
      var t = which === 'top';
      var fr = f ? 'sh' : 'wh';
      var tp = t ? 'sh' : 'wh';
      var body =
        '<polygon class="face ' + tp + '" points="50,55 120,55 150,30 80,30"/>' +
        '<polygon class="face wh" points="120,55 150,30 150,110 120,135"/>' +
        '<polygon class="face ' + fr + '" points="50,55 120,55 120,135 50,135"/>';
      return svgWrap(body, 'cube');
    }

    // CUBOID: front oblong face shaded -> rectangle. Front face is clearly oblong.
    function cuboid() {
      var body =
        '<polygon class="face wh" points="40,70 150,70 175,45 65,45"/>' +
        '<polygon class="face wh" points="150,70 175,45 175,105 150,130"/>' +
        '<polygon class="face sh" points="40,70 150,70 150,130 40,130"/>';
      return svgWrap(body, 'cuboid');
    }

    // CYLINDER: top flat end shaded -> circle
    function cylinder() {
      var body =
        '<path class="edge" d="M50,55 L50,135"/>' +
        '<path class="edge" d="M150,55 L150,135"/>' +
        '<path class="edge" d="M50,135 A50,18 0 0 0 150,135"/>' +
        '<ellipse class="face sh" cx="100" cy="55" rx="50" ry="18"/>';
      return svgWrap(body, 'cylinder');
    }

    // CONE: flat base shaded -> circle
    function cone() {
      var body =
        '<path class="edge" d="M100,30 L52,130"/>' +
        '<path class="edge" d="M100,30 L148,130"/>' +
        '<ellipse class="face sh" cx="100" cy="130" rx="48" ry="16"/>';
      return svgWrap(body, 'cone');
    }

    // SQUARE-BASED PYRAMID: a sloping triangular face shaded -> triangle
    function pyramid() {
      var bl = '50,130', br = '150,130', bb = '100,150', bt = '100,110';
      var ap = '100,30';
      var body =
        '<polygon class="face wh" points="' + bl + ' ' + bt + ' ' + br + ' ' + bb + '"/>' +
        '<polygon class="face wh" points="' + ap + ' ' + br + ' ' + bb + '"/>' +
        '<polygon class="face sh" points="' + ap + ' ' + bl + ' ' + bb + '"/>';
      return svgWrap(body, 'square-based pyramid');
    }

    var options = [];
    options.push({ q: 'cube', mk: function () { return cube(pick(['front', 'top'])); }, ans: 'Square' });
    options.push({ q: 'cuboid', mk: function () { return cuboid(); }, ans: 'Rectangle' });
    options.push({ q: 'cylinder', mk: function () { return cylinder(); }, ans: 'Circle' });
    options.push({ q: 'cone', mk: function () { return cone(); }, ans: 'Circle' });
    options.push({ q: 'square-based pyramid', mk: function () { return pyramid(); }, ans: 'Triangle' });

    var choice = pick(options);
    var qhtml = choice.mk();

    var qtn = 'Look at this 3-D shape (a ' + choice.q +
      '). One face is shaded grey. What is the name of the 2-D shape of the shaded face?';

    return { qtn: qtn, ans: choice.ans, qhtml: qhtml };
  };

  // Y2 · Geometry › Compare & sort  [geo_sort_shapes_by_sides]
  G['geo_sort_shapes_by_sides'] = function (d) {
    // Shape catalogue: side count for each clearly-drawn regular shape.
    // Several shape "skins" share a side count so a side-count rule can match
    // 2-4 distinct-looking shapes in a 6-shape row.
    var SHAPES = {
      circle:    { sides: 0, kind: 'circle' },
      triangle:  { sides: 3, kind: 'poly' },
      square:    { sides: 4, kind: 'square' },
      rectangle: { sides: 4, kind: 'rect' },
      pentagon:  { sides: 5, kind: 'poly' },
      hexagon:   { sides: 6, kind: 'poly' }
    };
    // Side-count -> the shape names that realise it (gives several skins per count).
    var BY_SIDES = {
      0: ['circle'],
      3: ['triangle'],
      4: ['square', 'rectangle'],
      5: ['pentagon'],
      6: ['hexagon']
    };

    // SVG drawing helper (self-contained). `rot` rotates polygons a little so
    // repeated shapes of the same kind still look distinct.
    function shapeSVG(name, rot) {
      var S = 70, c = S / 2, R = c - 8;
      var fill = '#eef6fb', stroke = '#205072', sw = 2.2;
      var open = '<svg width="' + S + '" height="' + S + '" viewBox="0 0 ' + S + ' ' + S +
        '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="shape">';
      var kind = SHAPES[name].kind;
      if (kind === 'circle') {
        return open + '<circle cx="' + c + '" cy="' + c + '" r="' + R + '" fill="' + fill +
          '" stroke="' + stroke + '" stroke-width="' + sw + '"/></svg>';
      }
      if (kind === 'rect') {
        var w = R * 1.85, hh = R * 1.05;
        return open + '<rect x="' + (c - w / 2).toFixed(1) + '" y="' + (c - hh / 2).toFixed(1) +
          '" width="' + w.toFixed(1) + '" height="' + hh.toFixed(1) + '" fill="' + fill +
          '" stroke="' + stroke + '" stroke-width="' + sw + '"/></svg>';
      }
      if (kind === 'square') {
        var sq = R * 1.45;
        return open + '<rect x="' + (c - sq / 2).toFixed(1) + '" y="' + (c - sq / 2).toFixed(1) +
          '" width="' + sq.toFixed(1) + '" height="' + sq.toFixed(1) + '" fill="' + fill +
          '" stroke="' + stroke + '" stroke-width="' + sw + '"/></svg>';
      }
      var sides = SHAPES[name].sides;
      var start = -90 + (rot || 0); // point-up, plus optional small rotation
      var pts = [];
      for (var i = 0; i < sides; i++) {
        var ang = (start + i * 360 / sides) * Math.PI / 180;
        pts.push((c + R * Math.cos(ang)).toFixed(1) + ',' + (c + R * Math.sin(ang)).toFixed(1));
      }
      return open + '<polygon points="' + pts.join(' ') + '" fill="' + fill + '" stroke="' +
        stroke + '" stroke-width="' + sw + '" stroke-linejoin="round"/></svg>';
    }

    // Possible side-count targets and their phrasing.
    var TARGETS = [
      { sides: 0, phrase: 'are round (have 0 straight sides)' },
      { sides: 3, phrase: 'have 3 sides' },
      { sides: 4, phrase: 'have 4 sides' },
      { sides: 5, phrase: 'have 5 sides' },
      { sides: 6, phrase: 'have 6 sides' }
    ];

    // Pick a target side-count, then build a 6-shape row with 2-4 matching shapes
    // and at least 2 non-matching shapes. Shapes may repeat in kind but are drawn
    // with size/rotation variation; each cell gets a distinct letter A-F.
    var target = pick(TARGETS);
    var matchN = ri(2, 4);              // how many shapes satisfy the rule
    var otherN = 6 - matchN;            // remaining shapes (>=2)

    var otherSides = [];               // side-counts available that are NOT the target
    for (var s = 0; s <= 6; s++) {
      if (s !== target.sides && BY_SIDES[s]) otherSides.push(s);
    }

    var rowShapes = [];               // {name, match:bool}
    var ci;
    for (ci = 0; ci < matchN; ci++) {
      var mn = pick(BY_SIDES[target.sides]);
      rowShapes.push({ name: mn, match: true });
    }
    for (ci = 0; ci < otherN; ci++) {
      var os = pick(otherSides);
      var on = pick(BY_SIDES[os]);
      rowShapes.push({ name: on, match: false });
    }
    rowShapes = shuffle(rowShapes);

    var letters = ['A', 'B', 'C', 'D', 'E', 'F'];
    var cells = [];
    var matchLetters = [];
    var rots = [0, 18, -18, 36, -36, 12];
    for (var j = 0; j < 6; j++) {
      var item = rowShapes[j];
      var L = letters[j];
      if (item.match) matchLetters.push(L);
      cells.push(
        '<div style="display:inline-block;text-align:center;margin:0 6px;vertical-align:top;">' +
        shapeSVG(item.name, rots[j % rots.length]) +
        '<div style="font-weight:bold;font-size:15px;margin-top:2px;">' + L + '</div></div>'
      );
    }

    var qhtml = '<div style="white-space:nowrap;">' + cells.join('') + '</div>';
    var qtn = 'Look at the shapes. Write the letters of all the shapes that ' + target.phrase + '.';
    var ans = matchLetters.join(' and ');

    return { qtn: qtn, ans: ans, qhtml: qhtml };
  };

  // Y2 · Geometry › Patterns & sequences  [geo_repeating_pattern_next]
  G['geo_repeating_pattern_next'] = function (d) {
    // colour palette and shape kinds
    var COLOURS = [
      { name: 'red',    hex: '#e23b3b' },
      { name: 'blue',   hex: '#2f6fdb' },
      { name: 'green',  hex: '#2e9e4f' },
      { name: 'yellow', hex: '#f2c12e' },
      { name: 'purple', hex: '#8a4fd0' },
      { name: 'orange', hex: '#ef8a2b' }
    ];
    var SHAPES = ['circle', 'square', 'triangle'];

    // draw a single shape inside a 40x40 area at offset (ox,oy)
    function shapeSVG(item, ox, oy) {
      var c = item.colour.hex;
      if (item.shape === 'circle') {
        return '<circle cx="' + (ox + 20) + '" cy="' + (oy + 20) + '" r="15" fill="' + c + '" stroke="#333" stroke-width="1"/>';
      }
      if (item.shape === 'square') {
        return '<rect x="' + (ox + 6) + '" y="' + (oy + 6) + '" width="28" height="28" fill="' + c + '" stroke="#333" stroke-width="1"/>';
      }
      // triangle
      var x1 = ox + 20, y1 = oy + 5;
      var x2 = ox + 5,  y2 = oy + 35;
      var x3 = ox + 35, y3 = oy + 35;
      return '<polygon points="' + x1 + ',' + y1 + ' ' + x2 + ',' + y2 + ' ' + x3 + ',' + y3 + '" fill="' + c + '" stroke="#333" stroke-width="1"/>';
    }

    function cellRect(ox, oy, dashed) {
      var dash = dashed ? ' stroke-dasharray="4,3"' : '';
      var stroke = dashed ? '#777' : '#ccc';
      return '<rect x="' + ox + '" y="' + oy + '" width="40" height="40" fill="none" stroke="' + stroke + '" stroke-width="1.5"' + dash + '/>';
    }

    function label(item) {
      return item.colour.name + ' ' + item.shape;
    }

    // --- build the repeat unit (2 or 3 distinct shape+colour combos) ---
    var unitLen = pick([2, 3]);
    var shapesForUnit = shuffle(SHAPES).slice(0, unitLen);
    var coloursForUnit = shuffle(COLOURS).slice(0, unitLen);
    var unit = [];
    var i;
    for (i = 0; i < unitLen; i++) {
      unit.push({ shape: shapesForUnit[i], colour: coloursForUnit[i] });
    }

    // number of next terms to ask for (1 or 2)
    var askNext = pick([1, 2]);
    // ensure asked terms fit within one further cycle of the unit
    if (askNext >= unitLen) askNext = 1;

    // number of full unit repeats, then break mid-unit so shown ends with a
    // partial unit. Total shown shapes ~ 7-9.
    // shownShapes = repeats*unitLen + breakOffset, where 1<=breakOffset<unitLen
    var breakOffset = ri(1, unitLen - 1); // how many of the next unit are shown before blanks
    // choose repeats so total shown shapes lands ~7-9
    var repeats;
    var target = ri(7, 9);
    repeats = Math.round((target - breakOffset) / unitLen);
    if (repeats < 2) repeats = 2;
    if (repeats > 3) repeats = 3;

    var shownCount = repeats * unitLen + breakOffset;

    // Build the full conceptual sequence index -> item via unit cycling.
    function termAt(idx) { return unit[idx % unitLen]; }

    // The shown filled cells are indices 0..shownCount-1.
    // The blank cells are indices shownCount..shownCount+askNext-1.
    var answers = [];
    for (i = 0; i < askNext; i++) {
      answers.push(termAt(shownCount + i));
    }

    // --- render SVG row ---
    var totalCells = shownCount + askNext;
    var cellW = 44; // 40 + small gap
    var pad = 6;
    var w = pad * 2 + totalCells * cellW;
    var h = 52;
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '" viewBox="0 0 ' + w + ' ' + h + '" role="img">';
    var x;
    for (i = 0; i < shownCount; i++) {
      x = pad + i * cellW;
      svg += cellRect(x, 6, false);
      svg += shapeSVG(termAt(i), x, 6);
    }
    for (i = 0; i < askNext; i++) {
      x = pad + (shownCount + i) * cellW;
      svg += cellRect(x, 6, true);
    }
    svg += '</svg>';

    // --- answer text ---
    var ansText;
    if (askNext === 1) {
      ansText = 'A ' + label(answers[0]);
    } else {
      var parts = [];
      for (i = 0; i < answers.length; i++) parts.push(label(answers[i]));
      ansText = parts.join(', then ');
    }

    // --- question text ---
    var qtn;
    if (askNext === 1) {
      qtn = 'Look at the repeating pattern. What comes next? Draw the missing shape in the empty box.';
    } else {
      qtn = 'Look at the repeating pattern. What comes next? Draw the missing shapes in the empty boxes (in order).';
    }

    return { qtn: qtn, qhtml: svg, ans: ansText };
  };

  // Y2 · Geometry › Position & rotation  [geo_position_rotation]
  G['geo_position_rotation'] = function (d) {
    var DIRS = ['up', 'right', 'down', 'left']; // clockwise order
    var start = pick(DIRS);
    var turn = pick([
      { name: 'quarter', steps: 1 },
      { name: 'half', steps: 2 },
      { name: 'three-quarter', steps: 3 }
    ]);
    var rot = pick([
      { name: 'clockwise', sign: 1 },
      { name: 'anti-clockwise', sign: -1 }
    ]);
    var si = DIRS.indexOf(start);
    var ei = ((si + rot.sign * turn.steps) % 4 + 4) % 4;
    var end = DIRS[ei];

    // angle for arrow rotation in SVG: up=0, right=90, down=180, left=270
    var ang = { up: 0, right: 90, down: 180, left: 270 }[start];
    var cx = 60, cy = 60;
    // arrow drawn pointing up at angle 0, rotated about centre
    var arrow =
      '<g transform="rotate(' + ang + ' ' + cx + ' ' + cy + ')">' +
      '<line x1="' + cx + '" y1="100" x2="' + cx + '" y2="28" stroke="#111" stroke-width="6" stroke-linecap="round"/>' +
      '<polygon points="' + cx + ',16 ' + (cx - 14) + ',40 ' + (cx + 14) + ',40" fill="#111"/>' +
      '</g>';
    var svg =
      '<svg width="120" height="120" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="arrow pointing ' + start + '">' +
      '<rect x="1" y="1" width="118" height="118" fill="none" stroke="#999" stroke-width="1"/>' +
      '<circle cx="' + cx + '" cy="' + cy + '" r="46" fill="none" stroke="#bbb" stroke-width="1" stroke-dasharray="4 4"/>' +
      arrow +
      '</svg>';
    var choices = '<div style="margin-top:6px;font-size:18px;">Circle one: up&nbsp;/&nbsp;right&nbsp;/&nbsp;down&nbsp;/&nbsp;left</div>';

    var qtn = 'The arrow below points ' + start.toUpperCase() + '. It makes a ' +
      turn.name + ' turn ' + rot.name.toUpperCase() +
      '. Which way does it point now? Circle one: up / right / down / left.';

    return { qtn: qtn, qhtml: svg + choices, ans: end };
  };

  // Y3 · Place value › Representations & estimate  [pv_dienes_represent_estimate]
  G['pv_dienes_represent_estimate'] = function (d) {
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}

    // ---- Estimate-on-a-number-line variant (Greater Depth / exceeding) ----
    if (d >= 4) {
      var tens = [10,20,30,40,50,60,70,80,90];
      var target = pick(tens.slice(1,8)); // 20..80 so neighbours exist on 0..100
      var nearest = Math.round(target/10)*10;
      // place the arrow at a non-multiple-of-10 value (genuine estimate)
      var arrowVal = nearest + pick([-3,-2,-1,1,2,3]);
      if (arrowVal < 1) arrowVal = 1; if (arrowVal > 99) arrowVal = 99;
      var closest = Math.round(arrowVal/10)*10;
      if (closest < 10) closest = 10; if (closest > 90) closest = 90;
      // build 3 options: closest + two distractors (other multiples of 10)
      var opts = [closest];
      var candidates = [];
      for (var t=10;t<=90;t+=10){ if(t!==closest) candidates.push(t); }
      candidates = shuffle(candidates);
      opts.push(candidates[0]); opts.push(candidates[1]);
      opts = shuffle(opts);

      var W=520,Hh=120,mx=40,lineY=70,lw=W-2*mx;
      function px(v){ return mx + (v/100)*lw; }
      var svg='';
      svg+='<svg xmlns="http://www.w3.org/2000/svg" width="'+W+'" height="'+Hh+'" viewBox="0 0 '+W+' '+Hh+'" font-family="sans-serif">';
      svg+='<line x1="'+px(0)+'" y1="'+lineY+'" x2="'+px(100)+'" y2="'+lineY+'" stroke="#222" stroke-width="2"/>';
      [0,100].forEach(function(v){
        svg+='<line x1="'+px(v)+'" y1="'+(lineY-8)+'" x2="'+px(v)+'" y2="'+(lineY+8)+'" stroke="#222" stroke-width="2"/>';
        svg+='<text x="'+px(v)+'" y="'+(lineY+28)+'" font-size="14" text-anchor="middle">'+v+'</text>';
      });
      var ax=px(arrowVal);
      svg+='<line x1="'+ax+'" y1="'+(lineY-34)+'" x2="'+ax+'" y2="'+(lineY-4)+'" stroke="#c00" stroke-width="2"/>';
      svg+='<polygon points="'+(ax-5)+','+(lineY-12)+' '+(ax+5)+','+(lineY-12)+' '+ax+','+(lineY-2)+'" fill="#c00"/>';
      svg+='<text x="'+ax+'" y="'+(lineY-40)+'" font-size="14" text-anchor="middle" fill="#c00">?</text>';
      svg+='</svg>';

      var qtnE='The arrow points to a number on the line from 0 to 100. Circle the multiple of 10 it is closest to: '
              + opts.join('   ');
      return { qtn: qtnE, ans: closest, qhtml: svg };
    }

    // ---- Base 10 (Dienes) blocks: read-the-representation (below / meeting) ----
    var hundreds, tens2, ones2;
    if (d <= 2) {
      hundreds = ri(1,3);
      tens2 = ri(0,5);
      ones2 = ri(0,6);
    } else {
      hundreds = ri(1,5);
      tens2 = ri(1,8);
      ones2 = ri(1,8);
    }
    var num = hundreds*100 + tens2*10 + ones2;

    var unit=10, gap=14, pad=10;
    var flatW=unit*10, rodW=unit, cubeW=unit;
    var groupGap=22;
    var hW = hundreds? hundreds*flatW + (hundreds-1)*gap : 0;
    var tW = tens2? tens2*rodW + (tens2-1)*gap : 0;
    var oCols = Math.min(ones2, 5);
    var oW = ones2? oCols*cubeW + (oCols-1)*gap : 0;
    var totalW = pad*2 + hW + tW + oW
               + (hundreds&&(tens2||ones2)?groupGap:0)
               + (tens2&&ones2?groupGap:0);
    if (totalW < 160) totalW = 160;
    var totalH = pad*2 + flatW + 26;

    function flatSVG(x,y){
      var s='<g>';
      s+='<rect x="'+x+'" y="'+y+'" width="'+flatW+'" height="'+flatW+'" fill="#dbeafe" stroke="#1d4ed8" stroke-width="1.5"/>';
      for(var k=1;k<10;k++){
        s+='<line x1="'+(x+k*unit)+'" y1="'+y+'" x2="'+(x+k*unit)+'" y2="'+(y+flatW)+'" stroke="#1d4ed8" stroke-width="0.6"/>';
        s+='<line x1="'+x+'" y1="'+(y+k*unit)+'" x2="'+(x+flatW)+'" y2="'+(y+k*unit)+'" stroke="#1d4ed8" stroke-width="0.6"/>';
      }
      return s+'</g>';
    }
    function rodSVG(x,y){
      var s='<g>';
      s+='<rect x="'+x+'" y="'+y+'" width="'+rodW+'" height="'+flatW+'" fill="#dcfce7" stroke="#15803d" stroke-width="1.5"/>';
      for(var k=1;k<10;k++) s+='<line x1="'+x+'" y1="'+(y+k*unit)+'" x2="'+(x+rodW)+'" y2="'+(y+k*unit)+'" stroke="#15803d" stroke-width="0.6"/>';
      return s+'</g>';
    }
    function cubeSVG(x,y){
      return '<rect x="'+x+'" y="'+y+'" width="'+cubeW+'" height="'+cubeW+'" fill="#fee2e2" stroke="#b91c1c" stroke-width="1.2"/>';
    }

    var svg='<svg xmlns="http://www.w3.org/2000/svg" width="'+totalW+'" height="'+totalH+'" viewBox="0 0 '+totalW+' '+totalH+'" font-family="sans-serif">';
    var cx=pad, baseY=pad;
    for(var i=0;i<hundreds;i++){ svg+=flatSVG(cx,baseY); cx+=flatW+gap; }
    if(hundreds && (tens2||ones2)) cx+=groupGap-gap;
    for(var j=0;j<tens2;j++){ svg+=rodSVG(cx,baseY); cx+=rodW+gap; }
    if(tens2 && ones2) cx+=groupGap-gap;
    var oStartX=cx;
    for(var u=0;u<ones2;u++){
      var col=Math.floor(u/5), row=u%5;
      svg+=cubeSVG(oStartX+col*(cubeW+gap), baseY+row*(cubeW+2));
    }
    svg+='<text x="'+pad+'" y="'+(totalH-6)+'" font-size="12" fill="#555">H='+hundreds+'  T='+tens2+'  O='+ones2+'</text>';
    svg+='</svg>';

    var qtn='What number is shown by these Base 10 (Dienes) blocks? '
          + 'Count the hundred-squares, ten-rods and unit cubes, then write the number in the box: ____';
    return { qtn: qtn, ans: num, qhtml: svg };
  };

  // Y3 · Geometry › Orientations  [geo_3d_orientation_name]
  G['geo_3d_orientation_name'] = function (d) {
    var esc = function (s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    };
    var S = 150, fill = '#dbeafe', stroke = '#1e3a5f', sw = 2, dash = '4 3';
    var open = '<svg class="tp-shape3d" width="' + S + '" height="' + S + '" viewBox="0 0 ' + S + ' ' + S +
      '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="3-D shape in an unusual orientation">';
    var close = '</svg>';
    var poly = function (pts, hidden) {
      return '<polygon points="' + pts.join(' ') + '" fill="' + (hidden ? 'none' : fill) + '" stroke="' + stroke +
        '" stroke-width="' + sw + '" stroke-linejoin="round"' + (hidden ? ' stroke-dasharray="' + dash + '"' : '') + '/>';
    };
    var pline = function (pts, hidden) {
      return '<polyline points="' + pts.join(' ') + '" fill="none" stroke="' + stroke + '" stroke-width="' + sw +
        '"' + (hidden ? ' stroke-dasharray="' + dash + '"' : '') + '/>';
    };
    var ell = function (cx, cy, rx, ry, hidden, filled) {
      return '<ellipse cx="' + cx + '" cy="' + cy + '" rx="' + rx + '" ry="' + ry + '" fill="' +
        (filled ? fill : 'none') + '" stroke="' + stroke + '" stroke-width="' + sw + '"' +
        (hidden ? ' stroke-dasharray="' + dash + '"' : '') + '/>';
    };
    var path = function (dstr, hidden, filled) {
      return '<path d="' + dstr + '" fill="' + (filled ? fill : 'none') + '" stroke="' + stroke +
        '" stroke-width="' + sw + '"' + (hidden ? ' stroke-dasharray="' + dash + '"' : '') + '/>';
    };

    var SHAPES = {
      'cube': [
        function () {
          return open + poly(['35,55', '95,40', '120,70', '60,85']) +
            poly(['35,55', '60,85', '60,130', '35,100']) +
            poly(['60,85', '120,70', '120,115', '60,130']) +
            pline(['35,55', '95,40', '95,85', '120,70'], true) +
            pline(['95,85', '60,85'], true) + close;
        },
        function () {
          return open + poly(['75,30', '110,65', '75,100', '40,65']) +
            poly(['110,65', '75,100', '90,120', '125,85']) +
            poly(['75,100', '40,65', '55,85', '90,120']) +
            pline(['40,65', '55,45', '90,80', '125,85'], true) +
            pline(['55,45', '75,30'], true) + pline(['90,80', '75,100'], true) + close;
        }
      ],
      'cuboid': [
        function () {
          return open + poly(['25,80', '105,55', '125,72', '45,97']) +
            poly(['25,80', '45,97', '45,122', '25,105']) +
            poly(['45,97', '125,72', '125,97', '45,122']) +
            pline(['25,80', '105,55', '105,80', '125,72'], true) +
            pline(['105,80', '45,97'], true) + close;
        },
        function () {
          return open + poly(['40,40', '70,30', '85,45', '55,55']) +
            poly(['40,40', '55,55', '95,120', '80,105']) +
            poly(['55,55', '85,45', '125,110', '95,120']) +
            pline(['40,40', '70,30', '110,95', '85,45'], true) +
            pline(['110,95', '125,110'], true) + close;
        }
      ],
      'cylinder': [
        function () {
          return open +
            '<path d="M40,50 L110,50 A12,30 0 0 1 110,110 L40,110 A12,30 0 0 1 40,50 Z" fill="' + fill +
            '" stroke="' + stroke + '" stroke-width="' + sw + '"/>' +
            ell(110, 80, 12, 30, false, false) +
            '<path d="M40,50 A12,30 0 0 0 40,110" fill="none" stroke="' + stroke + '" stroke-width="' + sw +
            '" stroke-dasharray="' + dash + '"/>' + close;
        },
        function () {
          return open +
            path('M45,40 L100,75 A26,12 0 0 1 78,108 L23,73 A26,12 0 0 1 45,40 Z', false, true) +
            '<ellipse cx="61" cy="56" rx="26" ry="12" fill="' + fill + '" stroke="' + stroke + '" stroke-width="' + sw +
            '" transform="rotate(32 61 56)"/>' + close;
        }
      ],
      'cone': [
        function () {
          return open +
            '<path d="M40,40 L120,75 L40,110 A14,35 0 0 1 40,40 Z" fill="' + fill + '" stroke="' + stroke +
            '" stroke-width="' + sw + '"/>' +
            '<path d="M40,40 A14,35 0 0 0 40,110" fill="none" stroke="' + stroke + '" stroke-width="' + sw +
            '" stroke-dasharray="' + dash + '"/>' + close;
        },
        function () {
          return open +
            '<path d="M115,40 L40,115 L95,118 A33,11 0 0 0 128,98 Z" fill="' + fill + '" stroke="' + stroke +
            '" stroke-width="' + sw + '"/>' +
            '<path d="M95,118 A33,11 0 0 1 62,95" fill="none" stroke="' + stroke + '" stroke-width="' + sw +
            '" stroke-dasharray="' + dash + '"/>' +
            '<line x1="62" y1="95" x2="40" y2="115" stroke="' + stroke + '" stroke-width="' + sw +
            '" stroke-dasharray="' + dash + '"/>' + close;
        }
      ],
      'sphere': [
        function () {
          return open + '<circle cx="75" cy="75" r="48" fill="' + fill + '" stroke="' + stroke +
            '" stroke-width="' + sw + '"/>' +
            '<ellipse cx="75" cy="75" rx="48" ry="16" fill="none" stroke="' + stroke + '" stroke-width="' + sw +
            '" transform="rotate(28 75 75)"/>' + close;
        },
        function () {
          return open + '<circle cx="75" cy="75" r="46" fill="' + fill + '" stroke="' + stroke +
            '" stroke-width="' + sw + '"/>' +
            '<ellipse cx="75" cy="75" rx="16" ry="46" fill="none" stroke="' + stroke + '" stroke-width="' + sw +
            '" transform="rotate(-22 75 75)"/>' + close;
        }
      ],
      'square-based pyramid': [
        function () {
          return open +
            poly(['30,95', '85,110', '110,90', '55,78']) +
            poly(['30,95', '55,78', '120,35']) +
            poly(['55,78', '110,90', '120,35']) +
            pline(['30,95', '85,110', '120,35'], true) + close;
        },
        function () {
          return open +
            poly(['115,45', '125,100', '95,115', '88,60']) +
            poly(['115,45', '88,60', '30,80']) +
            poly(['88,60', '95,115', '30,80']) +
            pline(['115,45', '125,100', '30,80'], true) + close;
        }
      ]
    };

    var names = ['cube', 'cuboid', 'cylinder', 'cone', 'sphere', 'square-based pyramid'];
    var chosen = pick(names);
    var variants = SHAPES[chosen];
    var svg = pick(variants)();

    var rows = '';
    for (var i = 0; i < names.length; i++) {
      rows += '<span class="tp-choice" style="display:inline-block;min-width:170px;margin:2px 0;">' +
        '<span style="display:inline-block;width:14px;height:14px;border:1.5px solid ' + stroke +
        ';vertical-align:middle;margin-right:6px;"></span>' + esc(names[i]) + '</span>';
    }
    var qhtml = '<div class="tp-3d-orient">' + svg +
      '<div class="tp-choices" style="margin-top:6px;line-height:1.6;">' + rows + '</div></div>';

    return {
      qtn: 'Tick the name of this 3-D shape. ' +
        '(cube / cuboid / cylinder / cone / sphere / square-based pyramid)',
      qhtml: qhtml,
      ans: chosen
    };
  };

  // Y3 · Geometry › Horizontal & vertical  [geo_horizontal_vertical_lines]
  G['geo_horizontal_vertical_lines'] = function (d) {
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
    var cell = 30, cells = 8, pad = 14;
    var W = cell * cells + pad * 2, Hh = cell * cells + pad * 2;
    function gx(c){ return pad + c * cell; }
    function gy(r){ return pad + r * cell; }

    // exceeding rotates the whole figure by a small offset; below prints a word bank
    var below = (d <= 2), exceeding = (d >= 4);

    // build the 4 line definitions (in grid coords, before any rotation)
    // horizontal: fixed row, spanning cols
    var hRow = ri(1, 6);
    var hC0 = ri(0, 3), hC1 = hC0 + ri(3, 4); if (hC1 > 8) hC1 = 8;
    var horiz = { x1: gx(hC0), y1: gy(hRow), x2: gx(hC1), y2: gy(hRow) };

    // vertical: fixed col, spanning rows
    var vCol = ri(1, 6);
    var vR0 = ri(0, 3), vR1 = vR0 + ri(3, 4); if (vR1 > 8) vR1 = 8;
    var vert = { x1: gx(vCol), y1: gy(vR0), x2: gx(vCol), y2: gy(vR1) };

    // diagonal: steep but not 45 (rise != run), never near-axis
    function diag(dir){
      var c0 = ri(0, 2), r0 = ri(2, 5);
      var run = pick([2, 4]);      // horizontal span in cells
      var rise = pick([3, 4]);     // vertical span in cells
      if (run === rise) run = (run === 2 ? 4 : 2);
      var c1 = c0 + run;
      if (c1 > 8) { c1 = 8; c0 = c1 - run; if (c0 < 0) c0 = 0; }
      var rr1, rr0;
      if (dir > 0) { // up to the right: y decreases as x increases
        rr0 = r0; rr1 = r0 - rise;
        if (rr1 < 0) { rr1 = 0; rr0 = rr1 + rise; }
      } else { // down to the right
        rr0 = r0 - rise; if (rr0 < 0) rr0 = 0; rr1 = rr0 + rise;
        if (rr1 > 8) { rr1 = 8; rr0 = rr1 - rise; }
      }
      return { x1: gx(c0), y1: gy(rr0), x2: gx(c1), y2: gy(rr1) };
    }
    var dA = diag(1), dB = diag(-1);

    var lines = [horiz, vert, dA, dB];
    var roles = ['horizontal', 'vertical', 'diag', 'diag'];

    // assign letters by shuffling order
    var order = shuffle([0, 1, 2, 3]);
    var letters = ['A', 'B', 'C', 'D'];
    var assigned = [];
    for (var i = 0; i < 4; i++) {
      assigned.push({ line: lines[order[i]], role: roles[order[i]], letter: letters[i] });
    }

    // pick target word
    var target = pick(['horizontal', 'vertical']);
    var ansLetter = null;
    for (var k = 0; k < 4; k++) if (assigned[k].role === target) ansLetter = assigned[k].letter;

    // optional small rotation for exceeding (rotate whole figure about centre)
    var rot = exceeding ? pick([-8, -6, 6, 8]) : 0;
    var cx = W / 2, cy = Hh / 2;
    var ca = Math.cos(rot * Math.PI / 180), sa = Math.sin(rot * Math.PI / 180);
    function rx(x, y){ return cx + (x - cx) * ca - (y - cy) * sa; }
    function ry(x, y){ return cy + (x - cx) * sa + (y - cy) * ca; }
    function R(v){ return Math.round(v * 100) / 100; }

    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + Hh +
      '" viewBox="0 0 ' + W + ' ' + Hh + '">';
    svg += '<rect x="0" y="0" width="' + W + '" height="' + Hh + '" fill="#ffffff"/>';
    var gi;
    for (gi = 0; gi <= cells; gi++) {
      var lx = gx(gi);
      svg += '<line x1="' + R(rx(lx, gy(0))) + '" y1="' + R(ry(lx, gy(0))) +
        '" x2="' + R(rx(lx, gy(cells))) + '" y2="' + R(ry(lx, gy(cells))) +
        '" stroke="#dddddd" stroke-width="1"/>';
      var lyv = gy(gi);
      svg += '<line x1="' + R(rx(gx(0), lyv)) + '" y1="' + R(ry(gx(0), lyv)) +
        '" x2="' + R(rx(gx(cells), lyv)) + '" y2="' + R(ry(gx(cells), lyv)) +
        '" stroke="#dddddd" stroke-width="1"/>';
    }
    for (var m = 0; m < 4; m++) {
      var L = assigned[m].line;
      var X1 = rx(L.x1, L.y1), Y1 = ry(L.x1, L.y1), X2 = rx(L.x2, L.y2), Y2 = ry(L.x2, L.y2);
      svg += '<line x1="' + R(X1) + '" y1="' + R(Y1) + '" x2="' + R(X2) + '" y2="' + R(Y2) +
        '" stroke="#111111" stroke-width="3" stroke-linecap="round"/>';
      var mx = (X1 < X2 || (X1 === X2 && Y1 <= Y2)) ? X1 : X2;
      var my = (X1 < X2 || (X1 === X2 && Y1 <= Y2)) ? Y1 : Y2;
      var lblx = mx - 16, lbly = my - 6;
      if (lblx < 8) lblx = mx + 6;
      if (lbly < 14) lbly = my + 16;
      svg += '<text x="' + R(lblx) + '" y="' + R(lbly) +
        '" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#111111">' +
        esc(assigned[m].letter) + '</text>';
    }
    svg += '</svg>';

    var qtn = 'Look at the lines on the grid. Which line is ' + target + '? Write the letter.';
    if (below) {
      qtn += ' (horizontal = flat like the horizon, vertical = standing straight up)';
    }

    return { qtn: qtn, qhtml: svg, ans: ansLetter };
  };

  // Y3 · Geometry › Perpendicular & parallel  [geo_parallel_perpendicular]
  G.geo_parallel_perpendicular = function (d) {
    // ---------- self-contained helpers ----------
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    function rnd(v){return Math.round(v*100)/100;}
    function clamp(v,lo,hi){return v<lo?lo:(v>hi?hi:v);}
    // a line as centre point + direction (deg) + half-length -> two endpoints
    // (in the BASE frame, before the whole-figure rotation is applied).
    function seg(cx,cy,deg,half){
      var r=deg*Math.PI/180, dx=Math.cos(r)*half, dy=Math.sin(r)*half;
      return {p1:[cx-dx,cy-dy], p2:[cx+dx,cy+dy]};
    }

    // ---------- choose directions (in the base frame) ----------
    // The whole figure is built upright in a base frame, then rotated by baseDir
    // about the canvas centre. Rotation preserves all parallel/perpendicular
    // relationships AND all separations, so we get orientation variety for free
    // while keeping the layout uncrowded and unambiguous.
    var baseDir = ri(0,11)*15;          // shared whole-figure rotation
    var parDir  = pick([0, 90]);        // parallel pair runs horizontal or vertical
    // perpendicular pair sits at an oblique offset from the parallel pair so it is
    // neither parallel nor perpendicular to it.
    var offset  = pick([25, 30, 35, 40, 45, 50, 55, 60, 65]);
    if (Math.random()<0.5) offset = -offset;
    var perpDir = parDir + offset;      // perpA dir; perpB = perpDir+90

    // ---------- build the lines in the base frame, in SEPARATED regions ----------
    // Region split (base frame, viewBox 300x200): parallel pair on the LEFT,
    // perpendicular pair on the RIGHT, distractor tucked away. Short half-lengths
    // keep everything compact so lines do not stray into each other's region.
    // All coordinates are kept within a disc of radius ~62 about the centre
    // (150,100) so that, after rotation by ANY angle, every point stays inside the
    // 300x200 viewBox with margin — no clamping is ever needed (clamping would
    // bend a line and break its parallel/perpendicular relationship).
    // Parallel pair: two short segments, same direction, clearly separated.
    var parHalf = ri(26, 34);
    var sep = ri(30, 42);
    var leftCx = 108, leftCy = 100;
    var pnx = Math.cos((parDir+90)*Math.PI/180), pny = Math.sin((parDir+90)*Math.PI/180);
    var L_par1 = seg(leftCx + pnx*sep/2, leftCy + pny*sep/2, parDir, parHalf);
    var L_par2 = seg(leftCx - pnx*sep/2, leftCy - pny*sep/2, parDir, parHalf);

    // Perpendicular pair: cross at a right angle, placed on the right.
    var perpHalf = ri(26, 33);
    var crossX = 192 + ri(-6,6), crossY = 100 + ri(-10,10);
    var L_perpA = seg(crossX, crossY, perpDir, perpHalf);
    var L_perpB = seg(crossX, crossY, perpDir + 90, perpHalf);
    var rightAngle = { x:crossX, y:crossY, dA:perpDir, dB:perpDir+90 };

    // Distractor: oblique direction unlike any existing one, in the top/bottom gap.
    var existing = [parDir, perpDir, perpDir+90].map(function(a){return ((a%180)+180)%180;});
    function okDir(deg){
      deg=((deg%180)+180)%180;
      for(var i=0;i<existing.length;i++){
        var diff=Math.abs(deg-existing[i]); diff=Math.min(diff,180-diff);
        if(diff<14 || Math.abs(diff-90)<14) return false;
      }
      return true;
    }
    var dDeg, dt=0;
    do{ dDeg = ri(0,179); dt++; } while(!okDir(dDeg) && dt<80);
    if(!okDir(dDeg)) dDeg = parDir + 22;
    // place it in a band away from both clusters (top or bottom strip)
    var dTop = Math.random()<0.5;
    var dcx = ri(135, 165), dcy = dTop ? ri(46,56) : ri(144,154), dh = ri(20,28);
    var L_dist = seg(dcx, dcy, dDeg, dh);

    // ---------- rotate the whole figure about the canvas centre ----------
    var RA = baseDir*Math.PI/180, RC=Math.cos(RA), RS=Math.sin(RA), CX=150, CY=100;
    function rotPt(p){ var dx=p[0]-CX, dy=p[1]-CY; return [CX+dx*RC-dy*RS, CY+dx*RS+dy*RC]; }
    function rotLine(L){ return { p1:rotPt(L.p1), p2:rotPt(L.p2) }; }
    L_par1=rotLine(L_par1); L_par2=rotLine(L_par2);
    L_perpA=rotLine(L_perpA); L_perpB=rotLine(L_perpB); L_dist=rotLine(L_dist);
    var rc=rotPt([rightAngle.x, rightAngle.y]);
    rightAngle = { x:rc[0], y:rc[1], dA:perpDir+baseDir, dB:perpDir+90+baseDir };

    // ---------- assemble in role order then shuffle letter labels ----------
    // roles: par1, par2, perpA, perpB, dist
    var roleLines = [L_par1, L_par2, L_perpA, L_perpB, L_dist];
    var n = roleLines.length;
    var letters = shuffle(['A','B','C','D','E'].slice(0, n));
    // letters[i] is the label for roleLines[i]
    var labPar1=letters[0], labPar2=letters[1], labPerpA=letters[2], labPerpB=letters[3];

    function pairKey(a,b){return a<b ? a+' and '+b : b+' and '+a;}
    var parallelPair = pairKey(labPar1, labPar2);
    var perpPair = pairKey(labPerpA, labPerpB);

    // ---------- render SVG (rotate is already baked into directions) ----------
    // Clip endpoints to stay inside the viewBox padding.
    function fit(L){
      return { p1:[clamp(L.p1[0],8,292), clamp(L.p1[1],10,190)],
               p2:[clamp(L.p2[0],8,292), clamp(L.p2[1],10,190)] };
    }
    var parts=[];
    parts.push('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 200" width="300" height="200">');
    parts.push('<rect x="0" y="0" width="300" height="200" fill="white"/>');
    // First pass: draw all lines.
    var fitted=[];
    for(var i=0;i<roleLines.length;i++){
      var L=fit(roleLines[i]);
      fitted.push(L);
      parts.push('<line x1="'+rnd(L.p1[0])+'" y1="'+rnd(L.p1[1])+'" x2="'+rnd(L.p2[0])+'" y2="'+rnd(L.p2[1])+'" stroke="black" stroke-width="3"/>');
    }
    // Second pass: place labels so each is UNAMBIGUOUSLY closest to its own line.
    // For each line build candidate positions off each end (nudged outward along
    // the line and sideways), then pick the candidate that maximises a score
    // combining: (1) how much closer it sits to its own line than to any foreign
    // line, and (2) its distance from labels already placed. This guarantees a
    // reader attributes each letter to the correct line.
    function distToSeg(px,py,L){
      var x1=L.p1[0],y1=L.p1[1],x2=L.p2[0],y2=L.p2[1];
      var dx=x2-x1,dy=y2-y1,L2=dx*dx+dy*dy;
      var t=L2?((px-x1)*dx+(py-y1)*dy)/L2:0; if(t<0)t=0; if(t>1)t=1;
      return Math.sqrt((px-(x1+t*dx))*(px-(x1+t*dx))+(py-(y1+t*dy))*(py-(y1+t*dy)));
    }
    function candidates(L){
      var out=[];
      var ends=[ {a:L.p1,b:L.p2}, {a:L.p2,b:L.p1} ];
      for(var e=0;e<ends.length;e++){
        var a=ends[e].a, b=ends[e].b;
        var ox=a[0]-b[0], oy=a[1]-b[1], mag=Math.sqrt(ox*ox+oy*oy)||1;
        ox/=mag; oy/=mag;            // unit vector pointing outward past end a
        var px=-oy, py=ox;           // perpendicular (sideways)
        var offs=[ [13,0],[15,0],[18,0],[12,11],[12,-11],[9,14],[9,-14],[16,9],[16,-9],[20,5],[20,-5],[22,0] ];
        for(var o=0;o<offs.length;o++){
          var lx=clamp(a[0]+ox*offs[o][0]+px*offs[o][1], 9, 291);
          var ly=clamp(a[1]+oy*offs[o][0]+py*offs[o][1], 12, 192);
          out.push([lx,ly]); // ly is glyph centre; baseline added at render time
        }
      }
      return out;
    }
    var placed=[];
    for(var i=0;i<fitted.length;i++){
      var cands=candidates(fitted[i]);
      var bestPos=cands[0], bestScore=-1e9;
      for(var ci=0;ci<cands.length;ci++){
        var cx2=cands[ci][0], cy2=cands[ci][1];
        var own=distToSeg(cx2,cy2,fitted[i]);
        var nearestForeign=1e9;
        for(var fj=0;fj<fitted.length;fj++){ if(fj===i)continue;
          var df=distToSeg(cx2,cy2,fitted[fj]); if(df<nearestForeign)nearestForeign=df; }
        var labMin=1e9;
        for(var pj=0;pj<placed.length;pj++){
          var dl=Math.sqrt((cx2-placed[pj][0])*(cx2-placed[pj][0])+(cy2-placed[pj][1])*(cy2-placed[pj][1]));
          if(dl<labMin)labMin=dl; }
        // ownGap: positive when clearly nearer own line; clamp label term.
        var ownGap=nearestForeign-own;
        var labTerm=labMin>20?20:labMin;
        // Strongly prefer positions clearly nearer the own line; once the gap is
        // comfortable (>=10px) extra gap matters less, so cap its reward and let
        // label-spacing break ties.
        var gapReward=ownGap>14?14:ownGap;
        var score=gapReward*10 + labTerm;
        if(score>bestScore){bestScore=score; bestPos=cands[ci];}
      }
      placed.push(bestPos);
      parts.push('<text x="'+rnd(bestPos[0])+'" y="'+rnd(bestPos[1]+5)+'" font-family="sans-serif" font-size="16" font-weight="bold" fill="#1a1a8c">'+esc(letters[i])+'</text>');
    }
    // right-angle marker: small square in the corner of the perpendicular pair.
    var s=9;
    var ra1=rightAngle.dA*Math.PI/180, ra2=rightAngle.dB*Math.PI/180;
    var u=[Math.cos(ra1),Math.sin(ra1)], v=[Math.cos(ra2),Math.sin(ra2)];
    var c0=[rightAngle.x+u[0]*s, rightAngle.y+u[1]*s];
    var c1=[rightAngle.x+u[0]*s+v[0]*s, rightAngle.y+u[1]*s+v[1]*s];
    var c2=[rightAngle.x+v[0]*s, rightAngle.y+v[1]*s];
    parts.push('<polyline points="'+rnd(c0[0])+','+rnd(c0[1])+' '+rnd(c1[0])+','+rnd(c1[1])+' '+rnd(c2[0])+','+rnd(c2[1])+'" fill="none" stroke="black" stroke-width="2"/>');
    parts.push('</svg>');

    var qtn = 'Look at the lines below. Each line is labelled with a letter. The small square shows a right angle.\n\n' +
              '(a) Write the pair of lines that are PARALLEL.\n' +
              '(b) Write the pair of lines that are PERPENDICULAR.';
    var ans = '(a) ' + parallelPair + '   (b) ' + perpPair;

    return { qtn: qtn, ans: ans, qhtml: parts.join('') };
  };

  // Y4 · Place value › Representations & estimate  [pv_numberline_estimate_y4]
  G['pv_numberline_estimate_y4'] = function (d) {
    // Pick a friendly 4-digit-range number line. Range is a power-of-10-ish
    // interval (1000, or 100 for harder bands) so each of the 10 segments
    // is a clean step.
    var configs;
    if (d <= 2) {
      // Below: simple 0->1000, arrow always on a tick.
      configs = [[0, 1000]];
    } else if (d >= 4) {
      // Exceeding: wider variety, may land midway between ticks (multiple of 50).
      configs = [[0, 1000], [1000, 2000], [3000, 4000], [5000, 6000], [0, 100], [200, 300]];
    } else {
      // Meeting: friendly thousands intervals, arrow on a tick.
      configs = [[0, 1000], [1000, 2000], [2000, 3000], [3000, 4000], [6000, 7000]];
    }
    var cfg = pick(configs);
    var start = cfg[0], end = cfg[1];
    var range = end - start;
    var step = range / 10;

    // Decide arrow position. For exceeding, sometimes land between two ticks
    // at the half-step (a clean multiple of step/2).
    var halfVariant = (d >= 4 && step % 2 === 0 && Math.random() < 0.45);
    var tickIndex = ri(1, 9); // 1..9 unlabelled interior ticks
    var value, fracAlong;
    if (halfVariant) {
      // sits between tick (tickIndex-1) and tickIndex... use half steps 1..19 odd
      var halfIndex = ri(1, 19); // odd -> midway, even -> on a tick
      if (halfIndex % 2 === 0) halfIndex += 1; // force midway for this variant
      if (halfIndex > 19) halfIndex = 19;
      fracAlong = halfIndex / 20;
      value = start + halfIndex * (step / 2);
    } else {
      fracAlong = tickIndex / 10;
      value = start + tickIndex * step;
    }

    // Whether to also label the midpoint (only when it isn't where the arrow is).
    var labelMid = (Math.random() < 0.5) && Math.abs(fracAlong - 0.5) > 1e-9;

    // ---- SVG drawing (self-contained) ----
    var W = 480, Hh = 90, padL = 40, padR = 40;
    var x0 = padL, x1 = W - padR, baseY = 60;
    var esc = function (s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    };
    var px = function (f) { return x0 + f * (x1 - x0); };
    var parts = [];
    parts.push('<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + Hh + '" viewBox="0 0 ' + W + ' ' + Hh + '">');
    // baseline
    parts.push('<line x1="' + x0 + '" y1="' + baseY + '" x2="' + x1 + '" y2="' + baseY + '" stroke="#000" stroke-width="2"/>');
    // 11 ticks
    for (var t = 0; t <= 10; t++) {
      var fx = px(t / 10);
      var major = (t === 0 || t === 10);
      var tickH = major ? 14 : 9;
      parts.push('<line x1="' + fx.toFixed(1) + '" y1="' + (baseY - tickH) + '" x2="' + fx.toFixed(1) + '" y2="' + (baseY + tickH) + '" stroke="#000" stroke-width="' + (major ? 2 : 1) + '"/>');
    }
    // endpoint labels
    parts.push('<text x="' + px(0).toFixed(1) + '" y="' + (baseY + 30) + '" font-family="sans-serif" font-size="14" text-anchor="middle">' + esc(fmt(start)) + '</text>');
    parts.push('<text x="' + px(1).toFixed(1) + '" y="' + (baseY + 30) + '" font-family="sans-serif" font-size="14" text-anchor="middle">' + esc(fmt(end)) + '</text>');
    if (labelMid) {
      parts.push('<text x="' + px(0.5).toFixed(1) + '" y="' + (baseY + 30) + '" font-family="sans-serif" font-size="14" text-anchor="middle">' + esc(fmt(start + range / 2)) + '</text>');
    }
    // arrow (downward) sitting above the line at fracAlong
    var ax = px(fracAlong);
    var topY = 12, tipY = baseY - 4;
    parts.push('<line x1="' + ax.toFixed(1) + '" y1="' + topY + '" x2="' + ax.toFixed(1) + '" y2="' + tipY + '" stroke="#c00" stroke-width="3"/>');
    parts.push('<polygon points="' + (ax - 6).toFixed(1) + ',' + (tipY - 8) + ' ' + (ax + 6).toFixed(1) + ',' + (tipY - 8) + ' ' + ax.toFixed(1) + ',' + (tipY + 2) + '" fill="#c00"/>');
    parts.push('</svg>');
    var svg = parts.join('');

    return {
      qtn: 'Estimate the number the arrow is pointing to on the number line.',
      qhtml: svg,
      ans: fmt(value)
    };
  };

  // Y4 · Measurement › Estimate/compare/calculate  [meas_convert_compare_diff]
  G['meas_convert_compare_diff'] = function (d) {
    // Year 4 statutory metric pairs. A in larger unit, B in smaller unit.
    // Convert A to smaller unit, then difference (always positive).
    var families = [
      { big:'m',  small:'cm', factor:100,
        items:[ ['ribbon','length','long','longer'], ['plank','length','long','longer'], ['rope','length','long','longer'] ] },
      { big:'km', small:'m', factor:1000,
        items:[ ['route','distance','long','longer'], ['path','distance','long','longer'], ['track','distance','long','longer'] ] },
      { big:'kg', small:'g', factor:1000,
        items:[ ['parcel','mass','heavy','heavier'], ['sack','mass','heavy','heavier'], ['box','mass','heavy','heavier'] ] },
      { big:'l',  small:'ml', factor:1000,
        items:[ ['bottle','capacity','full','more'], ['jug','capacity','full','more'], ['tank','capacity','full','more'] ] }
    ];
    var fam = pick(families);
    var item = pick(fam.items);
    var noun = item[0];
    var compWord = item[3]; // longer / heavier / more

    var colours = ['blue','red','green','yellow','purple','orange'];
    var two = shuffle(colours);
    var cA = two[0], cB = two[1];

    var f = fam.factor;
    // Choose A in big units. For factor 100 (m/cm) allow whole or half.
    var aBig, aSmall;
    if (f === 100) {
      var aHalfSteps = ri(3, 12); // 1.5 .. 6.0 in half steps
      aBig = aHalfSteps / 2;       // e.g. 3, 3.5
      aSmall = aHalfSteps * 50;    // = aBig*100
    } else {
      var hs = ri(2, 8);           // 1.0 .. 4.0 in half steps
      aBig = hs / 2;
      aSmall = hs * 500;           // = aBig*1000
    }

    // Choose B in small units, strictly less than aSmall by a clear margin.
    var maxB = aSmall - Math.max(Math.round(f * 0.25), 1); // clear margin
    var step = (f === 100) ? 5 : 50;
    var bSteps = Math.floor(maxB / step);
    if (bSteps < 1) bSteps = 1;
    var bChoice = ri(Math.max(1, Math.ceil(bSteps * 0.2)), bSteps);
    var bSmall = bChoice * step;
    if (bSmall >= aSmall) bSmall = aSmall - step; // safety
    if (bSmall < 0) bSmall = 0;

    var diff = aSmall - bSmall; // answer in small units, always positive

    var bigU = fam.big, smallU = fam.small;
    var aBigStr = fmt(aBig);

    var qtn = 'A ' + cA + ' ' + noun + ' is ' + aBigStr + ' ' + bigU + '. ' +
              'A ' + cB + ' ' + noun + ' is ' + fmt(bSmall) + ' ' + smallU + '. ' +
              'How much ' + compWord + ' is the ' + cA + ' ' + noun + ' than the ' + cB + ' ' + noun + '? ' +
              'Give your answer in ' + smallU + '.';

    var ans = fmt(diff) + ' ' + smallU;

    return { qtn: qtn, ans: ans };
  };

  // Y4 · Geometry › Classify quad/triangles  [geo_classify_quad_triangle]
  G.geo_classify_quad_triangle = function (d) {
    // ---- self-contained helpers ----
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    function rnd(x){return Math.round(x*10)/10;}
    function rot(px,py,cx,cy,deg){
      var r=deg*Math.PI/180,dx=px-cx,dy=py-cy;
      return {x:cx+dx*Math.cos(r)-dy*Math.sin(r), y:cy+dx*Math.sin(r)+dy*Math.cos(r)};
    }
    function rotAll(pts,cx,cy,deg){var o=[];for(var i=0;i<pts.length;i++){o.push(rot(pts[i].x,pts[i].y,cx,cy,deg));}return o;}
    function ptsStr(pts){var s=[];for(var i=0;i<pts.length;i++){s.push(rnd(pts[i].x)+','+rnd(pts[i].y));}return s.join(' ');}
    // tick mark across the midpoint of segment p->q; n = number of ticks
    function tick(p,q,n){
      var mx=(p.x+q.x)/2,my=(p.y+q.y)/2;
      var dx=q.x-p.x,dy=q.y-p.y,len=Math.sqrt(dx*dx+dy*dy)||1;
      var ux=dx/len,uy=dy/len;          // along edge
      var nx=-uy,ny=ux;                  // normal
      var s='',gap=4,half=5;
      for(var i=0;i<n;i++){
        var off=(i-(n-1)/2)*gap;
        var bx=mx+ux*off,by=my+uy*off;
        s+='<line x1="'+rnd(bx-nx*half)+'" y1="'+rnd(by-ny*half)+'" x2="'+rnd(bx+nx*half)+'" y2="'+rnd(by+ny*half)+'" stroke="#1f4e8a" stroke-width="1.6"/>';
      }
      return s;
    }
    // small square right-angle marker at vertex v, given its two neighbours a,b
    function raMark(v,a,b){
      function unit(p){var dx=p.x-v.x,dy=p.y-v.y,l=Math.sqrt(dx*dx+dy*dy)||1;return {x:dx/l,y:dy/l};}
      var ua=unit(a),ub=unit(b),m=8;
      var p1={x:v.x+ua.x*m,y:v.y+ua.y*m};
      var p2={x:v.x+ua.x*m+ub.x*m,y:v.y+ua.y*m+ub.y*m};
      var p3={x:v.x+ub.x*m,y:v.y+ub.y*m};
      return '<polyline points="'+rnd(p1.x)+','+rnd(p1.y)+' '+rnd(p2.x)+','+rnd(p2.y)+' '+rnd(p3.x)+','+rnd(p3.y)+'" fill="none" stroke="#c0392b" stroke-width="1.6"/>';
    }
    function svgWrap(inner){
      return '<svg class="tp-shape" width="200" height="180" viewBox="0 0 200 180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="shape to classify">'+inner+'</svg>';
    }

    var fill='#eaf5ee',stroke='#1f8a4d',sw='2.2';
    var cx=100,cy=90;
    var deg=pick([-12,-8,0,0,6,10,15,-15]); // slight rotation only (keeps shapes recognisable)

    var families = {
      triangle: ['equilateral','isosceles','scalene','right-angled'],
      quadrilateral: ['square','rectangle','rhombus','parallelogram','trapezium']
    };
    var family = pick(['triangle','quadrilateral']);
    var type = pick(families[family]);

    var pts, edgeTicks=[], rightV=[];

    if (family==='triangle') {
      if (type==='equilateral') {
        var s=ri(70,90), h=s*Math.sqrt(3)/2;
        pts=[{x:cx-s/2,y:cy+h/2},{x:cx+s/2,y:cy+h/2},{x:cx,y:cy-h/2}];
        edgeTicks=[[0,1,1],[1,2,1],[2,0,1]];
      } else if (type==='isosceles') {
        var b=ri(56,72), hh=ri(64,84); // base != legs, legs equal
        pts=[{x:cx-b/2,y:cy+hh/2},{x:cx+b/2,y:cy+hh/2},{x:cx,y:cy-hh/2}];
        edgeTicks=[[1,2,1],[2,0,1]]; // the two equal legs
      } else if (type==='scalene') {
        pts=[{x:cx-46,y:cy+34},{x:cx+52,y:cy+22},{x:cx-6,y:cy-44}];
      } else { // right-angled (legs clearly unequal -> not isosceles right)
        var l1=ri(58,74), l2=ri(40,50);
        pts=[{x:cx-l1/2,y:cy+l2/2},{x:cx+l1/2,y:cy+l2/2},{x:cx-l1/2,y:cy-l2/2}];
        rightV=[0];
      }
    } else {
      if (type==='square') {
        var a=ri(74,92);
        pts=[{x:cx-a/2,y:cy-a/2},{x:cx+a/2,y:cy-a/2},{x:cx+a/2,y:cy+a/2},{x:cx-a/2,y:cy+a/2}];
        edgeTicks=[[0,1,1],[1,2,1],[2,3,1],[3,0,1]];
        rightV=[0];
      } else if (type==='rectangle') {
        var w=ri(96,118), hr=ri(54,66); // clearly unequal adjacent sides
        pts=[{x:cx-w/2,y:cy-hr/2},{x:cx+w/2,y:cy-hr/2},{x:cx+w/2,y:cy+hr/2},{x:cx-w/2,y:cy+hr/2}];
        edgeTicks=[[0,1,1],[2,3,1],[1,2,2],[3,0,2]]; // opposite pairs equal, adjacent differ
        rightV=[0];
      } else if (type==='rhombus') {
        var dx2=ri(46,56), dy2=ri(34,44); // diamond, all sides equal, not right-angled
        pts=[{x:cx,y:cy-dy2},{x:cx+dx2,y:cy},{x:cx,y:cy+dy2},{x:cx-dx2,y:cy}];
        edgeTicks=[[0,1,1],[1,2,1],[2,3,1],[3,0,1]];
      } else if (type==='parallelogram') {
        var pw=ri(86,104), ph=ri(50,62), sk=ri(24,32); // slanted, adjacent sides unequal, no right angle
        pts=[{x:cx-pw/2,y:cy+ph/2},{x:cx-pw/2+sk,y:cy-ph/2},{x:cx+pw/2,y:cy-ph/2},{x:cx+pw/2-sk,y:cy+ph/2}];
        edgeTicks=[[1,2,1],[3,0,1],[0,1,2],[2,3,2]];
      } else { // trapezium: exactly one pair of parallel sides, the legs unequal length
        var top=ri(40,52), bot=ri(96,112), th=ri(54,64), shift=ri(8,18);
        pts=[{x:cx-bot/2,y:cy+th/2},{x:cx+bot/2,y:cy+th/2},{x:cx+top/2+shift,y:cy-th/2},{x:cx-top/2+shift,y:cy-th/2}];
      }
    }

    pts=rotAll(pts,cx,cy,deg);

    var decor='';
    for (var t=0;t<edgeTicks.length;t++){
      var e=edgeTicks[t];
      decor+=tick(pts[e[0]],pts[e[1]],e[2]);
    }
    for (var rv=0;rv<rightV.length;rv++){
      var vi=rightV[rv];
      var prev=pts[(vi-1+pts.length)%pts.length], next=pts[(vi+1)%pts.length];
      decor+=raMark(pts[vi],prev,next);
    }

    var poly='<polygon points="'+ptsStr(pts)+'" fill="'+fill+'" stroke="'+stroke+'" stroke-width="'+sw+'" stroke-linejoin="round"/>';
    var svg=svgWrap(poly+decor);

    var label={
      equilateral:'equilateral triangle', isosceles:'isosceles triangle',
      scalene:'scalene triangle', 'right-angled':'right-angled triangle',
      square:'square', rectangle:'rectangle', rhombus:'rhombus',
      parallelogram:'parallelogram', trapezium:'trapezium'
    };
    var pool=families[family].slice();
    var rest=shuffle(pool.filter(function(x){return x!==type;}));
    var distract=rest.slice(0,3);
    var opts=shuffle(distract.concat([type]));
    var letters=['(a)','(b)','(c)','(d)'];
    var optHtml='<div class="tp-mcq" style="margin-top:6px">';
    var correctLetter='';
    for (var o=0;o<opts.length;o++){
      if (opts[o]===type) correctLetter=letters[o];
      optHtml+='<div>'+letters[o]+' &#9744; '+esc(label[opts[o]])+'</div>';
    }
    optHtml+='</div>';

    var qhtml=svg+optHtml;
    var qtn='Look at the shape below. Tick the word that names this shape.';
    var ans=correctLetter+' '+label[type];

    return { qtn: qtn, ans: ans, qhtml: qhtml };
  };

  // Y4 · Geometry › Lines of symmetry  [geo_symmetry_line_rotated]
  G['geo_symmetry_line_rotated'] = function (d) {
    // -- self-contained helpers --
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    function rot(p, ang, cx, cy){
      var rad = ang * Math.PI / 180, c = Math.cos(rad), s = Math.sin(rad);
      var x = p[0]-cx, y = p[1]-cy;
      return [cx + x*c - y*s, cy + x*s + y*c];
    }
    function ptStr(arr){ var o=[]; for(var i=0;i<arr.length;i++){ o.push(arr[i][0].toFixed(1)+','+arr[i][1].toFixed(1)); } return o.join(' '); }

    // Shape bank. Each shape: list of vertices (polygon), a set of TRUE mirror
    // lines (each line = [pointA, pointB] in the same coords), and a set of
    // DISTRACTOR lines (straight lines that are NOT axes of symmetry).
    // All coords are in a 0..200 box, centred ~ (100,100).
    function build(){
      var bank = [];

      // Isosceles triangle (1 axis: apex -> base midpoint)
      (function(){
        var apex=[100,25], bl=[45,165], br=[155,165];
        bank.push({
          name:'isosceles triangle',
          verts:[apex,bl,br],
          axes:[[apex,[100,165]]],
          distractors:[[bl,[100,95]],[br,[100,95]]] // line from base corner to mid: not an axis
        });
      })();

      // Rectangle (2 axes: the two midlines, NOT the diagonals)
      (function(){
        var a=[40,55],b=[160,55],c=[160,145],dd=[40,145];
        bank.push({
          name:'rectangle',
          verts:[a,b,c,dd],
          axes:[[[100,55],[100,145]],[[40,100],[160,100]]],
          distractors:[[a,c],[b,dd]] // the diagonals -- the classic Y4 trap
        });
      })();

      // Square (4 axes: 2 midlines + 2 diagonals)
      (function(){
        var a=[45,45],b=[155,45],c=[155,155],dd=[45,155];
        bank.push({
          name:'square',
          verts:[a,b,c,dd],
          axes:[[[100,45],[100,155]],[[45,100],[155,100]],[a,c],[b,dd]],
          distractors:[[[100,45],[155,100]],[[45,100],[100,155]]] // off-centre lines
        });
      })();

      // Equilateral triangle (3 axes: each vertex to opposite-edge midpoint)
      (function(){
        // true equilateral: vertices on a circle 120deg apart
        var top=[100,33], br=[162.4,141], bl=[37.6,141];
        function mid(p,q){return [(p[0]+q[0])/2,(p[1]+q[1])/2];}
        bank.push({
          name:'equilateral triangle',
          verts:[top,br,bl],
          axes:[[top,mid(br,bl)],[br,mid(top,bl)],[bl,mid(top,br)]],
          // base corner to the base midpoint: NOT an axis (it bisects an angle of the wrong edge)
          distractors:[[br,mid(br,bl)],[bl,mid(br,bl)]]
        });
      })();

      // Regular pentagon (5 axes through each vertex)
      (function(){
        var verts=[], axes=[], cx=100, cy=103, R=72;
        for(var i=0;i<5;i++){
          var a=(-90 + i*72)*Math.PI/180;
          verts.push([cx+R*Math.cos(a), cy+R*Math.sin(a)]);
        }
        for(var j=0;j<5;j++){
          var opp=[(verts[(j+2)%5][0]+verts[(j+3)%5][0])/2,(verts[(j+2)%5][1]+verts[(j+3)%5][1])/2];
          axes.push([verts[j],opp]);
        }
        // distractor: vertex to the next vertex's nothing -> use a vertex-to-vertex chord (not an axis for pentagon)
        bank.push({
          name:'regular pentagon',
          verts:verts,
          axes:axes,
          distractors:[[verts[0],verts[2]],[verts[1],verts[3]]]
        });
      })();

      // Regular hexagon (6 axes: 3 vertex-vertex, 3 edge-edge)
      (function(){
        var verts=[], cx=100, cy=100, R=70;
        for(var i=0;i<6;i++){
          var a=(i*60)*Math.PI/180;
          verts.push([cx+R*Math.cos(a), cy+R*Math.sin(a)]);
        }
        var axes=[];
        // vertex to opposite vertex
        axes.push([verts[0],verts[3]]);
        axes.push([verts[1],verts[4]]);
        axes.push([verts[2],verts[5]]);
        // edge midpoint to opposite edge midpoint
        function mid(p,q){return [(p[0]+q[0])/2,(p[1]+q[1])/2];}
        axes.push([mid(verts[0],verts[1]),mid(verts[3],verts[4])]);
        axes.push([mid(verts[1],verts[2]),mid(verts[4],verts[5])]);
        axes.push([mid(verts[2],verts[3]),mid(verts[5],verts[0])]);
        bank.push({
          name:'regular hexagon',
          verts:verts,
          axes:axes,
          distractors:[[verts[0],verts[2]],[verts[1],verts[3]]] // short diagonal: not an axis
        });
      })();

      // Kite (1 axis: the long diagonal)
      (function(){
        var top=[100,30], left=[55,95], right=[145,95], bot=[100,170];
        bank.push({
          name:'kite',
          verts:[top,right,bot,left],
          axes:[[top,bot]], // vertical diagonal
          distractors:[[left,right]] // the other diagonal: not an axis of a kite
        });
      })();

      // Arrow / chevron-ish pointer (1 axis: horizontal centre line)
      (function(){
        // arrow pointing right, symmetric about y=100
        var verts=[[40,75],[110,75],[110,55],[165,100],[110,145],[110,125],[40,125]];
        bank.push({
          name:'arrow',
          verts:verts,
          axes:[[[40,100],[165,100]]],
          distractors:[[[100,55],[100,145]]] // a vertical line: not an axis
        });
      })();

      // Scalene triangle (0 axes) -- always a distractor source
      (function(){
        var a=[40,150], b=[80,40], c=[170,140];
        function mid(p,q){return [(p[0]+q[0])/2,(p[1]+q[1])/2];}
        bank.push({
          name:'triangle',
          verts:[a,b,c],
          axes:[],
          distractors:[[a,mid(b,c)],[b,mid(a,c)],[c,mid(a,b)]]
        });
      })();

      return bank;
    }

    var bank = build();
    var shape = pick(bank);

    // Decide: present a TRUE axis (answer yes) or a DISTRACTOR (answer no).
    // Balance roughly 50/50, but if a shape has no axes it must be a distractor.
    var useTrue;
    if (shape.axes.length === 0) useTrue = false;
    else useTrue = (Math.random() < 0.5);

    var line;
    if (useTrue) line = pick(shape.axes);
    else line = pick(shape.distractors);
    var answer = useTrue ? 'yes' : 'no';

    // Rotation: bias toward diagonal (45/135) to lift above "vertical only".
    var rotChoices = [0,45,90,135,45,135,45,135];
    var ang = pick(rotChoices);

    var cx=100, cy=100;
    // rotate verts
    var rv=[];
    for(var i=0;i<shape.verts.length;i++){ rv.push(rot(shape.verts[i],ang,cx,cy)); }
    // rotate line endpoints, then extend a little beyond the shape
    var p1=rot(line[0],ang,cx,cy), p2=rot(line[1],ang,cx,cy);
    var dx=p2[0]-p1[0], dy=p2[1]-p1[1];
    var len=Math.sqrt(dx*dx+dy*dy)||1;
    var ux=dx/len, uy=dy/len, ext=22;
    var e1=[p1[0]-ux*ext, p1[1]-uy*ext];
    var e2=[p2[0]+ux*ext, p2[1]+uy*ext];

    var W=220, H2=220, pad=10;
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'+W+'" height="'+H2+'" viewBox="0 0 '+W+' '+H2+'" role="img" aria-label="'+esc(shape.name)+' with a dashed line">';
    svg += '<rect x="0" y="0" width="'+W+'" height="'+H2+'" fill="#ffffff"/>';
    svg += '<polygon points="'+ptStr(rv)+'" fill="#f4f4f4" stroke="#111111" stroke-width="2.5" stroke-linejoin="round"/>';
    svg += '<line x1="'+e1[0].toFixed(1)+'" y1="'+e1[1].toFixed(1)+'" x2="'+e2[0].toFixed(1)+'" y2="'+e2[1].toFixed(1)+'" stroke="#777777" stroke-width="2" stroke-dasharray="6 5"/>';
    svg += '</svg>';

    var qtn = 'Look at the shape below. The dashed line is drawn across it. Is the dashed line a line of symmetry? Write "yes" or "no".';

    return { qtn: qtn, qhtml: svg, ans: answer };
  };

  // Y4 · Geometry › Complete symmetric figure  [geo_complete_symmetric_figure]
  G['geo_complete_symmetric_figure'] = function (d) {
    // --- tiny self-contained SVG helpers ---
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    var GRID = 8;            // 8x8 grid of cells (intersections 0..8)
    var CELL = 30;           // px per cell
    var PAD = 20;            // border padding so labels fit
    var W = GRID*CELL + PAD*2;
    // map grid coord (gx,gy) -> pixel. gy=0 at BOTTOM.
    function px(gx){return PAD + gx*CELL;}
    function py(gy){return PAD + (GRID-gy)*CELL;}

    // Orientation: vertical mirror (reflect in x) or horizontal mirror (reflect in y)
    var vertical = (ri(0,1)===0);

    // mirror line position kept central-ish so both halves fit
    var m = ri(3,5); // mirror column (vertical) or row (horizontal)

    // Build a simple polygon on the SOURCE side (3-5 vertices) on grid intersections.
    // We work in a "perp" coordinate u (distance from mirror, 1..3) and a "free" coord v (0..8).
    var nV = ri(3,5);
    var verts; // array of {u, v} with u>=1 (source side), then reflected to u' = -u
    var maxU = Math.min(m, GRID-m, 3); // available room on each side, cap at 3
    if (maxU < 1) maxU = 1;

    var tries=0, ok=false;
    while(!ok && tries<400){
      tries++;
      verts=[];
      var usedKey={};
      var goodShape=true;
      for(var k=0;k<nV;k++){
        var u = ri(1, maxU);
        var v = ri(0, GRID);
        var key=u+','+v;
        if(usedKey[key]){goodShape=false;break;}
        usedKey[key]=1;
        verts.push({u:u,v:v});
      }
      if(!goodShape) continue;
      // need at least 2 distinct free coords so the figure isn't a flat line along the mirror
      var us={},vs={};
      for(var a=0;a<verts.length;a++){us[verts[a].u]=1;vs[verts[a].v]=1;}
      var distinctV = 0; for(var kk in vs) distinctV++;
      if(distinctV < 2) continue;
      // Ensure not all u===1 (too flat against mirror) for nicer shapes
      var hasDeep=false; for(var b=0;b<verts.length;b++){ if(verts[b].u>=2){hasDeep=true;break;} }
      if(!hasDeep) continue;
      ok=true;
    }
    if(!ok){
      // guaranteed fallback shape
      verts=[{u:1,v:2},{u:3,v:2},{u:3,v:5},{u:2,v:6},{u:1,v:5}];
    }

    // Convert {u,v} on source side to absolute grid coords on BOTH sides.
    function srcCoord(p){
      if(vertical) return {x: m - p.u, y: p.v};
      return {x: p.v, y: m - p.u};
    }
    function refCoord(p){
      if(vertical) return {x: m + p.u, y: p.v};
      return {x: p.v, y: m + p.u};
    }

    var src=verts.map(srcCoord);
    var ref=verts.map(refCoord);

    // validity: all coords on grid 0..GRID; if any off-grid, use safe fallback
    for(var c=0;c<src.length;c++){
      if(src[c].x<0||src[c].x>GRID||src[c].y<0||src[c].y>GRID||ref[c].x<0||ref[c].x>GRID||ref[c].y<0||ref[c].y>GRID){
        vertical=true; m=4;
        verts=[{u:1,v:2},{u:3,v:2},{u:3,v:5},{u:2,v:6},{u:1,v:5}];
        src=verts.map(srcCoord); ref=verts.map(refCoord);
        break;
      }
    }

    // choose the ONE reflected vertex to omit (reflection is a bijection so its
    // location is unique among reflected vertices).
    var omit = ri(0, ref.length-1);
    var ans = ref[omit];

    // --- build SVG ---
    var s='';
    s+='<svg viewBox="0 0 '+W+' '+W+'" width="'+W+'" height="'+W+'" xmlns="http://www.w3.org/2000/svg" font-family="sans-serif">';
    s+='<rect x="0" y="0" width="'+W+'" height="'+W+'" fill="white"/>';
    // grid lines
    for(var g=0;g<=GRID;g++){
      s+='<line x1="'+px(g)+'" y1="'+py(0)+'" x2="'+px(g)+'" y2="'+py(GRID)+'" stroke="#ddd" stroke-width="1"/>';
      s+='<line x1="'+px(0)+'" y1="'+py(g)+'" x2="'+px(GRID)+'" y2="'+py(g)+'" stroke="#ddd" stroke-width="1"/>';
    }
    // axis numbers
    for(var n=0;n<=GRID;n++){
      s+='<text x="'+px(n)+'" y="'+(py(0)+14)+'" font-size="9" fill="#888" text-anchor="middle">'+n+'</text>';
      s+='<text x="'+(px(0)-9)+'" y="'+(py(n)+3)+'" font-size="9" fill="#888" text-anchor="middle">'+n+'</text>';
    }
    // mirror line (bold dashed)
    if(vertical){
      s+='<line x1="'+px(m)+'" y1="'+py(0)+'" x2="'+px(m)+'" y2="'+py(GRID)+'" stroke="#333" stroke-width="2.5" stroke-dasharray="6 4"/>';
      s+='<text x="'+px(m)+'" y="13" font-size="10" fill="#333" text-anchor="middle">line of symmetry</text>';
    } else {
      s+='<line x1="'+px(0)+'" y1="'+py(m)+'" x2="'+px(GRID)+'" y2="'+py(m)+'" stroke="#333" stroke-width="2.5" stroke-dasharray="6 4"/>';
      s+='<text x="'+(W-4)+'" y="'+(py(m)-4)+'" font-size="10" fill="#333" text-anchor="end">line of symmetry</text>';
    }
    // open-polygon path helper
    function pathOf(pts){
      var dd='M '+px(pts[0].x)+' '+py(pts[0].y);
      for(var i=1;i<pts.length;i++) dd+=' L '+px(pts[i].x)+' '+py(pts[i].y);
      return dd;
    }
    // draw source shape: solid blue, closed, with vertex dots
    s+='<path d="'+pathOf(src)+' Z" fill="none" stroke="#2255cc" stroke-width="2.5"/>';
    for(var i=0;i<src.length;i++){
      s+='<circle cx="'+px(src[i].x)+'" cy="'+py(src[i].y)+'" r="4" fill="#2255cc"/>';
    }
    // reflected shape: solid segments EXCEPT the two segments adjoining the omitted vertex (faint dashed)
    for(var i=0;i<ref.length;i++){
      var a1=ref[i], b1=ref[(i+1)%ref.length];
      var adjacent = (i===omit)||((i+1)%ref.length===omit);
      if(adjacent){
        s+='<line x1="'+px(a1.x)+'" y1="'+py(a1.y)+'" x2="'+px(b1.x)+'" y2="'+py(b1.y)+'" stroke="#aac4f0" stroke-width="2" stroke-dasharray="4 3"/>';
      } else {
        s+='<line x1="'+px(a1.x)+'" y1="'+py(a1.y)+'" x2="'+px(b1.x)+'" y2="'+py(b1.y)+'" stroke="#2255cc" stroke-width="2.5"/>';
      }
    }
    // reflected vertex dots, except the omitted one
    for(var i=0;i<ref.length;i++){
      if(i===omit) continue;
      s+='<circle cx="'+px(ref[i].x)+'" cy="'+py(ref[i].y)+'" r="4" fill="#2255cc"/>';
    }
    s+='</svg>';

    var lineDesc = vertical ? ('x = '+m) : ('y = '+m);
    var qtn = 'Here is half of a symmetric figure and most of its mirror image. The dashed line is the line of symmetry ('+lineDesc+'). One dot is missing. Mark the single grid point that completes the symmetric figure. Give your answer as coordinates (x, y).';
    var ansStr = '('+ans.x+', '+ans.y+')';

    return { qtn: qtn, ans: ansStr, qhtml: s };
  };

  // Y4 · Geometry › First-quadrant coordinates  [geo_first_quadrant_coords]
  G['geo_first_quadrant_coords'] = function (d) {
    // First-quadrant coordinate reading. Grid axes 0..8 on both x and y.
    // Plot 3-4 labelled points; ask for the coordinates of one of them.
    var N = 8;            // max axis value
    var px = 44;          // left padding (room for y labels)
    var pb = 36;          // bottom padding (room for x labels)
    var pt = 16, prgt = 16;
    var step = 30;        // pixels per unit
    var W = px + N * step + prgt;
    var Hgt = pt + N * step + pb;

    // map grid coords -> svg pixel coords
    function sx(x) { return px + x * step; }
    function sy(y) { return pt + (N - y) * step; }

    // choose how many points to plot
    var labels = ['A', 'B', 'C', 'D'];
    var howMany = pick([3, 3, 4]);

    // generate distinct points; bias so that for the TARGET point rx != ry
    var pts = [];
    var used = {};
    function freshPoint() {
      var x, y, key, tries = 0;
      do {
        x = ri(1, N); y = ri(1, N); key = x + ',' + y; tries++;
      } while (used[key] && tries < 100);
      used[key] = true;
      return { x: x, y: y };
    }
    for (var i = 0; i < howMany; i++) {
      var p = freshPoint();
      p.label = labels[i];
      pts.push(p);
    }
    // pick the target point (the one asked about); ensure rx != ry on most items
    var target = pick(pts);
    if (Math.random() < 0.85 && target.x === target.y) {
      // try to swap target to a non-symmetric point, else nudge x
      var alt = null;
      for (var j = 0; j < pts.length; j++) { if (pts[j].x !== pts[j].y) { alt = pts[j]; break; } }
      if (alt) { target = alt; }
      else {
        // adjust target's x to break symmetry, keeping distinctness
        var nx = target.x;
        for (var k = 1; k <= N; k++) {
          var cand = ((target.x - 1 + k) % N) + 1;
          if (!used[cand + ',' + target.y]) { nx = cand; break; }
        }
        delete used[target.x + ',' + target.y];
        target.x = nx; used[target.x + ',' + target.y] = true;
      }
    }

    // ---- build SVG ----
    function esc(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    var svg = '';
    svg += '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + Hgt +
      '" viewBox="0 0 ' + W + ' ' + Hgt + '" role="img" aria-label="First-quadrant coordinate grid">';
    // light gridlines
    var g2 = '';
    for (var gx = 0; gx <= N; gx++) {
      g2 += '<line x1="' + sx(gx) + '" y1="' + sy(0) + '" x2="' + sx(gx) + '" y2="' + sy(N) +
        '" stroke="#d9d9d9" stroke-width="1"/>';
    }
    for (var gy = 0; gy <= N; gy++) {
      g2 += '<line x1="' + sx(0) + '" y1="' + sy(gy) + '" x2="' + sx(N) + '" y2="' + sy(gy) +
        '" stroke="#d9d9d9" stroke-width="1"/>';
    }
    svg += g2;

    // arrow marker defs
    svg += '<defs><marker id="tp_arr_fqc" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto" markerUnits="strokeWidth">' +
      '<path d="M0,0 L6,3 L0,6 Z" fill="#333"/></marker></defs>';

    // axes (arrowed)
    svg += '<line x1="' + sx(0) + '" y1="' + sy(0) + '" x2="' + (sx(N) + 12) + '" y2="' + sy(0) +
      '" stroke="#333" stroke-width="2" marker-end="url(#tp_arr_fqc)"/>';
    svg += '<line x1="' + sx(0) + '" y1="' + sy(0) + '" x2="' + sx(0) + '" y2="' + (sy(N) - 12) +
      '" stroke="#333" stroke-width="2" marker-end="url(#tp_arr_fqc)"/>';

    // axis number labels
    for (var ax = 0; ax <= N; ax++) {
      svg += '<text x="' + sx(ax) + '" y="' + (sy(0) + 16) + '" font-family="sans-serif" font-size="12" fill="#333" text-anchor="middle">' + ax + '</text>';
    }
    for (var ay = 0; ay <= N; ay++) {
      svg += '<text x="' + (sx(0) - 10) + '" y="' + (sy(ay) + 4) + '" font-family="sans-serif" font-size="12" fill="#333" text-anchor="end">' + ay + '</text>';
    }
    // axis titles
    svg += '<text x="' + (sx(N) + 8) + '" y="' + (sy(0) + 26) + '" font-family="sans-serif" font-size="13" font-style="italic" fill="#333" text-anchor="middle">x</text>';
    svg += '<text x="' + (sx(0) - 22) + '" y="' + (sy(N) - 8) + '" font-family="sans-serif" font-size="13" font-style="italic" fill="#333" text-anchor="middle">y</text>';

    // plotted points
    for (var pi = 0; pi < pts.length; pi++) {
      var q = pts[pi];
      svg += '<circle cx="' + sx(q.x) + '" cy="' + sy(q.y) + '" r="4.5" fill="#1769aa"/>';
      // label offset up-right of the dot
      svg += '<text x="' + (sx(q.x) + 8) + '" y="' + (sy(q.y) - 7) + '" font-family="sans-serif" font-size="14" font-weight="bold" fill="#1769aa">' + esc(q.label) + '</text>';
    }
    svg += '</svg>';

    var qtn = 'Write the coordinates of point ' + target.label +
      '. Give your answer in the form (x, y).';
    var ans = '(' + target.x + ', ' + target.y + ')';

    return { qtn: qtn, ans: ans, qhtml: svg };
  };

  // Y4 · Geometry › Translations  [geo_translate_shape_grid]
  G['geo_translate_shape_grid'] = function (d) {
    // d: 2=below, 3=meeting, 4=exceeding. Translations on a 6x6 grid.
    var GRID = 6;           // 6 x 6 squares
    var CELL = 40;          // px per square
    var PAD = 14;           // padding around grid
    var W = GRID * CELL + PAD * 2;
    var H_ = GRID * CELL + PAD * 2 + 4;

    // Shape footprints in unit cells, anchor at (0,0) bottom-left.
    // Cells expressed as [col, row] offsets from the anchor.
    var shapes = {
      'L-shape': [[0,0],[0,1],[0,2],[1,0]],
      'T-shape': [[0,1],[1,1],[2,1],[1,0]],
      'square':  [[0,0],[1,0],[0,1],[1,1]],
      'step':    [[0,0],[1,0],[1,1],[2,1]]
    };
    var shapeNames = ['L-shape','T-shape','square','step'];
    var shapeName = pick(shapeNames);
    var cells = shapes[shapeName];

    // Footprint bounding box (in unit cells) so both copies stay on grid.
    var maxC = 0, maxR = 0;
    for (var i = 0; i < cells.length; i++) {
      if (cells[i][0] > maxC) maxC = cells[i][0];
      if (cells[i][1] > maxR) maxR = cells[i][1];
    }
    var spanC = maxC + 1, spanR = maxR + 1; // width/height in cells

    // Magnitude range: smaller for below (support), full for meeting/exceeding.
    var maxMag = (d <= 2) ? 3 : 4;

    // Horizontal shift dx (right=+, left=-), vertical shift dy (up=+, down=-).
    // Allow zero on at most one axis but guarantee at least one non-zero move.
    var dx, dy;
    do {
      dx = ri(0, maxMag) * (Math.random() < 0.5 ? 1 : -1);
      dy = ri(0, maxMag) * (Math.random() < 0.5 ? 1 : -1);
    } while (dx === 0 && dy === 0);

    // Start ranges so both start and end stay fully on grid.
    var loC = Math.max(0, -dx), hiC = Math.min(GRID - spanC, GRID - spanC - dx);
    var loR = Math.max(0, -dy), hiR = Math.min(GRID - spanR, GRID - spanR - dy);
    if (loC > hiC || loR > hiR) {
      dx = 1; dy = 1;
      loC = Math.max(0, -dx); hiC = Math.min(GRID - spanC, GRID - spanC - dx);
      loR = Math.max(0, -dy); hiR = Math.min(GRID - spanR, GRID - spanR - dy);
    }
    var sc = ri(loC, hiC);   // start anchor column (0-indexed from left)
    var sr = ri(loR, hiR);   // start anchor row (0-indexed from bottom)
    var ec = sc + dx, er = sr + dy;

    // ---- SVG helpers (self-contained) ----
    function cellX(col) { return PAD + col * CELL; }
    function cellY(row) { return PAD + (GRID - 1 - row) * CELL; } // invert: row0 at bottom

    function shapeRects(anchorCol, anchorRow, fillAttr) {
      var s = '';
      for (var k = 0; k < cells.length; k++) {
        var cc = anchorCol + cells[k][0];
        var rr = anchorRow + cells[k][1];
        var x = cellX(cc), y = cellY(rr);
        s += '<rect x="' + x + '" y="' + y + '" width="' + CELL + '" height="' + CELL + '" ' + fillAttr + '/>';
      }
      return s;
    }

    var svg = '';
    svg += '<svg class="tp-shape" width="' + W + '" height="' + H_ + '" viewBox="0 0 ' + W + ' ' + H_ + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="translation on grid">';
    for (var g = 0; g <= GRID; g++) {
      var gx = PAD + g * CELL;
      var gy = PAD + g * CELL;
      svg += '<line x1="' + gx + '" y1="' + PAD + '" x2="' + gx + '" y2="' + (PAD + GRID * CELL) + '" stroke="#bbb" stroke-width="1"/>';
      svg += '<line x1="' + PAD + '" y1="' + gy + '" x2="' + (PAD + GRID * CELL) + '" y2="' + gy + '" stroke="#bbb" stroke-width="1"/>';
    }
    svg += shapeRects(ec, er, 'fill="#dbe9fb" stroke="#3b7ddd" stroke-width="2" stroke-dasharray="5,4"');
    svg += shapeRects(sc, sr, 'fill="#3b7ddd" stroke="#1f4f9c" stroke-width="2"');
    svg += '</svg>';

    // ---- Sentence + answer ----
    var hMag = Math.abs(dx), vMag = Math.abs(dy);
    var hDir = dx > 0 ? 'right' : 'left';
    var vDir = dy > 0 ? 'up' : 'down';

    var parts = [];
    if (hMag > 0) parts.push(hMag + ' square' + (hMag === 1 ? '' : 's') + ' to the ' + hDir);
    if (vMag > 0) parts.push(vMag + ' square' + (vMag === 1 ? '' : 's') + ' ' + vDir);
    var ansText = parts.join(' and ');

    var qtn = 'The ' + shapeName + ' has been translated from the solid position to the dashed position. '
      + 'Complete the sentence: The ' + shapeName + ' moves ___ square(s) to the (left / right) and ___ square(s) (up / down).';

    return { qtn: qtn, qhtml: svg, ans: ansText };
  };

  // Y4 · Geometry › Plot & complete polygon  [geo_plot_complete_polygon]
  G['geo_plot_complete_polygon'] = function (d) {
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}

    // Decide square vs rectangle. Below(2): squares only. Meeting(3): squares + simple rects.
    // Exceeding(4+): rectangles allowed, larger range.
    var useSquare;
    if (d <= 2) useSquare = true;
    else if (d === 3) useSquare = (ri(0,1) === 0);
    else useSquare = (ri(0,1) === 0);

    // Pick an axis-aligned square or rectangle with integer vertices in 0..8.
    var x0, y0, w, h;
    if (useSquare) {
      var s = (d <= 2) ? ri(2, 4) : ri(2, 6);
      x0 = ri(0, 8 - s);
      y0 = ri(0, 8 - s);
      w = s; h = s;
    } else {
      w = ri(2, 6);
      do { h = ri(2, 6); } while (h === w); // distinct so it's clearly a rectangle
      x0 = ri(0, 8 - w);
      y0 = ri(0, 8 - h);
    }

    var shapeName = useSquare ? 'square' : 'rectangle';

    // Four vertices, ordered so consecutive ones share a side (A->B->C->D).
    var verts = [
      [x0,      y0],       // bottom-left  A
      [x0 + w,  y0],       // bottom-right B
      [x0 + w,  y0 + h],   // top-right    C
      [x0,      y0 + h]    // top-left     D
    ];
    var labels = ['A','B','C','D'];

    // Hide exactly one vertex.
    var hidden = ri(0, 3);
    var hv = verts[hidden];
    var hLabel = labels[hidden];

    // ---- Build SVG ----
    var N = 8;
    var pad = 28;          // room for axis labels
    var cell = 30;         // px per unit
    var plot = N * cell;   // 240
    var W = pad + plot + 12;
    var Hgt = pad + plot + 12;
    // map grid (gx,gy) -> svg px. y inverted.
    function px(gx){ return pad + gx * cell; }
    function py(gy){ return pad + (N - gy) * cell; }

    var svg = '';
    svg += '<svg xmlns="http://www.w3.org/2000/svg" width="' + W + '" height="' + Hgt + '" viewBox="0 0 ' + W + ' ' + Hgt + '" font-family="Arial,Helvetica,sans-serif">';
    svg += '<rect x="0" y="0" width="' + W + '" height="' + Hgt + '" fill="#ffffff"/>';

    // gridlines
    var i;
    for (i = 0; i <= N; i++) {
      var gx = px(i), gyTop = py(N), gyBot = py(0);
      svg += '<line x1="' + gx + '" y1="' + gyTop + '" x2="' + gx + '" y2="' + gyBot + '" stroke="#cccccc" stroke-width="1"/>';
      var gy = py(i), gxL = px(0), gxR = px(N);
      svg += '<line x1="' + gxL + '" y1="' + gy + '" x2="' + gxR + '" y2="' + gy + '" stroke="#cccccc" stroke-width="1"/>';
    }
    // axes
    svg += '<line x1="' + px(0) + '" y1="' + py(0) + '" x2="' + px(N) + '" y2="' + py(0) + '" stroke="#333333" stroke-width="2"/>';
    svg += '<line x1="' + px(0) + '" y1="' + py(0) + '" x2="' + px(0) + '" y2="' + py(N) + '" stroke="#333333" stroke-width="2"/>';
    // axis number labels
    for (i = 0; i <= N; i++) {
      svg += '<text x="' + px(i) + '" y="' + (py(0) + 14) + '" font-size="11" fill="#333" text-anchor="middle">' + i + '</text>';
      svg += '<text x="' + (px(0) - 8) + '" y="' + (py(i) + 4) + '" font-size="11" fill="#333" text-anchor="end">' + i + '</text>';
    }

    // Draw the sides that already exist: all sides NOT adjacent to the hidden vertex,
    // i.e. only sides between two visible vertices.
    function visible(idx){ return idx !== hidden; }
    var edges = [[0,1],[1,2],[2,3],[3,0]];
    for (i = 0; i < edges.length; i++) {
      var a = edges[i][0], b = edges[i][1];
      if (visible(a) && visible(b)) {
        svg += '<line x1="' + px(verts[a][0]) + '" y1="' + py(verts[a][1]) +
               '" x2="' + px(verts[b][0]) + '" y2="' + py(verts[b][1]) +
               '" stroke="#888888" stroke-width="2" stroke-dasharray="4,3"/>';
      }
    }

    // Draw the three visible vertices as solid dots with labels.
    for (i = 0; i < 4; i++) {
      if (i === hidden) continue;
      var cx = px(verts[i][0]), cy = py(verts[i][1]);
      svg += '<circle cx="' + cx + '" cy="' + cy + '" r="4.5" fill="#000000"/>';
      svg += '<text x="' + (cx + 7) + '" y="' + (cy - 7) + '" font-size="12" font-weight="bold" fill="#000">' + esc(labels[i]) + '</text>';
    }

    svg += '</svg>';

    var qtn = 'These are three corners of a ' + shapeName + '. Plot the fourth corner ' + hLabel +
              ' and complete the ' + shapeName + '. Write the coordinates of ' + hLabel + ': ( ___ , ___ )';

    var ans = hLabel + ' = (' + hv[0] + ', ' + hv[1] + ')';

    return { qtn: qtn, ans: ans, qhtml: svg };
  };

  // Y4 · Statistics › Time graphs  [stats_time_graph_readoff]
  G['stats_time_graph_readoff'] = function (d) {
    // local HTML escaper (self-contained; do not use file-private esc)
    function esc(s){return String(s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}

    // Context bank: title, y-axis label, unit, integer y-scale max & step.
    var ctx = pick([
      { title: 'Temperature in the greenhouse', ylab: 'Temperature (°C)', unit: '°C', max: 20, step: 2, noun: 'temperature' },
      { title: 'Temperature on the hospital ward', ylab: 'Temperature (°C)', unit: '°C', max: 24, step: 2, noun: 'temperature' },
      { title: 'Water in the tank', ylab: 'Water (litres)', unit: ' litres', max: 40, step: 4, noun: 'amount of water' },
      { title: 'Rainfall during the day', ylab: 'Rainfall (mm)', unit: ' mm', max: 16, step: 2, noun: 'rainfall' },
      { title: 'Visitors at the museum', ylab: 'Number of visitors', unit: ' visitors', max: 100, step: 10, noun: 'number of visitors' }
    ]);

    // Time points: 6-8 equally spaced hours of the day.
    var nPts = ri(6, 8);
    var startHr = ri(7, 9); // start hour (am)
    var times = [], labels = [];
    for (var i = 0; i < nPts; i++) {
      var h24 = startHr + i;
      times.push(h24);
      var h12 = ((h24 + 11) % 12) + 1;
      var ampm = h24 < 12 ? 'am' : 'pm';
      labels.push(h12 + ampm);
    }

    // y-values: every value a multiple of step, in [0, max]. Whole numbers on gridlines.
    var steps = Math.round(ctx.max / ctx.step); // number of gridline intervals
    var vals = [];
    for (var j = 0; j < nPts; j++) {
      vals.push(ri(1, steps - 1) * ctx.step); // avoid 0 and max so points sit nicely inside
    }

    // Pick question type: 0 = direct read-off, 1 = difference between two points.
    var qType = pick([0, 1]);
    var qtn, ans;
    var targetIdx = -1, idxA = -1, idxB = -1;

    if (qType === 0) {
      targetIdx = ri(0, nPts - 1);
      qtn = 'The line graph "' + ctx.title + '" shows how the ' + ctx.noun + ' changed during the day. What was the ' + ctx.noun + ' at ' + labels[targetIdx] + '?';
      ans = fmt(vals[targetIdx]) + ctx.unit;
    } else {
      // difference: ensure the two points differ so the answer is non-degenerate.
      var attempts = 0;
      do {
        idxA = ri(0, nPts - 1);
        idxB = ri(0, nPts - 1);
        attempts++;
      } while ((idxA === idxB || vals[idxA] === vals[idxB]) && attempts < 200);
      if (idxA === idxB || vals[idxA] === vals[idxB]) {
        // fallback to direct read-off if we somehow couldn't find a differing pair
        targetIdx = 0;
        qtn = 'The line graph "' + ctx.title + '" shows how the ' + ctx.noun + ' changed during the day. What was the ' + ctx.noun + ' at ' + labels[targetIdx] + '?';
        ans = fmt(vals[targetIdx]) + ctx.unit;
        qType = 0;
      } else {
        var hi = vals[idxA] >= vals[idxB] ? idxA : idxB;
        var lo = vals[idxA] >= vals[idxB] ? idxB : idxA;
        var diff = vals[hi] - vals[lo];
        qtn = 'The line graph "' + ctx.title + '" shows how the ' + ctx.noun + ' changed during the day. What was the difference between the ' + ctx.noun + ' at ' + labels[lo] + ' and at ' + labels[hi] + '?';
        ans = fmt(diff) + ctx.unit;
      }
    }

    // ----- Build SVG line graph -----
    var W = 360, Hh = 240;
    var ml = 46, mr = 14, mt = 30, mb = 38; // margins
    var plotW = W - ml - mr, plotH = Hh - mt - mb;
    var x0 = ml, y0 = Hh - mb; // origin (bottom-left of plot)
    var xStep = plotW / (nPts - 1);
    var yMax = ctx.max;
    function sx(i){ return x0 + i * xStep; }
    function sy(v){ return y0 - (v / yMax) * plotH; }

    var s = '';
    // background
    s += '<rect x="0" y="0" width="' + W + '" height="' + Hh + '" fill="#ffffff"/>';
    // title
    s += '<text x="' + (W/2) + '" y="18" font-size="13" text-anchor="middle" font-family="Georgia,serif" fill="#26302a">' + esc(ctx.title) + '</text>';
    // horizontal gridlines + y labels
    for (var gy = 0; gy <= yMax; gy += ctx.step) {
      var yy = sy(gy);
      s += '<line x1="' + x0.toFixed(1) + '" y1="' + yy.toFixed(1) + '" x2="' + (x0+plotW).toFixed(1) + '" y2="' + yy.toFixed(1) + '" stroke="#d7ddd2" stroke-width="1"/>';
      s += '<text x="' + (x0-6).toFixed(1) + '" y="' + (yy+3.3).toFixed(1) + '" font-size="9" text-anchor="end" font-family="Arial,sans-serif" fill="#26302a">' + gy + '</text>';
    }
    // vertical gridlines + x labels
    for (var gx = 0; gx < nPts; gx++) {
      var xx = sx(gx);
      s += '<line x1="' + xx.toFixed(1) + '" y1="' + mt.toFixed(1) + '" x2="' + xx.toFixed(1) + '" y2="' + y0.toFixed(1) + '" stroke="#eef1ec" stroke-width="1"/>';
      s += '<text x="' + xx.toFixed(1) + '" y="' + (y0+13).toFixed(1) + '" font-size="8.5" text-anchor="middle" font-family="Arial,sans-serif" fill="#26302a">' + esc(labels[gx]) + '</text>';
    }
    // axes
    s += '<line x1="' + x0.toFixed(1) + '" y1="' + mt.toFixed(1) + '" x2="' + x0.toFixed(1) + '" y2="' + y0.toFixed(1) + '" stroke="#26302a" stroke-width="1.5"/>';
    s += '<line x1="' + x0.toFixed(1) + '" y1="' + y0.toFixed(1) + '" x2="' + (x0+plotW).toFixed(1) + '" y2="' + y0.toFixed(1) + '" stroke="#26302a" stroke-width="1.5"/>';
    // axis labels
    s += '<text x="' + (x0+plotW/2).toFixed(1) + '" y="' + (Hh-4) + '" font-size="10" text-anchor="middle" font-family="Arial,sans-serif" fill="#26302a">Time</text>';
    s += '<text x="12" y="' + (mt+plotH/2).toFixed(1) + '" font-size="10" text-anchor="middle" font-family="Arial,sans-serif" fill="#26302a" transform="rotate(-90 12 ' + (mt+plotH/2).toFixed(1) + ')">' + esc(ctx.ylab) + '</text>';
    // line segments
    var path = '';
    for (var p = 0; p < nPts; p++) {
      path += (p === 0 ? 'M' : 'L') + sx(p).toFixed(1) + ',' + sy(vals[p]).toFixed(1) + ' ';
    }
    s += '<path d="' + path.trim() + '" fill="none" stroke="#1f8a4d" stroke-width="2"/>';
    // points (dots)
    for (var q = 0; q < nPts; q++) {
      s += '<circle cx="' + sx(q).toFixed(1) + '" cy="' + sy(vals[q]).toFixed(1) + '" r="2.6" fill="#26302a"/>';
    }

    var qhtml = '<svg class="tp-linegraph" width="' + W + '" height="' + Hh + '" viewBox="0 0 ' + W + ' ' + Hh + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="' + esc(ctx.title) + ' line graph">' + s + '</svg>';

    return { qtn: qtn, ans: ans, qhtml: qhtml };
  };

  // Y5 · Measurement › Estimate volume/capacity  [meas_estimate_capacity_mcq]
  G['meas_estimate_capacity_mcq'] = function (d) {
    // each item: realistic capacity expressed in millilitres (canonical)
    var items = [
      { name: 'teaspoon', ml: 5 },
      { name: 'kitchen mug', ml: 300 },
      { name: 'mug of tea', ml: 300 },
      { name: 'kettle', ml: 1500 },
      { name: 'large drinks bottle', ml: 2000 },
      { name: 'washing-up bowl', ml: 6000 },
      { name: 'bucket', ml: 10000 },
      { name: 'watering can', ml: 9000 },
      { name: 'small fish tank', ml: 30000 },
      { name: 'bath', ml: 150000 },
      { name: 'can of fizzy drink', ml: 330 },
      { name: 'cup of coffee', ml: 250 },
      { name: 'eggcup', ml: 50 },
      { name: 'saucepan', ml: 3000 },
      { name: 'wine glass', ml: 175 }
    ];

    // format a millilitre quantity sensibly as ml or litres
    function fmtCap(ml) {
      if (ml >= 1000 && ml % 1000 === 0) {
        var l = ml / 1000;
        return fmt(l) + (l === 1 ? ' litre' : ' litres');
      }
      if (ml >= 1000 && (ml % 100 === 0)) {
        // e.g. 1500 -> 1.5 litres
        var lv = ml / 1000;
        return fmt(lv) + ' litres';
      }
      return fmt(ml) + ' ml';
    }

    var it = pick(items);
    var trueMl = it.ml;

    // build distractor pool by multiplying/dividing by 10 and 100
    var factors = [0.01, 0.1, 10, 100];
    var optsMl = [trueMl];
    var pool = shuffle(factors);
    for (var k = 0; k < pool.length && optsMl.length < 4; k++) {
      var v = trueMl * pool[k];
      // keep it a clean-ish value and not below 1 ml
      if (v < 1) continue;
      if (v !== Math.round(v)) continue;
      var dup = false;
      for (var m = 0; m < optsMl.length; m++) { if (optsMl[m] === v) dup = true; }
      if (!dup) optsMl.push(v);
    }
    // fallback: ensure at least 3 options using *10 and /10 etc.
    var fb = [trueMl * 10, trueMl / 10, trueMl * 100, trueMl / 100, trueMl * 1000];
    for (var f = 0; f < fb.length && optsMl.length < 4; f++) {
      var fv = fb[f];
      if (fv < 1 || fv !== Math.round(fv)) continue;
      var dup2 = false;
      for (var mm = 0; mm < optsMl.length; mm++) { if (optsMl[mm] === fv) dup2 = true; }
      if (!dup2) optsMl.push(fv);
    }

    var shuffled = shuffle(optsMl);
    var labels = ['A', 'B', 'C', 'D'];
    var correctLabel = '';
    var optStrings = [];
    for (var i = 0; i < shuffled.length; i++) {
      optStrings.push(labels[i] + ') ' + fmtCap(shuffled[i]));
      if (shuffled[i] === trueMl) correctLabel = labels[i];
    }

    var exceeding = (d >= 4);

    if (exceeding) {
      // comparison reasoning variant: which holds more?
      var a = pick(items), b = pick(items);
      var guard = 0;
      while (b.ml === a.ml && guard < 20) { b = pick(items); guard++; }
      if (b.ml === a.ml) { // last-resort distinct pair
        a = { name: 'teaspoon', ml: 5 }; b = { name: 'bath', ml: 150000 };
      }
      var more = a.ml > b.ml ? a : b;
      var q = 'Which holds more when full: a ' + a.name + ' (about ' + fmtCap(a.ml) +
        ') or a ' + b.name + ' (about ' + fmtCap(b.ml) + ')?';
      return { qtn: q, ans: 'the ' + more.name };
    }

    var stem = 'Tick the best estimate for how much a ' + it.name + ' holds when full.   ' +
      optStrings.join('   ');
    return { qtn: stem, ans: correctLabel + ') ' + fmtCap(trueMl) };
  };

  // Y5 · Geometry › Rectangle properties  [geo_rect_missing_side_angle]
  G['geo_rect_missing_side_angle'] = function (d) {
    function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    // Rectangle integer sides; L (length, top) 6..14, W (width, right) 3..9, L != W
    var L = ri(6, 14);
    var W = ri(3, 9);
    while (W === L) { W = ri(3, 9); }
    var a = ri(25, 60);              // given diagonal-split angle at corner A
    var missAngle = 90 - a;         // answer angle

    // SVG layout. Corners: A top-left, B top-right, C bottom-right, D bottom-left.
    var pad = 40, w = 360, h = 200;
    var x0 = pad, y0 = pad;                 // A
    var x1 = w - pad, y1 = pad;             // B
    var x2 = w - pad, y2 = h - pad;         // C
    var x3 = pad, y3 = h - pad;             // D
    var rm = 12; // right-angle marker size

    function rightMarker(cx, cy, sx, sy) {
      // sx, sy are unit directions pointing INTO the rectangle from corner
      var ax = cx + sx * rm, ay = cy;
      var bx = cx + sx * rm, by = cy + sy * rm;
      var dx = cx, dy = cy + sy * rm;
      return '<polyline points="' + ax + ',' + ay + ' ' + bx + ',' + by + ' ' + dx + ',' + dy +
        '" fill="none" stroke="#333" stroke-width="1"/>';
    }

    var svg = '';
    svg += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + w + ' ' + h + '" width="' + w + '" height="' + h + '" role="img" aria-label="Rectangle ABCD with diagonal AC">';
    // rectangle
    svg += '<rect x="' + x0 + '" y="' + y0 + '" width="' + (x1 - x0) + '" height="' + (y2 - y1) + '" fill="none" stroke="#222" stroke-width="2"/>';
    // diagonal A -> C
    svg += '<line x1="' + x0 + '" y1="' + y0 + '" x2="' + x2 + '" y2="' + y2 + '" stroke="#1a6" stroke-width="2"/>';
    // right-angle markers at all four corners
    svg += rightMarker(x0, y0, 1, 1);
    svg += rightMarker(x1, y1, -1, 1);
    svg += rightMarker(x2, y2, -1, -1);
    svg += rightMarker(x3, y3, 1, -1);
    // corner labels
    svg += '<text x="' + (x0 - 14) + '" y="' + (y0 - 6) + '" font-size="14" font-family="sans-serif">A</text>';
    svg += '<text x="' + (x1 + 4) + '" y="' + (y1 - 6) + '" font-size="14" font-family="sans-serif">B</text>';
    svg += '<text x="' + (x2 + 4) + '" y="' + (y2 + 16) + '" font-size="14" font-family="sans-serif">C</text>';
    svg += '<text x="' + (x3 - 14) + '" y="' + (y3 + 16) + '" font-size="14" font-family="sans-serif">D</text>';
    // top side AB length label
    svg += '<text x="' + ((x0 + x1) / 2) + '" y="' + (y0 - 8) + '" font-size="13" text-anchor="middle" font-family="sans-serif">' + L + ' cm</text>';
    // bottom side DC = ? cm
    svg += '<text x="' + ((x3 + x2) / 2) + '" y="' + (y2 + 16) + '" font-size="13" text-anchor="middle" font-family="sans-serif">? cm</text>';
    // angle 'a' at corner A between AB and diagonal (small arc + label)
    svg += '<path d="M ' + (x0 + 26) + ' ' + y0 + ' A 26 26 0 0 1 ' + (x0 + 22) + ' ' + (y0 + 14) + '" fill="none" stroke="#c33" stroke-width="1.5"/>';
    svg += '<text x="' + (x0 + 34) + '" y="' + (y0 + 18) + '" font-size="12" fill="#c33" font-family="sans-serif">' + a + '°</text>';
    // angle ? at corner C between diagonal and BC
    svg += '<path d="M ' + x2 + ' ' + (y2 - 26) + ' A 26 26 0 0 1 ' + (x2 - 22) + ' ' + (y2 - 14) + '" fill="none" stroke="#36c" stroke-width="1.5"/>';
    svg += '<text x="' + (x2 - 34) + '" y="' + (y2 - 20) + '" font-size="12" fill="#36c" text-anchor="end" font-family="sans-serif">?°</text>';
    svg += '</svg>';

    var qtn = 'ABCD is a rectangle (not drawn to scale). The diagonal AC has been drawn. ' +
      'Side AB = ' + L + ' cm. The angle between side AB and the diagonal AC at corner A is ' + a + '°. ' +
      'Use the properties of a rectangle to find: (a) the length of side DC, and ' +
      '(b) the size of the angle between the diagonal AC and side BC at corner C.';

    var ans = '(a) DC = ' + L + ' cm (opposite sides of a rectangle are equal). ' +
      '(b) ' + missAngle + '° (the two angles at corner C made by the diagonal sum to 90°, so 90 − ' + a + ' = ' + missAngle + '°).';

    return { qtn: qtn, ans: ans, qhtml: svg };
  };

  // Y5 · Geometry › Draw & measure  [geo_measure_angle_protractor]
  G['geo_measure_angle_protractor'] = function (d) {
    // self-contained SVG helpers
    function rnd(x){ return Math.round(x*100)/100; }
    function pt(cx, cy, len, deg){
      var r = deg*Math.PI/180;
      return [rnd(cx + len*Math.cos(r)), rnd(cy - len*Math.sin(r))];
    }
    // Choose tolerance / step by difficulty band (2 below, 3 meeting, 4 exceeding)
    var band = (d===2||d===4)?d:3;
    var tol, step;
    if (band === 4) { // Greater Depth: non-multiples of 5, tighter tolerance
      step = 1; tol = 1;
    } else if (band === 2) { // Working Towards: multiples of 10, one arm on baseline
      step = 10; tol = 2;
    } else { // Meeting: multiples of 5
      step = 5; tol = 2;
    }
    // assessable range, avoiding values too close to 0/90/180
    var candidates = [];
    for (var a = 20; a <= 160; a += step) {
      if (Math.abs(a-90) <= 4) continue;   // not too close to a right angle
      if (a < 15 || a > 165) continue;
      candidates.push(a);
    }
    var angle = pick(candidates);

    var S = 220, cx = S/2, cy = (S-24)/2 + 2, len = 80;
    // base orientation of first arm; Working Towards keeps an arm horizontal
    var rot;
    if (band === 2) {
      rot = 0; // one arm on the horizontal baseline
    } else {
      rot = ri(0, 359);
    }
    var armA = rot;
    var armB = rot + angle;
    var pA = pt(cx, cy, len, armA);
    var pB = pt(cx, cy, len, armB);

    // arc marking the angle near the vertex
    var arcR = 26;
    var aStart = pt(cx, cy, arcR, armA);
    var aEnd   = pt(cx, cy, arcR, armB);
    // angle<180 so large-arc-flag=0; SVG y is inverted so sweep-flag=1 -> minor arc
    var arcPath = 'M ' + aStart[0] + ' ' + aStart[1] +
                  ' A ' + arcR + ' ' + arcR + ' 0 0 1 ' + aEnd[0] + ' ' + aEnd[1];
    var lbl = pt(cx, cy, arcR + 16, rot + angle/2);

    var svg = '<svg class="tp-angle" width="' + S + '" height="' + (S-20) + '" viewBox="0 0 ' + S + ' ' + (S-20) + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="angle to measure">' +
      '<rect x="2" y="2" width="' + (S-4) + '" height="' + (S-24) + '" fill="#fff" stroke="#c8ccc4" stroke-width="1"/>' +
      '<path d="' + arcPath + '" fill="none" stroke="#1f8a4d" stroke-width="1.6"/>' +
      '<line x1="' + cx + '" y1="' + cy + '" x2="' + pA[0] + '" y2="' + pA[1] + '" stroke="#26302a" stroke-width="2" stroke-linecap="round"/>' +
      '<line x1="' + cx + '" y1="' + cy + '" x2="' + pB[0] + '" y2="' + pB[1] + '" stroke="#26302a" stroke-width="2" stroke-linecap="round"/>' +
      '<text x="' + lbl[0] + '" y="' + (lbl[1]+4) + '" font-size="14" text-anchor="middle" fill="#1f8a4d" font-family="Georgia,serif">?</text>' +
      '<circle cx="' + cx + '" cy="' + cy + '" r="3" fill="#26302a"/>' +
      '<text x="' + (S/2) + '" y="' + (S-30) + '" font-size="8.5" text-anchor="middle" fill="#9aa096" font-family="Georgia,serif">place the centre of your protractor on the dot</text>' +
      '</svg>';

    var qtn = 'Measure the angle marked below. Use a protractor. Write your answer in degrees. (Accept within ±' + tol + '°.)';
    return { qtn: qtn, qhtml: svg, ans: angle + '°' };
  };

  // ---- idx 235 | Y5 Geometry > Position and direction > Reflection/translation ----
  G['geo_reflect_translate_point_y5'] = function (d) {
    var x = ri(1, 6), y = ri(1, 6);
    var mode = pick(['reflect_x', 'reflect_y', 'translate']);
    if (mode === 'reflect_x') {
      return { qtn: 'The point (' + x + ', ' + y + ') is reflected in the x-axis. The shape is unchanged in size. What are the coordinates of the image of this point?', ans: '(' + x + ', ' + (-y) + ')' };
    }
    if (mode === 'reflect_y') {
      return { qtn: 'The point (' + x + ', ' + y + ') is reflected in the y-axis. What are the coordinates of the image of this point?', ans: '(' + (-x) + ', ' + y + ')' };
    }
    var dx = ri(1, 5), dy = ri(1, 5), hx = pick(['right', 'left']), vy = pick(['up', 'down']);
    var nx = x + (hx === 'right' ? dx : -dx), ny = y + (vy === 'up' ? dy : -dy);
    return { qtn: 'The point (' + x + ', ' + y + ') is translated ' + dx + ' ' + hx + ' and ' + dy + ' ' + vy + '. What are the new coordinates?', ans: '(' + nx + ', ' + ny + ')' };
  };

  // ---- idx 274 | Y6 Measurement > Perimeter, area, volume > Same area != same perimeter ----
  G['meas_same_area_diff_perimeter_y6'] = function (d) {
    var A = pick([12, 16, 18, 24, 36, 48]);
    var pairs = [];
    for (var w = 1; w <= Math.sqrt(A); w++) { if (A % w === 0 && w !== A / w) { pairs.push([w, A / w]); } }
    var two = shuffle(pairs).slice(0, 2);
    var r1 = two[0], r2 = two[1];
    var p1 = 2 * (r1[0] + r1[1]), p2 = 2 * (r2[0] + r2[1]);
    var bigger = p1 > p2 ? 'A' : 'B';
    return {
      qtn: 'Rectangle A is ' + r1[0] + ' cm by ' + r1[1] + ' cm. Rectangle B is ' + r2[0] + ' cm by ' + r2[1] + ' cm. Both have an area of ' + A + ' cm². Which rectangle has the greater perimeter, A or B?',
      ans: bigger + ' (A = ' + p1 + ' cm, B = ' + p2 + ' cm)'
    };
  };

  // ---- idx 275 | Y6 Measurement > Perimeter, area, volume > When to use formulae ----
  G['geo_when_formula_y6'] = function (d) {
    var mode = pick(['select', 'apply']);
    if (mode === 'select') {
      var items = [
        { q: 'the area of a rectangle', a: 'length × width' },
        { q: 'the area of a triangle', a: '(base × height) ÷ 2' },
        { q: 'the volume of a cuboid', a: 'length × width × height' },
        { q: 'the area of a parallelogram', a: 'base × height' }
      ];
      var it = pick(items);
      var opts = shuffle(['length × width', '(base × height) ÷ 2', 'length × width × height', 'base × height']);
      return { qtn: 'Which calculation gives ' + it.q + '?  Choose one: ' + opts.join(';  ') + '.', ans: it.a };
    }
    var cases = [
      { s: 'a rectangle', f: 'length × width', ok: true },
      { s: 'a square', f: 'side × side', ok: true },
      { s: 'a cuboid', f: 'length × width × height', ok: true },
      { s: 'an irregular L-shaped patio', f: 'length × width', ok: false },
      { s: 'a circle', f: 'length × width', ok: false },
      { s: 'a triangle', f: 'length × width', ok: false }
    ];
    var c = pick(cases);
    return { qtn: 'Can you correctly find the area or volume of ' + c.s + ' using the formula “' + c.f + '”? Write “yes” or “no”.', ans: c.ok ? 'yes' : 'no' };
  };

  // ---- idx 278 | Y6 Geometry > 2-D shapes > Draw from dimensions/angles ----
  G['geo_draw_dimensions_angle_y6'] = function (d) {
    var mode = pick(['tri_angle', 'quad_angle', 'rect_dims']);
    if (mode === 'tri_angle') {
      var a = ri(30, 80), b = ri(30, 80);
      while (a + b >= 170) { a = ri(30, 80); b = ri(30, 80); }
      return { qtn: 'You are drawing a triangle. Two of its angles are ' + a + '° and ' + b + '°. What size must you draw the third angle?', ans: (180 - a - b) + '°' };
    }
    if (mode === 'quad_angle') {
      var p = ri(60, 120), q = ri(60, 120), r = ri(60, 120);
      while (p + q + r >= 350 || p + q + r <= 180) { p = ri(60, 120); q = ri(60, 120); r = ri(60, 120); }
      return { qtn: 'You are drawing a quadrilateral. Three of its angles are ' + p + '°, ' + q + '° and ' + r + '°. What must the fourth angle be?', ans: (360 - p - q - r) + '°' };
    }
    var ww = ri(3, 9), hh = ri(3, 9);
    while (hh === ww) { hh = ri(3, 9); }
    return { qtn: 'You draw a rectangle ' + ww + ' cm wide and ' + hh + ' cm tall. What is the perimeter of the rectangle you have drawn?', ans: (2 * (ww + hh)) + ' cm' };
  };

  // ---- idx 279 | Y6 Geometry > 2-D shapes > Classify shapes ----
  G['geo_classify_shape_y6'] = function (d) {
    var cases = [
      { a: 'square', clue: '4 equal sides and 4 right angles' },
      { a: 'rectangle', clue: '4 right angles, with opposite sides equal but not all four sides equal' },
      { a: 'rhombus', clue: '4 equal sides but no right angles' },
      { a: 'parallelogram', clue: '2 pairs of parallel sides, no right angles, and not all sides equal' },
      { a: 'trapezium', clue: 'exactly one pair of parallel sides' },
      { a: 'equilateral triangle', clue: '3 equal sides and 3 equal angles' },
      { a: 'isosceles triangle', clue: 'exactly 2 equal sides' },
      { a: 'pentagon', clue: '5 straight sides' },
      { a: 'hexagon', clue: '6 straight sides' }
    ];
    var c = pick(cases);
    return { qtn: 'Name the 2-D shape that has ' + c.clue + '.', ans: c.a };
  };

  // ---- idx 281 | Y6 Geometry > 3-D shapes > Build shapes & nets ----
  G['geo_net_solid_y6'] = function (d) {
    var solids = [
      { name: 'cube', faces: 6, fdesc: '6 identical squares', poly: true },
      { name: 'cuboid', faces: 6, fdesc: '6 rectangles (in 3 matching pairs)', poly: true },
      { name: 'square-based pyramid', faces: 5, fdesc: '1 square and 4 triangles', poly: true },
      { name: 'triangular prism', faces: 5, fdesc: '2 triangles and 3 rectangles', poly: true },
      { name: 'tetrahedron', faces: 4, fdesc: '4 identical triangles', poly: true },
      { name: 'cylinder', faces: 3, fdesc: '2 circles and 1 rectangle', poly: false }
    ];
    var mode = pick(['net2solid', 'faces']);
    if (mode === 'net2solid') {
      var s = pick(solids);
      return { qtn: 'A net is made from ' + s.fdesc + '. Which 3-D shape does this net fold up into?', ans: s.name };
    }
    var polys = solids.filter(function (z) { return z.poly; });
    var sp = pick(polys);
    return { qtn: 'How many faces does a ' + sp.name + ' have?', ans: '' + sp.faces };
  };

  // ---- idx 284 | Y6 Geometry > Position and direction > Four quadrants ----
  G['geo_four_quadrant_read_y6'] = function (d) {
    var x = ri(-5, 5), y = ri(-5, 5);
    while (x === 0 && y === 0) { x = ri(-5, 5); y = ri(-5, 5); }
    var size = 240, n = 5, step = size / (2 * n), cx = size / 2, cy = size / 2;
    function px(gx) { return (cx + gx * step).toFixed(1); }
    function py(gy) { return (cy - gy * step).toFixed(1); }
    var s = '<svg width="' + size + '" height="' + size + '" viewBox="0 0 ' + size + ' ' + size + '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="coordinate grid with point P">';
    s += '<rect width="' + size + '" height="' + size + '" fill="#ffffff"/>';
    for (var i = -n; i <= n; i++) {
      s += '<line x1="' + px(i) + '" y1="' + py(-n) + '" x2="' + px(i) + '" y2="' + py(n) + '" stroke="#e2e6df" stroke-width="1"/>';
      s += '<line x1="' + px(-n) + '" y1="' + py(i) + '" x2="' + px(n) + '" y2="' + py(i) + '" stroke="#e2e6df" stroke-width="1"/>';
    }
    s += '<line x1="' + px(-n) + '" y1="' + py(0) + '" x2="' + px(n) + '" y2="' + py(0) + '" stroke="#333333" stroke-width="1.5"/>';
    s += '<line x1="' + px(0) + '" y1="' + py(-n) + '" x2="' + px(0) + '" y2="' + py(n) + '" stroke="#333333" stroke-width="1.5"/>';
    s += '<circle cx="' + px(x) + '" cy="' + py(y) + '" r="4.5" fill="#1f8a4d"/>';
    s += '<text x="' + (cx + x * step + 7).toFixed(1) + '" y="' + (cy - y * step - 7).toFixed(1) + '" font-size="13" font-family="sans-serif" fill="#1f8a4d">P</text>';
    s += '</svg>';
    return { qtn: 'Write the coordinates of point P shown on the grid.', qhtml: s, ans: '(' + x + ', ' + y + ')' };
  };

  // ---- idx 285 | Y6 Geometry > Position and direction > Translate & reflect ----
  G['geo_translate_reflect_y6'] = function (d) {
    var x = ri(-5, 5), y = ri(-5, 5);
    var mode = pick(['translate', 'reflect_x', 'reflect_y']);
    if (mode === 'reflect_x') {
      return { qtn: 'A shape has a vertex at (' + x + ', ' + y + '). The shape is reflected in the x-axis. What are the coordinates of the image of this vertex?', ans: '(' + x + ', ' + (-y) + ')' };
    }
    if (mode === 'reflect_y') {
      return { qtn: 'A shape has a vertex at (' + x + ', ' + y + '). The shape is reflected in the y-axis. What are the coordinates of the image of this vertex?', ans: '(' + (-x) + ', ' + y + ')' };
    }
    var dx = ri(1, 6), dy = ri(1, 6), hx = pick(['right', 'left']), vy = pick(['up', 'down']);
    var nx = x + (hx === 'right' ? dx : -dx), ny = y + (vy === 'up' ? dy : -dy);
    return { qtn: 'A shape has a vertex at (' + x + ', ' + y + '). The shape is translated ' + dx + ' ' + hx + ' and ' + dy + ' ' + vy + '. What are the new coordinates of this vertex?', ans: '(' + nx + ', ' + ny + ')' };
  };

  // ---- idx 286 | Y6 Statistics > Present and interpret data > Pie charts & line graphs ----
  G['stats_pie_line_y6'] = function (d) {
    var cats = shuffle([['Football', '#e63946'], ['Swimming', '#457b9d'], ['Cycling', '#2a9d8f'], ['Reading', '#e9c46a']]);
    var nCat = pick([3, 4]);
    var parts = [], rem = 8, i;
    for (i = 0; i < nCat - 1; i++) { var maxp = rem - (nCat - 1 - i); var p = ri(1, maxp); parts.push(p); rem -= p; }
    parts.push(rem);
    var total = pick([16, 24, 32, 40, 48, 80]);
    var cx = 110, cy = 110, r = 92, ang = -90;
    function ptx(a) { return (cx + r * Math.cos(a * Math.PI / 180)).toFixed(1); }
    function pty(a) { return (cy + r * Math.sin(a * Math.PI / 180)).toFixed(1); }
    var s = '<svg width="300" height="220" viewBox="0 0 300 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="pie chart">';
    s += '<rect width="300" height="220" fill="#ffffff"/>';
    var legend = '';
    for (var j = 0; j < parts.length; j++) {
      var sweep = parts[j] / 8 * 360, a2 = ang + sweep, large = sweep > 180 ? 1 : 0;
      s += '<path d="M' + cx + ',' + cy + ' L' + ptx(ang) + ',' + pty(ang) + ' A' + r + ',' + r + ' 0 ' + large + ' 1 ' + ptx(a2) + ',' + pty(a2) + ' Z" fill="' + cats[j][1] + '" stroke="#ffffff" stroke-width="1.5"/>';
      ang = a2;
      legend += '<rect x="232" y="' + (24 + j * 22) + '" width="12" height="12" fill="' + cats[j][1] + '"/><text x="250" y="' + (34 + j * 22) + '" font-size="11" font-family="sans-serif">' + cats[j][0] + '</text>';
    }
    s += legend + '</svg>';
    var qi = ri(0, parts.length - 1);
    var count = total * parts[qi] / 8;
    return { qtn: 'The pie chart shows the favourite activity of ' + total + ' children. How many children chose ' + cats[qi][0] + '?', qhtml: s, ans: '' + count };
  };

})();
