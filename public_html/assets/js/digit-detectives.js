/* =============================================================================
 * digit-detectives.js — Digit Detectives.
 * -----------------------------------------------------------------------------
 * A correct column ADDITION is printed in formal written layout (addends stacked
 * over a rule, the total beneath). Several INTERIOR digits are replaced by
 * lettered boxes (A, B, C…). The solver recovers each missing digit purely by
 * deduction — running the addition algorithm backwards using place value, the
 * visible total digit, and column carries. No guessing: the visible digits
 * UNIQUELY determine every blank.
 *
 * Each recovered digit maps to a letter via a small cipher key printed on the
 * sheet (0->A 1->B … 9->J). Reading the recovered digits in label order A,B,C…
 * spells a short hidden word at the footer — the self-mark (right digits ->
 * real word). Generation is word-first: a target word is chosen, its letters
 * map back to digits, and those digits are placed as the blanks of a freshly
 * built, arithmetically-correct sum; the placement is only kept if the backward
 * solver recovers every blank uniquely.
 *
 * Pure engine exposed as window.TP_DD for Node tests; DOM wiring runs in-browser.
 * Self-contained (own ri/pick/shuffle) per the engine rules. See
 * dev/RESOURCE_WORKFLOW.md.
 * ========================================================================== */
(function () {
  'use strict';

  // ---- self-contained helpers ---------------------------------------------
  function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
  function pick(a) { return a[Math.floor(Math.random() * a.length)]; }
  function shuffle(arr) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; }
    return a;
  }

  // Cipher key: digit -> letter.  0->A 1->B … 9->J.  KEY[d] is the letter for d;
  // LETTER_OF is the inverse (letter -> digit).
  var KEY = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
  var DIGIT_OF = {};
  for (var _k = 0; _k < KEY.length; _k++) { DIGIT_OF[KEY[_k]] = _k; }

  // Curated reveal words — every letter must be in the cipher (A..J), so words
  // use only the letters a..j. Grouped by length so difficulty can pick a
  // length band (Below 2, Meeting 3, Exceeding 4-5).
  var WORDS = {
    2: ['AD', 'BE', 'GO', 'HI', 'IF', 'AH', 'ID', 'EH', 'BA', 'CD'],
    3: ['CAB', 'BAD', 'BEE', 'DAB', 'EGG', 'FIB', 'FAD', 'HAG', 'HID', 'JAB',
        'JIG', 'BIG', 'DIG', 'FIG', 'GAB', 'GAD', 'BID', 'AID', 'BAG', 'BED',
        'DAD', 'FED', 'HAD', 'ACE', 'AGE', 'BACH', 'EBB'],
    4: ['BEAD', 'CAGE', 'CHAD', 'DEAF', 'FACE', 'FADE', 'GIBE', 'HIDE',
        'BADGE', 'CAFE', 'DICE', 'FIBE', 'GAFE', 'BEEF', 'CHIC', 'DEED',
        'AIDE', 'BACH', 'CHEF', 'EDGE', 'GAFF', 'HACK', 'IDEA', 'BIDE',
        'GAGE', 'FEED', 'HEED', 'DEAD', 'FEDE', 'GEED'],
    5: ['BADGE', 'CAGED', 'FACED', 'FADED', 'HEDGE', 'AHEAD', 'BEADS',
        'DECAF', 'FIDGE', 'GAFFE', 'ACHED', 'BEACH', 'CHAFE', 'FACADE',
        'EDGED', 'AIDED', 'BEADED', 'CHIDE', 'GADID', 'DECADE', 'FECAL']
  };
  // Filter to only words whose every letter is a valid cipher letter (A..J) and
  // of exactly the keyed length (guards against typos above).
  (function sanitiseWords() {
    var L, i, w, out;
    for (L in WORDS) {
      out = [];
      for (i = 0; i < WORDS[L].length; i++) {
        w = WORDS[L][i];
        if (w.length !== Number(L)) { continue; }
        var ok = true;
        for (var c = 0; c < w.length; c++) { if (DIGIT_OF[w.charAt(c)] === undefined) { ok = false; break; } }
        if (ok) { out.push(w); }
      }
      WORDS[L] = out;
    }
  })();

  // Year -> maximum number of digits in any number (addend OR total). Curriculum
  // METHOD gate: Year 3 column addition is "up to 3 digits"; Year 4+ up to 4.
  function ceilingDigits(year) { return year <= 3 ? 3 : 4; }

  // ---- tier configuration ---------------------------------------------------
  // Each tier sets: addend count, the total-width band (clamped to the year
  // ceiling), how many blanks, and the reveal word length. Carry-chain length is
  // an emergent property of where blanks land; the uniqueness gate guarantees
  // solvability whatever the chain.
  function tierConfig(tier, year) {
    var ceil = ceilingDigits(year);
    if (tier === 'below') {
      return { nAdd: 2, widthMin: 2, widthMax: Math.min(3, ceil), blanks: ri(1, 2), totalBlankOk: false };
    }
    if (tier === 'exceeding') {
      return { nAdd: 3, widthMin: ceil, widthMax: ceil, blanks: ri(3, 4), totalBlankOk: true };
    }
    // meeting
    return { nAdd: ri(2, 3), widthMin: Math.min(3, ceil), widthMax: ceil, blanks: 3, totalBlankOk: true };
  }

  // ---- arithmetic representation -------------------------------------------
  // A puzzle's numbers are stored as digit arrays, index 0 = MOST significant.
  // addends: array of digit-arrays (each length == width, zero-padded on the
  // left). total: digit-array length == width. Column c (0=leftmost) corresponds
  // to place value 10^(width-1-c).
  function digitsOf(n, width) {
    var s = String(n), out = [], i;
    for (i = 0; i < s.length; i++) { out.push(s.charCodeAt(i) - 48); }
    while (out.length < width) { out.unshift(0); }
    return out;
  }

  // Build a correct addition with nAdd addends, all within [lo,hi], whose total
  // also fits `width` digits (no overflow beyond width) and has no number with a
  // leading zero. Returns { addends:[[..]], total:[..], width } or null.
  function buildSum(nAdd, width) {
    var lo = Math.pow(10, width - 1), hi = Math.pow(10, width) - 1;
    for (var attempt = 0; attempt < 400; attempt++) {
      var nums = [], i, sum = 0;
      for (i = 0; i < nAdd; i++) { var v = ri(lo, hi); nums.push(v); sum += v; }
      // total must be exactly `width` digits (so it shows no extra column and no
      // number exceeds the year ceiling) — reject overflow / underflow.
      if (sum < lo || sum > hi) { continue; }
      var addends = [];
      for (i = 0; i < nAdd; i++) { addends.push(digitsOf(nums[i], width)); }
      return { addends: addends, total: digitsOf(sum, width), width: width, sum: sum };
    }
    return null;
  }

  // Enumerate every blankable INTERIOR cell. A cell is a {kind,row,col}. We allow
  // blanking any addend digit and (if totalBlankOk) any total digit, EXCEPT a
  // cell that is the leading digit of its number (col 0) when that would let the
  // number read with a leading zero — to keep "no leading zero" inferable, we
  // simply never blank a leading (col 0) digit. That also matches the "interior
  // digits" brief.
  function blankableCells(sum, totalBlankOk) {
    var cells = [], r, c, w = sum.width;
    for (r = 0; r < sum.addends.length; r++) {
      for (c = 1; c < w; c++) { cells.push({ kind: 'addend', row: r, col: c }); }
    }
    if (totalBlankOk) {
      for (c = 1; c < w; c++) { cells.push({ kind: 'total', row: 0, col: c }); }
    }
    return cells;
  }

  function cellDigit(sum, cell) {
    return cell.kind === 'total' ? sum.total[cell.col] : sum.addends[cell.row][cell.col];
  }

  // ---- backward solver (the deductive oracle) -------------------------------
  // Given the puzzle (visible digits + blanks), recover each blank by constraint
  // propagation over columns. Returns { digits:{A:3,...}, unique:true|false }.
  //
  // Model: each blank cell has a domain (0..9). Visible cells are fixed. For each
  // column we enforce:  sum(addend digits in col) + carryIn = totalDigit + 10*carryOut
  // with carryOut in 0..nAdd. We also enforce no-leading-zero on any number whose
  // leading cell is a blank (we don't blank leading cells, so this is moot, but
  // kept for safety). We iterate column constraints to a fixpoint, pruning each
  // blank's domain to values consistent with SOME assignment of the other blanks
  // and carries in that column. A blank is forced when its domain collapses to 1.
  //
  // To certify UNIQUENESS rigorously (not just via local propagation), after
  // propagation we do an exhaustive consistency count via backtracking search
  // over the remaining blank domains, short-circuiting at 2 solutions. unique iff
  // exactly one full assignment satisfies every column.
  function solve(qtn) {
    var w = qtn.width, nAdd = qtn.addends.length;

    // Index blanks by a stable key; build a working grid of "known or null".
    // grid.addends[r][c], grid.total[c]: a digit (0..9) if visible, or {b:idx}
    // reference to blank `idx` if blanked.
    var blanks = qtn.blanks;               // [{label,kind,row,col}]
    var nB = blanks.length;
    var domains = [];
    var i, c, r;
    for (i = 0; i < nB; i++) {
      // interior cells: 0..9 (leading cells are never blanked, so 0 allowed)
      domains.push([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
    }
    // map cell -> blank index
    function blankAt(kind, row, col) {
      for (var k = 0; k < nB; k++) {
        var b = blanks[k];
        if (b.kind === kind && b.col === col && (kind === 'total' || b.row === row)) { return k; }
      }
      return -1;
    }

    var aBlank = [];   // aBlank[r][c] = blank idx or -1
    for (r = 0; r < nAdd; r++) { aBlank.push([]); for (c = 0; c < w; c++) { aBlank[r].push(blankAt('addend', r, c)); } }
    var tBlank = [];   // tBlank[c] = blank idx or -1
    for (c = 0; c < w; c++) { tBlank.push(blankAt('total', 0, c)); }

    // value getters that read an assignment (`asg` = array of chosen digits per
    // blank, or null = use domain). For propagation we test column feasibility.
    function addendVal(r, c, asg, trial) {
      var bi = aBlank[r][c];
      if (bi === -1) { return qtn.addends[r][c]; }
      if (asg && asg[bi] != null) { return asg[bi]; }
      return trial[bi];
    }
    function totalVal(c, asg, trial) {
      var bi = tBlank[c];
      if (bi === -1) { return qtn.total[c]; }
      if (asg && asg[bi] != null) { return asg[bi]; }
      return trial[bi];
    }

    // ---- exhaustive solution count over blank domains (columns as constraints).
    // We search blanks in a fixed order, and at each leaf verify all columns.
    // To keep it fast, we verify columns right-to-left propagating carry; with at
    // most 4 blanks each 0..9 this is <=10^4 leaves worst case — trivial.
    var solutions = [];
    var order = []; for (i = 0; i < nB; i++) { order.push(i); }

    function columnsConsistent(trial) {
      // process columns right (w-1) -> left (0), carry starts 0
      var carry = 0;
      for (var col = w - 1; col >= 0; col--) {
        var s = carry, rr;
        for (rr = 0; rr < nAdd; rr++) { s += addendVal(rr, col, null, trial); }
        var td = totalVal(col, null, trial);
        if (s % 10 !== td) { return false; }
        carry = Math.floor(s / 10);
      }
      return carry === 0;   // no overflow past the leftmost column
    }

    function search(k, trial) {
      if (solutions.length >= 2) { return; }     // short-circuit: 2 is enough
      if (k === nB) {
        if (columnsConsistent(trial)) { solutions.push(trial.slice()); }
        return;
      }
      var bi = order[k], dom = domains[bi];
      for (var di = 0; di < dom.length; di++) {
        trial[bi] = dom[di];
        search(k + 1, trial);
        if (solutions.length >= 2) { return; }
      }
    }

    var trial0 = new Array(nB);
    search(0, trial0);

    if (solutions.length !== 1) {
      return { digits: null, unique: false, count: solutions.length };
    }
    var sol = solutions[0], digits = {};
    for (i = 0; i < nB; i++) { digits[blanks[i].label] = sol[i]; }
    return { digits: digits, unique: true, count: 1 };
  }

  // ---- word-first blank placement ------------------------------------------
  // Try to realise `word` as the reveal: its letters give the required blank
  // digits (in label order A,B,C…). Build a sum, pick a placement of blanks whose
  // hidden digits equal exactly the word's digits, and accept iff solve() proves
  // uniqueness. Returns a full qtn/ans or null.
  function placeWord(word, cfg, year) {
    var wantDigits = [];
    for (var i = 0; i < word.length; i++) { wantDigits.push(DIGIT_OF[word.charAt(i)]); }
    var nB = wantDigits.length;

    for (var attempt = 0; attempt < 120; attempt++) {
      var width = ri(cfg.widthMin, cfg.widthMax);
      if (width < 2) { width = 2; }
      var sum = buildSum(cfg.nAdd, width);
      if (!sum) { continue; }

      // candidate cells whose CURRENT true digit equals the wanted digit for each
      // label, position by position. We need to find nB cells (distinct) such
      // that cell_k's true digit == wantDigits[k]. Group blankable cells by digit.
      var cells = blankableCells(sum, cfg.totalBlankOk);
      var byDigit = {};
      for (var ci = 0; ci < cells.length; ci++) {
        var d = cellDigit(sum, cells[ci]);
        (byDigit[d] = byDigit[d] || []).push(cells[ci]);
      }
      // greedily assign each label a distinct cell of the required digit
      var used = {}, chosen = [], ok = true;
      for (var k = 0; k < nB; k++) {
        var poolAll = byDigit[wantDigits[k]] || [];
        var pool = shuffle(poolAll).filter(function (cell) { return !used[cellKey(cell)]; });
        if (!pool.length) { ok = false; break; }
        var cell = pool[0];
        used[cellKey(cell)] = true;
        chosen.push(cell);
      }
      if (!ok) { continue; }

      var qtnAns = assemble(sum, chosen, width);
      var res = solve(qtnAns.qtn);
      if (!res.unique) { continue; }
      // confirm the recovered digits equal the originals (and thus the word)
      var good = true;
      for (var b = 0; b < qtnAns.qtn.blanks.length; b++) {
        var lab = qtnAns.qtn.blanks[b].label;
        if (res.digits[lab] !== cellDigit(sum, chosen[b])) { good = false; break; }
      }
      if (!good) { continue; }
      qtnAns.ans.word = word;
      qtnAns.ans.real = true;
      return qtnAns;
    }
    return null;
  }

  function cellKey(cell) { return cell.kind + ':' + cell.row + ':' + cell.col; }

  // Assemble a qtn/ans pair from a sum and chosen blank cells (in label order).
  function assemble(sum, chosen, width) {
    var labels = [];
    for (var i = 0; i < chosen.length; i++) { labels.push(KEY[i]); } // A,B,C…
    var blanks = [];
    for (i = 0; i < chosen.length; i++) {
      blanks.push({ label: labels[i], kind: chosen[i].kind, row: chosen[i].row, col: chosen[i].col });
    }
    // Build display grids: addends/total with null where blanked.
    var blankSet = {};
    for (i = 0; i < chosen.length; i++) { blankSet[cellKey(chosen[i])] = labels[i]; }

    var addends = [], r, c;
    for (r = 0; r < sum.addends.length; r++) {
      var row = [];
      for (c = 0; c < width; c++) {
        var key = cellKey({ kind: 'addend', row: r, col: c });
        row.push(blankSet[key] !== undefined ? null : sum.addends[r][c]);
      }
      addends.push(row);
    }
    var total = [];
    for (c = 0; c < width; c++) {
      var tkey = cellKey({ kind: 'total', row: 0, col: c });
      total.push(blankSet[tkey] !== undefined ? null : sum.total[c]);
    }

    // letters + per-blank digit for the answer key
    var letters = [], digitsByLabel = {};
    for (i = 0; i < chosen.length; i++) {
      var dg = cellDigit(sum, chosen[i]);
      digitsByLabel[labels[i]] = dg;
      letters.push(KEY[dg]);
    }

    // full solved grids (all digits visible) for the answer key
    var fullAddends = [];
    for (r = 0; r < sum.addends.length; r++) { fullAddends.push(sum.addends[r].slice()); }

    return {
      qtn: {
        addends: addends, total: total, width: width, nAdd: sum.addends.length,
        blanks: blanks, key: KEY.slice()
      },
      ans: {
        word: letters.join(''), real: false, letters: letters, digits: digitsByLabel,
        fullAddends: fullAddends, fullTotal: sum.total.slice()
      }
    };
  }

  // ---- generate -------------------------------------------------------------
  // opts = { year, tier }  (tier: 'below'|'meeting'|'exceeding'). Returns
  // { qtn, ans }. Word-first: pick a target word of the tier's length and try to
  // place it; on repeated failure, fall back to blank-first (accept whatever
  // string the forced digits spell — may not be a real word).
  function generate(opts) {
    opts = opts || {};
    var year = Math.max(3, Math.min(6, (opts.year || 4) | 0));
    var tier = opts.tier || 'meeting';
    if (tier !== 'below' && tier !== 'meeting' && tier !== 'exceeding') { tier = 'meeting'; }

    for (var outer = 0; outer < 40; outer++) {
      var cfg = tierConfig(tier, year);
      var wordLen = cfg.blanks;
      // pick a word of length == blank count when a curated list exists; clamp to
      // available lengths (2..5).
      var L = Math.max(2, Math.min(5, wordLen));
      var pool = WORDS[L] || [];
      if (pool.length) {
        var word = pick(pool);
        var got = placeWord(word, cfg, year);
        if (got) { return got; }
      }
    }

    // ---- fallback: blank-first. Build any sum, blank random interior cells,
    // keep iff uniquely solvable; the reveal is whatever the digits spell. We try
    // the tier's full blank count first, then step DOWN to 1 so a tight grid
    // (e.g. Y3 exceeding, 3-digit total) can always emit *some* valid puzzle
    // rather than ever returning null — a single interior blank is always
    // uniquely recoverable. Real-word placement is preferred via placeWord above;
    // this guarantees liveness.
    for (var fb = 0; fb < 600; fb++) {
      var cfg2 = tierConfig(tier, year);
      var width = ri(cfg2.widthMin, cfg2.widthMax); if (width < 2) { width = 2; }
      var sum = buildSum(cfg2.nAdd, width);
      if (!sum) { continue; }
      var cells0 = blankableCells(sum, cfg2.totalBlankOk);
      var maxB = Math.min(cfg2.blanks, cells0.length, 5);
      for (var nB = maxB; nB >= 1; nB--) {
        var cells = shuffle(cells0);
        var chosen = cells.slice(0, nB);
        var qa = assemble(sum, chosen, width);
        var res = solve(qa.qtn);
        if (!res.unique) { continue; }
        var good = true;
        for (var b = 0; b < qa.qtn.blanks.length; b++) {
          if (res.digits[qa.qtn.blanks[b].label] !== cellDigit(sum, chosen[b])) { good = false; break; }
        }
        if (!good) { continue; }
        qa.ans.real = WORDS[qa.ans.letters.length] ? WORDS[qa.ans.letters.length].indexOf(qa.ans.word) !== -1 : false;
        return qa;
      }
    }
    return null;
  }

  if (typeof window !== 'undefined') {
    window.TP_DD = { generate: generate, solve: solve, KEY: KEY.slice(), WORDS: WORDS };
  }

  /* ---- DOM (browser only) ------------------------------------------------- */
  if (typeof document === 'undefined') { return; }

  var ACCENT = '#34507a';
  function $(id) { return document.getElementById(id); }

  var state = {
    year: 4,
    difficulty: 3,
    count: 6,
    tab: 'sheet',          // 'sheet' | 'answers'
    items: [],
    word: '', letters: [], digits: {}, labels: []
  };
  var els = {};

  function tierFromMeter(m) { return m <= 2 ? 'below' : m >= 4 ? 'exceeding' : 'meeting'; }

  function rebuild() {
    var yr = Math.max(3, Math.min(6, state.year | 0));
    var tier = tierFromMeter(state.difficulty);
    state.items = [];
    var seen = {}, attempts = 0, cap = state.count * 60 + 300;
    while (state.items.length < state.count && attempts < cap) {
      attempts++;
      var it = generate({ year: yr, tier: tier });
      if (!it) { continue; }
      var sig = sigOf(it);
      if (seen[sig]) { continue; }
      seen[sig] = true;
      state.items.push(it);
    }
    // pad if (extremely unlikely) we couldn't fill — never leave a blank grid
    while (state.items.length < state.count && state.items.length) {
      state.items.push(generate({ year: yr, tier: tier }) || state.items[0]);
    }
    render();
  }

  function sigOf(it) {
    var p = it.qtn, s = p.width + '|' + p.nAdd + '|';
    for (var r = 0; r < p.addends.length; r++) { s += p.addends[r].join('') + ','; }
    s += '=' + p.total.join('') + '|' + it.ans.word;
    return s;
  }

  function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

  // ---- one card: a column-addition sum rendered as a CSS grid of digit cells.
  // Visible digits are solid glyphs; blanks are outlined boxes with a small
  // superscript label. On the answer key the blank shows its digit + letter, and
  // carry ticks appear above each column.
  function cardHTML(item, idx, revealed) {
    var p = item.qtn, w = p.width, nAdd = p.nAdd;
    // compute carries for the answer key (right to left)
    var carries = new Array(w + 1).fill(0);
    if (revealed) {
      var carry = 0;
      for (var col = w - 1; col >= 0; col--) {
        var s = carry;
        for (var rr = 0; rr < nAdd; rr++) { s += item.ans.fullAddends[rr][col]; }
        carry = Math.floor(s / 10);
        carries[col] = carry; // carry INTO column col-1 (i.e. out of col)
      }
    }

    function cellSpan(val, label, isTotal) {
      if (val === null) {
        // a blank: outlined box. On the key, fill the digit.
        if (revealed) {
          var dg = item.ans.digits[label];
          return '<span class="dd-cell dd-blank dd-solved">' +
            '<span class="dd-bl-lab">' + label + '</span>' + dg + '</span>';
        }
        return '<span class="dd-cell dd-blank"><span class="dd-bl-lab">' + label + '</span></span>';
      }
      return '<span class="dd-cell">' + val + '</span>';
    }

    // map a (kind,row,col) to its blank label, if any
    function labelAt(kind, row, col) {
      for (var b = 0; b < p.blanks.length; b++) {
        var bl = p.blanks[b];
        if (bl.kind === kind && bl.col === col && (kind === 'total' || bl.row === row)) { return bl.label; }
      }
      return null;
    }

    var gridStyle = 'grid-template-columns: 18px repeat(' + w + ', 1fr);';
    var html = '<div class="dd-sum" style="' + gridStyle + '">';

    // carry-tick row (answer key only) — small ticks above the columns
    if (revealed) {
      html += '<span class="dd-op"></span>';
      for (var c = 0; c < w; c++) {
        // carry shown sits above column c if there's a carry INTO it (out of c+1)
        var cin = c < w - 1 ? carries[c + 1] : 0;
        html += '<span class="dd-carry">' + (cin ? '<small>' + cin + '</small>' : '') + '</span>';
      }
    }

    // addend rows
    for (var r = 0; r < nAdd; r++) {
      html += (r === nAdd - 1) ? '<span class="dd-op">+</span>' : '<span class="dd-op"></span>';
      for (c = 0; c < w; c++) {
        html += cellSpan(p.addends[r][c], labelAt('addend', r, c), false);
      }
    }
    // rule
    html += '<span class="dd-rule" style="grid-column: 1 / ' + (w + 2) + ';"></span>';
    // total row
    html += '<span class="dd-op"></span>';
    for (c = 0; c < w; c++) {
      html += cellSpan(p.total[c], labelAt('total', 0, c), true);
    }
    html += '</div>';

    return '<figure class="dd-card"><div class="dd-card-no">' + (idx + 1) + '</div>' + html + '</figure>';
  }

  // ---- cipher codebook strip (top of sheet) --------------------------------
  function codebookHTML() {
    var html = '<div class="dd-codebook"><span class="dd-cb-title">Detective&rsquo;s Codebook</span><div class="dd-cb-keys">';
    for (var d = 0; d <= 9; d++) {
      html += '<span class="dd-cb-key"><b>' + d + '</b><i>&rarr;</i><u>' + KEY[d] + '</u></span>';
    }
    html += '</div></div>';
    return html;
  }

  // ---- reveal footer (letter slots A,B,C… in order across the sheet) --------
  // Aggregates every card's blanks in card order, then A,B,C… within the card.
  function revealHTML(revealed) {
    // collect (cardIndex, label, letterIfRevealed) across all items
    var slots = [];
    state.items.forEach(function (it, ci) {
      it.qtn.blanks.forEach(function (bl) {
        slots.push({ ci: ci, label: bl.label, letter: it.ans.digits ? KEY[it.ans.digits[bl.label]] : '' });
      });
    });
    var html = '<div class="dd-reveal"><div class="dd-reveal-cap">Crack the digits, then read the secret word' +
      (state.items.length > 1 ? 's' : '') + ' below.</div><div class="dd-reveal-rows">';
    state.items.forEach(function (it, ci) {
      html += '<div class="dd-reveal-word">';
      html += '<span class="dd-rw-no">' + (ci + 1) + '</span>';
      it.qtn.blanks.forEach(function (bl) {
        var letter = revealed ? KEY[it.ans.digits[bl.label]] : '';
        html += '<span class="dd-slot"><span class="dd-slot-lab">' + bl.label + '</span>' +
          '<span class="dd-slot-ltr">' + letter + '</span></span>';
      });
      if (revealed) { html += '<span class="dd-rw-word">' + esc(it.ans.word) + (it.ans.real ? '' : ' <i>(check!)</i>') + '</span>'; }
      html += '</div>';
    });
    html += '</div></div>';
    return html;
  }

  function render() {
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    var revealed = state.tab === 'answers';

    if (els.codebook) { els.codebook.innerHTML = codebookHTML(); }

    // cards rendered DIRECTLY into #dd-grid (no nested grid).
    var cols = state.count >= 9 ? 3 : 2;
    els.grid.style.setProperty('--dd-cols', cols);
    // Card min-height by layout so the page fills WITHOUT stranding cards: with
    // few rows (4-up = 2 rows) make cards tall; with more rows keep them compact.
    // align-content:space-between then distributes the (small) remaining slack
    // between rows instead of opening one big mid-page gap.
    var rows = Math.ceil(state.count / cols);
    var cardH = rows <= 2 ? 270 : 150;
    els.grid.style.setProperty('--dd-cardh', cardH + 'px');
    var html = '';
    state.items.forEach(function (it, i) { html += cardHTML(it, i, revealed); });
    els.grid.innerHTML = html;

    if (els.reveal) { els.reveal.innerHTML = revealHTML(revealed); }
  }

  // ---- toolbar wiring -------------------------------------------------------
  function setOnState(wrap, attr, val) {
    Array.prototype.forEach.call(wrap.querySelectorAll('[' + attr + ']'), function (b) {
      b.classList.toggle('chip-on', b.getAttribute(attr) === String(val));
    });
  }

  function moveThumb(thumb, wrap, selector, index) {
    if (!thumb || !wrap) { return; }
    var active = wrap.querySelectorAll(selector)[index];
    if (active) { thumb.style.left = active.offsetLeft + 'px'; thumb.style.width = active.offsetWidth + 'px'; }
  }

  function setDiff(d) {
    state.difficulty = Math.max(1, Math.min(5, d));
    moveThumb(els.diffThumb, $('dd-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }

  function setTab(tab) {
    state.tab = tab;
    moveThumb(els.tabThumb, $('dd-tabs'), '[data-tab]', tab === 'answers' ? 1 : 0);
    render();
  }

  function regen() {
    if (els.spin) { els.spin.style.transform = 'rotate(360deg)'; setTimeout(function () { els.spin.style.transform = 'rotate(0deg)'; }, 500); }
    rebuild();
  }

  function showToast(msg) {
    if (!els.toast) { return; }
    els.toast.textContent = msg;
    els.toast.classList.remove('hide');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function () { els.toast.classList.add('hide'); }, 1900);
  }

  function onSave() {
    var form = new FormData();
    form.append('title', 'Digit Detectives');
    form.append('activity', 'digit-detectives');
    form.append('config', JSON.stringify({
      year: state.year, difficulty: state.difficulty, count: state.count, tier: tierFromMeter(state.difficulty)
    }));
    fetch(window.TP_SAVE_URL || '/account/save', {
      method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: form, credentials: 'same-origin', redirect: 'follow'
    }).then(function (res) {
      if (res.status === 401 || res.status === 403 || (res.redirected && /\/login/.test(res.url))) {
        window.location.href = window.TP_LOGIN_URL || '/login'; return;
      }
      showToast(res.ok ? '✓ Saved' : 'Could not save');
    }).catch(function () { showToast('Could not save'); });
  }

  function init() {
    els.grid = $('dd-grid');
    els.codebook = $('dd-codebook');
    els.reveal = $('dd-reveal');
    els.diffThumb = $('dd-difficulty') ? $('dd-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('dd-diff-label');
    els.eyebrowDiff = $('dd-eyebrow-diff');
    els.tabThumb = $('dd-tabs') ? $('dd-tabs').querySelector('.seg-thumb') : null;
    els.spin = $('dd-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('dd-toast');

    var yearEl = $('dd-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var y0 = window.TP_wireYears ? window.TP_wireYears('dd', function (y) { state.year = y; rebuild(); }) : null;
    if (y0) { state.year = y0; }

    var cnt = $('dd-count');
    if (cnt) {
      Array.prototype.forEach.call(cnt.querySelectorAll('[data-count]'), function (b) {
        b.addEventListener('click', function () { state.count = Number(b.getAttribute('data-count')); setOnState(cnt, 'data-count', state.count); rebuild(); });
      });
      setOnState(cnt, 'data-count', state.count);
    }

    Array.prototype.forEach.call($('dd-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('dd-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('dd-save').addEventListener('click', onSave);
    $('dd-print').addEventListener('click', function () { window.print(); });
    $('dd-regen').addEventListener('click', regen);

    setDiff(state.difficulty);
    setTab('sheet');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
