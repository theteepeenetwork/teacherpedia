/* =============================================================================
 * loop-cards.js — KS2 Numeracy "Loop Cards / Dominoes" self-marking game.
 * -----------------------------------------------------------------------------
 * A deck of domino-style cards, each split into [ ANSWER | QUESTION ]. Laid end
 * to end, correct matching forms one continuous closed loop back to the start —
 * the same closed-chain data as a Treasure Hunt, rendered as tabletop dominoes.
 *
 * Builds the deck client-side from the server-supplied objective library
 * (window.TP_OBJECTIVES) using the shared TP_batch / TP_loopCards / TP_shuffle
 * helpers (tp-tool.js). The teacher's loop order is 0 → 1 → … → 0 by card index;
 * the printed card order is shuffled while the logical loop stays intact.
 *
 * Cards tab    — the domino cards (print 2-up, faint cut lines).
 * Answer key   — the loop order as a clean table.
 * Save POSTs to /account/save as activity 'loop-cards'.
 * ========================================================================== */
(function () {
  'use strict';

  // The strand most teachers reach for first; falls back to the first strand
  // with generatable objectives if it isn't present in the library.
  var DEFAULT_STRAND = 'Multiplication & division';
  var MIN_CARDS = 4; // a loop needs at least this many cards to be worthwhile

  var state = {
    difficulty: 3,
    year: 4,
    strands: [],           // selected strand names
    count: 12,             // requested deck size
    tab: 'cards',          // 'cards' | 'answers'
    cards: null,           // loop cards (logical order, by index)
    order: null,           // physical (shuffled) display order
    warning: ''            // in-sheet warning when too few unique answers
  };

  // strand -> [generator keys]  (only auto-generating objectives)
  var STRAND_KEYS = {};
  // generator key -> year
  var KEY_YEAR = {};

  // ---- helpers --------------------------------------------------------------
  function $(id) { return document.getElementById(id); }

  // Build the strand -> generatable keys map from the objective library.
  function indexObjectives() {
    var objs = Array.isArray(window.TP_OBJECTIVES) ? window.TP_OBJECTIVES : [];
    var gen = window.TP_GEN || {};
    objs.forEach(function (o) {
      var key = o && o.key;
      if (!key || !gen[key]) { return; } // skip non-auto-generating objectives
      var strand = o.strand || 'Other';
      if (!STRAND_KEYS[strand]) { STRAND_KEYS[strand] = []; }
      if (STRAND_KEYS[strand].indexOf(key) === -1) { STRAND_KEYS[strand].push(key); }
      KEY_YEAR[key] = o.year;
    });
  }

  function strandNames() { return Object.keys(STRAND_KEYS); }

  // Keys across all currently selected strands.
  function pool() {
    var keys = [];
    state.strands.forEach(function (s) {
      (STRAND_KEYS[s] || []).forEach(function (k) {
        if (KEY_YEAR[k] !== state.year) { return; }
        if (keys.indexOf(k) === -1) { keys.push(k); }
      });
    });
    return keys;
  }

  // ---- build ----------------------------------------------------------------
  function build() {
    state.warning = '';
    var n = state.count;
    var keys = pool();

    var batch = window.TP_batch(keys, (window.TP_effDifficulty ? window.TP_effDifficulty(state.year, state.difficulty) : state.difficulty), n, { dedupeOn: 'answer' });

    if (batch.length < n) {
      if (batch.length >= MIN_CARDS) {
        state.warning = 'Only ' + batch.length + ' cards with unique answers could be made from the chosen objectives at this difficulty — using those. Add more objectives or change the difficulty for a bigger deck.';
        batch = batch.slice(0, batch.length);
      } else {
        state.warning = 'Not enough unique answers to build a loop from the chosen objectives at this difficulty. Pick more objectives or a different difficulty.';
        batch = batch.slice(0, batch.length);
      }
    }

    if (batch.length < MIN_CARDS) {
      state.cards = null;
      state.order = null;
      return;
    }

    state.cards = window.TP_loopCards(batch);
    // Physical print order shuffled; logical loop stays by index.
    state.order = window.TP_shuffle(state.cards);
  }

  // ---- rendering ------------------------------------------------------------
  var els = {};

  function renderWarning() {
    if (!els.warn) { return; }
    if (state.warning) {
      els.warn.textContent = state.warning;
      els.warn.style.display = '';
    } else {
      els.warn.style.display = 'none';
    }
  }

  function renderCards() {
    var grid = els.grid;
    grid.innerHTML = '';
    if (!state.order) { return; }

    state.order.forEach(function (card, i) {
      var wrap = document.createElement('div');
      wrap.className = 'lc-card';

      var station = document.createElement('span');
      station.className = 'lc-station';
      station.textContent = 'Card ' + (i + 1);
      wrap.appendChild(station);

      // LEFT half — the ANSWER to someone else's question (big).
      var left = document.createElement('div');
      left.className = 'lc-half lc-answer-half';
      var lLbl = document.createElement('span');
      lLbl.className = 'lc-half-lbl'; lLbl.textContent = 'Answer';
      var lVal = document.createElement('span');
      lVal.className = 'lc-answer-val'; lVal.textContent = String(card.header);
      left.appendChild(lLbl); left.appendChild(lVal);

      var divider = document.createElement('div');
      divider.className = 'lc-divider';

      // RIGHT half — the QUESTION.
      var right = document.createElement('div');
      right.className = 'lc-half lc-q-half';
      var rLbl = document.createElement('span');
      rLbl.className = 'lc-half-lbl'; rLbl.textContent = 'Question';
      var rVal = document.createElement('span');
      rVal.className = 'lc-q-val'; rVal.textContent = String(card.question);
      right.appendChild(rLbl); right.appendChild(rVal);

      wrap.appendChild(left);
      wrap.appendChild(divider);
      wrap.appendChild(right);
      grid.appendChild(wrap);
    });
  }

  function renderAnswers() {
    var box = els.answer;
    box.innerHTML = '';
    if (!state.cards) { return; }

    var table = document.createElement('table');
    table.className = 'lc-loop-table';
    var thead = document.createElement('thead');
    thead.innerHTML = '<tr><th>Order</th><th>Left / Answer</th><th>Right / Question</th></tr>';
    table.appendChild(thead);

    var tbody = document.createElement('tbody');
    // Loop order 0 → 1 → … → 0 by card index.
    state.cards.forEach(function (card, i) {
      var tr = document.createElement('tr');
      var nextAns = state.cards[(i + 1) % state.cards.length].header;

      var ord = document.createElement('td');
      ord.className = 'lc-ord'; ord.textContent = (i + 1);
      var ans = document.createElement('td');
      ans.className = 'lc-ans'; ans.textContent = String(card.header);
      var qn = document.createElement('td');
      qn.className = 'lc-qn';
      qn.textContent = card.question + '  →  ' + String(nextAns);

      tr.appendChild(ord); tr.appendChild(ans); tr.appendChild(qn);
      tbody.appendChild(tr);
    });
    table.appendChild(tbody);
    box.appendChild(table);
  }

  function renderControls() {
    // strand chips
    if (els.strands) {
      Array.prototype.forEach.call(els.strands.querySelectorAll('[data-strand]'), function (c) {
        c.classList.toggle('chip-on', state.strands.indexOf(c.getAttribute('data-strand')) !== -1);
      });
    }
    // deck-size chips
    if (els.count) {
      Array.prototype.forEach.call(els.count.querySelectorAll('[data-count]'), function (c) {
        c.classList.toggle('chip-on', Number(c.getAttribute('data-count')) === state.count);
      });
    }
    // difficulty thumb + labels (measure the active button)
    var diffWrap = $('lc-difficulty');
    var active = diffWrap ? diffWrap.querySelectorAll('[data-diff]')[state.difficulty - 1] : null;
    if (els.diffThumb && active) {
      els.diffThumb.style.left = active.offsetLeft + 'px';
      els.diffThumb.style.width = active.offsetWidth + 'px';
    }
    if (els.diffLabel) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    if (els.eyebrowDiff) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    // tab thumb (measure the active segment)
    var tabsWrap = $('lc-tabs');
    var activeTab = tabsWrap ? tabsWrap.querySelectorAll('[data-tab]')[state.tab === 'answers' ? 1 : 0] : null;
    if (els.tabThumb && activeTab) {
      els.tabThumb.style.left = activeTab.offsetLeft + 'px';
      els.tabThumb.style.width = activeTab.offsetWidth + 'px';
    }
  }

  function render() {
    renderControls();
    renderWarning();
    renderCards();
    renderAnswers();
    var answersOn = state.tab === 'answers';
    els.grid.style.display = answersOn ? 'none' : '';
    els.answer.style.display = answersOn ? '' : 'none';
  }

  // ---- strand chips ---------------------------------------------------------
  function buildStrandChips() {
    if (!els.strands) { return; }
    els.strands.innerHTML = '';
    var names = strandNames();
    names.forEach(function (name) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'chip';
      btn.setAttribute('data-strand', name);
      btn.textContent = name;
      btn.addEventListener('click', function () { toggleStrand(name); });
      els.strands.appendChild(btn);
    });

    // Default selection: the favoured strand, or the first available.
    if (names.indexOf(DEFAULT_STRAND) !== -1) {
      state.strands = [DEFAULT_STRAND];
    } else if (names.length) {
      state.strands = [names[0]];
    } else {
      state.strands = [];
    }
  }

  // ---- actions --------------------------------------------------------------
  function rebuild() { build(); render(); }

  function setDiff(d) { state.difficulty = d; rebuild(); }
  function setCount(n) { state.count = n; rebuild(); }
  function setTab(tab) { state.tab = tab; render(); }

  function toggleStrand(name) {
    var i = state.strands.indexOf(name);
    if (i === -1) { state.strands.push(name); } else { state.strands.splice(i, 1); }
    if (!state.strands.length) { state.strands = [name]; } // at least one strand on
    rebuild();
  }

  var spinT;
  function regen() {
    if (els.spin) {
      els.spin.style.transform = 'rotate(360deg)';
      clearTimeout(spinT);
      spinT = setTimeout(function () { els.spin.style.transform = 'none'; }, 500);
    }
    rebuild();
  }

  var toastT;
  function showToast(msg) {
    if (!els.toast) { return; }
    els.toast.textContent = msg;
    els.toast.classList.remove('hide');
    clearTimeout(toastT);
    toastT = setTimeout(function () { els.toast.classList.add('hide'); }, 1900);
  }

  function onSave() {
    var config = {
      difficulty: state.difficulty,
      strands: state.strands.slice(),
      count: state.count
    };
    var form = new FormData();
    form.append('title', 'Loop Cards');
    form.append('activity', 'loop-cards');
    form.append('config', JSON.stringify(config));

    fetch(window.TP_SAVE_URL || '/account/save', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form,
      credentials: 'same-origin',
      redirect: 'follow'
    }).then(function (res) {
      if (res.status === 401 || res.status === 403 || (res.redirected && /\/login/.test(res.url))) {
        window.location.href = window.TP_LOGIN_URL || '/login';
        return;
      }
      showToast(res.ok ? '✓ Saved' : 'Could not save');
    }).catch(function () { showToast('Could not save'); });
  }

  // ---- wire up --------------------------------------------------------------
  function init() {
    els.strands = $('lc-strands');
    els.count = $('lc-count');
    els.grid = $('lc-grid');
    els.answer = $('lc-answer');
    els.warn = $('lc-warn');
    els.diffThumb = $('lc-difficulty') ? $('lc-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('lc-diff-label');
    els.eyebrowDiff = $('lc-eyebrow-diff');
    els.tabThumb = $('lc-tabs') ? $('lc-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('lc-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('lc-toast');

    var yearEl = $('lc-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    indexObjectives();
    buildStrandChips();

    var y0 = window.TP_wireYears ? window.TP_wireYears('lc', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    Array.prototype.forEach.call(els.count.querySelectorAll('[data-count]'), function (c) {
      c.addEventListener('click', function () { setCount(Number(c.getAttribute('data-count'))); });
    });
    Array.prototype.forEach.call($('lc-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('lc-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('lc-save').addEventListener('click', onSave);
    $('lc-print').addEventListener('click', function () { window.print(); });
    $('lc-regen').addEventListener('click', regen);

    rebuild();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
