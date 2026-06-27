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
  G.count_negatives = seqGen(pick([2, 3, 5]), { neg: true });
  G.count_neg_through0 = function () { return seqGen(pick([1, 2, 3, 5, 10]), { neg: true })(); };
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
  G.one_more = moreLess([1], 0, 99);
  G.one_less = function () { var n = ri(1, 100); return { qtn: '1 less than ' + n + ' =', ans: fmt(n - 1) }; };
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
  function placeValue(digits) {
    return function () {
      var lo = Math.pow(10, digits - 1), hi = Math.pow(10, digits) - 1;
      var n = ri(lo, hi), s = String(n);
      var idx = ri(0, s.length - 1), digit = Number(s[idx]);
      var place = digit * Math.pow(10, s.length - 1 - idx);
      return { qtn: 'In ' + fmt(n) + ', what is the value of the digit ' + digit + '?', ans: fmt(place) };
    };
  }
  G.place_2 = placeValue(2); G.place_3 = placeValue(3); G.place_4 = placeValue(4);
  G.place_1m = placeValue(7); G.place_10m = placeValue(8);

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
    var hi = pick([100, 1000, 10000]), set = [];
    while (set.length < 4) { var v = ri(0, hi); if (set.indexOf(v) === -1) { set.push(v); } }
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
  G.round_dp2 = function () { var n = ri(1, 1999) / 100; return { qtn: 'Round ' + n.toFixed(2) + ' to the nearest whole number:', ans: fmt(Math.round(n)) }; };

  // -------------------------------------------------------------------------
  // ADDITION & SUBTRACTION
  // -------------------------------------------------------------------------
  function addSub(genA, genB, opts) {
    opts = opts || {};
    return function () {
      var a = genA(), b = genB();
      var op = opts.add === true ? '+' : opts.add === false ? '−' : pick(['+', '−']);
      if (op === '−' && b > a) { var t = a; a = b; b = t; }
      return { qtn: fmt(a) + ' ' + op + ' ' + fmt(b) + ' =', ans: fmt(op === '+' ? a + b : a - b) };
    };
  }
  G.add_to_20 = addSub(function () { return ri(0, 19); }, function () { return ri(0, 19); }, { add: true });
  G.sub_to_20 = addSub(function () { return ri(0, 20); }, function () { return ri(0, 20); }, { add: false });
  G.addsub_2d_ones = addSub(function () { return ri(11, 99); }, function () { return ri(1, 9); });
  G.addsub_2d_tens = addSub(function () { return ri(11, 89); }, function () { return ri(1, 9) * 10; });
  G.addsub_2d_2d = addSub(function () { return ri(11, 99); }, function () { return ri(11, 99); });
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

  // missing-number / inverse
  G.missing_number = function () { var a = ri(2, 9), b = ri(2, 9); return { qtn: a + ' + ___ = ' + fmt(a + b), ans: fmt(b) }; };
  G.missing_number_sub = function () { var a = ri(5, 20), b = ri(1, a); return { qtn: '___ − ' + b + ' = ' + (a - b), ans: fmt(a) }; };
  G.inverse_check = function () { var a = ri(20, 90), b = ri(20, 90); return { qtn: 'If ' + a + ' + ' + b + ' = ' + (a + b) + ', what is ' + (a + b) + ' − ' + b + '?', ans: fmt(a) }; };

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
  G.odd_even = function () { var n = ri(1, 99); return { qtn: 'Is ' + n + ' odd or even?', ans: n % 2 === 0 ? 'even' : 'odd' }; };
  G.commutativity = function () { var a = ri(2, 9), b = ri(2, 9); return { qtn: a + ' × ' + b + ' = ' + b + ' × ___', ans: fmt(a) }; };
  G.mult_statements = function () { var a = ri(2, 10), b = ri(2, 10); return { qtn: a + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.mult_2dx1d = function () { var a = ri(11, 99), b = ri(2, 9); return { qtn: a + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.mult_3dx1d = function () { var a = ri(100, 999), b = ri(2, 9); return { qtn: fmt(a) + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.mult_three = function () { var a = ri(2, 6), b = ri(2, 6), c = ri(2, 5); return { qtn: a + ' × ' + b + ' × ' + c + ' =', ans: fmt(a * b * c) }; };
  G.mental_md = function () { return pick([function () { var a = ri(2, 12), b = pick([0, 1, 10]); return { qtn: a + ' × ' + b + ' =', ans: fmt(a * b) }; }, function () { var a = ri(2, 12); return { qtn: a + ' ÷ 1 =', ans: fmt(a) }; }])(); };
  G.long_mult = function () { var a = ri(1000, 9999), b = ri(11, 99); return { qtn: fmt(a) + ' × ' + b + ' =', ans: fmt(a * b) }; };
  G.long_mult_4x2 = G.long_mult;
  G.short_div = function () { var b = ri(2, 9), q = ri(100, 999), r = ri(0, b - 1); return { qtn: fmt(b * q + r) + ' ÷ ' + b + ' =', ans: r ? fmt(q) + ' r ' + r : fmt(q) }; };
  G.short_div_2digit = function () { var b = ri(11, 25), q = ri(50, 400); return { qtn: fmt(b * q) + ' ÷ ' + b + ' =', ans: fmt(q) }; };
  G.long_div = function () { var b = ri(11, 40), q = ri(50, 250), r = ri(0, b - 1); return { qtn: fmt(b * q + r) + ' ÷ ' + b + ' =', ans: r ? fmt(q) + ' r ' + r : fmt(q) }; };
  G.md_10_100_1000 = function () { var p = pick([10, 100, 1000]), a = ri(2, 99); return pick([true, false]) ? { qtn: fmt(a) + ' × ' + p + ' =', ans: fmt(a * p) } : { qtn: fmt(a * p) + ' ÷ ' + p + ' =', ans: fmt(a) }; };

  G.factors = function () { var n = ri(12, 48); var f = []; for (var i = 1; i <= n; i++) { if (n % i === 0) { f.push(i); } } return { qtn: 'List all the factors of ' + n + '.', ans: f.join(', ') }; };
  G.multiples = function () { var n = ri(3, 9), k = ri(3, 6); var m = []; for (var i = 1; i <= k; i++) { m.push(n * i); } return { qtn: 'Write the first ' + k + ' multiples of ' + n + '.', ans: m.join(', ') }; };
  G.common_factors = function () { var a = ri(8, 30), b = ri(8, 30); var f = []; for (var i = 1; i <= Math.min(a, b); i++) { if (a % i === 0 && b % i === 0) { f.push(i); } } return { qtn: 'Find the common factors of ' + a + ' and ' + b + '.', ans: f.join(', ') }; };
  var isPrime = function (x) { if (x < 2) { return false; } for (var i = 2; i * i <= x; i++) { if (x % i === 0) { return false; } } return true; };
  G.primes_test = function () { var n = ri(2, 100); return { qtn: 'Is ' + n + ' a prime number? (yes / no)', ans: isPrime(n) ? 'yes' : 'no' }; };
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
  G.frac_threeq = fracOf(3, 4); G.frac_twoq = fracOf(2, 4);
  G.frac_unit_set = function () { var d = pick([2, 3, 4, 5, 6, 10]); var k = d * ri(2, 8); return { qtn: '1/' + d + ' of ' + k + ' =', ans: fmt(k / d) }; };
  G.frac_nonunit_set = function () { var d = pick([3, 4, 5, 6, 8, 10]); var n = ri(2, d - 1); var k = d * ri(2, 8); return { qtn: frac(n, d) + ' of ' + k + ' =', ans: fmt(k * n / d) }; };
  G.frac_simple = function () { var d = pick([2, 3, 4, 5]); var k = d * ri(2, 6); return { qtn: '1/' + d + ' of ' + k + ' =', ans: fmt(k / d) }; };
  G.frac_equiv = function () { var d = pick([2, 3, 4, 5]); var k = ri(2, 4); return { qtn: 'Complete the equivalent fraction:  1/' + d + ' = ___/' + (d * k), ans: fmt(k) }; };
  G.frac_equiv_family = function () { var d = pick([2, 3, 4, 5]), n = 1, k = ri(2, 5); return { qtn: 'Write a fraction equivalent to ' + n + '/' + d + '.', ans: (n * k) + '/' + (d * k) }; };
  G.frac_simplify = function () { var g = ri(2, 6), d = ri(2, 6), n = ri(1, d - 1); return { qtn: 'Simplify the fraction ' + (n * g) + '/' + (d * g) + '.', ans: frac(n * g, d * g) }; };
  G.frac_addsub_same = function () { var d = pick([4, 5, 6, 8, 10]); var a = ri(1, d - 1), b = ri(1, d - 1); var op = pick(['+', '−']); if (op === '−' && b > a) { var t = a; a = b; b = t; } return { qtn: a + '/' + d + ' ' + op + ' ' + b + '/' + d + ' =', ans: frac(op === '+' ? a + b : a - b, d) }; };
  G.frac_addsub_related = function () { var d = pick([2, 3, 4]); var d2 = d * pick([2, 3]); var a = ri(1, d - 1), b = ri(1, d2 - 1); return { qtn: a + '/' + d + ' + ' + b + '/' + d2 + ' =', ans: frac(a * (d2 / d) + b, d2) }; };
  G.frac_addsub_diff = function () { var d1 = pick([2, 3, 4]), d2 = pick([3, 5, 6]); var a = ri(1, d1 - 1), b = ri(1, d2 - 1); var L = d1 * d2 / gcd(d1, d2); return { qtn: a + '/' + d1 + ' + ' + b + '/' + d2 + ' =', ans: frac(a * (L / d1) + b * (L / d2), L) }; };
  G.frac_mult_whole = function () { var d = pick([2, 3, 4, 5]); var n = ri(1, d - 1), w = ri(2, 6); return { qtn: n + '/' + d + ' × ' + w + ' =', ans: frac(n * w, d) }; };
  G.frac_mult_pair = function () { var d1 = pick([2, 3, 4]), d2 = pick([2, 3, 5]); var n1 = ri(1, d1 - 1), n2 = ri(1, d2 - 1); return { qtn: n1 + '/' + d1 + ' × ' + n2 + '/' + d2 + ' =', ans: frac(n1 * n2, d1 * d2) }; };
  G.frac_div_whole = function () { var d = pick([2, 3, 4]); var n = ri(1, d - 1), w = ri(2, 5); return { qtn: '(' + n + '/' + d + ') ÷ ' + w + ' =', ans: frac(n, d * w) }; };
  G.mixed_improper = function () { var d = pick([2, 3, 4, 5]); var whole = ri(1, 4), n = ri(1, d - 1); var imp = whole * d + n; return pick([true, false]) ? { qtn: 'Write ' + whole + ' ' + n + '/' + d + ' as an improper fraction.', ans: imp + '/' + d } : { qtn: 'Write ' + imp + '/' + d + ' as a mixed number.', ans: whole + ' ' + n + '/' + d }; };
  G.frac_compare = function () { var d = pick([4, 5, 6, 8]); var a = ri(1, d - 1), b = pick([true, false]) ? a : ri(1, d - 1); var sym = a < b ? '<' : a > b ? '>' : '='; return { qtn: 'Insert <, > or = :  ' + a + '/' + d + ' ___ ' + b + '/' + d, ans: sym }; };
  G.frac_as_number = function () { var d = pick([2, 4, 5, 10]); var n = ri(1, d - 1); return { qtn: 'What is ' + n + '/' + d + ' as a decimal?', ans: fmt(n / d) }; };

  // -------------------------------------------------------------------------
  // DECIMALS & PERCENTAGES
  // -------------------------------------------------------------------------
  G.dec_tenths_hundredths = function () { var n = ri(1, 99); return { qtn: 'Write ' + n + ' hundredths as a decimal.', ans: fmt(n / 100) }; };
  G.dec_equiv_quarter = function () { var f = pick([[1, 4], [1, 2], [3, 4]]); return { qtn: 'Write ' + f[0] + '/' + f[1] + ' as a decimal.', ans: fmt(f[0] / f[1]) }; };
  G.dec_as_fraction = function () { var n = ri(1, 99); return { qtn: 'Write ' + (n / 100).toFixed(2) + ' as a fraction (over 100).', ans: n + '/100' }; };
  G.dec_thousandths = function () { var n = ri(1, 999); return { qtn: 'Write ' + n + ' thousandths as a decimal.', ans: fmt(n / 1000) }; };
  G.dec_compare = function () { var a = ri(1, 999) / 100, b = pick([true, false]) ? a : ri(1, 999) / 100; var sym = a < b ? '<' : a > b ? '>' : '='; return { qtn: 'Insert <, > or = :  ' + a.toFixed(2) + ' ___ ' + b.toFixed(2), ans: sym }; };
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
  G.money_problems = function () { var items = ri(2, 6), price = ri(2, 9); var total = items * price; var paid = pick([10, 20, 50]); return { qtn: items + ' pens cost £' + price + ' each. Pay with £' + paid + '. How much change?', ans: '£' + fmt(paid - total) }; };
  G.money_pounds = function () { var p = ri(120, 980); return { qtn: 'Write ' + p + 'p in pounds.', ans: '£' + (p / 100).toFixed(2) }; };

  // -------------------------------------------------------------------------
  // TIME FACTS / CONVERSIONS (clock-reading skills reuse the SVG keys)
  // -------------------------------------------------------------------------
  G.time_facts = function () { return pick([function () { return { qtn: 'How many minutes are there in an hour?', ans: '60' }; }, function () { return { qtn: 'How many hours are there in a day?', ans: '24' }; }, function () { var h = ri(2, 6); return { qtn: 'How many minutes are there in ' + h + ' hours?', ans: fmt(h * 60) }; }])(); };
  G.time_facts3 = function () { return pick([function () { return { qtn: 'How many seconds are there in a minute?', ans: '60' }; }, function () { return { qtn: 'How many days are there in a leap year?', ans: '366' }; }, function () { var m = pick(['September', 'April', 'June', 'November']); return { qtn: 'How many days are there in ' + m + '?', ans: '30' }; }])(); };
  G.time_convert = function () { return pick([function () { var h = ri(2, 6); return { qtn: 'Convert ' + h + ' hours to minutes.', ans: fmt(h * 60) }; }, function () { var m = ri(2, 8); return { qtn: 'Convert ' + m + ' minutes to seconds.', ans: fmt(m * 60) }; }, function () { var y = ri(2, 6); return { qtn: 'Convert ' + y + ' years to months.', ans: fmt(y * 12) }; }])(); };

  // -------------------------------------------------------------------------
  // MEASUREMENT — metric conversions, perimeter / area / volume
  // -------------------------------------------------------------------------
  G.convert_length = function () { return pick([function () { var m = ri(1, 9); return { qtn: 'Convert ' + m + ' m to cm.', ans: fmt(m * 100) + ' cm' }; }, function () { var cm = ri(2, 9); return { qtn: 'Convert ' + cm + ' cm to mm.', ans: fmt(cm * 10) + ' mm' }; }, function () { var km = ri(1, 9); return { qtn: 'Convert ' + km + ' km to m.', ans: fmt(km * 1000) + ' m' }; }])(); };
  G.convert_mass = function () { var kg = ri(1, 9); return { qtn: 'Convert ' + kg + ' kg to g.', ans: fmt(kg * 1000) + ' g' }; };
  G.convert_capacity = function () { var l = ri(1, 9); return { qtn: 'Convert ' + l + ' litres to ml.', ans: fmt(l * 1000) + ' ml' }; };
  G.convert_metric = function () { return pick([G.convert_length, G.convert_mass, G.convert_capacity])(); };
  G.convert_miles_km = function () { var mi = ri(5, 50); return { qtn: 'Using 5 miles ≈ 8 km, convert ' + mi + ' miles to km.', ans: fmt(mi / 5 * 8) + ' km' }; };
  G.measure_decimal = function () { var m = ri(1, 9) + ri(1, 9) / 10; return { qtn: 'Convert ' + m.toFixed(1) + ' m to cm.', ans: fmt(Math.round(m * 100)) + ' cm' }; };
  G.perimeter_rect = function () { var w = ri(2, 20), h = ri(2, 20); return { qtn: 'A rectangle is ' + w + ' cm by ' + h + ' cm. What is its perimeter?', ans: fmt(2 * (w + h)) + ' cm' }; };
  G.perimeter_2d = function () { var s = ri(2, 15), n = pick([3, 4, 5, 6]); return { qtn: 'A regular shape has ' + n + ' sides of ' + s + ' cm. What is its perimeter?', ans: fmt(n * s) + ' cm' }; };
  G.area_rect = function () { var w = ri(2, 20), h = ri(2, 20); return { qtn: 'A rectangle is ' + w + ' cm by ' + h + ' cm. What is its area?', ans: fmt(w * h) + ' cm²' }; };
  G.area_count = function () { var w = ri(2, 8), h = ri(2, 6); return { qtn: 'A rectangle covers ' + w + ' squares across and ' + h + ' squares down. How many squares is its area?', ans: fmt(w * h) }; };
  G.area_tri_para = function () { return pick([function () { var b = ri(4, 20), h = ri(2, 12); return { qtn: 'A triangle has base ' + b + ' cm and height ' + h + ' cm. What is its area?', ans: fmt(b * h / 2) + ' cm²' }; }, function () { var b = ri(3, 15), h = ri(2, 12); return { qtn: 'A parallelogram has base ' + b + ' cm and height ' + h + ' cm. What is its area?', ans: fmt(b * h) + ' cm²' }; }])(); };
  G.volume_cuboid = function () { var a = ri(2, 8), b = ri(2, 8), c = ri(2, 8); return { qtn: 'A cuboid is ' + a + ' cm × ' + b + ' cm × ' + c + ' cm. What is its volume?', ans: fmt(a * b * c) + ' cm³' }; };

  // -------------------------------------------------------------------------
  // STATISTICS — small data sets
  // -------------------------------------------------------------------------
  function dataset(cats) {
    var data = {}; cats.forEach(function (c) { data[c] = ri(2, 12); }); return data;
  }
  function dataLine(data) { return Object.keys(data).map(function (k) { return k + ': ' + data[k]; }).join(', '); }
  G.stats_total = function () { var d = dataset(shuffle(['Red', 'Blue', 'Green', 'Yellow']).slice(0, 3)); var total = Object.keys(d).reduce(function (s, k) { return s + d[k]; }, 0); return { qtn: 'Favourite colours — ' + dataLine(d) + '. How many children altogether?', ans: fmt(total) }; };
  G.stats_compare = function () { var keys = shuffle(['Cats', 'Dogs', 'Fish', 'Birds']).slice(0, 2); var d = dataset(keys); var diff = Math.abs(d[keys[0]] - d[keys[1]]); return { qtn: 'Pets — ' + dataLine(d) + '. How many more ' + (d[keys[0]] > d[keys[1]] ? keys[0] : keys[1]) + ' than ' + (d[keys[0]] > d[keys[1]] ? keys[1] : keys[0]) + '?', ans: fmt(diff) }; };
  G.stats_one_step = function () { var d = dataset(shuffle(['Mon', 'Tue', 'Wed', 'Thu']).slice(0, 3)); var keys = Object.keys(d); var most = keys.reduce(function (a, b) { return d[a] >= d[b] ? a : b; }); return { qtn: 'Books read — ' + dataLine(d) + '. Which day had the most?', ans: most }; };
  G.stats_two_step = function () { var keys = shuffle(['A', 'B', 'C']).slice(0, 2); var d = dataset(keys); return { qtn: 'Class scores — ' + dataLine(d) + '. What is the total of the two classes?', ans: fmt(d[keys[0]] + d[keys[1]]) }; };
  G.stats_mean = function () { var vals = []; var n = ri(3, 5); var sum = 0; while (vals.length < n) { var v = ri(2, 20); vals.push(v); sum += v; } if (sum % n !== 0) { vals[0] += n - (sum % n); sum += n - (sum % n); } return { qtn: 'Find the mean of:  ' + vals.join(', '), ans: fmt(sum / n) }; };
  G.stats_table = function () { return G.stats_total(); };

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
