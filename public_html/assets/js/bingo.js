/* =============================================================================
 * bingo.js — KS2 Numeracy whole-class Bingo generator.
 * -----------------------------------------------------------------------------
 * Auto-filled bingo cards (3×3, 4×4 or 5×5 with a FREE centre). The teacher
 * reads / projects the questions from the Caller sheet; children dab the
 * matching ANSWER on their card.
 *
 * Cards tab    — N unique printed bingo cards, each a random sample of answers
 *                from a shared master batch (neighbours differ).
 * Caller tab   — the master question→answer pairs in a shuffled call order, as a
 *                numbered table for the teacher.
 *
 * Questions come from the shared generator engine via window.TP_batch /
 * window.TP_generate. An objective is generatable iff its key is present in
 * window.TP_GEN. Prints as clean cards. Save POSTs to /account/save.
 * ========================================================================== */
(function () {
  'use strict';

  var OBJ = (window.TP_OBJECTIVES || []).slice();

  // ----- state ---------------------------------------------------------------
  var state = {
    difficulty: 3,
    year: 4,
    size: 4,              // 3 | 4 | 5  (5 has a FREE centre)
    cardsN: 4,            // how many unique printed cards
    strands: {},          // strand name -> true (selected)
    tab: 'cards',         // 'cards' | 'caller'
    game: null            // { cards: [...], caller: [...], warn: '' }
  };

  // ----- helpers -------------------------------------------------------------
  function $(id) { return document.getElementById(id); }
  function canGenerate(o) { return !!(o && o.key && window.TP_GEN && window.TP_GEN[o.key]); }

  // Objectives we can actually generate questions for.
  function generatable() { return OBJ.filter(canGenerate); }

  // The distinct strands that contain at least one generatable objective.
  function strandList() {
    var seen = {};
    var out = [];
    generatable().forEach(function (o) {
      if (!seen[o.strand]) { seen[o.strand] = true; out.push(o.strand); }
    });
    return out;
  }

  // All generator keys for the currently selected strands.
  function selectedPool() {
    var keys = [];
    generatable().forEach(function (o) {
      if (state.strands[o.strand] && o.year === state.year) { keys.push(o.key); }
    });
    return keys;
  }

  // ----- build ---------------------------------------------------------------
  function build() {
    var size = state.size;
    var cells = size * size;
    var hasFree = size === 5;            // 5×5 has a free centre
    var perCard = hasFree ? cells - 1 : cells;

    var pool = selectedPool();
    var poolNeeded = cells * 2;          // so cards can differ
    var want = Math.max(poolNeeded, cells + 8);

    var master = window.TP_batch(pool, (window.TP_effDifficulty ? window.TP_effDifficulty(state.year, state.difficulty) : state.difficulty), want, { dedupeOn: 'answer' });

    var warn = '';
    if (master.length < perCard) {
      warn = 'Not enough unique questions for this grid at this difficulty — ' +
             'add more objectives, lower the difficulty or choose a smaller grid. ' +
             'Showing ' + master.length + ' of ' + perCard + ' needed.';
    }

    // Each printed card = a random sample of ANSWERS from the master pool, laid
    // into the grid (free centre for 5×5). Neighbours differ because each card
    // takes an independent shuffle+slice.
    var sampleN = Math.min(perCard, master.length);
    var cards = [];
    for (var c = 0; c < state.cardsN; c++) {
      var picks = window.TP_shuffle(master).slice(0, sampleN);
      var answers = picks.map(function (p) { return p.answer; });
      var grid = [];
      var ai = 0;
      var centre = hasFree ? Math.floor(cells / 2) : -1;
      for (var i = 0; i < cells; i++) {
        if (i === centre) {
          grid.push({ free: true, answer: 'FREE' });
        } else {
          grid.push({ free: false, answer: answers[ai] != null ? answers[ai] : '' });
          ai++;
        }
      }
      cards.push({ index: c + 1, grid: grid });
    }

    // Caller list = the master pairs in a shuffled call order. Every answer that
    // appears on any card is present in the master, so the call list covers them.
    var caller = window.TP_shuffle(master).map(function (p) {
      return { question: p.question, answer: p.answer };
    });

    return { cards: cards, caller: caller, warn: warn };
  }

  // ----- rendering -----------------------------------------------------------
  var els = {};

  function code() { return window.TP_diffDots(state.difficulty); }

  function renderGrid() {
    var g = state.game;
    var host = els.grid;
    host.innerHTML = '';

    if (state.tab === 'caller') {
      renderCaller(host, g);
    } else {
      renderCards(host, g);
    }
  }

  function renderCards(host, g) {
    if (g.warn) {
      var w = document.createElement('div');
      w.className = 'bg-warn';
      w.textContent = '⚠ ' + g.warn;
      host.appendChild(w);
    }

    var wrap = document.createElement('div');
    wrap.className = 'bg-cards';

    g.cards.forEach(function (card) {
      var el = document.createElement('div');
      el.className = 'bg-card';

      var head = document.createElement('div');
      head.className = 'bg-card-head';
      var title = document.createElement('span');
      title.className = 'bg-card-title';
      title.textContent = 'BINGO';
      var codeSpan = document.createElement('span');
      codeSpan.className = 'bg-card-code';
      codeSpan.textContent = 'Card ' + card.index + ' · ' + code();
      head.appendChild(title);
      head.appendChild(codeSpan);
      el.appendChild(head);

      var grid = document.createElement('div');
      grid.className = 'bg-grid';
      grid.style.setProperty('--bg-n', state.size);
      card.grid.forEach(function (cell) {
        var c = document.createElement('div');
        c.className = 'bg-cell' + (cell.free ? ' bg-free' : '');
        c.textContent = cell.free ? 'FREE' : cell.answer;
        grid.appendChild(c);
      });
      el.appendChild(grid);

      wrap.appendChild(el);
    });

    host.appendChild(wrap);
  }

  function renderCaller(host, g) {
    if (g.warn) {
      var w = document.createElement('div');
      w.className = 'bg-warn';
      w.textContent = '⚠ ' + g.warn;
      host.appendChild(w);
    }

    var intro = document.createElement('p');
    intro.style.cssText = 'margin:0 0 16px; font-size:14px; color:#4a514a; line-height:1.55;';
    intro.innerHTML = '📣 Read each question aloud in order. Children dab the matching ' +
      '<strong>answer</strong> on their card. First to complete a line shouts <strong>BINGO!</strong>';
    host.appendChild(intro);

    var table = document.createElement('table');
    table.className = 'bg-caller';
    var thead = document.createElement('thead');
    var hr = document.createElement('tr');
    ['Call #', 'Question', 'Answer'].forEach(function (h) {
      var th = document.createElement('th');
      th.textContent = h;
      hr.appendChild(th);
    });
    thead.appendChild(hr);
    table.appendChild(thead);

    var tbody = document.createElement('tbody');
    g.caller.forEach(function (row, i) {
      var tr = document.createElement('tr');
      var n = document.createElement('td');
      n.className = 'bg-call-n';
      n.textContent = String(i + 1);
      var q = document.createElement('td');
      q.textContent = row.question;
      var a = document.createElement('td');
      a.className = 'bg-call-a';
      a.textContent = row.answer;
      tr.appendChild(n); tr.appendChild(q); tr.appendChild(a);
      tbody.appendChild(tr);
    });
    table.appendChild(tbody);
    host.appendChild(table);
  }

  function renderStrandChips() {
    var host = els.strands;
    if (!host) { return; }
    host.style.display = 'flex';
    host.style.flexWrap = 'wrap';
    host.style.gap = '7px';
    host.innerHTML = '';
    strandList().forEach(function (s) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'chip' + (state.strands[s] ? ' chip-on' : '');
      b.textContent = s;
      b.addEventListener('click', function () { toggleStrand(s); });
      host.appendChild(b);
    });
  }

  function renderControls() {
    renderStrandChips();
    // size chips
    Array.prototype.forEach.call(els.size.querySelectorAll('[data-size]'), function (c) {
      c.classList.toggle('chip-on', Number(c.getAttribute('data-size')) === state.size);
    });
    // cards-n chips
    Array.prototype.forEach.call(els.cardsN.querySelectorAll('[data-n]'), function (c) {
      c.classList.toggle('chip-on', Number(c.getAttribute('data-n')) === state.cardsN);
    });
    // difficulty thumb + labels (measure the active button)
    var diffWrap = $('bingo-difficulty');
    var active = diffWrap ? diffWrap.querySelectorAll('[data-diff]')[state.difficulty - 1] : null;
    if (els.diffThumb && active) {
      els.diffThumb.style.left = active.offsetLeft + 'px';
      els.diffThumb.style.width = active.offsetWidth + 'px';
    }
    if (els.diffLabel) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    if (els.eyebrowDiff) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    // tab thumb (measure the active segment)
    var tabsWrap = $('bingo-tabs');
    var activeTab = tabsWrap ? tabsWrap.querySelectorAll('[data-tab]')[state.tab === 'caller' ? 1 : 0] : null;
    if (els.tabThumb && activeTab) {
      els.tabThumb.style.left = activeTab.offsetLeft + 'px';
      els.tabThumb.style.width = activeTab.offsetWidth + 'px';
    }
  }

  function render() {
    renderControls();
    renderGrid();
  }

  // ----- actions -------------------------------------------------------------
  function rebuild() {
    state.game = build();
    render();
  }

  function setDiff(d) { state.difficulty = d; rebuild(); }
  function setSize(n) { state.size = n; rebuild(); }
  function setCardsN(n) { state.cardsN = n; rebuild(); }
  function setTab(tab) { state.tab = tab; render(); }

  function toggleStrand(s) {
    if (state.strands[s]) {
      delete state.strands[s];
    } else {
      state.strands[s] = true;
    }
    // Never leave the pool empty — keep at least one strand selected.
    if (Object.keys(state.strands).length === 0) { state.strands[s] = true; }
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
      size: state.size,
      cardsN: state.cardsN
    };
    var form = new FormData();
    form.append('title', 'Bingo');
    form.append('activity', 'bingo');
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

  // ----- default strand selection --------------------------------------------
  function applyDefaultStrand() {
    var strands = strandList();
    var preferred = 'Multiplication & division';
    if (strands.indexOf(preferred) !== -1) {
      state.strands[preferred] = true;
    } else if (strands.length) {
      state.strands[strands[0]] = true;
    }
  }

  // ----- wire up -------------------------------------------------------------
  function init() {
    els.strands = $('bingo-strands');
    els.size = $('bingo-size');
    els.cardsN = $('bingo-cards-n');
    els.grid = $('bingo-grid');
    els.diffThumb = $('bingo-difficulty') ? $('bingo-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('bingo-diff-label');
    els.eyebrowDiff = $('bingo-eyebrow-diff');
    els.tabThumb = $('bingo-tabs') ? $('bingo-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('bingo-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('bingo-toast');

    var yearEl = $('bingo-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    Array.prototype.forEach.call(els.size.querySelectorAll('[data-size]'), function (c) {
      c.addEventListener('click', function () { setSize(Number(c.getAttribute('data-size'))); });
    });
    Array.prototype.forEach.call(els.cardsN.querySelectorAll('[data-n]'), function (c) {
      c.addEventListener('click', function () { setCardsN(Number(c.getAttribute('data-n'))); });
    });
    Array.prototype.forEach.call($('bingo-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('bingo-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('bingo-save').addEventListener('click', onSave);
    $('bingo-print').addEventListener('click', function () { window.print(); });
    $('bingo-regen').addEventListener('click', regen);

    var y0 = window.TP_wireYears ? window.TP_wireYears('bingo', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    applyDefaultStrand();
    rebuild();

    // keep sliding thumbs aligned on resize
    window.addEventListener('resize', renderControls);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
