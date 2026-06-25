/* =============================================================================
 * beat-the-clock.js — KS2 Numeracy timed fluency challenge.
 * -----------------------------------------------------------------------------
 * A screen-only, self-marking sprint. The teacher picks one or more strands to
 * practise, a difficulty (1-5) and a length (1/2/3 min); on Start, the pupil is
 * shown one auto-generated question at a time and types the answer. Pressing
 * Enter marks it instantly (tick / cross flash) and moves straight on. When the
 * clock hits zero, a results screen shows attempted / correct / accuracy and a
 * short list of the questions answered wrong.
 *
 * Questions come from the shared curriculum generators (window.TP_GEN via
 * window.TP_generate). Only objectives with a usable generator are offered.
 * Screen-only: no print, no answer key, no save.
 * ========================================================================== */
(function () {
  'use strict';

  var DEFAULT_STRAND = 'Multiplication & division';

  var state = {
    difficulty: 3,
    secs: 120,            // chosen length (default 2 min)
    strands: [],          // selected strand names
    pool: [],             // active generator keys (selected strands)
    running: false,
    timeLeft: 0,
    timerId: null,
    attempted: 0,
    correct: 0,
    wrong: [],            // {question, given, answer}
    cur: null,            // current {question, answer}
    lastQ: null           // last question text (avoid immediate repeats)
  };

  // Generatable objectives only: must have a key with a matching generator.
  var GENERATABLE = [];

  function $(id) { return document.getElementById(id); }

  var els = {};

  // ---- selection / pool -----------------------------------------------------
  function distinctStrands() {
    var seen = {}, out = [];
    GENERATABLE.forEach(function (o) {
      if (!seen[o.strand]) { seen[o.strand] = true; out.push(o.strand); }
    });
    return out;
  }

  function rebuildPool() {
    var sel = {};
    state.strands.forEach(function (s) { sel[s] = true; });
    var keys = [], seen = {};
    GENERATABLE.forEach(function (o) {
      if (sel[o.strand] && !seen[o.key]) { seen[o.key] = true; keys.push(o.key); }
    });
    state.pool = keys;
  }

  // ---- controls rendering ---------------------------------------------------
  function renderStrands() {
    var strands = distinctStrands();
    els.strands.innerHTML = '';
    strands.forEach(function (s) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'chip';
      b.setAttribute('data-strand', s);
      b.textContent = s;
      b.addEventListener('click', function () { toggleStrand(s); });
      els.strands.appendChild(b);
    });
    syncStrandChips();
  }

  function syncStrandChips() {
    var sel = {};
    state.strands.forEach(function (s) { sel[s] = true; });
    Array.prototype.forEach.call(els.strands.querySelectorAll('[data-strand]'), function (c) {
      c.classList.toggle('chip-on', !!sel[c.getAttribute('data-strand')]);
    });
  }

  function syncLengthChips() {
    Array.prototype.forEach.call(els.length.querySelectorAll('[data-secs]'), function (c) {
      c.classList.toggle('chip-on', Number(c.getAttribute('data-secs')) === state.secs);
    });
  }

  function renderDifficulty() {
    // Position the thumb by measuring the active button (copied from maths-maze).
    var diffWrap = $('btc-difficulty');
    var active = diffWrap ? diffWrap.querySelectorAll('[data-diff]')[state.difficulty - 1] : null;
    if (els.diffThumb && active) {
      els.diffThumb.style.left = active.offsetLeft + 'px';
      els.diffThumb.style.width = active.offsetWidth + 'px';
    }
    if (els.diffLabel) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
  }

  // ---- setting handlers -----------------------------------------------------
  function toggleStrand(s) {
    var i = state.strands.indexOf(s);
    if (i === -1) { state.strands.push(s); } else { state.strands.splice(i, 1); }
    if (!state.strands.length) { state.strands = [s]; } // keep at least one
    rebuildPool();
    syncStrandChips();
  }

  function setDiff(d) { state.difficulty = d; renderDifficulty(); }
  function setSecs(n) { state.secs = n; syncLengthChips(); }

  // ---- engine ---------------------------------------------------------------
  function fmtTime(secs) {
    var m = Math.floor(secs / 60), s = secs % 60;
    return m + ':' + (s < 10 ? '0' + s : s);
  }

  function nextQuestion() {
    if (!state.pool.length) { return; }
    var r, guard = 0;
    do {
      var key = state.pool[Math.floor(Math.random() * state.pool.length)];
      r = window.TP_generate(key, state.difficulty);
      guard++;
    } while ((!r || r.question == null || r.answer == null || (state.lastQ !== null && r.question === state.lastQ)) && guard < 60);

    if (!r || r.question == null) { return; }
    state.cur = { question: r.question, answer: r.answer };
    state.lastQ = r.question;
    els.question.textContent = r.question;
    els.input.value = '';
    els.input.className = 'btc-input';
    els.input.disabled = false;
    els.input.focus();
  }

  function submit() {
    if (!state.running || !state.cur) { return; }
    var given = els.input.value;
    if (given.trim() === '') { return; } // ignore empty submits

    var expected = String(state.cur.answer).trim().toLowerCase();
    var got = given.trim().toLowerCase();
    var ok = got === expected;

    state.attempted++;
    if (ok) {
      state.correct++;
      flash(true);
    } else {
      state.wrong.push({ question: state.cur.question, given: given.trim(), answer: String(state.cur.answer) });
      flash(false);
    }
    els.score.textContent = 'Score ' + state.correct;
    nextQuestion();
  }

  function flash(ok) {
    els.feedback.textContent = ok ? '✓' : '✗';
    els.feedback.className = 'btc-feedback ' + (ok ? 'btc-right' : 'btc-wrong');
    clearTimeout(flash._t);
    flash._t = setTimeout(function () {
      els.feedback.textContent = '';
      els.feedback.className = 'btc-feedback';
    }, 450);
  }

  function tick() {
    state.timeLeft--;
    els.timer.textContent = fmtTime(Math.max(0, state.timeLeft));
    els.timer.classList.toggle('btc-low', state.timeLeft <= 10);
    if (state.timeLeft <= 0) { finish(); }
  }

  function start() {
    if (!state.pool.length) { return; }
    state.running = true;
    state.attempted = 0;
    state.correct = 0;
    state.wrong = [];
    state.cur = null;
    state.lastQ = null;
    state.timeLeft = state.secs;

    els.ready.style.display = 'none';
    els.results.style.display = 'none';
    els.running.style.display = '';
    els.feedback.textContent = '';
    els.feedback.className = 'btc-feedback';
    els.score.textContent = 'Score 0';
    els.timer.textContent = fmtTime(state.timeLeft);
    els.timer.classList.remove('btc-low');

    nextQuestion();
    clearInterval(state.timerId);
    state.timerId = setInterval(tick, 1000);
  }

  function finish() {
    state.running = false;
    clearInterval(state.timerId);
    state.timerId = null;
    els.input.disabled = true;

    var acc = state.attempted ? Math.round((state.correct / state.attempted) * 100) : 0;
    els.rAttempted.textContent = state.attempted;
    els.rCorrect.textContent = state.correct;
    els.rAccuracy.textContent = acc + '%';

    els.wrongList.innerHTML = '';
    if (state.wrong.length) {
      state.wrong.slice(0, 10).forEach(function (w) {
        var li = document.createElement('li');
        var q = document.createElement('span'); q.className = 'q'; q.textContent = w.question;
        var ans = document.createElement('span');
        var given = document.createElement('span'); given.className = 'given'; given.textContent = w.given || '—';
        var right = document.createElement('span'); right.className = 'right'; right.textContent = w.answer;
        ans.appendChild(given);
        ans.appendChild(document.createTextNode('  '));
        ans.appendChild(right);
        li.appendChild(q); li.appendChild(ans);
        els.wrongList.appendChild(li);
      });
      els.wrong.style.display = '';
    } else {
      els.wrong.style.display = 'none';
    }

    els.running.style.display = 'none';
    els.results.style.display = '';
  }

  function playAgain() {
    els.results.style.display = 'none';
    els.running.style.display = 'none';
    els.ready.style.display = '';
  }

  // ---- wire up --------------------------------------------------------------
  function init() {
    els.strands = $('btc-strands');
    els.length = $('btc-length');
    els.diffThumb = $('btc-difficulty') ? $('btc-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('btc-diff-label');
    els.ready = $('btc-ready');
    els.running = $('btc-running');
    els.results = $('btc-results');
    els.start = $('btc-start');
    els.again = $('btc-again');
    els.timer = $('btc-timer');
    els.question = $('btc-question');
    els.input = $('btc-input');
    els.feedback = $('btc-feedback');
    els.score = $('btc-score');
    els.rAttempted = $('btc-r-attempted');
    els.rCorrect = $('btc-r-correct');
    els.rAccuracy = $('btc-r-accuracy');
    els.wrong = $('btc-wrong');
    els.wrongList = $('btc-wrong-list');

    // Build the generatable-objective list from the server-shipped objectives.
    var objs = Array.isArray(window.TP_OBJECTIVES) ? window.TP_OBJECTIVES : [];
    GENERATABLE = objs.filter(function (o) {
      return o && o.key && window.TP_GEN && window.TP_GEN[o.key];
    });

    // Default-select the multiplication strand if present, else the first.
    var strands = distinctStrands();
    if (strands.indexOf(DEFAULT_STRAND) !== -1) {
      state.strands = [DEFAULT_STRAND];
    } else if (strands.length) {
      state.strands = [strands[0]];
    }
    rebuildPool();

    renderStrands();
    syncLengthChips();
    renderDifficulty();

    Array.prototype.forEach.call($('btc-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call(els.length.querySelectorAll('[data-secs]'), function (b) {
      b.addEventListener('click', function () { setSecs(Number(b.getAttribute('data-secs'))); });
    });

    els.start.addEventListener('click', start);
    els.again.addEventListener('click', playAgain);
    els.input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); submit(); }
    });

    // Re-measure the difficulty thumb once layout settles (fonts/flex widths).
    window.addEventListener('resize', renderDifficulty);
    setTimeout(renderDifficulty, 60);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
