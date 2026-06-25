/* =============================================================================
   build.js — Worksheet Builder (/build) behaviour
   -----------------------------------------------------------------------------
   Vanilla-JS port of the design's Multi-Generator DCLogic component.

   Reads window.TP_OBJECTIVES (array of {id,year,strand,text,key,auto}) shipped
   by the Build controller, renders the interactive objective library + live A4
   worksheet preview, and saves to /account/save.

   Generation goes through window.TP_generate(key, difficulty) -> {question,
   answer} | null. We ONLY treat an objective as generatable when its key is
   non-null AND present in window.TP_GEN; otherwise it is shown disabled
   ("soon") and cannot be added.
   ========================================================================== */
(function () {
  'use strict';

  var OBJ = (window.TP_OBJECTIVES || []).slice();

  // ----- strand ordering + colours (ported from the design) -----
  var STRAND_ORDER = [
    'Counting', 'Comparing & ordering', 'Reading & writing', 'Representing & estimating',
    'Place value', 'Rounding', 'Addition & subtraction', 'Multiplication & division',
    'Written methods', 'Order of operations', 'Fractions, decimals & %', 'Percentages',
    'Estimation', 'Problem solving', 'Mastery / missing number'
  ];
  var STRAND_COLOR = {
    'Counting': '#1f8a4d', 'Comparing & ordering': '#2a6fdb', 'Reading & writing': '#7a4fbf',
    'Representing & estimating': '#0f9b9b', 'Place value': '#c0563a', 'Rounding': '#b8742e',
    'Addition & subtraction': '#1f8a4d', 'Multiplication & division': '#2a6fdb',
    'Written methods': '#7a4fbf', 'Order of operations': '#5a6b3b',
    'Fractions, decimals & %': '#0f9b9b', 'Percentages': '#c0563a', 'Estimation': '#b8742e',
    'Problem solving': '#5a6b3b', 'Mastery / missing number': '#7a4fbf'
  };
  function colorFor(s) { return STRAND_COLOR[s] || '#1f8a4d'; }

  var DIFF_LABELS = ['Foundation', 'Emerging', 'Expected', 'Greater depth', 'Challenge'];

  // ----- state -----
  var state = {
    title: '',
    difficulty: 3,
    years: [6],
    query: '',
    autoOnly: false,
    selected: {},     // objectiveId -> qty
    questions: [],    // [{id, question, answer, num}]
    tab: 'worksheet', // 'worksheet' | 'answerkey'
    twoCol: true,
    answerSpace: true,
    rolling: false
  };

  // index objectives by id for O(1) lookup
  var BY_ID = {};
  OBJ.forEach(function (o) { BY_ID[o.id] = o; });

  // An objective is generatable iff it has a key present in TP_GEN.
  function canGenerate(o) {
    return !!(o && o.key && window.TP_GEN && window.TP_GEN[o.key]);
  }

  // ----- DOM refs -----
  var els = {};
  function $(id) { return document.getElementById(id); }

  // ----- generation -----
  function generateOne(o) {
    // Guard: never call the generator for an unknown / null key.
    if (!canGenerate(o)) { return null; }
    var r = window.TP_generate(o.key, state.difficulty);
    if (!r) { return null; }
    return { question: r.question, answer: r.answer };
  }

  // Rebuild the question list. force=true re-rolls everything; force=false keeps
  // already-generated questions where the qty is unchanged (so adding one
  // objective doesn't re-roll the others).
  function rebuild(force) {
    var existing = {};
    if (!force) {
      state.questions.forEach(function (q) {
        (existing[q.id] = existing[q.id] || []).push(q);
      });
    }
    var list = [];
    var n = 1;
    // Preserve selection insertion order via the order objectives appear in OBJ,
    // falling back to whatever order Object.keys yields. We iterate OBJ so the
    // worksheet order is stable and matches the library order.
    var seen = {};
    function emit(id) {
      if (seen[id]) { return; }
      seen[id] = true;
      var qty = state.selected[id];
      if (!qty) { return; }
      var o = BY_ID[id];
      var have = (existing[id] || []).slice();
      for (var i = 0; i < qty; i++) {
        var q;
        if (!force && have[i]) {
          q = have[i];
        } else {
          var g = generateOne(o);
          // Guard null: skip objectives we cannot generate (shouldn't be
          // selectable, but stay defensive).
          if (!g) { continue; }
          q = { id: id, question: g.question, answer: g.answer };
        }
        list.push({ id: id, question: q.question, answer: q.answer, num: 0 });
      }
    }
    OBJ.forEach(function (o) { emit(o.id); });
    // any selected ids not in OBJ (defensive)
    Object.keys(state.selected).forEach(function (id) { emit(Number(id)); });

    // number sequentially
    list.forEach(function (q, i) { q.num = i + 1; });
    state.questions = list;
    renderSheet();
    renderFooterCounts();
  }

  // ----- selection mutations -----
  function addObj(id) {
    var o = BY_ID[id];
    if (!canGenerate(o)) { return; }          // not addable
    if (state.selected[id]) { return; }
    state.selected[id] = 3;
    rebuild(false);
    renderLibrary();
  }
  function incQty(id) {
    if (!state.selected[id]) { return; }
    state.selected[id] = Math.min(state.selected[id] + 1, 30);
    rebuild(false);
    renderLibrary();
  }
  function decQty(id) {
    if (!state.selected[id]) { return; }
    if (state.selected[id] <= 1) {
      delete state.selected[id];
    } else {
      state.selected[id] = state.selected[id] - 1;
    }
    rebuild(false);
    renderLibrary();
  }
  function clearAll() {
    state.selected = {};
    rebuild(true);
    renderLibrary();
  }

  // ----- library rendering -----
  function totalQuestions() {
    var t = 0;
    Object.keys(state.selected).forEach(function (id) { t += state.selected[id]; });
    return t;
  }
  function selCount() { return Object.keys(state.selected).length; }

  function visibleObjectives() {
    var q = state.query.trim().toLowerCase();
    return OBJ.filter(function (o) {
      if (state.years.indexOf(o.year) === -1) { return false; }
      if (state.autoOnly && !canGenerate(o)) { return false; }
      if (q && o.text.toLowerCase().indexOf(q) === -1 && o.strand.toLowerCase().indexOf(q) === -1) {
        return false;
      }
      return true;
    });
  }

  function renderLibrary() {
    var lib = els.library;
    if (!lib) { return; }
    var visible = visibleObjectives();

    if (visible.length === 0) {
      var msg = state.years.length === 0
        ? 'No year selected.<br>Pick a year above to see its objectives.'
        : 'No objectives match.<br>Try another year or search term.';
      lib.innerHTML = '<div class="build-empty-lib">' + msg + '</div>';
      return;
    }

    // group by strand
    var byStrand = {};
    visible.forEach(function (o) { (byStrand[o.strand] = byStrand[o.strand] || []).push(o); });
    var strands = STRAND_ORDER.filter(function (s) { return byStrand[s]; });
    Object.keys(byStrand).forEach(function (s) {
      if (strands.indexOf(s) === -1) { strands.push(s); }
    });

    var frag = document.createDocumentFragment();
    strands.forEach(function (strand) {
      var items = byStrand[strand];
      var autoN = items.filter(canGenerate).length;
      var color = colorFor(strand);

      var group = document.createElement('div');
      group.className = 'build-group';

      var head = document.createElement('div');
      head.className = 'build-strand-head';
      head.innerHTML =
        '<span class="build-strand-dot" style="background:' + color + ';"></span>' +
        '<span class="build-strand-name"></span>' +
        '<span class="build-strand-line"></span>' +
        '<span class="build-strand-count"></span>';
      head.querySelector('.build-strand-name').textContent = strand;
      head.querySelector('.build-strand-count').textContent =
        (autoN === items.length) ? String(items.length) : (autoN + '/' + items.length);
      group.appendChild(head);

      items.forEach(function (o) {
        group.appendChild(buildRow(o, color));
      });
      frag.appendChild(group);
    });

    lib.innerHTML = '';
    lib.appendChild(frag);
  }

  function buildRow(o, color) {
    var selected = !!state.selected[o.id];
    var addable = !selected && canGenerate(o);
    var soon = !selected && !canGenerate(o);

    var row = document.createElement('div');
    row.className = 'orow';

    // selection accent bar
    if (selected) {
      var bar = document.createElement('span');
      bar.className = 'build-orow-bar';
      bar.style.background = color;
      row.appendChild(bar);
    }

    // tick / checkbox / soon marker
    var tick = document.createElement('div');
    tick.className = 'build-tick';
    if (selected) {
      tick.innerHTML = '<span class="box sel" style="background:' + color + ';">&#10003;</span>';
    } else if (soon) {
      tick.innerHTML = '<span class="box soon"></span>';
    } else {
      tick.innerHTML = '<span class="box"></span>';
    }
    row.appendChild(tick);

    // text + meta
    var textWrap = document.createElement('div');
    textWrap.className = 'build-otext';
    var t = document.createElement('div');
    t.className = 't';
    t.textContent = o.text;
    t.style.color = canGenerate(o) ? '#26302a' : '#a8a294';
    textWrap.appendChild(t);
    var meta = document.createElement('div');
    meta.className = 'build-ometa';
    var yr = document.createElement('span');
    yr.className = 'build-oyear';
    yr.textContent = 'Year ' + o.year;
    meta.appendChild(yr);
    if (soon) {
      var tag = document.createElement('span');
      tag.className = 'build-soon-tag';
      tag.textContent = 'Coming soon';
      meta.appendChild(tag);
    }
    textWrap.appendChild(meta);
    row.appendChild(textWrap);

    // controls
    if (addable) {
      var add = document.createElement('button');
      add.type = 'button';
      add.className = 'build-add';
      add.textContent = '+';
      add.addEventListener('click', function () { addObj(o.id); });
      row.appendChild(add);
    } else if (selected) {
      var stepper = document.createElement('div');
      stepper.className = 'stepper';
      stepper.style.border = '1px solid ' + color;
      var dec = document.createElement('button');
      dec.type = 'button'; dec.textContent = '−';
      dec.addEventListener('click', function () { decQty(o.id); });
      var qty = document.createElement('span');
      qty.className = 'qty';
      qty.textContent = String(state.selected[o.id]);
      var inc = document.createElement('button');
      inc.type = 'button'; inc.textContent = '+';
      inc.addEventListener('click', function () { incQty(o.id); });
      stepper.appendChild(dec); stepper.appendChild(qty); stepper.appendChild(inc);
      row.appendChild(stepper);
    }
    // soon rows: no control (non-addable), matching the design.

    return row;
  }

  // ----- sheet rendering -----
  function selectedYears() {
    var ys = {};
    Object.keys(state.selected).forEach(function (id) {
      var o = BY_ID[id];
      if (o) { ys[o.year] = true; }
    });
    return Object.keys(ys).map(Number).sort(function (a, b) { return a - b; });
  }

  function yearSpanLabel() {
    var ys = selectedYears();
    if (ys.length === 0) { return 'Year 6'; }
    if (ys.length === 1) { return 'Year ' + ys[0]; }
    return 'Years ' + ys[0] + '–' + ys[ys.length - 1];
  }

  function displayTitle() {
    return state.title.trim() || 'Mixed Maths Practice';
  }

  function numLabel(n) { return n + ')'; }

  function renderSheet() {
    var answersOn = state.tab === 'answerkey';
    var diffLabel = DIFF_LABELS[state.difficulty - 1];

    if (els.sheetEyebrow) {
      els.sheetEyebrow.textContent = yearSpanLabel() + ' · Numeracy · ' + diffLabel;
    }
    if (els.sheetTitle) { els.sheetTitle.textContent = displayTitle(); }
    if (els.keybadge) { els.keybadge.style.display = answersOn ? 'inline-flex' : 'none'; }

    var list = els.qlist;
    if (!list) { return; }

    if (state.questions.length === 0) {
      list.innerHTML = '';
      list.style.display = 'none';
      if (els.sheetEmpty) { els.sheetEmpty.style.display = 'block'; }
    } else {
      if (els.sheetEmpty) { els.sheetEmpty.style.display = 'none'; }
      list.style.display = '';
      var frag = document.createDocumentFragment();
      state.questions.forEach(function (q) {
        var li = document.createElement('li');

        var num = document.createElement('span');
        num.className = 'sheet-qnum';
        num.textContent = numLabel(q.num);
        li.appendChild(num);

        var body = document.createElement('span');
        body.className = 'sheet-qtext';
        var qspan = document.createElement('span');
        qspan.textContent = q.question;
        body.appendChild(qspan);

        if (!answersOn && state.answerSpace) {
          var blank = document.createElement('span');
          blank.className = 'sheet-blank';
          body.appendChild(blank);
        }
        if (answersOn) {
          var ans = document.createElement('span');
          ans.className = 'sheet-answer';
          ans.textContent = ' ' + q.answer;
          body.appendChild(ans);
        }
        li.appendChild(body);
        frag.appendChild(li);
      });
      list.innerHTML = '';
      list.appendChild(frag);
    }

    // two-column toggle + rolling fade
    list.classList.toggle('two-col', state.twoCol);
    list.style.opacity = state.rolling ? '0.12' : '1';
  }

  function renderFooterCounts() {
    var total = totalQuestions();
    if (els.totalQ) { els.totalQ.textContent = String(total); }
    if (els.selCount) { els.selCount.textContent = String(selCount()); }
    if (els.footMeta) {
      els.footMeta.textContent = state.questions.length + ' questions · auto-generated · ' +
        (window.TP_YEAR || new Date().getFullYear());
    }
  }

  // ----- control rendering (difficulty thumb, segmented thumb, chips) -----
  function renderDifficulty() {
    var wrap = els.difficulty;
    if (!wrap) { return; }
    var btns = wrap.querySelectorAll('button');
    var thumb = wrap.querySelector('.diff-thumb');
    var active = btns[state.difficulty - 1];
    if (thumb && active) {
      thumb.style.left = active.offsetLeft + 'px';
      thumb.style.width = active.offsetWidth + 'px';
    }
    if (els.diffLabel) { els.diffLabel.textContent = DIFF_LABELS[state.difficulty - 1]; }
  }

  function renderTabs() {
    var wrap = els.tabs;
    if (!wrap) { return; }
    var thumb = wrap.querySelector('.seg-thumb');
    var idx = state.tab === 'answerkey' ? 1 : 0;
    var btn = wrap.querySelectorAll('button')[idx];
    if (thumb && btn) {
      thumb.style.left = btn.offsetLeft + 'px';
      thumb.style.width = btn.offsetWidth + 'px';
    }
  }

  function renderChips() {
    // year chips
    document.querySelectorAll('[data-year]').forEach(function (b) {
      var y = Number(b.getAttribute('data-year'));
      b.classList.toggle('chip-on', state.years.indexOf(y) !== -1);
    });
    if (els.auto) { els.auto.classList.toggle('chip-on', state.autoOnly); }
    if (els.twoColBtn) { els.twoColBtn.classList.toggle('chip-on', state.twoCol); }
    if (els.answerSpaceBtn) { els.answerSpaceBtn.classList.toggle('chip-on', state.answerSpace); }
  }

  // ----- actions -----
  function setDifficulty(d) {
    state.difficulty = d;
    renderDifficulty();
    rebuild(true); // re-roll because difficulty changes the numbers
  }

  function setTab(tab) {
    state.tab = tab;
    renderTabs();
    renderSheet();
  }

  function toggleYear(y) {
    var i = state.years.indexOf(y);
    if (i === -1) {
      state.years.push(y);
      state.years.sort(function (a, b) { return a - b; });
    } else {
      // Allow deselecting every year — the library simply shows an empty
      // state. Forcing at least one year on meant you couldn't clear the
      // selection without re-deselecting them all again.
      state.years.splice(i, 1);
    }
    renderChips();
    renderLibrary();
  }

  function regenerate() {
    state.rolling = true;
    renderSheet();
    if (els.regenIcon) { els.regenIcon.classList.add('build-spinning'); }
    setTimeout(function () {
      rebuild(true);
      state.rolling = false;
      renderSheet();
      if (els.regenIcon) { els.regenIcon.classList.remove('build-spinning'); }
    }, 220);
  }

  function showToast() {
    if (!els.toast) { return; }
    els.toast.style.display = 'block';
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function () { els.toast.style.display = 'none'; }, 1900);
  }

  function buildConfigItems() {
    var items = [];
    OBJ.forEach(function (o) {
      var qty = state.selected[o.id];
      if (!qty) { return; }
      items.push({
        key: o.key,            // may be null in schema; selected ones are non-null
        text: o.text,
        year: o.year,
        strand: o.strand,
        qty: qty
      });
    });
    return items;
  }

  function save() {
    var config = {
      title: state.title,
      difficulty: state.difficulty,
      twoCol: state.twoCol,
      answerSpace: state.answerSpace,
      items: buildConfigItems()
    };
    var body = new FormData();
    body.append('title', state.title.trim() || 'Untitled worksheet');
    body.append('activity', 'worksheet');
    body.append('config', JSON.stringify(config));

    fetch(window.TP_SAVE_URL, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
      redirect: 'manual',
      body: body
    }).then(function (res) {
      // Not logged in -> auth filter redirects (302) or returns 401.
      if (res.status === 401 || res.status === 0 || res.type === 'opaqueredirect' ||
          (res.status >= 300 && res.status < 400)) {
        window.location.href = window.TP_LOGIN_URL;
        return;
      }
      if (res.ok) { showToast(); return; }
      // Fallback: try to detect a JSON error, else just toast nothing useful.
      return res.text().then(function () { /* swallow */ });
    }).catch(function () {
      // Network/redirect error — safest is to push to login.
      window.location.href = window.TP_LOGIN_URL;
    });
  }

  // ----- restore from a saved sheet -----
  function restoreFromSaved() {
    if (!window.TP_SAVED || !window.TP_SAVED.config) { return; }
    var cfg = window.TP_SAVED.config;
    state.title = (cfg.title != null) ? String(cfg.title) : (window.TP_SAVED.title || '');
    if (cfg.difficulty >= 1 && cfg.difficulty <= 5) { state.difficulty = cfg.difficulty; }
    state.twoCol = !!cfg.twoCol;
    state.answerSpace = !!cfg.answerSpace;

    var yearsSeen = {};
    state.selected = {};
    (cfg.items || []).forEach(function (it) {
      // Match a saved item back to a current objective. Prefer key+text, then
      // text+year, to survive id changes between environments.
      var match = null;
      for (var i = 0; i < OBJ.length; i++) {
        var o = OBJ[i];
        if (it.key && o.key === it.key && o.text === it.text) { match = o; break; }
      }
      if (!match) {
        for (var j = 0; j < OBJ.length; j++) {
          var o2 = OBJ[j];
          if (o2.text === it.text && o2.year === it.year) { match = o2; break; }
        }
      }
      if (match && canGenerate(match)) {
        state.selected[match.id] = Math.max(1, Math.min(Number(it.qty) || 1, 30));
        yearsSeen[match.year] = true;
      }
    });
    // widen visible years to include restored selections
    Object.keys(yearsSeen).map(Number).forEach(function (y) {
      if (state.years.indexOf(y) === -1) { state.years.push(y); }
    });
    state.years.sort();

    if (els.titleInput) { els.titleInput.value = state.title; }
  }

  // ----- default selection (matches design: first 3 auto-gen Y6) -----
  function applyDefaultSelection() {
    var count = 0;
    for (var i = 0; i < OBJ.length && count < 3; i++) {
      var o = OBJ[i];
      if (o.year === 6 && canGenerate(o)) {
        state.selected[o.id] = (count === 0) ? 4 : 3;
        count++;
      }
    }
  }

  // ----- wiring -----
  function init() {
    els.library = $('build-library');
    els.titleInput = $('build-title');
    els.searchInput = $('build-search');
    els.auto = $('build-auto');
    els.clear = $('build-clear');
    els.totalQ = $('build-total-q');
    els.selCount = $('build-sel-count');
    els.difficulty = $('build-difficulty');
    els.diffLabel = $('build-diff-label');
    els.twoColBtn = $('build-twocol');
    els.answerSpaceBtn = $('build-answerspace');
    els.tabs = $('build-tabs');
    els.save = $('build-save');
    els.print = $('build-print');
    els.regen = $('build-regen');
    els.regenIcon = $('build-regen-icon');
    els.sheet = $('build-sheet');
    els.sheetEyebrow = $('build-sheet-eyebrow');
    els.sheetTitle = $('build-sheet-title');
    els.keybadge = $('build-keybadge');
    els.qlist = $('build-qlist');
    els.sheetEmpty = $('build-sheet-empty');
    els.footMeta = $('build-sheet-foot-meta');
    els.toast = $('build-toast');

    // title
    if (els.titleInput) {
      els.titleInput.addEventListener('input', function (e) {
        state.title = e.target.value;
        renderSheet();
      });
    }
    // search
    if (els.searchInput) {
      els.searchInput.addEventListener('input', function (e) {
        state.query = e.target.value;
        renderLibrary();
      });
    }
    // year chips
    document.querySelectorAll('[data-year]').forEach(function (b) {
      b.addEventListener('click', function () { toggleYear(Number(b.getAttribute('data-year'))); });
    });
    // auto-only
    if (els.auto) {
      els.auto.addEventListener('click', function () {
        state.autoOnly = !state.autoOnly;
        renderChips();
        renderLibrary();
      });
    }
    // clear
    if (els.clear) { els.clear.addEventListener('click', clearAll); }
    // difficulty
    if (els.difficulty) {
      els.difficulty.querySelectorAll('button').forEach(function (b) {
        b.addEventListener('click', function () { setDifficulty(Number(b.getAttribute('data-diff'))); });
      });
    }
    // two col / answer space
    if (els.twoColBtn) {
      els.twoColBtn.addEventListener('click', function () {
        state.twoCol = !state.twoCol; renderChips(); renderSheet();
      });
    }
    if (els.answerSpaceBtn) {
      els.answerSpaceBtn.addEventListener('click', function () {
        state.answerSpace = !state.answerSpace; renderChips(); renderSheet();
      });
    }
    // tabs
    if (els.tabs) {
      els.tabs.querySelectorAll('button').forEach(function (b) {
        b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
      });
    }
    // actions
    if (els.save) { els.save.addEventListener('click', save); }
    if (els.print) { els.print.addEventListener('click', function () { window.print(); }); }
    if (els.regen) { els.regen.addEventListener('click', regenerate); }

    // restore or default
    if (window.TP_SAVED) {
      restoreFromSaved();
    } else {
      applyDefaultSelection();
    }

    // first render
    renderChips();
    renderDifficulty();
    renderTabs();
    renderLibrary();
    rebuild(true);

    // keep sliding thumbs aligned on resize
    window.addEventListener('resize', function () { renderDifficulty(); renderTabs(); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
