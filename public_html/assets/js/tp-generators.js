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

  // ---------- Year 1 & Year 2 (KS1) ----------
  // Grounded in National Curriculum / White Rose 'Ready to progress' KS1
  // expectations. YEAR drives the level here; difficulty arg is ignored.

  // --- Year 1 ---
  const ks1seq = (step, max) => () => { const start = pick([0, step]); const last = start + step * 3; const next = last + step; return { qtn: `Continue: ${start}, ${start + step}, ${start + step * 2}, ${last}, ___`, ans: fmt(next) }; };
  G.y1_count_2s = ks1seq(2, 20);
  G.y1_count_5s = ks1seq(5, 50);
  G.y1_count_10s = ks1seq(10, 100);
  G.y1_one_more_less = () => { const n = ri(1, 99); const more = Math.random() < .5; return { qtn: `1 ${more ? 'more' : 'less'} than ${fmt(n)} =`, ans: fmt(more ? n + 1 : n - 1) }; };
  G.y1_compare_20 = () => { const a = ri(0, 20), b = ri(0, 20); return { qtn: `Insert < > or = :  ${a} ___ ${b}`, ans: a < b ? '<' : a > b ? '>' : '=' }; };
  G.y1_add_10 = () => { const a = ri(1, 9), b = ri(1, 10 - a); return { qtn: `${a} + ${b} =`, ans: fmt(a + b) }; };
  G.y1_sub_10 = () => { const a = ri(2, 10), b = ri(1, a); return { qtn: `${a} − ${b} =`, ans: fmt(a - b) }; };
  G.y1_add_20 = () => { const a = ri(1, 19), b = ri(1, 20 - a); return { qtn: `${a} + ${b} =`, ans: fmt(a + b) }; };
  G.y1_sub_20 = () => { const a = ri(2, 20), b = ri(1, a); return { qtn: `${a} − ${b} =`, ans: fmt(a - b) }; };
  G.y1_bonds_10 = () => { const n = ri(0, 10); return { qtn: `${n} + ___ = 10`, ans: fmt(10 - n) }; };
  G.y1_bonds_20 = () => { const n = ri(0, 20); return { qtn: `${n} + ___ = 20`, ans: fmt(20 - n) }; };
  G.y1_double_10 = () => { const n = ri(1, 10); return { qtn: `Double ${n} =`, ans: fmt(n * 2) }; };
  G.y1_halve_20 = () => { const n = 2 * ri(1, 10); return { qtn: `Half of ${n} =`, ans: fmt(n / 2) }; };
  G.y1_half_amount = () => { const n = 2 * ri(1, 10); return { qtn: `1/2 of ${n} =`, ans: fmt(n / 2) }; };
  G.y1_quarter_amount = () => { const n = 4 * ri(1, 6); return { qtn: `1/4 of ${n} =`, ans: fmt(n / 4) }; };

  // --- Year 2 ---
  G.y2_tens_ones = () => { const n = ri(11, 99); const t = Math.floor(n / 10) * 10, o = n % 10; return { qtn: `Partition ${n} into tens and ones`, ans: `${t} + ${o}` }; };
  G.y2_ten_more_less = () => { const n = ri(10, 89); const more = Math.random() < .5; return { qtn: `10 ${more ? 'more' : 'less'} than ${fmt(n)} =`, ans: fmt(more ? n + 10 : n - 10) }; };
  G.y2_compare_100 = () => { const a = ri(0, 100), b = ri(0, 100); return { qtn: `Insert < > or = :  ${a} ___ ${b}`, ans: a < b ? '<' : a > b ? '>' : '=' }; };
  G.y2_add_ones = () => { let t = ri(11, 89), o = ri(1, 9); while (t + o > 99) { t = ri(11, 89); o = ri(1, 9); } return { qtn: `${t} + ${o} =`, ans: fmt(t + o) }; };
  G.y2_add_tens = () => { const t = ri(11, 79); const maxMult = Math.max(1, Math.floor((90 - t) / 10)); const tens = 10 * ri(1, maxMult); return { qtn: `${t} + ${tens} =`, ans: fmt(t + tens) }; };
  G.y2_add_2digit = () => { const a = ri(11, 80); const b = ri(11, 99 - a); return { qtn: `${a} + ${b} =`, ans: fmt(a + b) }; };
  G.y2_sub_100 = () => { const a = ri(20, 99), b = ri(1, a); return { qtn: `${a} − ${b} =`, ans: fmt(a - b) }; };
  G.y2_bonds_20 = () => { const n = ri(0, 20); return { qtn: `${n} + ___ = 20`, ans: fmt(20 - n) }; };
  G.y2_bonds_100_tens = () => { const n = 10 * ri(0, 10); return { qtn: `${fmt(n)} + ___ = 100`, ans: fmt(100 - n) }; };
  G.y2_mult_2 = () => { const n = ri(1, 12); return { qtn: `2 × ${n} =`, ans: fmt(2 * n) }; };
  G.y2_mult_5 = () => { const n = ri(1, 12); return { qtn: `5 × ${n} =`, ans: fmt(5 * n) }; };
  G.y2_mult_10 = () => { const n = ri(1, 12); return { qtn: `10 × ${n} =`, ans: fmt(10 * n) }; };
  G.y2_div_2 = () => { const n = ri(1, 12); return { qtn: `${2 * n} ÷ 2 =`, ans: fmt(n) }; };
  G.y2_div_5 = () => { const n = ri(1, 12); return { qtn: `${5 * n} ÷ 5 =`, ans: fmt(n) }; };
  G.y2_div_10 = () => { const n = ri(1, 12); return { qtn: `${10 * n} ÷ 10 =`, ans: fmt(n) }; };
  G.y2_half_amount = () => { const n = 2 * ri(2, 20); return { qtn: `1/2 of ${n} =`, ans: fmt(n / 2) }; };
  G.y2_third_amount = () => { const n = 3 * ri(2, 12); return { qtn: `1/3 of ${n} =`, ans: fmt(n / 3) }; };
  G.y2_quarter_amount = () => { const n = 4 * ri(2, 9); return { qtn: `1/4 of ${n} =`, ans: fmt(n / 4) }; };
  G.y2_threequarter_amount = () => { const n = 4 * ri(2, 9); return { qtn: `3/4 of ${n} =`, ans: fmt(3 * (n / 4)) }; };

  // ===== Year 6 written/number methods =====
  G.y6_digit_value_3dp = () => {
    const n = ri(1, 9) + ri(1, 999) / 1000;
    const s = n.toFixed(3);
    const places = [{ name: 'tenths', idx: 2, val: 0.1 }, { name: 'hundredths', idx: 3, val: 0.01 }, { name: 'thousandths', idx: 4, val: 0.001 }];
    const p = pick(places);
    const digit = Number(s[p.idx]);
    return { qtn: `What is the value of the ${digit} in the ${p.name} place of ${s}?`, ans: fmt(digit * p.val) };
  };
  G.y6_hcf = () => { const a = ri(8, 40), b = ri(8, 40); return { qtn: `HCF of ${a} and ${b} =`, ans: fmt(gcd(a, b)) }; };
  G.y6_lcm = () => { const a = ri(2, 12), b = ri(2, 12); return { qtn: `LCM of ${a} and ${b} =`, ans: fmt(a * b / gcd(a, b)) }; };
  G.y6_prime_factorisation = () => {
    let n = ri(12, 60);
    const isP = (x) => { if (x < 2) return false; for (let i = 2; i * i <= x; i++) if (x % i === 0) return false; return true; };
    while (isP(n)) n = ri(12, 60);
    const factors = []; let m = n;
    for (let d = 2; d <= m; d++) { while (m % d === 0) { factors.push(d); m /= d; } }
    return { qtn: `Write ${n} as a product of prime factors`, ans: factors.join(' × ') };
  };
  G.y6_product_of_primes = () => {
    const primes = [2, 3, 5, 7];
    const count = ri(2, 3);
    const chosen = []; for (let i = 0; i < count; i++) chosen.push(pick(primes));
    chosen.sort((a, b) => a - b);
    const product = chosen.reduce((a, b) => a * b, 1);
    return { qtn: `${chosen.join(' × ')} =`, ans: fmt(product) };
  };
  G.y6_digit_value_10m = () => {
    const n = ri(1000000, 9999999);
    const s = String(n);
    const idx = ri(0, s.length - 1);
    const digit = Number(s[idx]);
    const placeVal = digit * Math.pow(10, s.length - 1 - idx);
    return { qtn: `In ${fmt(n)}, what is the value of the digit ${digit}?`, ans: fmt(placeVal) };
  };
  G.y6_compare_symbols = () => {
    const mk = () => pick([() => ri(-50, 50), () => ri(-1000, 1000), () => ri(1, 999) / 10])();
    let a = mk(), b = pick([true, false]) ? a : mk();
    const sym = a < b ? '<' : a > b ? '>' : '=';
    return { qtn: `Insert <, > or = :  ${fmt(a)} ___ ${fmt(b)}`, ans: sym };
  };
  G.y6_word_problem = () => {
    const items = ri(3, 12), price = ri(2, 20), paid = items * price + ri(5, 40);
    const change = paid - items * price;
    return { qtn: `A shop sells pens for £${price} each. Jo buys ${items} pens and pays with £${paid}. How much change (in £) does Jo get?`, ans: fmt(change) };
  };
  G.y6_mental_mixed = () => {
    const a = ri(1000, 9000), b = ri(100, 900), c = ri(100, 900);
    return { qtn: `${fmt(a)} + ${fmt(b)} − ${fmt(c)} =`, ans: fmt(a + b - c) };
  };
  G.y6_decimal_addsub = () => {
    let x = ri(100, 9999) / 100, y = ri(100, 9999) / 100;
    if (pick([true, false])) {
      return { qtn: `${fmt(x)} + ${fmt(y)} =`, ans: fmt(Number((x + y).toFixed(2))) };
    }
    if (y > x) { const t = x; x = y; y = t; }
    return { qtn: `${fmt(x)} − ${fmt(y)} =`, ans: fmt(Number((x - y).toFixed(2))) };
  };
  G.y6_inverse = () => {
    const a = ri(20, 90), b = ri(20, 90), c = a + b;
    return { qtn: `If ${a} + ${b} = ${c}, what is ${c} − ${b}?`, ans: fmt(a) };
  };
  G.y6_long_mult_2x2 = () => { const a = ri(11, 99), b = ri(11, 99); return { qtn: `${a} × ${b} =`, ans: fmt(a * b) }; };
  G.y6_long_mult_3x2 = () => { const a = ri(100, 999), b = ri(11, 99); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.y6_long_mult_4x2 = () => { const a = ri(1000, 9999), b = ri(11, 99); return { qtn: `${fmt(a)} × ${b} =`, ans: fmt(a * b) }; };
  G.y6_div_2by2 = () => { const b = ri(11, 25), q = ri(2, 9); return { qtn: `${fmt(b * q)} ÷ ${b} =`, ans: fmt(q) }; };
  G.y6_div_3by2 = () => { const b = ri(11, 30), q = ri(10, 40); return { qtn: `${fmt(b * q)} ÷ ${b} =`, ans: fmt(q) }; };
  G.y6_div_4by2 = () => {
    const b = ri(12, 40); let q = ri(40, 250);
    if (b * q > 9999) q = Math.floor(9999 / b);
    return { qtn: `${fmt(b * q)} ÷ ${b} =`, ans: fmt(q) };
  };
  G.y6_estimate = () => {
    const a = ri(100, 9999), b = ri(100, 9999);
    const ra = Math.round(a / 100) * 100, rb = Math.round(b / 100) * 100;
    return { qtn: `Estimate by rounding to the nearest 100:  ${fmt(a)} + ${fmt(b)} ≈`, ans: fmt(ra + rb) };
  };

  /* =========================================================================
     VISUAL STRANDS — Time & Geometry (Shape)
     -------------------------------------------------------------------------
     These generators return a `qhtml` field (an inline SVG string) ALONGSIDE
     the usual { qtn, ans }. The worksheet renderer draws qhtml under the
     question caption; tools that only want text simply ignore it. Curriculum
     bands (Below / Meeting / Exceeding) reach these via the difficulty arg
     (2 = below, 3 = meeting, 4 = exceeding) so e.g. Y2 clocks read to the
     nearest 5 minutes at Meeting but stay on o'clock / half past at Below.
     ====================================================================== */

  // ---- Time helpers -------------------------------------------------------
  // h is 1..12, m is 0..59. Words follow UK primary phrasing.
  const timeWords = (h, m) => {
    const h12 = ((h + 11) % 12) + 1;          // keep 12 as 12
    const next = (h12 % 12) + 1;
    if (m === 0) return `${h12} o'clock`;
    if (m === 15) return `quarter past ${h12}`;
    if (m === 30) return `half past ${h12}`;
    if (m === 45) return `quarter to ${next}`;
    if (m < 30) return `${m} minute${m === 1 ? '' : 's'} past ${h12}`;
    return `${60 - m} minute${60 - m === 1 ? '' : 's'} to ${next}`;
  };
  const digital = (h, m) => `${h}:${String(m).padStart(2, '0')}`;
  const digital24 = (h24, m) => `${String(h24).padStart(2, '0')}:${String(m).padStart(2, '0')}`;

  // Analogue clock face as inline SVG. roman=true draws Roman numerals (Y3+).
  const clockSVG = (h, m, roman) => {
    const S = 92, c = S / 2, r = c - 3;
    const romans = ['XII', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI'];
    const pt = (ang, rad) => [c + rad * Math.cos((ang - 90) * Math.PI / 180), c + rad * Math.sin((ang - 90) * Math.PI / 180)];
    let s = `<circle cx="${c}" cy="${c}" r="${r}" fill="#fff" stroke="#26302a" stroke-width="2"/>`;
    for (let i = 0; i < 12; i++) {
      const [x1, y1] = pt(i * 30, r - 2), [x2, y2] = pt(i * 30, r - (i % 3 ? 4 : 7));
      s += `<line x1="${x1.toFixed(1)}" y1="${y1.toFixed(1)}" x2="${x2.toFixed(1)}" y2="${y2.toFixed(1)}" stroke="#9aa096" stroke-width="${i % 3 ? 1 : 2}"/>`;
    }
    for (let n = 1; n <= 12; n++) {
      const [x, y] = pt(n * 30, r - 13);
      s += `<text x="${x.toFixed(1)}" y="${(y + 3.3).toFixed(1)}" font-size="9.5" text-anchor="middle" fill="#26302a" font-family="Georgia,serif">${roman ? romans[n % 12] : n}</text>`;
    }
    const ha = ((h % 12) + m / 60) * 30, ma = m * 6;
    const [hx, hy] = pt(ha, r * 0.5), [mx, my] = pt(ma, r * 0.78);
    s += `<line x1="${c}" y1="${c}" x2="${hx.toFixed(1)}" y2="${hy.toFixed(1)}" stroke="#26302a" stroke-width="3.4" stroke-linecap="round"/>`;
    s += `<line x1="${c}" y1="${c}" x2="${mx.toFixed(1)}" y2="${my.toFixed(1)}" stroke="#1f8a4d" stroke-width="2.2" stroke-linecap="round"/>`;
    s += `<circle cx="${c}" cy="${c}" r="2.6" fill="#26302a"/>`;
    return `<svg class="tp-clock" width="${S}" height="${S}" viewBox="0 0 ${S} ${S}" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="clock face">${s}</svg>`;
  };

  // ---- Time generators ----------------------------------------------------
  // Y1: o'clock & half past (read the drawn clock).
  G.y1_time_oclock = (d) => {
    const h = ri(1, 12);
    const m = (d <= 2) ? 0 : pick([0, 30]);     // Below: o'clock only
    return { qtn: 'What time is shown on the clock?', qhtml: clockSVG(h, m, false), ans: timeWords(h, m) };
  };
  // Y2: to five minutes incl. quarter past / to.
  G.y2_time_5min = (d) => {
    const h = ri(1, 12);
    const choices = (d <= 2) ? [0, 15, 30, 45] : [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];
    const m = pick(choices);
    return { qtn: 'What time is shown on the clock?', qhtml: clockSVG(h, m, false), ans: timeWords(h, m) };
  };
  // Y3: read analogue to the minute (Roman numerals appear at Meeting+).
  G.y3_time_minute = (d) => {
    const h = ri(1, 12);
    const m = (d <= 2) ? pick([0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55]) : ri(1, 59);
    const roman = d >= 3 && pick([true, false]);
    return { qtn: 'Write the time shown in digital (hh:mm).', qhtml: clockSVG(h, m, roman), ans: digital(h, m) };
  };
  // Y4: convert analogue / 12-hour to 24-hour digital.
  G.y4_time_24hr = () => {
    const h = ri(1, 12), m = pick([0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55]);
    const pm = pick([true, false]);
    const h24 = pm ? (h === 12 ? 12 : h + 12) : (h === 12 ? 0 : h);
    const ampm = pm ? 'pm' : 'am';
    return { qtn: `Write ${digital(h, m)} ${ampm} as a 24-hour time.`, ans: digital24(h24, m) };
  };
  // Y5 / Y6: convert between units of time.
  G.y5_time_convert = () => {
    const kind = pick([
      () => { const h = ri(2, 6); return { qtn: `How many minutes are there in ${h} hours?`, ans: fmt(h * 60) }; },
      () => { const m = ri(2, 8) * 30; return { qtn: `How many hours and minutes is ${m} minutes? (write as h hr m min)`, ans: `${Math.floor(m / 60)} hr ${m % 60} min` }; },
      () => { const w = ri(2, 8); return { qtn: `How many days are there in ${w} weeks?`, ans: fmt(w * 7) }; },
      () => { const y = ri(2, 6); return { qtn: `How many months are there in ${y} years?`, ans: fmt(y * 12) }; },
      () => { const m = ri(2, 6); return { qtn: `How many seconds are there in ${m} minutes?`, ans: fmt(m * 60) }; },
    ]);
    return kind();
  };
  G.y6_time_convert = () => {
    const kind = pick([
      () => { const h = ri(2, 9), m = ri(1, 5) * 10; return { qtn: `Convert ${h} hours ${m} minutes into minutes.`, ans: fmt(h * 60 + m) }; },
      () => { const d = ri(2, 9); return { qtn: `How many hours are there in ${d} days?`, ans: fmt(d * 24) }; },
      () => { const wk = ri(2, 6); return { qtn: `How many hours are there in ${wk} day${wk === 1 ? '' : 's'}? Then in ${wk} weeks?`, ans: `${fmt(wk * 24)} hours; ${fmt(wk * 7 * 24)} hours` }; },
    ]);
    return kind();
  };

  // ---- Shape (2-D) helpers ------------------------------------------------
  const SHAPES2D = {
    circle: { sides: 0, vertices: 0 },
    triangle: { sides: 3, vertices: 3 },
    square: { sides: 4, vertices: 4 },
    rectangle: { sides: 4, vertices: 4 },
    pentagon: { sides: 5, vertices: 5 },
    hexagon: { sides: 6, vertices: 6 },
    heptagon: { sides: 7, vertices: 7 },
    octagon: { sides: 8, vertices: 8 },
  };
  const shape2dSVG = (name) => {
    const S = 84, c = S / 2, R = c - 9;
    const fill = '#eaf5ee', stroke = '#1f8a4d', sw = 2.2;
    const open = `<svg class="tp-shape" width="${S}" height="${S}" viewBox="0 0 ${S} ${S}" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="shape">`;
    if (name === 'circle') {
      return `${open}<circle cx="${c}" cy="${c}" r="${R}" fill="${fill}" stroke="${stroke}" stroke-width="${sw}"/></svg>`;
    }
    if (name === 'rectangle') {
      const w = R * 1.75, hh = R * 1.0;
      return `${open}<rect x="${(c - w / 2).toFixed(1)}" y="${(c - hh / 2).toFixed(1)}" width="${w.toFixed(1)}" height="${hh.toFixed(1)}" fill="${fill}" stroke="${stroke}" stroke-width="${sw}"/></svg>`;
    }
    const sides = SHAPES2D[name].sides;
    // Start angle chosen so each shape sits "the right way up".
    const start = name === 'square' ? 45 : name === 'octagon' ? -90 + 22.5 : -90;
    const pts = [];
    for (let i = 0; i < sides; i++) {
      const a = (start + i * 360 / sides) * Math.PI / 180;
      pts.push(`${(c + R * Math.cos(a)).toFixed(1)},${(c + R * Math.sin(a)).toFixed(1)}`);
    }
    return `${open}<polygon points="${pts.join(' ')}" fill="${fill}" stroke="${stroke}" stroke-width="${sw}" stroke-linejoin="round"/></svg>`;
  };

  // Y1: name a common 2-D shape.
  G.y1_name_2d = () => {
    const name = pick(['circle', 'triangle', 'square', 'rectangle']);
    return { qtn: 'What is the name of this 2-D shape?', qhtml: shape2dSVG(name), ans: name };
  };
  // Y2: count sides / vertices of a drawn 2-D shape.
  G.y2_2d_properties = (d) => {
    const name = pick(d <= 2 ? ['triangle', 'square', 'rectangle', 'pentagon'] : ['triangle', 'pentagon', 'hexagon', 'heptagon', 'octagon']);
    const ask = pick(['sides', 'vertices']);
    return { qtn: `How many ${ask} does this shape have?`, qhtml: shape2dSVG(name), ans: fmt(SHAPES2D[name][ask]) };
  };
  // Y3: name a polygon by its number of sides (drawn).
  G.y3_name_polygon = () => {
    const name = pick(['pentagon', 'hexagon', 'heptagon', 'octagon']);
    return { qtn: 'Name this polygon.', qhtml: shape2dSVG(name), ans: name };
  };

  // ---- Shape (3-D) --------------------------------------------------------
  const SHAPES3D = {
    cube: { faces: 6, edges: 12, vertices: 8, objects: ['a dice', 'a sugar cube', 'a Rubik’s cube'] },
    cuboid: { faces: 6, edges: 12, vertices: 8, objects: ['a cereal box', 'a brick', 'a matchbox'] },
    sphere: { faces: 1, edges: 0, vertices: 0, objects: ['a football', 'a marble', 'an orange'] },
    cylinder: { faces: 3, edges: 2, vertices: 0, objects: ['a tin of beans', 'a drinks can', 'a candle'] },
    cone: { faces: 2, edges: 1, vertices: 1, objects: ['an ice-cream cone', 'a party hat', 'a traffic cone'] },
    'square-based pyramid': { faces: 5, edges: 8, vertices: 5, objects: ['the Egyptian pyramids'] },
    'triangular prism': { faces: 5, edges: 9, vertices: 6, objects: ['a Toblerone box', 'a tent'] },
  };
  // Y1: name the 3-D shape of an everyday object.
  G.y1_name_3d = () => {
    const names = Object.keys(SHAPES3D);
    const name = pick(names);
    const obj = pick(SHAPES3D[name].objects);
    return { qtn: `What 3-D shape is ${obj}?`, ans: name };
  };
  // Y2/Y3: faces / edges / vertices of a named 3-D shape (flat-faced solids only).
  G.y2_3d_properties = () => {
    const name = pick(['cube', 'cuboid', 'square-based pyramid', 'triangular prism']);
    const ask = pick(['faces', 'edges', 'vertices']);
    return { qtn: `How many ${ask} does ${name === 'square-based pyramid' || name === 'triangular prism' ? 'a' : 'a'} ${name} have?`, ans: fmt(SHAPES3D[name][ask]) };
  };

  // ---- Angles & lines -----------------------------------------------------
  // An angle drawn from a vertex; learners classify it.
  const angleSVG = (deg) => {
    const S = 96, ox = 16, oy = 74, len = 64;
    const ray = (a) => [ox + len * Math.cos(a * Math.PI / 180), oy - len * Math.sin(a * Math.PI / 180)];
    const [x2, y2] = ray(deg);
    const [ax] = ray(0);
    let s = `<line x1="${ox}" y1="${oy}" x2="${(ox + len).toFixed(1)}" y2="${oy}" stroke="#26302a" stroke-width="2.4" stroke-linecap="round"/>`;
    s += `<line x1="${ox}" y1="${oy}" x2="${x2.toFixed(1)}" y2="${y2.toFixed(1)}" stroke="#26302a" stroke-width="2.4" stroke-linecap="round"/>`;
    // small arc to mark the angle
    const [arx, ary] = ray(deg / 2);
    s += `<path d="M ${(ox + 22)} ${oy} A 22 22 0 0 0 ${(ox + 22 * Math.cos(deg * Math.PI / 180)).toFixed(1)} ${(oy - 22 * Math.sin(deg * Math.PI / 180)).toFixed(1)}" fill="none" stroke="#1f8a4d" stroke-width="1.8"/>`;
    s += `<circle cx="${ox}" cy="${oy}" r="2.4" fill="#26302a"/>`;
    return `<svg class="tp-angle" width="${S}" height="${S}" viewBox="0 0 ${S} ${S}" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="angle">${s}</svg>`;
  };
  const classifyAngle = (deg) => deg < 90 ? 'acute' : deg === 90 ? 'right angle' : deg < 180 ? 'obtuse' : deg === 180 ? 'straight' : 'reflex';
  // Y3: right angle / less / greater (drawn).
  G.y3_angle_right = () => {
    const deg = pick([35, 50, 70, 90, 90, 110, 130, 150]);
    const ans = deg < 90 ? 'less than a right angle' : deg === 90 ? 'a right angle' : 'greater than a right angle';
    return { qtn: 'Is this angle less than, equal to, or greater than a right angle?', qhtml: angleSVG(deg), ans };
  };
  // Y4: classify acute / obtuse (drawn).
  G.y4_angle_type = () => {
    const deg = pick([25, 40, 55, 70, 100, 120, 135, 160]);
    return { qtn: 'Is this angle acute or obtuse?', qhtml: angleSVG(deg), ans: classifyAngle(deg) };
  };
  // Y5: estimate/identify; angles at a point / on a line.
  G.y5_angle_facts = () => {
    const kind = pick([
      () => { const a = ri(2, 17) * 10; return { qtn: `Angles on a straight line add up to 180°. One angle is ${a}°. Find the other.`, ans: `${180 - a}°` }; },
      () => { const a = ri(3, 32) * 10; return { qtn: `Angles at a point add up to 360°. One angle is ${a}°. Find the other.`, ans: `${360 - a}°` }; },
      () => { const deg = pick([30, 45, 60, 120, 135, 200, 250, 300]); return { qtn: 'Classify this angle (acute / right / obtuse / reflex).', qhtml: angleSVG(Math.min(deg, 179)), ans: classifyAngle(deg) }; },
    ]);
    return kind();
  };
  // Y6: missing angles in triangles / on lines / at a point.
  G.y6_angle_missing = () => {
    const kind = pick([
      () => { const a = ri(30, 80), b = ri(30, 80); return { qtn: `A triangle has angles ${a}° and ${b}°. Find the third angle.`, ans: `${180 - a - b}°` }; },
      () => { const a = ri(20, 160); return { qtn: `Two angles on a straight line: one is ${a}°. Find the other.`, ans: `${180 - a}°` }; },
      () => { const a = ri(40, 140), b = ri(40, 140); const sum = a + b; return { qtn: `Three angles meet at a point: ${a}°, ${b}° and one more. Find the missing angle.`, ans: `${360 - sum}°` }; },
    ]);
    return kind();
  };
  // Y6: parts of a circle (radius / diameter relationship).
  G.y6_circle_parts = () => {
    const kind = pick([
      () => { const r = ri(2, 25); return { qtn: `A circle has a radius of ${r} cm. What is its diameter?`, ans: `${fmt(r * 2)} cm` }; },
      () => { const dm = ri(2, 25) * 2; return { qtn: `A circle has a diameter of ${dm} cm. What is its radius?`, ans: `${fmt(dm / 2)} cm` }; },
      () => ({ qtn: 'What is the name of a straight line from the centre of a circle to its edge?', ans: 'radius' }),
    ]);
    return kind();
  };

  window.TP_GEN = G;
})();



/* ---------------------------------------------------------------------------
   A legacy objective manifest (window.TP_OBJECTIVES in an abbreviated
   {y,s,t,k,a} shape) was removed from here. It ran at load time and CLOBBERED
   the real, server-supplied window.TP_OBJECTIVES (shape
   {id,year,strand,text,key,auto}) that the Build view inlines before this
   script loads — leaving build.js with undefined years and an empty objective
   library on /build. The live app reads objectives from the server / DB; the
   canonical artefacts in this file are the TP_GEN generator map above and the
   TP_generate adapter below.
   --------------------------------------------------------------------------- */


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
  return { question: r.qtn ?? r.question, answer: r.ans ?? r.answer, qhtml: r.qhtml ?? null };
};
