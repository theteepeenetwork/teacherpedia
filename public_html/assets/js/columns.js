/* =============================================================================
 * columns.js — KS1-2 Numeracy "Column Methods" formal written-method sheet.
 * -----------------------------------------------------------------------------
 * Generates a grid of formal column / standard-method calculations for a SINGLE
 * chosen operation (column addition, column subtraction, short/long
 * multiplication, short/long division). The operand magnitude is driven by the
 * school YEAR (the 1-5 meter is shown as the difficulty circle meter and used
 * for the eyebrow). Division is always EXACT (built as divisor × quotient) and
 * is rendered bus-stop style.
 *
 * Worksheet tab — blank answer line / quotient (room to work).
 * Answer key    — the answer / quotient filled in.
 * Prints as a clean sheet. Save POSTs to /account/save.
 * ========================================================================== */
(function () {
  'use strict';

  var OP_LABEL = {
    add: 'Column addition',
    subtract: 'Column subtraction',
    multiply: 'Short / long multiplication',
    divide: 'Short / long division'
  };
  var OP_SYM = { add: '+', subtract: '−', multiply: '×' };

  var state = {
    year: 4,
    difficulty: 3,
    op: (window.TP_COL_OP || 'add'),
    count: 12,
    tab: 'worksheet',
    items: []
  };

  // ---- helpers --------------------------------------------------------------
  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function $(id) { return document.getElementById(id); }

  // A random integer with EXACTLY `digits` digits (no leading zero).
  function nDigit(digits) {
    if (digits <= 1) { return ri(0, 9); }
    var lo = Math.pow(10, digits - 1);
    var hi = Math.pow(10, digits) - 1;
    return ri(lo, hi);
  }

  // Operand digit-length per year for add / subtract.
  function addDigits(year) {
    var map = { 2: 2, 3: 3, 4: 4, 5: 5, 6: 5 };
    return Math.max(2, map[year] || 4);
  }

  // ---- generation -----------------------------------------------------------
  function genAdd(year) {
    var d = addDigits(year);
    // Year 4+ may use three operands.
    var threeOp = year >= 4 && Math.random() < 0.4;
    var ops = [nDigit(d), nDigit(d)];
    if (threeOp) { ops.push(nDigit(d)); }
    var sum = ops.reduce(function (a, b) { return a + b; }, 0);
    return { op: 'add', operands: ops, answer: sum };
  }

  function genSubtract(year) {
    var d = addDigits(year);
    var a = nDigit(d), b = nDigit(d);
    if (a < b) { var t = a; a = b; b = t; }
    if (a === b) { a = a + ri(1, 9); } // avoid a trivial zero difference
    return { op: 'subtract', operands: [a, b], answer: a - b };
  }

  function genMultiply(year) {
    var a, b;
    if (year <= 3) { a = nDigit(2); b = nDigit(1); }
    else if (year === 4) { a = nDigit(3); b = nDigit(1); }
    else if (year === 5) { a = nDigit(4); b = nDigit(1); }
    else { a = nDigit(3); b = nDigit(2); } // year >= 6: long multiplication
    return { op: 'multiply', operands: [a, b], answer: a * b };
  }

  function genDivide(year) {
    var divisor, quotient;
    if (year <= 4) { divisor = ri(2, 9); quotient = nDigit(year <= 3 ? 2 : 3); }
    else if (year === 5) { divisor = ri(2, 9); quotient = nDigit(3); }
    else { divisor = ri(12, 29); quotient = nDigit(2); } // year >= 6: 2-digit divisor
    var dividend = divisor * quotient;
    return { op: 'divide', divisor: divisor, quotient: quotient, dividend: dividend, answer: quotient };
  }

  function genOne(op, year) {
    if (op === 'subtract') { return genSubtract(year); }
    if (op === 'multiply') { return genMultiply(year); }
    if (op === 'divide') { return genDivide(year); }
    return genAdd(year);
  }

  function build() {
    var items = [];
    for (var i = 0; i < state.count; i++) {
      items.push(genOne(state.op, state.year));
    }
    return items;
  }

  // ---- rendering ------------------------------------------------------------
  // Vertical stack for add / subtract / multiply. The operator sits to the LEFT
  // of the second operand; a rule separates the working from the answer line.
  function renderStack(item, answersOn) {
    var stack = document.createElement('div');
    stack.className = 'col-stack';
    var ops = item.operands;
    var sym = OP_SYM[item.op];

    // All operand rows EXCEPT the last are plain right-aligned numbers; the last
    // operand carries the operator on its left.
    for (var i = 0; i < ops.length - 1; i++) {
      var row = document.createElement('div');
      row.className = 'col-row';
      row.textContent = String(ops[i]);
      stack.appendChild(row);
    }
    var opRow = document.createElement('div');
    opRow.className = 'col-op-row';
    var opSpan = document.createElement('span');
    opSpan.className = 'col-op';
    opSpan.textContent = sym;
    var lastSpan = document.createElement('span');
    lastSpan.textContent = String(ops[ops.length - 1]);
    opRow.appendChild(opSpan);
    opRow.appendChild(lastSpan);
    stack.appendChild(opRow);

    var bar = document.createElement('div');
    bar.className = 'col-bar';
    stack.appendChild(bar);

    var ans = document.createElement('div');
    ans.className = 'col-ans' + (answersOn ? '' : ' blank');
    ans.textContent = answersOn ? String(item.answer) : '0';
    stack.appendChild(ans);

    return stack;
  }

  // Bus-stop for division: divisor )‾ dividend, quotient above the line.
  function renderBus(item, answersOn) {
    var bus = document.createElement('div');
    bus.className = 'col-bus';

    var divisor = document.createElement('div');
    divisor.className = 'col-divisor';
    divisor.textContent = String(item.divisor);

    var house = document.createElement('div');
    house.className = 'col-house';

    var quot = document.createElement('div');
    quot.className = 'col-quot' + (answersOn ? '' : ' blank');
    quot.textContent = answersOn ? String(item.quotient) : '0';

    var dividend = document.createElement('div');
    dividend.className = 'col-dividend';
    dividend.textContent = String(item.dividend);

    house.appendChild(quot);
    house.appendChild(dividend);
    bus.appendChild(divisor);
    bus.appendChild(house);
    return bus;
  }

  function renderGrid() {
    var grid = els.grid;
    var answersOn = state.tab === 'answers';
    // 2 columns for the wider long-multiplication/division sheets, else 3.
    var cols = (state.op === 'divide') ? 3 : 3;
    grid.style.setProperty('--col-n', cols);
    grid.innerHTML = '';

    state.items.forEach(function (item, idx) {
      var prob = document.createElement('div');
      prob.className = 'col-prob';

      var num = document.createElement('div');
      num.className = 'col-num';
      num.textContent = (idx + 1) + '.';
      prob.appendChild(num);

      var body = document.createElement('div');
      body.className = 'col-body';
      body.appendChild(item.op === 'divide' ? renderBus(item, answersOn) : renderStack(item, answersOn));
      prob.appendChild(body);

      grid.appendChild(prob);
    });
  }

  function renderControls() {
    // operation chips (single-select)
    Array.prototype.forEach.call(els.op.querySelectorAll('[data-cop]'), function (c) {
      c.classList.toggle('chip-on', c.getAttribute('data-cop') === state.op);
    });
    // count chips
    Array.prototype.forEach.call(els.count.querySelectorAll('[data-count]'), function (c) {
      c.classList.toggle('chip-on', Number(c.getAttribute('data-count')) === state.count);
    });
    // difficulty thumb + labels (measure the active button)
    var diffWrap = $('col-difficulty');
    var active = diffWrap ? diffWrap.querySelectorAll('[data-diff]')[state.difficulty - 1] : null;
    if (els.diffThumb && active) {
      els.diffThumb.style.left = active.offsetLeft + 'px';
      els.diffThumb.style.width = active.offsetWidth + 'px';
    }
    if (els.diffLabel) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    // tab thumb (measure the active segment)
    var tabsWrap = $('col-tabs');
    var activeTab = tabsWrap ? tabsWrap.querySelectorAll('[data-tab]')[state.tab === 'answers' ? 1 : 0] : null;
    if (els.tabThumb && activeTab) {
      els.tabThumb.style.left = activeTab.offsetLeft + 'px';
      els.tabThumb.style.width = activeTab.offsetWidth + 'px';
    }
    // eyebrow reflects op + difficulty meter
    if (els.eyebrow) {
      els.eyebrow.textContent = OP_LABEL[state.op] + ' · ' + window.TP_diffDots(state.difficulty);
    }
  }

  function render() {
    renderControls();
    renderGrid();
  }

  // ---- actions --------------------------------------------------------------
  function rebuild() {
    state.items = build();
    render();
  }

  function setDiff(d) { state.difficulty = d; rebuild(); }
  function setCount(n) { state.count = n; rebuild(); }
  function setTab(tab) { state.tab = tab; render(); }
  function setOp(op) { state.op = op; rebuild(); }

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
      year: state.year,
      difficulty: state.difficulty,
      op: state.op,
      count: state.count
    };
    var form = new FormData();
    form.append('title', 'Column Methods');
    form.append('activity', 'columns');
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
  var els = {};
  function init() {
    els.op = $('col-op');
    els.count = $('col-count');
    els.grid = $('col-grid');
    els.eyebrow = $('col-eyebrow');
    els.diffThumb = $('col-difficulty') ? $('col-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('col-diff-label');
    els.tabThumb = $('col-tabs') ? $('col-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('col-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('col-toast');

    var yearEl = $('col-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    // Year selector (shared partial) — sets operand magnitude.
    var y0 = window.TP_wireYears ? window.TP_wireYears('col', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    Array.prototype.forEach.call(els.op.querySelectorAll('[data-cop]'), function (c) {
      c.addEventListener('click', function () { setOp(c.getAttribute('data-cop')); });
    });
    Array.prototype.forEach.call(els.count.querySelectorAll('[data-count]'), function (c) {
      c.addEventListener('click', function () { setCount(Number(c.getAttribute('data-count'))); });
    });
    Array.prototype.forEach.call($('col-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('col-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('col-save').addEventListener('click', onSave);
    $('col-print').addEventListener('click', function () { window.print(); });
    $('col-regen').addEventListener('click', regen);

    rebuild();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
