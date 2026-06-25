/* =============================================================================
 * treasure-hunt.js — KS2 Numeracy "Maths Trail" room treasure hunt.
 * -----------------------------------------------------------------------------
 * A set of clue cards placed around the room. Each card shows an ANSWER at the
 * top and a QUESTION at the bottom. Children solve the question, hunt for the
 * card whose answer matches, forming a single closed loop that visits every
 * card exactly once. A wrong answer breaks the trail — so it self-marks.
 *
 * Questions are drawn from the curriculum objective generators (TP_GEN) for the
 * strands the teacher selects. TP_batch gives a de-duped (by answer) batch,
 * TP_loopCards arranges them into the closed loop, and TP_shuffle scatters the
 * PHYSICAL print order while the logical loop stays keyed on card.index.
 *
 * Cards tab    — the printable trail cards (2 per row).
 * Answer order — the teacher's solution: the visiting order + a station table.
 * Prints as a clean set of cards. Save POSTs to /account/save.
 * ========================================================================== */
(function () {
  'use strict';

  // A small spread of theme icons for visual variety on the cards.
  var ICONS = ['🗺️', '🧭', '🔑', '⭐', '🏆', '💎', '🎯', '🔍'];

  var state = {
    difficulty: 3,
    year: 4,
    count: 16,
    tab: 'cards',          // 'cards' | 'answers'
    strands: {},           // strand name -> true when selected
    cards: null,           // logical loop (by card.index)
    order: null,           // physical (shuffled) display order
    shortfall: 0           // how many fewer cards than requested (0 = none)
  };

  // ---- helpers --------------------------------------------------------------
  function $(id) { return document.getElementById(id); }

  // Station letter for a logical index: 0->A, 1->B, … 25->Z, 26->AA, …
  function stationLetter(i) {
    var s = '';
    i = i + 1;
    while (i > 0) {
      var rem = (i - 1) % 26;
      s = String.fromCharCode(65 + rem) + s;
      i = Math.floor((i - 1) / 26);
    }
    return s;
  }

  // ---- objective / strand wiring -------------------------------------------
  // Only objectives that have a working generator are usable here.
  function generatableObjectives() {
    var objs = window.TP_OBJECTIVES || [];
    return objs.filter(function (o) {
      return o && o.key && window.TP_GEN && window.TP_GEN[o.key];
    });
  }

  // Distinct strands (in first-seen order) that have at least one generator.
  function availableStrands() {
    var seen = {}, out = [];
    generatableObjectives().forEach(function (o) {
      if (!seen[o.strand]) { seen[o.strand] = true; out.push(o.strand); }
    });
    return out;
  }

  // The pool of generator keys across the currently-selected strands.
  function activeKeyPool() {
    var keys = [], seen = {};
    generatableObjectives().forEach(function (o) {
      if (state.strands[o.strand] && o.year === state.year && !seen[o.key]) { seen[o.key] = true; keys.push(o.key); }
    });
    return keys;
  }

  // ---- build ----------------------------------------------------------------
  function build() {
    var pool = activeKeyPool();
    var n = state.count;

    state.shortfall = 0;
    if (!pool.length) {
      state.cards = [];
      state.order = [];
      state.shortfall = n;
      return;
    }

    // De-dupe on the ANSWER so two cards can never share a header/answer (that
    // would make the loop ambiguous).
    var batch = window.TP_batch(pool, (window.TP_effDifficulty ? window.TP_effDifficulty(state.year, state.difficulty) : state.difficulty), n, { dedupeOn: 'answer' });

    if (batch.length < n) {
      state.shortfall = n - batch.length;
      // Use what we have, but a loop needs at least a few cards to make sense.
      if (batch.length < 4) {
        state.cards = [];
        state.order = [];
        return;
      }
    }

    var cards = window.TP_loopCards(batch);   // logical closed loop, by .index
    state.cards = cards;
    state.order = window.TP_shuffle(cards);   // scrambled physical print order
  }

  // ---- DOM references -------------------------------------------------------
  var els = {};

  // ---- rendering: settings/controls ----------------------------------------
  function renderStrandChips() {
    if (!els.strands) { return; }
    els.strands.innerHTML = '';
    availableStrands().forEach(function (s) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'chip' + (state.strands[s] ? ' chip-on' : '');
      b.setAttribute('data-strand', s);
      b.textContent = s;
      b.addEventListener('click', function () { toggleStrand(s); });
      els.strands.appendChild(b);
    });
  }

  function renderControls() {
    // strand chips reflect selection
    if (els.strands) {
      Array.prototype.forEach.call(els.strands.querySelectorAll('[data-strand]'), function (c) {
        c.classList.toggle('chip-on', !!state.strands[c.getAttribute('data-strand')]);
      });
    }
    // count chips
    Array.prototype.forEach.call(els.count.querySelectorAll('[data-count]'), function (c) {
      c.classList.toggle('chip-on', Number(c.getAttribute('data-count')) === state.count);
    });
    // difficulty thumb + labels (measure the active button)
    var diffWrap = $('th-difficulty');
    var active = diffWrap ? diffWrap.querySelectorAll('[data-diff]')[state.difficulty - 1] : null;
    if (els.diffThumb && active) {
      els.diffThumb.style.left = active.offsetLeft + 'px';
      els.diffThumb.style.width = active.offsetWidth + 'px';
    }
    if (els.diffLabel) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    if (els.eyebrowDiff) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    // tab thumb (measure the active segment)
    var tabsWrap = $('th-tabs');
    var activeTab = tabsWrap ? tabsWrap.querySelectorAll('[data-tab]')[state.tab === 'answers' ? 1 : 0] : null;
    if (els.tabThumb && activeTab) {
      els.tabThumb.style.left = activeTab.offsetLeft + 'px';
      els.tabThumb.style.width = activeTab.offsetWidth + 'px';
    }
  }

  // ---- rendering: warning ---------------------------------------------------
  function warningEl() {
    var div = document.createElement('div');
    div.className = 'th-warn';
    div.textContent = 'Only ' + (state.cards ? state.cards.length : 0) +
      ' unique answers available — pick more objectives or a higher level.';
    return div;
  }

  function emptyMessage() {
    var div = document.createElement('div');
    div.className = 'th-warn';
    if (!activeKeyPool().length) {
      div.textContent = 'Pick at least one set of objectives to practise to build a trail.';
    } else {
      div.textContent = 'Not enough unique answers for a trail — pick more objectives or a higher level.';
    }
    return div;
  }

  // ---- rendering: cards tab -------------------------------------------------
  function renderCards() {
    var grid = els.grid;
    grid.innerHTML = '';

    if (!state.order || state.order.length < 4) {
      grid.appendChild(emptyMessage());
      return;
    }
    if (state.shortfall > 0) { grid.appendChild(warningEl()); }

    state.order.forEach(function (card) {
      var wrap = document.createElement('div');
      wrap.className = 'th-card';

      var top = document.createElement('div');
      top.className = 'th-card-top';
      var st = document.createElement('span');
      st.className = 'th-station';
      st.textContent = stationLetter(card.index);
      var ic = document.createElement('span');
      ic.className = 'th-icon';
      ic.textContent = ICONS[card.index % ICONS.length];
      top.appendChild(st); top.appendChild(ic);

      var aLbl = document.createElement('div');
      aLbl.className = 'th-answer-lbl';
      aLbl.textContent = 'Answer';
      var ans = document.createElement('div');
      ans.className = 'th-answer';
      ans.textContent = card.header;   // the answer that LEADS to this card

      var rule = document.createElement('div');
      rule.className = 'th-card-rule';

      var qLbl = document.createElement('div');
      qLbl.className = 'th-question-lbl';
      qLbl.textContent = 'Next question';
      var q = document.createElement('div');
      q.className = 'th-question';
      q.textContent = card.question;   // solve to reach the next card

      wrap.appendChild(top);
      wrap.appendChild(aLbl);
      wrap.appendChild(ans);
      wrap.appendChild(rule);
      wrap.appendChild(qLbl);
      wrap.appendChild(q);
      grid.appendChild(wrap);
    });
  }

  // ---- rendering: answer-order tab -----------------------------------------
  function renderAnswers() {
    var box = els.answer;
    box.innerHTML = '';

    if (!state.cards || state.cards.length < 4) {
      box.appendChild(emptyMessage());
      return;
    }
    if (state.shortfall > 0) { box.appendChild(warningEl()); }

    // Logical loop order: start at index 0 then 1, 2, … back to 0.
    var ordered = state.cards.slice().sort(function (a, b) { return a.index - b.index; });

    var head = document.createElement('div');
    head.style.cssText = 'font-size:10.5px; font-weight:800; letter-spacing:.07em; text-transform:uppercase; color:#9a9f95; margin-bottom:10px;';
    head.textContent = 'Trail solution — the order to visit the stations';
    box.appendChild(head);

    // "Start at A → D → F → …" (then loops back to the start).
    var order = document.createElement('div');
    order.className = 'th-order';
    order.appendChild(document.createTextNode('Start at '));
    ordered.forEach(function (card, i) {
      if (i > 0) { order.appendChild(document.createTextNode(' → ')); }
      var L = document.createElement('span');
      L.className = 'th-letter';
      L.textContent = stationLetter(card.index);
      order.appendChild(L);
    });
    order.appendChild(document.createTextNode(' → back to start'));
    box.appendChild(order);

    // Station table (Station, Question, Answer).
    var table = document.createElement('table');
    table.className = 'th-table';
    var thead = document.createElement('thead');
    thead.innerHTML = '<tr><th>Station</th><th>Question</th><th>Answer</th></tr>';
    table.appendChild(thead);
    var tbody = document.createElement('tbody');
    ordered.forEach(function (card) {
      var tr = document.createElement('tr');
      var td1 = document.createElement('td');
      td1.className = 'th-t-station';
      td1.textContent = stationLetter(card.index);
      var td2 = document.createElement('td');
      td2.textContent = card.question;
      var td3 = document.createElement('td');
      td3.className = 'th-t-answer';
      td3.textContent = card.answer;
      tr.appendChild(td1); tr.appendChild(td2); tr.appendChild(td3);
      tbody.appendChild(tr);
    });
    table.appendChild(tbody);
    box.appendChild(table);
  }

  function render() {
    renderControls();
    var answersOn = state.tab === 'answers';
    els.grid.style.display = answersOn ? 'none' : '';
    els.answer.style.display = answersOn ? '' : 'none';
    if (answersOn) { renderAnswers(); } else { renderCards(); }
  }

  // ---- actions --------------------------------------------------------------
  function rebuild() { build(); render(); }

  function setDiff(d) { state.difficulty = d; rebuild(); }
  function setCount(n) { state.count = n; rebuild(); }
  function setTab(tab) { state.tab = tab; render(); }

  function toggleStrand(s) {
    if (state.strands[s]) { delete state.strands[s]; } else { state.strands[s] = true; }
    // Never leave the trail with nothing to draw from: keep at least one strand.
    if (!Object.keys(state.strands).length) { state.strands[s] = true; }
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
      strands: Object.keys(state.strands),
      count: state.count
    };
    var form = new FormData();
    form.append('title', 'Treasure Hunt');
    form.append('activity', 'treasure-hunt');
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
    els.strands = $('th-strands');
    els.count = $('th-count');
    els.grid = $('th-grid');
    els.answer = $('th-answer');
    els.diffThumb = $('th-difficulty') ? $('th-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('th-diff-label');
    els.eyebrowDiff = $('th-eyebrow-diff');
    els.tabThumb = $('th-tabs') ? $('th-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('th-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('th-toast');

    var yearEl = $('th-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    // Default-select 'Multiplication & division' if present, else the first
    // strand that has a generator.
    var strands = availableStrands();
    if (strands.indexOf('Multiplication & division') !== -1) {
      state.strands['Multiplication & division'] = true;
    } else if (strands.length) {
      state.strands[strands[0]] = true;
    }

    renderStrandChips();

    var y0 = window.TP_wireYears ? window.TP_wireYears('th', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    Array.prototype.forEach.call(els.count.querySelectorAll('[data-count]'), function (c) {
      c.addEventListener('click', function () { setCount(Number(c.getAttribute('data-count'))); });
    });
    Array.prototype.forEach.call($('th-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('th-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('th-save').addEventListener('click', onSave);
    $('th-print').addEventListener('click', function () { window.print(); });
    $('th-regen').addEventListener('click', regen);

    rebuild();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
