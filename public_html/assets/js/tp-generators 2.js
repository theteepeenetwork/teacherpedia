/* =============================================================================
   tp-generators.js — Teacherpedia question-generator engine
   -----------------------------------------------------------------------------
   Ported verbatim from the design source `teacherpedia-data.js`. Vanilla JS,
   no build step, no dependencies. Safe to load in a browser or under Node
   (only touches `window`, which the caller may stub).

   PUBLIC API
     window.TP_GEN
       { generatorKey -> (difficulty 1..5) => { qtn, ans } }
       The canonical map of question generators. Keys MUST match the "key"
       field in the objectives manifest (app/Database/data/objectives.json) and
       are NOT renamed. Most generators ignore the `difficulty` argument; a few
       (bodmas, fracOfAmount) branch on it.

     window.TP_generate(key, difficulty)  -> { question, answer } | null
       Uniform adapter (defined at the bottom of this file). Wraps TP_GEN and
       normalises the legacy { qtn, ans } shape to { question, answer }.
       Returns null when the key is unknown. PREFER THIS over TP_GEN directly.

     window.TP_OBJECTIVES
       [ { y, s, t, k, a } ]  legacy objective manifest kept for parity with the
       original mockups. NOTE: the live app gets objectives from the server
       (the DB / app/Database/data/objectives.json), NOT from this array — the
       canonical thing shipped here is the TP_GEN generator map. An objective is
       "auto-generating" iff its key exists in TP_GEN.
   ========================================================================== */
(function () {
  const ri = (a, b) => Math.floor(Math.random() * (b - a + 1)) + a;
  const pick = (a) => a[Math.floor(Math.random() * a.length)];
  const gcd = (a, b) => (b ? gcd(b, a % b) : a);
  const fmt = (n) => Number.isInteger(n) ? n.toLocaleString('en-GB')
    : Number(n.toFixed(3)).toLocaleString('en-GB', { maximumFractionDigits: 3 });
  const frac = (n, d) => { const g = gcd(Math.abs(n), Math.abs(d)) || 1; n /= g; d /= g; return d === 1 ? `${n}` : `${n}/${d}`; };
  // n-digit random by pattern letter
  const dgt = { O: () => ri(2, 9), TO: () => ri(11, 99), HTO: () => ri(100, 999), ThHTO: () => ri(1000, 9999), TThHTO: () => ri(10000, 99999) };
  const decN = (places) => ri(1, Math.pow(10, places + 1) - 1) / Math.pow(10, places);

  // number -> words (British English, to billions)
  const ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
  const tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
  function under1000(n) {
    let s = '';
    if (n >= 100) { s += ones[Math.floor(n / 100)] + ' hundred'; n %= 100; if (n) s += ' and '; }
    if (n >= 20) { s += tens[Math.floor(n / 10)]; if (n % 10) s += '-' + ones[n % 10]; }
    else if (n > 0) s += ones[n];
    return s;
  }
  function words(n) {
    if (n === 0) return 'zero';
    const parts = []; const scales = [[1e9, 'billion'], [1e6, 'million'], [1e3, 'thousand']];
    let neg = n < 0; n = Math.abs(n);
    for (const [v, name] of scales) { if (n >= v) { parts.push(under1000(Math.floor(n / v)) + ' ' + name); n %= v; } }
    if (n > 0) parts.push((parts.length && n < 100 ? 'and ' : '') + under1000(n));
    return (neg ? 'negative ' : '') + parts.join(' ').replace(/\s+/g, ' ').trim();
  }
  // roman numerals
  const RN = [[1000, 'M'], [900, 'CM'], [500, 'D'], [400, 'CD'], [100, 'C'], [90, 'XC'], [50, 'L'], [40, 'XL'], [10, 'X'], [9, 'IX'], [5, 'V'], [4, 'IV'], [1, 'I']];
  const toRoman = (n) => { let s = ''; for (const [v, sym] of RN) while (n >= v) { s += sym; n -= v; } return s; };

  const G = {};

  // ---------- Counting ----------
  const countSeq = (step, start, back) => () => {
    let s = start + step * ri(0, 6); if (back) { const seq = [s, s - step, s - step * 2, s - step * 3]; return { qtn: `Continue: ${seq.map(fmt).join(', ')}, ___`, ans: fmt(s - step * 4) }; }
    const seq = [s, s + step, s + step * 2, s + step * 3]; return { qtn: `Continue: ${seq.map(fmt).join(', ')}, ___`, ans: fmt(s + step * 4) };
  };
  G.count_in_fifties_zero = countSeq(50, 0);
  G.count_in_hundreds_zero = countSeq(100, 0);
  G.count_in_fours = countSeq(4, 0);
  G.count_in_eights = countSeq(8, 0);
  G.count_in_fifties_multiple_5 = countSeq(50, 500, true);
  G._count_back_in_hundreds = countSeq(100, 900, true);
  G.count_through_zero = () => { const start = ri(3, 12), step = ri(2, 5); const seq = [start, start - step, start - step * 2]; return { qtn: `Continue across zero: ${seq.join(', ')}, ___`, ans: fmt(start - step * 3) }; };
  G.rand_thousand_tenthousand_hundredthousand = () => { const step = pick([1000, 10000, 100000]); const s = step * ri(2, 9); return { qtn: `Continue: ${fmt(s)}, ${fmt(s + step)}, ${fmt(s + step * 2)}, ___`, ans: fmt(s + step * 3) }; };
  G.rand_forwardsbackwards_10s_100s = () => { const step = pick([10, 100]) * (Math.random() < .5 ? 1 : -1); const s = ri(200, 9000); return { qtn: `Continue: ${fmt(s)}, ${fmt(s + step)}, ${fmt(s + step * 2)}, ___`, ans: fmt(s + step * 3) }; };
  G.rand_count_whole_and_decimal = () => { const step = pick([0.1, 0.5, 2, 5]); const s = ri(1, 9) + (Math.random() < .5 ? 0.5 : 0); return { qtn: `Continue: ${fmt(s)}, ${fmt(s + step)}, ${fmt(s + step * 2)}, ___`, ans: fmt(s + step * 3) }; };
  G.random_25_50_11 = countSeq(pick([25, 1000, 11]), 0);
  G.random_7_9 = countSeq(pick([7, 9]), 0);
  G.random_count_x = () => { const step = pick([6, 7, 9, 11, 12, 25, 1000]); return countSeq(step, 0)(); };

  // ---------- More / less ----------
  const moreLess = (delta) => () => { const n = ri(delta < 1000 ? 20 : 2000, delta < 1000 ? 880 : 8000); const more = Math.random() < .5; return { qtn: `${delta} ${more ? 'more' : 'less'} than ${fmt(n)} =`, ans: fmt(more ? n + delta : n - delta) }; };
  G.rand_ten_more_less = moreLess(10);
  G.rand_hundred_more_less = moreLess(100);
  G.rand_ten_hundred_more_less = () => moreLess(pick([10, 100]))();
  G.thousand_more = () => { const n = ri(1000, 90000); return { qtn: `1000 more than ${fmt(n)} =`, ans: fmt(n + 1000) }; };
  G.thousand_less = () => { const n = ri(2000, 90000); return { qtn: `1000 less than ${fmt(n)} =`, ans: fmt(n - 1000) }; };
  G.number_bond_100 = () => { const n = ri(1, 99); return { qtn: `${n} + ___ = 100`, ans: fmt(100 - n) }; };
  G.number_bond_1000 = () => { const n = 10 * ri(1, 99); return { qtn: `${fmt(n)} + ___ = 1000`, ans: fmt(1000 - n) }; };

  // ---------- Compare / order ----------
  const orderGen = (max, asWords) => () => { const set = Array.from({ length: 4 }, () => ri(Math.floor(max / 50), max)); const sorted = [...set].sort((a, b) => a - b); return { qtn: `Order smallest first: ${set.map(asWords ? words : fmt).join(', ')}`, ans: sorted.map(fmt).join(', ') }; };
  G._order_size_order = orderGen(1000);
  G.below_size_order = orderGen(500000);
  G._order_size_order_y5 = orderGen(1000000);
  G.exceeding_size_order = orderGen(2000000);
  G.size_order_words = orderGen(1000, true);
  G.rand_place_value_order = orderGen(1000);
  G.more_or_less_than = () => { const a = ri(100, 9999), b = ri(100, 9999); return { qtn: `Insert < or > :  ${fmt(a)} ___ ${fmt(b)}`, ans: a > b ? '>' : '<' }; };
  G.rand_read_write_order_compare_y6 = orderGen(10000000);

  // ---------- Reading / writing numbers ----------
  G.words_write_in_numerals = () => { const n = ri(100, 999); return { qtn: `Write in digits: ${words(n)}`, ans: fmt(n) }; };
  G.write_numerals_in_words = () => { const n = ri(100, 999); return { qtn: `Write in words: ${fmt(n)}`, ans: words(n) }; };
  G.rand_nums_words = () => { const n = ri(1000, 99999); return Math.random() < .5 ? { qtn: `Write in words: ${fmt(n)}`, ans: words(n) } : { qtn: `Write in digits: ${words(n)}`, ans: fmt(n) }; };
  G.write_in_numerals = G.words_write_in_numerals;
  G.words_write_in_numerals_below = () => { const n = ri(100, 999); return { qtn: `Write in digits: ${words(n)}`, ans: fmt(n) }; };
  G.d_exceeding_rand_nums_words = () => { const n = ri(10000, 100000); return { qtn: `Write in words: ${fmt(n)}`, ans: words(n) }; };
  G.size_order_words_write = G.write_numerals_in_words;
  G.read_roman_numerals = () => { const n = ri(1, 100); return { qtn: `Write as a number: ${toRoman(n)}`, ans: fmt(n) }; };
  G.roman_numerals_exceeding = () => { const n = ri(100, 500); return { qtn: `Write as a number: ${toRoman(n)}`, ans: fmt(n) }; };
  G.write_roman_numerals_exceeding = () => { const n = ri(1, 1000); return { qtn: `Write in Roman numerals: ${fmt(n)}`, ans: toRoman(n) }; };

  // ---------- Place value / partition ----------
  const digitValue = (digits) => () => { const n = ri(Math.pow(10, digits - 1), Math.pow(10, digits) - 1); const s = String(n); let i = ri(0, s.length - 1); while (s[i] === '0') i = ri(0, s.length - 1); return { qtn: `In ${fmt(n)}, the value of the digit ${s[i]} is =`, ans: fmt(+s[i] * Math.pow(10, s.length - 1 - i)) }; };
  G.partition_number_2digit = digitValue(2);
  G.partition_number_3digit = digitValue(3);
  G.place_value_below = digitValue(4);
  G.place_value = digitValue(5);
  G.place_value_exceeding = digitValue(6);
  G._add_partition_number = () => { const n = ri(100, 999); const s = String(n); return { qtn: `Partition ${fmt(n)} into hundreds, tens and ones (add to check):`, ans: `${+s[0] * 100} + ${+s[1] * 10} + ${+s[2]}` }; };
  G.place_value_decimal = () => { const n = ri(1000, 9999) / 1000; const s = n.toFixed(3); const idx = pick([0, 2, 3, 4]); const place = [1, 0, 0.1, 0.01, 0.001][idx]; return { qtn: `In ${s}, the value of the digit ${s[idx]} is =`, ans: fmt(+s[idx] * place) }; };
  G.place_value_decimal_confidently = G.place_value_decimal;

  // ---------- Rounding ----------
  const roundTo = (place) => () => { const n = ri(place, place * 99); return { qtn: `Round ${fmt(n)} to the nearest ${fmt(place)} =`, ans: fmt(Math.round(n / place) * place) }; };
  G.ten_round_numbers = roundTo(10);
  G.round_numbers_hundred = roundTo(100);
  G.round_ten_and_hundred = () => roundTo(pick([10, 100]))();
  G.round_ten_and_hundred_thousand = () => roundTo(pick([10, 100, 1000]))();
  G.round_tenthousand_hundredthousand = () => roundTo(pick([10000, 100000]))();
  G.round_ten_and_hundred_thousand_tenthousand_hundredthousand = () => roundTo(pick([10, 100, 1000, 10000, 100000]))();
  G.rand_rounding = () => roundTo(pick([10, 100]))();
  G.round_decimal_numbers_2dp = () => { const x = decN(3); return { qtn: `Round ${x} to 2 decimal places =`, ans: fmt(Math.round(x * 100) / 100) }; };
  G.round_decimal_numbers_3dp = () => { const x = ri(1000, 99999) / 10000; return { qtn: `Round ${x} to 3 decimal places =`, ans: fmt(Math.round(x * 1000) / 1000) }; };

  // ---------- Double / halve ----------
  G.double_number_multiplesof100 = () => { const n = 100 * ri(2, 9); return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G.double_number_multiplesof_10 = () => { const n = 10 * ri(11, 49); return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G._double_number_1000 = () => { const n = ri(101, 499); return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G.double_number_exceeding = () => { const n = ri(2, 100); return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G.double_number_meeting = () => { const n = ri(21, 50); return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G.double_number_1decimal = () => { const n = ri(11, 99) / 10; return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G.double_number_2decimal = () => { const n = ri(101, 999) / 100; return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G._rand_double_halve_wt_y5 = () => { const n = ri(100, 999); return Math.random() < .5 ? { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) } : { qtn: `Halve ${fmt(n * 2)} =`, ans: fmt(n) }; };
  G.double_halve_aare_y5_rand = () => { const n = ri(1000, 9999); return Math.random() < .5 ? { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) } : { qtn: `Halve ${fmt(n * 2)} =`, ans: fmt(n) }; };
  G.double_halve_aboveare_y5_rand = () => { const n = ri(10000, 99999); return { qtn: `Double ${fmt(n)} =`, ans: fmt(n * 2) }; };
  G.halve_number_multipleof100 = () => { const n = 100 * ri(2, 9); return { qtn: `Halve ${fmt(n)} =`, ans: fmt(n / 2) }; };
  G._halve_number_answerendingzero = () => { const n = 20 * ri(2, 9); return { qtn: `Halve ${fmt(n)} =`, ans: fmt(n / 2) }; };
  G.halve_number_answermultiple5 = () => { const n = 10 * (2 * ri(2, 9) + 1); return { qtn: `Halve ${fmt(n)} =`, ans: fmt(n / 2) }; };
  G.halve_number_100 = () => { const n = 2 * ri(2, 50); return { qtn: `Halve ${fmt(n)} =`, ans: fmt(n / 2) }; };
  G.halve_number_answerof1decimal = () => { const n = 2 * ri(20, 99) + 1; return { qtn: `Halve ${fmt(n)} =`, ans: fmt(n / 2) }; };
  G.halve_number_answerof2decimal = () => { const n = ri(101, 999) / 10; return { qtn: `Halve ${fmt(n)} =`, ans: fmt(n / 2) }; };

  // ---------- Written + / − ----------
  const addG = (p, q) => () => { const a = dgt[p](), b = dgt[q](); return { qtn: `${fmt(a)} + ${fmt(b)} =`, ans: fmt(a + b) }; };
  const subG = (p, q) => () => { let a = dgt[p](), b = dgt[q](); if (b > a) [a, b] = [b, a]; return { qtn: `${fmt(a)} − ${fmt(b)} =`, ans: fmt(a - b) }; };
  G.addition_TO_TO = addG('TO', 'TO'); G.addition_HTO_TO = addG('HTO', 'TO'); G.HTO_HTO_addition = addG('HTO', 'HTO'); G.idHTO_HTO_addition = addG('HTO', 'HTO');
  G.addition_ThHTO_HTO = addG('ThHTO', 'HTO'); G.addition_ThHTO_ThHTO = addG('ThHTO', 'ThHTO');
  G.bridge1000_addition_HTO_HTO = addG('HTO', 'HTO'); G.add_or_subtract_4_digit = () => (Math.random() < .5 ? addG('ThHTO', 'ThHTO') : subG('ThHTO', 'ThHTO'))();
  G.rand_adding_beyond_four_digits = () => (Math.random() < .5 ? addG('TThHTO', 'TThHTO') : subG('TThHTO', 'TThHTO'))();
  G.subtraction_TO_TO = subG('TO', 'TO'); G.subtraction_HTO_TO = subG('HTO', 'TO'); G.subtraction_HTO_HTO = subG('HTO', 'HTO');
  G.subtraction_ThHTO_HTO = subG('ThHTO', 'HTO'); G.subtraction_ThHTO_ThHTO = subG('ThHTO', 'ThHTO');
  // decimal add/sub
  const decAdd = (pl) => () => { const a = decN(pl), b = decN(pl); return { qtn: `${fmt(a)} + ${fmt(b)} =`, ans: fmt(a + b) }; };
  const decSub = (pl) => () => { let a = decN(pl), b = decN(pl); if (b > a) [a, b] = [b, a]; return { qtn: `${fmt(a)} − ${fmt(b)} =`, ans: fmt(a - b) }; };
  G.addition_ot_ot = decAdd(1); G.addition_oth_oth = decAdd(2); G.addition_othth_othth = decAdd(3);
  G.subtraction_ot_ot = decSub(1); G.subtraction_oth_oth = decSub(2); G.subtraction_othth_othth = decSub(3); G.subtraction_oth_othth = decSub(2); G.subtraction_ot_otth = decSub(2);

  // ---------- Times tables ----------
  const table = (t) => () => { const b = ri(2, 12); return { qtn: `${t} × ${b} =`, ans: fmt(t * b) }; };
  const inverse = (t) => () => { const b = ri(2, 12); return { qtn: `${fmt(t * b)} ÷ ${t} =`, ans: fmt(b) }; };
  G.three_x = table(3); G.four_x = table(4); G.eight_x = table(8); G.ten_x = table(10);
  G.rand_3_4_8 = () => table(pick([3, 4, 8]))(); G.all_rand = () => table(ri(2, 12))();
  G.inverse_three_x = inverse(3); G.inverse_four_x = inverse(4); G.inverse_eight_x = inverse(8);
  G.rand_inverse = () => inverse(ri(2, 12))(); G.rand_inverse_y3 = () => inverse(pick([2, 3, 4, 5, 8, 10]))();
  G.multiply_inverse_rand = () => (Math.random() < .5 ? table(ri(2, 12)) : inverse(ri(2, 12)))();
  G.rand_inverse_multiply = G.multiply_inverse_rand;
  G._3_number_multiply = () => { const a = ri(2, 6), b = ri(2, 6), c = ri(2, 5); return { qtn: `${a} × ${b} × ${c} =`, ans: fmt(a * b * c) }; };
  G.factor_pairs = () => { const n = pick([12, 16, 18, 24, 36, 48]); const pairs = []; for (let i = 1; i * i <= n; i++) if (n % i === 0) pairs.push(`${i}×${n / i}`); return { qtn: `List the factor pairs of ${n}:`, ans: pairs.join(', ') }; };
  G.commutativity = () => { const a = ri(2, 9), b = ri(2, 9), c = ri(2, 5); return { qtn: `${a} × ${b} × ${c} =`, ans: fmt(a * b * c) }; };
  G.multiplybyten = () => { const n = ri(2, 99); return { qtn: `${fmt(n)} × 10 =`, ans: fmt(n * 10) }; };
  G.multiplybyhundred = () => { const n = ri(2, 99); return { qtn: `${fmt(n)} × 100 =`, ans: fmt(n * 100) }; };
  G.rand_multiply_divide_powers10 = () => { const n = ri(2, 999) / 10, m = pick([10, 100, 1000]); return Math.random() < .5 ? { qtn: `${fmt(n)} × ${fmt(m)} =`, ans: fmt(n * m) } : { qtn: `${fmt(n * m)} ÷ ${fmt(m)} =`, ans: fmt(n) }; };

  // ---------- Written × / ÷ ----------
  G.TO_O_multiply = () => { const a = dgt.TO(), b = dgt.O(); return { qtn: `${a} × ${b} =`, ans: fmt(a * b) }; };
  G.multiply_HTO_O = () => { const a = dgt.HTO(), b = dgt.O(); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.multiply_ThHTO_O = () => { const a = dgt.ThHTO(), b = dgt.O(); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.x_multiply_TO_TO = () => { const a = dgt.TO(), b = dgt.TO(); return { qtn: `${a} × ${b} =`, ans: fmt(a * b) }; };
  G.multiply_ThHTO_TO = () => { const a = dgt.ThHTO(), b = dgt.TO(); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.decimalmultiplybyones = () => { const a = ri(11, 99) / 10, b = dgt.O(); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.divide_TO_O_noremainder = () => { const b = dgt.O(), c = ri(2, 11); return { qtn: `${fmt(b * c)} ÷ ${b} =`, ans: fmt(c) }; };
  G.TO_O_divide = () => { const b = ri(3, 9), c = ri(2, 11), r = ri(1, b - 1); return { qtn: `${fmt(b * c + r)} ÷ ${b} =`, ans: `${fmt(c)} r ${r}` }; };
  G.divide_HTO_O = () => { const b = dgt.O(), c = ri(20, 110); return { qtn: `${fmt(b * c)} ÷ ${b} =`, ans: fmt(c) }; };
  G.divide_ThHTO_O = () => { const b = dgt.O(), c = ri(200, 1100); return { qtn: `${fmt(b * c)} ÷ ${b} =`, ans: fmt(c) }; };

  // ---------- Factors / primes / squares ----------
  G.rand_factor_multiple = () => { const n = pick([12, 15, 18, 20, 24, 30]); if (Math.random() < .5) { const f = ri(2, 9); return { qtn: `Is ${f} a factor of ${n}? (yes/no)`, ans: n % f === 0 ? 'yes' : 'no' }; } return { qtn: `State the first multiple of ${n} after ${n} =`, ans: fmt(n * 2) }; };
  G.prime_numbers = () => { const n = ri(2, 40); const isP = (x) => { if (x < 2) return false; for (let i = 2; i * i <= x; i++) if (x % i === 0) return false; return true; }; return { qtn: `Is ${n} a prime number? (yes/no)`, ans: isP(n) ? 'yes' : 'no' }; };
  G.common_factors = () => { const a = pick([12, 18, 24]), b = pick([16, 20, 30]); const cf = []; for (let i = 1; i <= Math.min(a, b); i++) if (a % i === 0 && b % i === 0) cf.push(i); return { qtn: `Common factors of ${a} and ${b}:`, ans: cf.join(', ') }; };
  G.rand_cubed_squared = () => Math.random() < .6 ? (() => { const n = ri(2, 15); return { qtn: `${n}² =`, ans: fmt(n * n) }; })() : (() => { const n = ri(2, 9); return { qtn: `${n}³ =`, ans: fmt(n * n * n) }; })();

  // ---------- Fractions / decimals / % ----------
  G.orderFractionsSameDenoms = () => { const d = pick([5, 6, 8, 10, 12]); const ns = []; while (ns.length < 3) { const x = ri(1, d - 1); if (!ns.includes(x)) ns.push(x); } const sorted = [...ns].sort((a, b) => a - b); return { qtn: `Order smallest first: ${ns.map(n => n + '/' + d).join(', ')}`, ans: sorted.map(n => n + '/' + d).join(', ') }; };
  G.orderFractionsDifferentDenoms = () => { const set = [[1, 2], [2, 3], [3, 4], [1, 4], [2, 5]]; const chosen = []; while (chosen.length < 3) { const c = pick(set); if (!chosen.includes(c)) chosen.push(c); } const sorted = [...chosen].sort((a, b) => a[0] / a[1] - b[0] / b[1]); return { qtn: `Order smallest first: ${chosen.map(f => f[0] + '/' + f[1]).join(', ')}`, ans: sorted.map(f => f[0] + '/' + f[1]).join(', ') }; };
  G.fractionAsDecimalNumbers = () => { const f = pick([[1, 2], [1, 4], [3, 4], [1, 5], [2, 5], [1, 10], [3, 10]]); return { qtn: `Write ${f[0]}/${f[1]} as a decimal =`, ans: fmt(f[0] / f[1]) }; };
  G.decimalAsFraction = () => { const n = ri(1, 99); return { qtn: `Write 0.${n < 10 ? '0' + n : n} as a fraction over 100 =`, ans: `${n}/100` }; };
  G.decimalAsSimplestFraction = () => { const opts = [[0.5, '1/2'], [0.25, '1/4'], [0.75, '3/4'], [0.2, '1/5'], [0.4, '2/5'], [0.1, '1/10']]; const o = pick(opts); return { qtn: `Write ${o[0]} as a fraction in its simplest form =`, ans: o[1] }; };
  G.decimalAsPercent = () => { const x = pick([0.05, 0.1, 0.2, 0.25, 0.5, 0.75, 0.6]); return { qtn: `Write ${x} as a percentage =`, ans: `${x * 100}%` }; };
  G.fractionAsPercent = () => { const f = pick([[1, 2], [1, 4], [3, 4], [1, 5], [1, 10], [3, 10]]); return { qtn: `Write ${f[0]}/${f[1]} as a percentage =`, ans: `${f[0] / f[1] * 100}%` }; };

  // ---------- Percentages ----------
  G.percentage10percent10multiple = () => { const a = 10 * ri(2, 30); return { qtn: `10% of ${fmt(a)} =`, ans: fmt(a / 10) }; };
  G.percentage10percentNumber = () => { const a = ri(20, 480); return { qtn: `10% of ${fmt(a)} =`, ans: fmt(a / 10) }; };
  G.percent5Multiple10 = () => { const a = 10 * ri(2, 30); return { qtn: `5% of ${fmt(a)} =`, ans: fmt(a / 20) }; };
  G.percentage20percent10multiple = () => { const a = 10 * ri(2, 30); return { qtn: `20% of ${fmt(a)} =`, ans: fmt(a / 5) }; };
  G.percentage20percentNumber = () => { const a = 5 * ri(4, 99); return { qtn: `20% of ${fmt(a)} =`, ans: fmt(a / 5) }; };
  G.percentage1percent10multiple = () => { const a = 100 * ri(2, 30); return { qtn: `1% of ${fmt(a)} =`, ans: fmt(a / 100) }; };
  G.percentage1percentNumber = () => { const a = 100 * ri(2, 30); return { qtn: `1% of ${fmt(a)} =`, ans: fmt(a / 100) }; };
  G.percentNumber = () => { const p = pick([15, 25, 30, 40, 60, 75]); const a = 20 * ri(2, 25); return { qtn: `${p}% of ${fmt(a)} =`, ans: fmt(p / 100 * a) }; };

  // ---------- Mastery: missing number ----------
  const missAdd = (p, q) => () => { const a = dgt[p](), b = dgt[q](); const sum = a + b; const sa = String(a); const hide = ri(0, sa.length - 1); const shown = sa.split('').map((d, i) => i === hide ? '☐' : d).join(''); return { qtn: `${shown} + ${fmt(b)} = ${fmt(sum)}`, ans: sa[hide] }; };
  const missSub = (p, q) => () => { let a = dgt[p](), b = dgt[q](); if (b > a) [a, b] = [b, a]; const diff = a - b; const sb = String(b); const hide = ri(0, sb.length - 1); const shown = sb.split('').map((d, i) => i === hide ? '☐' : d).join(''); return { qtn: `${fmt(a)} − ${shown} = ${fmt(diff)}`, ans: sb[hide] }; };
  G.missing_HTO_HTO_addition = missAdd('HTO', 'HTO');
  G.missing_ThHTO_ThHTO_addition = missAdd('ThHTO', 'ThHTO');
  G.missing_TThThHTO_TthThHTO_addition = missAdd('TThHTO', 'TThHTO');
  G.missing_ThHTO_ThHTO_subtraction = missSub('ThHTO', 'ThHTO');
  G.missing_TThThHTO_TthThHTO_subtraction = missSub('TThHTO', 'TThHTO');
  G.missing_HTO_TO_multiplication = () => { const a = dgt.HTO(), b = dgt.TO(); return { qtn: `${fmt(a)} × ☐ = ${fmt(a * b)}`, ans: fmt(b) }; };

  // ---------- Y6 written ×/÷ + reasoning (remapped patterns) ----------
  G.multiply_HTO_TO = () => { const a = dgt.HTO(), b = dgt.TO(); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.divide_TO_TO = () => { const b = dgt.TO(), c = ri(2, 9); return { qtn: `${fmt(b * c)} ÷ ${b} =`, ans: fmt(c) }; };
  G.divide_HTO_TO = () => { const b = ri(11, 40), c = ri(3, 22); return { qtn: `${fmt(b * c)} ÷ ${b} =`, ans: fmt(c) }; };
  G.divide_ThHTO_TO = () => { const b = ri(11, 40), c = ri(20, 99); return { qtn: `${fmt(b * c)} ÷ ${b} =`, ans: fmt(c) }; };
  G.bodmas = (d) => { d = d || 3; if (d <= 2) { const a = ri(2, 12), b = ri(2, 12), c = ri(2, 12); return { qtn: `${a} + ${b} × ${c} =`, ans: fmt(a + b * c) }; } if (d === 3) { const a = ri(2, 20), b = ri(2, 20), c = ri(2, 9); return { qtn: `(${a} + ${b}) × ${c} =`, ans: fmt((a + b) * c) }; } const a = ri(2, 15), b = ri(2, 15), c = ri(2, 9), f = ri(2, 30); return { qtn: `(${a} + ${b}) × ${c} − ${f} =`, ans: fmt((a + b) * c - f) }; };
  G.mental_mixed = () => { const a = ri(3, 9) * 100, b = 10 * ri(1, 9), c = 10 * ri(1, 9); return { qtn: `${fmt(a)} − ${b} + ${c} =`, ans: fmt(a - b + c) }; };
  G.fracOfAmount = (d) => { const den = pick([3, 4, 5, 6, 8]); const num = ri(1, den - 1); const amt = den * ri(2, 60); return { qtn: `${num}/${den} of ${fmt(amt)} =`, ans: fmt(num * amt / den) }; };
  G.pctOfAmount = () => { const p = pick([15, 25, 30, 40, 60, 75]); const a = 20 * ri(2, 25); return { qtn: `${p}% of ${fmt(a)} =`, ans: fmt(p / 100 * a) }; };
  G.commonFactorsMultiples = () => { const a = pick([12, 18, 24]), b = pick([16, 20, 30]); const cf = []; for (let i = 1; i <= Math.min(a, b); i++) if (a % i === 0 && b % i === 0) cf.push(i); return { qtn: `Common factors of ${a} and ${b}:`, ans: cf.join(', ') }; };
  // ---------- 4 keys present in tables but previously ungenerated ----------
  G.number_liney3 = () => { const max = pick([100, 500, 1000]); const n = ri(1, max - 1); return { qtn: `Estimate the number marked on a 0–${max} number line at about ${Math.round(n / max * 100)}% along =`, ans: fmt(Math.round(n / (max / 10)) * (max / 10)) }; };
  G.multiples_of_2_5_10 = () => { const m = pick([2, 5, 10]); const n = m * ri(2, 100); const isM = n % m === 0; return { qtn: `Is ${fmt(n)} a multiple of ${m}? (yes/no)`, ans: isM ? 'yes' : 'no' }; };
  G.decimals_size_order = () => { const set = Array.from({ length: 4 }, () => ri(10, 999) / 100); const sorted = [...set].sort((a, b) => a - b); return { qtn: `Order smallest first: ${set.map(fmt).join(', ')}`, ans: sorted.map(fmt).join(', ') }; };
  G.rand_write_exceeding = () => { const n = ri(1000000, 9999999); return { qtn: `Write in words: ${fmt(n)}`, ans: words(n) }; };

  window.TP_GEN = G;
})();



/* ---------------------------------------------------------------------------
   Legacy objective manifest (design parity only).
   The LIVE app reads objectives from the server / DB (objectives.json), not
   from this array. Kept so the original mockups still run standalone. The
   canonical artefact in this file is the TP_GEN generator map above.
   --------------------------------------------------------------------------- */
window.TP_OBJECTIVES = [{"y":3,"s":"Counting","t":"Count in steps of 50 from 0","k":"count_in_fifties_zero","a":true},{"y":3,"s":"Counting","t":"Count in steps of 4 from 0","k":"count_in_fours","a":true},{"y":3,"s":"Counting","t":"Count backwards in steps of 50","k":"count_in_fifties_multiple_5","a":true},{"y":3,"s":"Counting","t":"Count in steps of 100 from 0","k":"count_in_hundreds_zero","a":true},{"y":3,"s":"Counting","t":"Count in steps of 8 from 0","k":"count_in_eights","a":true},{"y":3,"s":"Counting","t":"Count backwards in steps of 100","k":"_count_back_in_hundreds","a":true},{"y":3,"s":"Counting","t":"Find 10 more or less than a given number","k":"rand_ten_more_less","a":true},{"y":3,"s":"Counting","t":"Find 100 more or less than a given number","k":"rand_hundred_more_less","a":true},{"y":3,"s":"Counting","t":"Find 10 or 100 more or less than a given number.","k":"rand_ten_hundred_more_less","a":true},{"y":3,"s":"Comparing & ordering","t":"Compare and order numbers up to 1000 in numerals","k":"_order_size_order","a":true},{"y":3,"s":"Comparing & ordering","t":"Compare and order numbers up to 1000 in words","k":"size_order_words","a":true},{"y":3,"s":"Representing & estimating","t":"Identify and represent numbers using different methods by estimating postion on a number line","k":"number_liney3","a":true},{"y":3,"s":"Representing & estimating","t":"Recognise multiples of 2, 5 and 10 up to 1000","k":"multiples_of_2_5_10","a":true},{"y":3,"s":"Reading & writing","t":"Read and write numbers up to 1000 in numerals","k":"words_write_in_numerals","a":true},{"y":3,"s":"Reading & writing","t":"Read and write up to 1000 in words","k":"write_numerals_in_words","a":true},{"y":3,"s":"Reading & writing","t":"Read and write numbers up to 1000 in numerals and words","k":"rand_nums_words","a":true},{"y":3,"s":"Place value","t":"Recognise the value of each digit in 2-digit numbers (tens, ones/units)","k":"partition_number_2digit","a":true},{"y":3,"s":"Place value","t":"Partition and recognise the value of HTU","k":"partition_number_3digit","a":true},{"y":3,"s":"Place value","t":"Partition 3-digit numbers in more than one way (adding values)","k":"_add_partition_number","a":true},{"y":3,"s":"Place value","t":"Double simple numbers beyond 20 (ending in zero e.g. 30, 40, 50)","k":"double_number_multiplesof_10","a":true},{"y":3,"s":"Place value","t":"Double numbers beyond 20, with units (e.g. 21, 33 etc) up to 50","k":"double_number_meeting","a":true},{"y":3,"s":"Place value","t":"I am fluent in the order and place value of numbers to 1000","k":"rand_place_value_order","a":true},{"y":3,"s":"Place value","t":"Halve simple numbers beyond 20 (answers ending in zero)","k":"_halve_number_answerendingzero","a":true},{"y":3,"s":"Place value","t":"Halve numbers beyond 20 (answers ending in 5 e.g. halve of 30 is 15)","k":"halve_number_answermultiple5","a":true},{"y":3,"s":"Place value","t":"Double all numbers up to 100","k":"double_number_exceeding","a":true},{"y":3,"s":"Place value","t":"Halve all numbers up to 100","k":"halve_number_100","a":true},{"y":3,"s":"Rounding","t":"Round 2-digit numbers to the nearest 10","k":"ten_round_numbers","a":true},{"y":3,"s":"Rounding","t":"Round 2 and 3-digit numbers to the nearest 10 or 100","k":"round_ten_and_hundred","a":true},{"y":3,"s":"Rounding","t":"Round 2 and 3-digit numbers to the nearest 10 or 100 and give estimates for their sums and differences","k":"rand_rounding","a":true},{"y":3,"s":"Rounding","t":"Add TU and TU (bridging 100)","k":"addition_TO_TO","a":true},{"y":3,"s":"Rounding","t":"Add HTU and TU (not bridging 1000)","k":"addition_HTO_TO","a":true},{"y":3,"s":"Rounding","t":"Add HTU and HTU","k":"HTO_HTO_addition","a":true},{"y":3,"s":"Rounding","t":"Subtract TU and TU","k":"subtraction_TO_TO","a":true},{"y":3,"s":"Rounding","t":"Subtract TU from HTU (HTU - TU)","k":"subtraction_HTO_TO","a":true},{"y":3,"s":"Rounding","t":"Subtract HTU from HTU","k":"subtraction_HTO_HTO","a":true},{"y":3,"s":"Multiplication & division","t":"3x table.","k":"three_x","a":true},{"y":3,"s":"Multiplication & division","t":"8 x table","k":"eight_x","a":true},{"y":3,"s":"Multiplication & division","t":"Mixed 3, 4 and 8 x table","k":"rand_3_4_8","a":true},{"y":3,"s":"Multiplication & division","t":"4 x table","k":"four_x","a":true},{"y":3,"s":"Multiplication & division","t":"Ones x 10","k":"ten_x","a":true},{"y":3,"s":"Multiplication & division","t":"Inverse 3 x table","k":"inverse_three_x","a":true},{"y":3,"s":"Multiplication & division","t":"Inverse 8 x table","k":"inverse_eight_x","a":true},{"y":3,"s":"Multiplication & division","t":"Inverse 4 x table","k":"inverse_four_x","a":true},{"y":3,"s":"Multiplication & division","t":"TO x 10","k":"multiplybyten","a":true},{"y":3,"s":"Multiplication & division","t":"Ones x 100","k":"multiplybyhundred","a":true},{"y":3,"s":"Multiplication & division","t":"Inverse 2, 3, 4, 5, 8, 10 x table","k":"rand_inverse_y3","a":true},{"y":3,"s":"Multiplication & division","t":"TO x 100","k":"multiplybyhundred","a":true},{"y":3,"s":"Written methods","t":"TO x O","k":"TO_O_multiply","a":true},{"y":3,"s":"Written methods","t":"TO ÷ O no remainder","k":"divide_TO_O_noremainder","a":true},{"y":3,"s":"Written methods","t":"TO ÷ O remainder","k":"TO_O_divide","a":true},{"y":3,"s":"Written methods","t":"TO x TO","k":"x_multiply_TO_TO","a":true},{"y":4,"s":"Counting","t":"I can count in multiples of 25, 1000 and 11","k":"random_25_50_11","a":true},{"y":4,"s":"Counting","t":"I can count in multiples of 7 and 9","k":"random_7_9","a":true},{"y":4,"s":"Counting","t":"I can count in multiples of 6, 7, 9, 11, 12, 25 and 1000 (Children should know all tables by this point)","k":"random_count_x","a":true},{"y":4,"s":"Counting","t":"I can find 1000 more than any given number","k":"thousand_more","a":true},{"y":4,"s":"Counting","t":"I can find 1000 less than any given number","k":"thousand_less","a":true},{"y":4,"s":"Counting","t":"I can count backwards through to zero","k":"count_through_zero","a":true},{"y":4,"s":"Counting","t":"I can say how many more is needed to make 100 from a given number","k":"number_bond_100","a":true},{"y":4,"s":"Counting","t":"I can say how many more is needed to make 1000 from a given number","k":"number_bond_1000","a":true},{"y":4,"s":"Comparing & ordering","t":"I can order and compare numbers beyond 1000","k":"_order_size_order","a":true},{"y":4,"s":"Comparing & ordering","t":"I can compare numbers with the same number to decimal places up to 2 decimal places (copied from fractions)","k":"decimals_size_order","a":true},{"y":4,"s":"Comparing & ordering","t":"I can use the symbols < and > to state inequalities","k":"more_or_less_than","a":true},{"y":4,"s":"Representing & estimating","t":"I can identify, represent and estimate numbers using different representations; including measures","k":null,"a":false},{"y":4,"s":"Reading & writing","t":"I can read numbers in words and write as digits to 1000.","k":"words_write_in_numerals_below","a":true},{"y":4,"s":"Reading & writing","t":"I can read numbers in words and write in digits to at least 10,000 and vice versa.","k":"rand_nums_words","a":true},{"y":4,"s":"Reading & writing","t":"I can read, write, order and compare numbers to 100,000","k":"d_exceeding_rand_nums_words","a":true},{"y":4,"s":"Reading & writing","t":"I can read Roman numerals to 100 (I to C)","k":"read_roman_numerals","a":true},{"y":4,"s":"Reading & writing","t":"I can read Roman numerals to 500.","k":"roman_numerals_exceeding","a":true},{"y":4,"s":"Place value","t":"I can recognise the place value of each digit in 4-digit numbers (ThHTU)","k":"place_value_below","a":true},{"y":4,"s":"Place value","t":"I can recognise the place value of each digit in 5-digit numbers","k":"place_value","a":true},{"y":4,"s":"Place value","t":"I can recognise the place value of each digit in 6-digit numbers","k":"place_value_exceeding","a":true},{"y":4,"s":"Place value","t":"I can double numbers beyond 100 (e.g. 200, 300, 400 etc)","k":"double_number_multiplesof100","a":true},{"y":4,"s":"Place value","t":"I can double numbers beyond 100 involving tens (e.g. 120, 140 etc)","k":"double_number_multiplesof_10","a":true},{"y":4,"s":"Place value","t":"I can double numbers beyond 100 involving tens and units (e.g 115)","k":"_double_number_1000","a":true},{"y":4,"s":"Place value","t":"I can halve numbers above 100 (e.g. 200, 300, 400 etc)","k":"halve_number_multipleof100","a":true},{"y":4,"s":"Place value","t":"I can halve numbers above 100 (e.g. 120, 140 etc)","k":"_halve_number_answerendingzero","a":true},{"y":4,"s":"Place value","t":"I can halve numbers above 100 involving tens that will end in 5 (e.g 110)","k":"halve_number_answermultiple5","a":true},{"y":4,"s":"Rounding","t":"I can round any number to the nearest 10","k":"ten_round_numbers","a":true},{"y":4,"s":"Rounding","t":"I can round any number to the nearest 100","k":"round_numbers_hundred","a":true},{"y":4,"s":"Rounding","t":"I can round any number to the nearest 10, 100 or 1000","k":"round_ten_and_hundred_thousand","a":true},{"y":4,"s":"Addition & subtraction","t":"I can estimate and use inverse operations to check answers","k":"rand_rounding","a":true},{"y":4,"s":"Written methods","t":"I can add HTU and HTU","k":"HTO_HTO_addition","a":true},{"y":4,"s":"Written methods","t":"I can add HTU and HTU (bridging 1000)","k":"bridge1000_addition_HTO_HTO","a":true},{"y":4,"s":"Written methods","t":"I can add and subtract numbers up to 4-digits","k":"add_or_subtract_4_digit","a":true},{"y":4,"s":"Written methods","t":"I can subtract TU from HTU (HTU - TU)","k":"subtraction_HTO_TO","a":true},{"y":4,"s":"Written methods","t":"I can subtract HTU from HTU","k":"subtraction_HTO_HTO","a":true},{"y":4,"s":"Multiplication & division","t":"I can recall multiplication facts for all the multiplication tables up to 12 x 12.","k":"all_rand","a":true},{"y":4,"s":"Multiplication & division","t":"I can recall inverse multiplication facts for all the multiplication tables up to 12 x 12.","k":"rand_inverse","a":true},{"y":4,"s":"Multiplication & division","t":"I can multiply up to 12x12 and recall inverse facts.","k":"multiply_inverse_rand","a":true},{"y":4,"s":"Multiplication & division","t":"I can use factor pairs in mental calculations.","k":"factor_pairs","a":true},{"y":4,"s":"Multiplication & division","t":"I can multiply 3 numnbers.","k":"_3_number_multiply","a":true},{"y":4,"s":"Multiplication & division","t":"I can recognise and use factor pairs and commutatively in mental calculations","k":"commutativity","a":true},{"y":4,"s":"Written methods","t":"TU x U","k":"TO_O_multiply","a":true},{"y":4,"s":"Written methods","t":"I can Calculate TU x TU","k":"x_multiply_TO_TO","a":true},{"y":4,"s":"Written methods","t":"HTU x U","k":"multiply_HTO_O","a":true},{"y":4,"s":"Written methods","t":"I can divide TU by U","k":"TO_O_divide","a":true},{"y":5,"s":"Counting","t":"Count forwards and backwards with positive and negative whole numbers through zero","k":"count_through_zero","a":true},{"y":5,"s":"Counting","t":"Count forwards/backwards in 1000s, 10,000s and 100,000s up to 1,000,000","k":"rand_thousand_tenthousand_hundredthousand","a":true},{"y":5,"s":"Counting","t":"Count from any given number in whole number and decimal steps","k":"rand_count_whole_and_decimal","a":true},{"y":5,"s":"Counting","t":"Count forwards/backwards in 10s and 100s up to 1,000,000","k":"rand_forwardsbackwards_10s_100s","a":true},{"y":5,"s":"Comparing & ordering","t":"Order numbers up to 500,000","k":"below_size_order","a":true},{"y":5,"s":"Comparing & ordering","t":"Confidently order numbers up to 1,000,000","k":"_order_size_order","a":true},{"y":5,"s":"Comparing & ordering","t":"Confidently order numbers beyond 1,000,000","k":"exceeding_size_order","a":true},{"y":5,"s":"Reading & writing","t":"Write numbers to 1,000,000 in words.","k":"size_order_words","a":true},{"y":5,"s":"Reading & writing","t":"Write numbers to 1,000,000 in words as digits.","k":"words_write_in_numerals","a":true},{"y":5,"s":"Reading & writing","t":"Write numbers beyond 1,000,000 in words and digits.","k":"rand_write_exceeding","a":true},{"y":5,"s":"Reading & writing","t":"Read Roman numerals to 1000 (M)","k":"read_roman_numerals","a":true},{"y":5,"s":"Reading & writing","t":"Recognise years written in Roman numerals","k":"roman_numerals_exceeding","a":true},{"y":5,"s":"Reading & writing","t":"Write Roman numerals to 1000 (M)","k":"write_roman_numerals_exceeding","a":true},{"y":5,"s":"Place value","t":"Identify the place value of any digit up to 500,000","k":"place_value","a":true},{"y":5,"s":"Place value","t":"Identify the place value of any digit up to 1,000,000","k":"place_value_exceeding","a":true},{"y":5,"s":"Place value","t":"Identify the place value of any digit up to 1,000,000 and add place values together.","k":"_add_partition_number","a":true},{"y":5,"s":"Place value","t":"Confidently double/halve any number up to 3-digits","k":"_rand_double_halve_wt_y5","a":true},{"y":5,"s":"Place value","t":"Double/halve any number up to 4-digits","k":"double_halve_aare_y5_rand","a":true},{"y":5,"s":"Place value","t":"Double/halve any number beyond 4-digits","k":"double_halve_aboveare_y5_rand","a":true},{"y":5,"s":"Place value","t":"Double numbers involving decimals to 1dp","k":"double_number_1decimal","a":true},{"y":5,"s":"Place value","t":"Double numbers involving decimals to 2dp","k":"double_number_2decimal","a":true},{"y":5,"s":"Place value","t":"Have halve numbers that will end in decimals to 1dp (e.g. halve of 133 = 66.5)","k":"halve_number_answerof1decimal","a":true},{"y":5,"s":"Place value","t":"Halve numbers that will end in decimals to 2dp (e.g. halve of 66.5 = 33.25)","k":"halve_number_answerof2decimal","a":true},{"y":5,"s":"Rounding","t":"Round any number up to 1,000,000 to the nearest 10, 100, 1000","k":"round_ten_and_hundred_thousand","a":true},{"y":5,"s":"Rounding","t":"Round any number up to 1,000,000 to the nearest 10,000 and 100,000","k":"round_tenthousand_hundredthousand","a":true},{"y":5,"s":"Rounding","t":"Round any number up to 1,000,000 to the nearest 10, 100, 1000, 10,000 and 100,000","k":"round_ten_and_hundred_thousand_tenthousand_hundredthousand","a":true},{"y":5,"s":"Rounding","t":"Round decimals to 2dp","k":"round_decimal_numbers_2dp","a":true},{"y":5,"s":"Rounding","t":"Round decimals to 3dp","k":"round_decimal_numbers_3dp","a":true},{"y":5,"s":"Written methods","t":"Add ThHTU and HTU","k":"addition_ThHTO_HTO","a":true},{"y":5,"s":"Written methods","t":"Add ThHTU and ThHTU","k":"addition_ThHTO_ThHTO","a":true},{"y":5,"s":"Written methods","t":"Add and subtract numbers with more than 4-digits","k":"rand_adding_beyond_four_digits","a":true},{"y":5,"s":"Written methods","t":"Subtract HTU and ThHTU","k":"subtraction_ThHTO_HTO","a":true},{"y":5,"s":"Written methods","t":"Subtract ThHTU from ThHTU","k":"subtraction_ThHTO_ThHTO","a":true},{"y":5,"s":"Written methods","t":"U. t h th + U. t h th","k":"addition_othth_othth","a":true},{"y":5,"s":"Written methods","t":"U. t + U. t","k":"addition_ot_ot","a":true},{"y":5,"s":"Written methods","t":"U. t h th - U. t h th","k":"subtraction_othth_othth","a":true},{"y":5,"s":"Written methods","t":"U. t h + U. t h","k":"addition_oth_oth","a":true},{"y":5,"s":"Written methods","t":"U. t h - U. t h th","k":"subtraction_oth_othth","a":true},{"y":5,"s":"Written methods","t":"U. t - U. t","k":"subtraction_ot_ot","a":true},{"y":5,"s":"Written methods","t":"U. t h - U. t h","k":"subtraction_oth_oth","a":true},{"y":5,"s":"Written methods","t":"U. t - U. t h","k":"subtraction_ot_otth","a":true},{"y":5,"s":"Multiplication & division","t":"Identify multiples and factors.","k":"rand_factor_multiple","a":true},{"y":5,"s":"Multiplication & division","t":"Find all factor pairs of a number","k":"factor_pairs","a":true},{"y":5,"s":"Multiplication & division","t":"Recognise and use square numbers and cube numbers and the notations for these (2 and3).","k":"rand_cubed_squared","a":true},{"y":5,"s":"Multiplication & division","t":"Know and use the vocabulary of prime numbers, prime factors and composite (non- prime) numbers","k":"prime_numbers","a":true},{"y":5,"s":"Multiplication & division","t":"Identify common factors of two numbers.","k":"common_factors","a":true},{"y":5,"s":"Multiplication & division","t":"Multiply and divide whole numbers and decimals by 10, 100 and 1000.","k":"rand_multiply_divide_powers10","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate HTU x U","k":"multiply_HTO_O","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate ThHTU x U","k":"multiply_ThHTO_O","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate U.t x U","k":"decimalmultiplybyones","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate TU x TU","k":"x_multiply_TO_TO","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate ThHTU x TU","k":"multiply_ThHTO_TO","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate HTU ÷ U","k":"divide_HTO_O","a":true},{"y":5,"s":"Multiplication & division","t":"Calculate ThHTU ÷ U","k":"divide_ThHTO_O","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can compare and order fractions whose denominators are all multiples of the same number.","k":"orderFractionsSameDenoms","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can compare and order fractions whose denominators are all different.","k":"orderFractionsDifferentDenoms","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can read and write fractions as decimal numbers.","k":"fractionAsDecimalNumbers","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can write decimals as fractions over 100","k":"decimalAsFraction","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can read and write decimal numbers as equivalent fractions in their simplest form.","k":"decimalAsSimplestFraction","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can write decimals as percent.","k":"decimalAsPercent","a":true},{"y":5,"s":"Fractions, decimals & %","t":"I can write fractions as percent.*","k":"fractionAsPercent","a":true},{"y":5,"s":"Percentages","t":"Find 10% of a multiple of 10","k":"percentage10percent10multiple","a":true},{"y":5,"s":"Percentages","t":"Find 10% of any number","k":"percentage10percentNumber","a":true},{"y":5,"s":"Percentages","t":"Find 5% of a multiple of 10","k":"percent5Multiple10","a":true},{"y":5,"s":"Percentages","t":"Find 20% of a multiple of 10","k":"percentage20percent10multiple","a":true},{"y":5,"s":"Percentages","t":"Frind 20% of any number","k":"percentage20percentNumber","a":true},{"y":5,"s":"Percentages","t":"Find 1% of a multple of 10","k":"percentage1percent10multiple","a":true},{"y":5,"s":"Percentages","t":"Find 1% of any number","k":"percentage1percentNumber","a":true},{"y":5,"s":"Percentages","t":"Find any % of a number","k":"percentNumber","a":true},{"y":5,"s":"Mastery / missing number","t":"Identify the missing numbers in an HTO+HTO addition calculation.","k":"missing_HTO_HTO_addition","a":true},{"y":5,"s":"Mastery / missing number","t":"Identify the missing numbers in an ThHTO+ThHTO addition calculation.","k":"missing_ThHTO_ThHTO_addition","a":true},{"y":5,"s":"Mastery / missing number","t":"Identify the missing numbers in an TthThHTO+ThThHTO subtraction calculation.","k":"missing_TThThHTO_TthThHTO_addition","a":true},{"y":5,"s":"Mastery / missing number","t":"Identify the missing numbers in an ThHTO-ThHTO subtraction calculation.","k":"missing_ThHTO_ThHTO_subtraction","a":true},{"y":5,"s":"Mastery / missing number","t":"Identify the missing numbers in an TthThHTO-ThThHTO subtraction calculation.","k":"missing_TThThHTO_TthThHTO_subtraction","a":true},{"y":5,"s":"Mastery / missing number","t":"Identify the missing numbers in an HTO x TO multiplication calculation.","k":"missing_HTO_TO_multiplication","a":true},{"y":6,"s":"Counting","t":"I can use negative numbers in context, and calculate intervals across zero","k":"count_through_zero","a":true},{"y":6,"s":"Comparing & ordering","t":"I can read, write, count, order and compare numbers to 10,000,000","k":"rand_read_write_order_compare_y6","a":true},{"y":6,"s":"Comparing & ordering","t":"I can determine the value of each digit to 10,000,000","k":"place_value","a":true},{"y":6,"s":"Comparing & ordering","t":"I can determine the value of each digit 100,000,000","k":"place_value_exceeding","a":true},{"y":6,"s":"Representing & estimating","t":"I can identify the value of each digit in numbers given to 3dp","k":"place_value_decimal","a":true},{"y":6,"s":"Representing & estimating","t":"I can confidently identify the value of each digit in numbers given to 3dp","k":"place_value_decimal_confidently","a":true},{"y":6,"s":"Reading & writing","t":"I can read, write, count and order numbers to 10,000,000","k":null,"a":false},{"y":6,"s":"Reading & writing","t":"I can confidently to read, write, count and order numbers to 10,000,000 and determine the value of each digit","k":null,"a":false},{"y":6,"s":"Reading & writing","t":"I can confidently to read, write, count and order numbers to 10,000,000 and beyond;and determine the value of each digit","k":null,"a":false},{"y":6,"s":"Place value","t":"I can confidently read, write, count, order and compare numbers to 10,000,000","k":null,"a":false},{"y":6,"s":"Place value","t":"I can understand place value for decimals, measures and integers of any size","k":null,"a":false},{"y":6,"s":"Place value","t":"I can order positive and negative integers, decimals and fractions; use the number line as a model for ordering real numbers; use the symbols = < > ≤ ≥ ≠","k":null,"a":false},{"y":6,"s":"Rounding","t":"I can round any whole number to a required degree of accuracy","k":null,"a":false},{"y":6,"s":"Problem solving","t":"Solve number and practical problems using the above objectives","k":null,"a":false},{"y":6,"s":"Addition & subtraction","t":"I can perform mental calculations, including with mixed operations and large numbers","k":"mental_mixed","a":true},{"y":6,"s":"Addition & subtraction","t":"I can solve problems involving addition and subtraction","k":null,"a":false},{"y":6,"s":"Addition & subtraction","t":"I can solve addition and subtraction multi-step problems in contexts, deciding which operations and methods to use and why","k":null,"a":false},{"y":6,"s":"Addition & subtraction","t":"I can use the four operations, including formal written methods, applied to integers, decimals, proper and improper fractions, and mixed numbers, all both positive and negative","k":null,"a":false},{"y":6,"s":"Addition & subtraction","t":"I can recognise and use relationships between operations including inverse","k":null,"a":false},{"y":6,"s":"Multiplication & division","t":"I can identify the value of each digit in numbers given to 3dp and multiply and divide numbers by 10, 100 and 1000 (giving answers up to 3dp)","k":null,"a":false},{"y":6,"s":"Multiplication & division","t":"I can identify common factors, common multiples and prime numbers","k":"commonFactorsMultiples","a":true},{"y":6,"s":"Estimation","t":"I can use estimation to check answers to calculations and determine, in the context of a problem, and appropriate degree of accuracy","k":null,"a":false},{"y":6,"s":"Order of operations","t":"I can use my knowledge of the order of operations to carry out calculations involving 4 operations, including BODMAS","k":"bodmas","a":true},{"y":6,"s":"Mastery / missing number","t":"Identify the missing numbers in an HTO+HTO addition calculation.","k":"missing_HTO_HTO_addition","a":true},{"y":6,"s":"Mastery / missing number","t":"Identify the missing numbers in an ThHTO+ThHTO addition calculation.","k":"missing_ThHTO_ThHTO_addition","a":true},{"y":6,"s":"Mastery / missing number","t":"Identify the missing numbers in an TthThHTO+ThThHTO subtraction calculation.","k":"missing_TThThHTO_TthThHTO_addition","a":true},{"y":6,"s":"Mastery / missing number","t":"Identify the missing numbers in an ThHTO-ThHTO subtraction calculation.","k":"missing_ThHTO_ThHTO_subtraction","a":true},{"y":6,"s":"Mastery / missing number","t":"Identify the missing numbers in an TthThHTO-ThThHTO subtraction calculation.","k":"missing_TThThHTO_TthThHTO_subtraction","a":true},{"y":6,"s":"Mastery / missing number","t":"Identify the missing numbers in an HTO x TO multiplication calculation.","k":"missing_HTO_TO_multiplication","a":true}];


/* =============================================================================
   UNIFORM ADAPTER
   -----------------------------------------------------------------------------
   window.TP_generate(key, difficulty) -> { question, answer } | null
   Looks up the generator by key, runs it with the requested difficulty (1..5),
   and normalises the source { qtn, ans } shape into { question, answer }.
   Returns null if the key is not an auto-generating objective.
   ========================================================================== */
window.TP_generate = function (key, difficulty) {
  const g = window.TP_GEN[key];
  if (!g) return null;
  const r = g(difficulty);
  return { question: r.qtn ?? r.question, answer: r.ans ?? r.answer };
};
