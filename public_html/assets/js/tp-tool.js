/* =============================================================================
 * tp-tool.js — shared helpers for every resource tool.
 * -----------------------------------------------------------------------------
 * Loaded BEFORE each tool's own script (build.js, code-breaker.js,
 * maths-maze.js, beat-the-clock.js, …) so the common primitives live in ONE
 * place.
 * ========================================================================== */

/* ---- Difficulty meter -------------------------------------------------------
 * Difficulty is shown as a filled/empty circle meter rather than the attainment
 * band names, so the level isn't spelled out for pupils/parents. Teachers read
 * it against the 1-5 slider: ●●●○○ = level 3 of 5.
 * ------------------------------------------------------------------------- */
window.TP_diffDots = function (d) {
  d = Math.max(0, Math.min(5, d | 0));
  return '●●●●●'.slice(0, d) + '○○○○○'.slice(0, 5 - d);
};

/* ---- Year-aware difficulty --------------------------------------------------
 * The school YEAR sets the difficulty band; the 1-5 circle meter fine-tunes
 * within it. Returns an effective 1-5 difficulty to pass to TP_generate.
 *   year 1-2 -> band 1, 3 -> 2, 4 -> 3, 5 -> 4, 6 -> 5 ; meter nudges by (meter-3).
 * ------------------------------------------------------------------------- */
window.TP_effDifficulty = function (year, meter) {
  var band = [1, 1, 1, 2, 3, 4, 5][Math.max(1, Math.min(6, year | 0))]; // index by year
  var nudge = (Math.max(1, Math.min(5, meter | 0)) - 3);
  return Math.max(1, Math.min(5, band + nudge));
};

/* ---- Curriculum: multiplication tables introduced by year -------------------
 * Per the National Curriculum / White Rose RtP mapping:
 *   Y1-2: ×2, ×5, ×10   |   Y3: + ×3, ×4, ×8   |   Y4+: all tables to 12×12.
 * Self-generating tools (Code Breaker, Maths Maze) use this so they never put an
 * off-curriculum fact (e.g. 4×4 below Year 4) on a sheet.
 * ------------------------------------------------------------------------- */
window.TP_yearTables = function (year) {
  year = year | 0;
  if (year <= 2) { return [2, 5, 10]; }
  if (year === 3) { return [2, 3, 4, 5, 8, 10]; }
  return [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]; // Year 4 and above
};

/* ---- Achievable target values for a set of operations -----------------------
 * Returns the set of answer-values (within [lo,hi]) that can be represented by
 * at least one of the SELECTED ops at the given year, so a tool can assign
 * cipher/answer values the chosen ops can actually make — e.g. with only '×'
 * selected, every value is a real table product, so no question ever falls back
 * to another operation.
 *   +  : any value (>= 2)            -  : any value (>= 1)
 *   ×  : products f×m, f a year table, multiplier m = 1..12
 *   ÷  : table quotients 1..12 (shown as (q×b)÷b)
 * ------------------------------------------------------------------------- */
window.TP_achievableValues = function (ops, year, lo, hi) {
  var tables = window.TP_yearTables(year);
  var set = {};
  var v, m, i;
  for (var oi = 0; oi < ops.length; oi++) {
    var op = ops[oi];
    if (op === '+') { for (v = Math.max(2, lo); v <= hi; v++) { set[v] = 1; } }
    else if (op === '-') { for (v = Math.max(1, lo); v <= hi; v++) { set[v] = 1; } }
    else if (op === '×') {
      for (i = 0; i < tables.length; i++) {
        for (m = 1; m <= 12; m++) { v = tables[i] * m; if (v >= lo && v <= hi) { set[v] = 1; } }
      }
    } else if (op === '÷') {
      for (v = 1; v <= 12; v++) { if (v >= lo && v <= hi) { set[v] = 1; } }
    }
  }
  return Object.keys(set).map(Number);
};

/* ---- Year selector wiring ---------------------------------------------------
 * Wires the shared #<prefix>-years chip row (rendered by partials/tool_toolbar).
 * Manages the .chip-on state and calls onChange(year) when an enabled chip is
 * clicked. Returns the initially-selected year (the server-rendered .chip-on),
 * or null if there's no year row.
 * ------------------------------------------------------------------------- */
window.TP_wireYears = function (prefix, onChange) {
  var wrap = document.getElementById(prefix + '-years');
  if (!wrap) { return null; }
  var btns = wrap.querySelectorAll('[data-yr]');
  var selected = null;
  Array.prototype.forEach.call(btns, function (b) {
    if (b.classList.contains('chip-on')) { selected = Number(b.getAttribute('data-yr')); }
    b.addEventListener('click', function () {
      if (b.disabled) { return; }
      Array.prototype.forEach.call(btns, function (x) { x.classList.remove('chip-on'); });
      b.classList.add('chip-on');
      selected = Number(b.getAttribute('data-yr'));
      if (onChange) { onChange(selected); }
    });
  });
  return selected;
};

/* ---- Question batching ------------------------------------------------------
 * TP_batch(keys, difficulty, n, opts) -> array of up to n de-duplicated
 * { question, answer, key } pairs drawn at random from the given generator
 * keys. Dedupes on the ANSWER by default (so e.g. Bingo / Treasure-Hunt cards
 * can't collide); pass {dedupeOn:'question'} to dedupe on the question instead.
 *
 * Returns FEWER than n if the selected keys can't produce that many unique
 * pairs at this difficulty — callers should check `result.length` and widen the
 * selection / difficulty or warn the teacher.
 * ------------------------------------------------------------------------- */
window.TP_batch = function (keys, difficulty, n, opts) {
  opts = opts || {};
  var dedupeOn = opts.dedupeOn === 'question' ? 'question' : 'answer';
  var out = [];
  var seen = {};
  if (!keys || !keys.length || typeof window.TP_generate !== 'function') { return out; }
  var guard = 0;
  var maxGuard = n * 80 + 300; // bounded so a thin answer-space can't hang us
  while (out.length < n && guard < maxGuard) {
    guard++;
    var key = keys[Math.floor(Math.random() * keys.length)];
    var r = window.TP_generate(key, difficulty);
    if (!r || r.question == null || r.answer == null) { continue; }
    var sig = String(r[dedupeOn]);
    if (seen[sig]) { continue; }
    seen[sig] = true;
    out.push({ question: r.question, answer: r.answer, key: key });
  }
  return out;
};

/* ---- Closed loop of cards ---------------------------------------------------
 * Shared by the Treasure Hunt (wall cards) and Loop Cards / Dominoes (tabletop).
 * Given a batch of {question, answer} pairs with UNIQUE answers, returns cards
 * that form a single closed loop visiting every card exactly once:
 *
 *   card i = { index, header: answer[i-1], question: question[i], answer: answer[i] }
 *
 * The teacher's solution order is simply 0 → 1 → 2 → … → 0 (use the cards'
 * `index`); the renderer is expected to SHUFFLE the physical print order while
 * keeping this logical loop intact.
 * ------------------------------------------------------------------------- */
window.TP_loopCards = function (batch) {
  var n = batch.length;
  var cards = [];
  for (var i = 0; i < n; i++) {
    cards.push({
      index: i,
      header: batch[(i - 1 + n) % n].answer, // the answer that leads HERE
      question: batch[i].question,           // solve this to reach the next card
      answer: batch[i].answer
    });
  }
  return cards;
};

/* ---- Fisher-Yates shuffle (returns a new array) -------------------------- */
window.TP_shuffle = function (arr) {
  var a = arr.slice();
  for (var i = a.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var t = a[i]; a[i] = a[j]; a[j] = t;
  }
  return a;
};
