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
