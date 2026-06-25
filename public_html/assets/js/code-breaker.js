/* =============================================================================
 * code-breaker.js — KS2 Numeracy "Crack the Secret Code" cipher puzzle.
 * -----------------------------------------------------------------------------
 * Self-contained port of the design's DCLogic (Code Breaker.dc.html) to vanilla
 * JS. Does NOT depend on TP_GEN / curriculum objectives.
 *
 * Flow: pick a WORD -> assign each distinct letter a unique, difficulty-scaled
 * value -> generate one calculation per letter that equals that value -> render
 * the code key, the questions (one per letter position) and the secret-message
 * boxes. Activity tab shows blanks; Answer key tab fills answers + reveals the
 * message. Regenerate builds a fresh puzzle. Save POSTs to /account/save.
 * ========================================================================== */
(function () {
  'use strict';

  var WORDS = [
    'MATHS', 'NUMBER', 'GENIUS', 'CLEVER', 'SUPERB', 'BRAINY', 'WIZARD',
    'EXPERT', 'ROCKET', 'PUZZLE', 'WELL DONE', 'TOP MARKS', 'BRILLIANT',
    'SUPERSTAR', 'CHAMPION'
  ];

  var DIFF_LABELS = ['Foundation', 'Emerging', 'Expected', 'Greater depth', 'Challenge'];
  // Difficulty-scaled value ranges for the letter cipher (index = difficulty-1).
  var RANGES = [[2, 20], [5, 40], [10, 99], [20, 300], [50, 900]];

  // ---- State (mirrors the design component's state) -------------------------
  var state = {
    difficulty: 2,
    ops: ['+', '-', '×'],   // +  -  ×   (default selection)
    tab: 'active',
    word: '',               // teacher's custom message ('' = pick a random word)
    puzzle: null
  };

  // ---- Small helpers --------------------------------------------------------
  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function pick(a) { return a[Math.floor(Math.random() * a.length)]; }
  function fmt(n) { return n.toLocaleString('en-GB'); }

  // Keep only A–Z and spaces, upper-case, collapse runs of spaces, and trim to
  // a sane length so a custom message always fits the sheet.
  function cleanWord(s) {
    return String(s || '')
      .toUpperCase()
      .replace(/[^A-Z ]+/g, '')
      .replace(/ {2,}/g, ' ')
      .replace(/^ +/, '')
      .slice(0, 24);
  }

  // Assign each distinct letter a unique value. The difficulty range may hold
  // fewer integers than there are distinct letters (e.g. a long custom message
  // on difficulty 1), so widen the upper bound until there's comfortably room.
  function assignValues(letters, range) {
    var lo = range[0], hi = range[1];
    while (hi - lo + 1 < letters.length * 2) { hi += (hi - lo + 1); }
    var used = {}, map = {};
    letters.forEach(function (L) {
      var v, guard = 0;
      do { v = ri(lo, hi); guard++; } while (used[v] && guard < 200);
      used[v] = true;
      map[L] = v;
    });
    return map;
  }

  // Build a calculation string that evaluates to `target`, for op + difficulty.
  function calcFor(target, op, d) {
    if (op === '+') {
      var a = ri(Math.max(1, Math.floor(target * 0.2)), Math.max(1, target - 1));
      return fmt(a) + ' + ' + fmt(target - a) + ' =';
    }
    if (op === '-') {
      var extra = ri(target + 1, target + (d <= 2 ? 30 : d <= 4 ? 200 : 900));
      return fmt(extra) + ' − ' + fmt(extra - target) + ' =';
    }
    if (op === '×') { // ×
      var factors = [];
      for (var i = 2; i <= Math.min(12, target); i++) {
        if (target % i === 0) factors.push(i);
      }
      if (factors.length) {
        var f = pick(factors);
        return f + ' × ' + fmt(target / f) + ' =';
      }
      var ax = ri(1, target - 1);
      return fmt(ax) + ' + ' + fmt(target - ax) + ' =';
    }
    if (op === '÷') { // ÷
      var b = ri(2, 9);
      return fmt(target * b) + ' ÷ ' + b + ' =';
    }
    var af = ri(1, target - 1);
    return fmt(af) + ' + ' + fmt(target - af) + ' =';
  }

  // Build a complete puzzle from the current difficulty + operations.
  function build() {
    var d = state.difficulty;
    // Use the teacher's custom message when set, otherwise pick a random word.
    var word = state.word ? cleanWord(state.word) : pick(WORDS);
    if (!word.replace(/ /g, '')) { word = pick(WORDS); } // guard all-spaces input

    // distinct letters (ignore spaces)
    var seen = {};
    var letters = [];
    word.replace(/ /g, '').split('').forEach(function (ch) {
      if (!seen[ch]) { seen[ch] = true; letters.push(ch); }
    });

    // assign each distinct letter a unique difficulty-scaled value
    var range = RANGES[d - 1];
    var map = assignValues(letters, range);

    // code key, sorted by value ascending
    var cipher = letters.map(function (L) {
      return { letter: L, value: map[L] };
    }).sort(function (a, b) { return a.value - b.value; });

    // one question per letter position in the word (spaces split the message)
    var ops = state.ops.length ? state.ops : ['+'];
    var questions = [];
    var wordsOut = [];
    var curWord = { letters: [] };
    var qn = 0;

    word.split('').forEach(function (ch) {
      if (ch === ' ') {
        wordsOut.push(curWord);
        curWord = { letters: [] };
        return;
      }
      qn++;
      var op = pick(ops);
      questions.push({
        num: qn,
        qtn: calcFor(map[ch], op, d),
        value: map[ch],
        letter: ch
      });
      curWord.letters.push({ letter: ch, qNum: 'Q' + qn });
    });
    wordsOut.push(curWord);

    return { word: word, cipher: cipher, questions: questions, words: wordsOut };
  }

  function ensure() {
    if (!state.puzzle) state.puzzle = build();
    return state.puzzle;
  }

  // ---- DOM references -------------------------------------------------------
  var els = {};
  function $(id) { return document.getElementById(id); }

  // ---- Rendering ------------------------------------------------------------
  function renderCipher(p) {
    els.cipher.innerHTML = '';
    p.cipher.forEach(function (c) {
      var box = document.createElement('div');
      box.style.cssText = 'display:flex; align-items:center; gap:6px; background:#fff; border:1px solid rgba(28,36,32,.12); border-radius:8px; padding:5px 9px;';
      var L = document.createElement('span');
      L.style.cssText = "font-family:var(--font-head); font-weight:800; font-size:15px; color:var(--accent);";
      L.textContent = c.letter;
      var eq = document.createElement('span');
      eq.style.cssText = 'color:#c8ccc2; font-size:12px;';
      eq.textContent = '=';
      var v = document.createElement('span');
      v.style.cssText = 'font-weight:700; font-size:14px; font-variant-numeric:tabular-nums;';
      v.textContent = fmt(c.value);
      box.appendChild(L); box.appendChild(eq); box.appendChild(v);
      els.cipher.appendChild(box);
    });
  }

  function renderQuestions(p, answersOn) {
    els.questions.innerHTML = '';
    p.questions.forEach(function (q) {
      var li = document.createElement('li');

      var num = document.createElement('span');
      num.className = 'sheet-qnum';
      num.textContent = 'Q' + q.num;

      var txt = document.createElement('span');
      txt.className = 'sheet-qtext';
      txt.textContent = q.qtn;

      var box = document.createElement('span');
      box.style.cssText = 'display:inline-flex; align-items:center; gap:4px;';

      if (answersOn) {
        var val = document.createElement('span');
        val.style.cssText = 'font-weight:700; color:#6c716a; font-variant-numeric:tabular-nums;';
        val.textContent = fmt(q.value);
        var letter = document.createElement('span');
        letter.style.cssText = 'width:26px; height:26px; border:1.5px solid var(--accent); border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-family:var(--font-head); font-weight:800; font-size:15px; color:var(--accent);';
        letter.textContent = q.letter;
        box.appendChild(val); box.appendChild(letter);
      } else {
        var blank = document.createElement('span');
        blank.style.cssText = 'display:inline-block; width:26px; border-bottom:1.5px solid #c2c6bd;';
        blank.innerHTML = '&nbsp;';
        var empty = document.createElement('span');
        empty.style.cssText = 'width:26px; height:26px; border:1.5px solid rgba(28,36,32,.22); border-radius:6px; display:inline-block;';
        box.appendChild(blank); box.appendChild(empty);
      }

      li.appendChild(num); li.appendChild(txt); li.appendChild(box);
      els.questions.appendChild(li);
    });
  }

  function renderMessage(p, answersOn) {
    els.message.innerHTML = '';
    p.words.forEach(function (w) {
      var group = document.createElement('div');
      group.style.cssText = 'display:flex; gap:6px;';
      w.letters.forEach(function (ltr) {
        var col = document.createElement('div');
        col.style.cssText = 'display:flex; flex-direction:column; align-items:center; gap:4px;';
        var cell = document.createElement('div');
        cell.style.cssText = 'width:34px; height:40px; border:1.5px solid rgba(28,36,32,.2); border-radius:7px; background:#fff; display:flex; align-items:center; justify-content:center; font-family:var(--font-head); font-weight:800; font-size:21px; color:var(--accent);';
        cell.textContent = answersOn ? ltr.letter : '';
        var lab = document.createElement('div');
        lab.style.cssText = 'font-size:9.5px; font-weight:700; color:#a8a294; font-variant-numeric:tabular-nums;';
        lab.textContent = ltr.qNum;
        col.appendChild(cell); col.appendChild(lab);
        group.appendChild(col);
      });
      els.message.appendChild(group);
    });
  }

  function renderControls() {
    // operation chips
    var chips = els.ops.querySelectorAll('[data-op]');
    Array.prototype.forEach.call(chips, function (c) {
      var on = state.ops.indexOf(c.getAttribute('data-op')) !== -1;
      c.classList.toggle('chip-on', on);
    });

    // difficulty thumb + labels
    var diffLeft = [3, 37, 71, 105, 139][state.difficulty - 1];
    els.diffThumb.style.left = diffLeft + 'px';
    var label = DIFF_LABELS[state.difficulty - 1];
    els.diffLabel.textContent = label;
    els.eyebrowDiff.textContent = label;

    // tab thumb
    var answersOn = state.tab === 'answers';
    els.tabThumb.style.left = answersOn ? 'calc(50% + 1.5px)' : '3px';
    els.tabThumb.style.width = 'calc(50% - 4.5px)';
  }

  function render() {
    var p = ensure();
    var answersOn = state.tab === 'answers';
    renderControls();
    renderCipher(p);
    renderQuestions(p, answersOn);
    renderMessage(p, answersOn);
  }

  // ---- Actions --------------------------------------------------------------
  function rebuild() { state.puzzle = build(); render(); }

  function setDiff(d) { state.difficulty = d; rebuild(); }

  function toggleOp(op) {
    var i = state.ops.indexOf(op);
    if (i === -1) {
      state.ops.push(op);
    } else {
      state.ops.splice(i, 1);
    }
    if (!state.ops.length) state.ops = [op]; // at least one op always on
    rebuild();
  }

  function setTab(tab) { state.tab = tab; render(); }

  // Teacher typed their own message: re-derive the puzzle from it live.
  function setWord(raw) {
    state.word = cleanWord(raw);
    rebuild();
  }

  // Clear the custom message and roll a fresh random word.
  function randomWord() {
    state.word = '';
    if (els.word) { els.word.value = ''; }
    rebuild();
  }

  var spinT;
  function regen() {
    els.spin.style.transform = 'rotate(360deg)';
    clearTimeout(spinT);
    spinT = setTimeout(function () { els.spin.style.transform = 'none'; }, 500);
    rebuild();
  }

  var toastT;
  function showToast(msg) {
    els.toast.textContent = msg;
    els.toast.classList.remove('hide');
    clearTimeout(toastT);
    toastT = setTimeout(function () { els.toast.classList.add('hide'); }, 1900);
  }

  function onSave() {
    var p = ensure();
    var config = {
      difficulty: state.difficulty,
      operations: state.ops.slice(),
      word: p.word
    };
    var form = new FormData();
    form.append('title', 'Code Breaker');
    form.append('activity', 'code-breaker');
    form.append('config', JSON.stringify(config));

    fetch('/account/save', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form,
      credentials: 'same-origin',
      redirect: 'follow'
    }).then(function (res) {
      if (res.status === 401 || res.status === 403) {
        window.location.href = '/login';
        return;
      }
      // A redirect to the login page (e.g. via the auth filter) ends up here.
      if (res.redirected && /\/login/.test(res.url)) {
        window.location.href = '/login';
        return;
      }
      if (res.ok) {
        showToast('✓ Saved');
      } else {
        showToast('Could not save');
      }
    }).catch(function () {
      showToast('Could not save');
    });
  }

  // ---- Wire up --------------------------------------------------------------
  function init() {
    els.ops = $('cb-ops');
    els.diffThumb = $('cb-diff-thumb');
    els.diffLabel = $('cb-diff-label');
    els.eyebrowDiff = $('cb-eyebrow-diff');
    els.tabThumb = $('cb-tab-thumb');
    els.cipher = $('cb-cipher');
    els.questions = $('cb-questions');
    els.message = $('cb-message');
    els.spin = $('cb-spin');
    els.toast = $('cb-toast');
    els.word = $('cb-word');
    els.random = $('cb-random');

    var yearEl = $('cb-year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    // operation chips
    Array.prototype.forEach.call(els.ops.querySelectorAll('[data-op]'), function (c) {
      c.addEventListener('click', function () { toggleOp(c.getAttribute('data-op')); });
    });

    // difficulty buttons
    Array.prototype.forEach.call($('cb-difficulty').querySelectorAll('[data-d]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-d'), 10)); });
    });

    // tabs
    Array.prototype.forEach.call($('cb-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    // custom secret message: rebuild live as the teacher types; tidy on blur
    if (els.word) {
      els.word.addEventListener('input', function () { setWord(els.word.value); });
      els.word.addEventListener('blur', function () { els.word.value = state.word; });
    }
    if (els.random) { els.random.addEventListener('click', randomWord); }

    $('cb-save').addEventListener('click', onSave);
    $('cb-print').addEventListener('click', function () { window.print(); });
    $('cb-regen').addEventListener('click', regen);

    render();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
