/* =============================================================================
 * admin-studio.js — Resource Studio (vanilla JS port of the DCLogic mockup)
 * -----------------------------------------------------------------------------
 * 3-pane authoring tool:
 *   LEFT   metadata form + live validation checklist
 *   CENTER dark code editor (syntax highlight overlay + gutter + textarea)
 *   RIGHT  live preview — runs generate(difficulty) -> { question, answer }
 *
 * The editor compiles the user's code with `new Function`, exposing the helpers
 * ri / pick / fmt, then runs 8 samples at the selected difficulty.
 * ========================================================================== */
(function () {
  'use strict';

  var STARTER = [
    '// Write a generator for one curriculum objective.',
    '// It must return { question, answer } for a difficulty (1-5).',
    '// Helpers available: ri(min,max), pick(array), fmt(number)',
    '',
    'function generate(difficulty) {',
    '  // scale the numbers with difficulty',
    '  const max = [20, 99, 999, 9999, 99999][difficulty - 1];',
    '  const a = ri(2, max);',
    '  const b = ri(2, max);',
    '',
    '  return {',
    "    question: fmt(a) + ' + ' + fmt(b) + ' =',",
    '    answer: fmt(a + b)',
    '  };',
    '}'
  ].join('\n');

  // ---- state -------------------------------------------------------------
  var state = { diff: 3, valid: false };
  var ta, code, gutter, hl;
  var runTimer = null, nameTimer = null, toastTimer = null;
  var cfg = window.TP_STUDIO || { draftUrl: '/admin/studio/draft', submitUrl: '/admin/studio/submit', hasSeed: false };

  // ---- helpers exposed to user code --------------------------------------
  function makeHelpers() {
    var ri = function (a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; };
    var pick = function (a) { return a[Math.floor(Math.random() * a.length)]; };
    var fmt = function (n) { return (typeof n === 'number') ? n.toLocaleString('en-GB') : n; };
    return { ri: ri, pick: pick, fmt: fmt };
  }

  // ---- syntax highlighting ----------------------------------------------
  function esc(s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  var KW = ['function', 'return', 'const', 'let', 'var', 'if', 'else', 'for', 'while', 'of', 'in', 'new', 'true', 'false', 'null', 'undefined', 'switch', 'case', 'break', 'do', 'this', 'Math'];
  var FNS = ['generate', 'ri', 'pick', 'fmt', 'round', 'floor', 'ceil', 'abs', 'random', 'min', 'max', 'pow', 'map', 'join', 'push', 'toFixed'];

  function highlightCode(src) {
    var out = '';
    var re = /(\/\/[^\n]*)|(`(?:\\.|[^`\\])*`|'(?:\\.|[^'\\])*'|"(?:\\.|[^"\\])*")|(\b\d+(?:\.\d+)?\b)|([A-Za-z_$][A-Za-z0-9_$]*)|([+\-*/%=<>!&|?:.])/g;
    var last = 0, m;
    while ((m = re.exec(src))) {
      out += esc(src.slice(last, m.index));
      last = re.lastIndex;
      if (m[1]) out += '<span class="tok-com">' + esc(m[1]) + '</span>';
      else if (m[2]) out += '<span class="tok-str">' + esc(m[2]) + '</span>';
      else if (m[3]) out += '<span class="tok-num">' + esc(m[3]) + '</span>';
      else if (m[4]) {
        var w = m[4];
        if (KW.indexOf(w) !== -1) out += '<span class="tok-kw">' + w + '</span>';
        else if (FNS.indexOf(w) !== -1) out += '<span class="tok-fn">' + w + '</span>';
        else out += esc(w);
      } else if (m[5]) out += '<span class="tok-op">' + esc(m[5]) + '</span>';
    }
    out += esc(src.slice(last));
    return out;
  }

  function highlight() {
    var src = ta.value;
    code.innerHTML = highlightCode(src) + '\n';
    var n = src.split('\n').length;
    var g = '';
    for (var i = 1; i <= n; i++) g += i + '\n';
    gutter.textContent = g;
    syncScroll();
  }

  function syncScroll() {
    hl.scrollTop = ta.scrollTop;
    hl.scrollLeft = ta.scrollLeft;
    gutter.scrollTop = ta.scrollTop;
  }

  function onKey(e) {
    if (e.key === 'Tab') {
      e.preventDefault();
      var s = ta.selectionStart, end = ta.selectionEnd;
      ta.value = ta.value.slice(0, s) + '  ' + ta.value.slice(end);
      ta.selectionStart = ta.selectionEnd = s + 2;
      highlight();
      scheduleRun();
    }
  }

  function scheduleRun() {
    clearTimeout(runTimer);
    runTimer = setTimeout(run, 350);
  }

  // ---- run + validate ----------------------------------------------------
  function nameValue() {
    var el = document.getElementById('f-name');
    return el ? el.value.trim() : '';
  }

  function run() {
    var src = ta ? ta.value : '';
    var dot = document.getElementById('run-dot');
    var status = document.getElementById('run-status');
    var detail = document.getElementById('run-detail');
    var list = document.getElementById('sample-list');
    var checksEl = document.getElementById('meta-checks');
    var h = makeHelpers();

    var fn = null, err = null;
    try {
      // Compile the user's generate() in a sandbox-ish scope with the helpers.
      fn = new Function('ri', 'pick', 'fmt', src + '\n; return generate;')(h.ri, h.pick, h.fmt);
      if (typeof fn !== 'function') throw new Error('No function called generate() found.');
    } catch (e) {
      err = e;
    }

    var samples = [], runErr = err, shapeOk = true;
    if (!err) {
      try {
        for (var i = 0; i < 8; i++) {
          var r = fn(state.diff);
          if (!r || typeof r !== 'object' || !('question' in r) || !('answer' in r)) { shapeOk = false; break; }
          samples.push(r);
        }
      } catch (e2) { runErr = e2; }
    }

    var ok = !runErr && shapeOk;
    if (dot) dot.style.background = runErr ? '#c0563a' : (!shapeOk ? '#b8742e' : '#1f8a4d');
    if (status) {
      status.textContent = runErr ? 'Error' : (!shapeOk ? 'Wrong shape' : 'Generator works');
      status.style.color = runErr ? '#c0563a' : (!shapeOk ? '#b8742e' : '#1f8a4d');
    }
    if (detail) {
      detail.textContent = runErr ? (runErr.message || String(runErr))
        : (!shapeOk ? 'generate() must return an object with { question, answer }.'
          : 'Returned ' + samples.length + ' valid questions at difficulty ' + state.diff + '.');
    }

    if (list) {
      if (runErr || !shapeOk) {
        list.innerHTML = '<li style="font-family:JetBrains Mono,monospace; font-size:12px; color:#c0563a; line-height:1.5; padding:6px 0;">'
          + esc((runErr && (runErr.message || String(runErr))) || 'Invalid return shape') + '</li>';
      } else {
        list.innerHTML = samples.map(function (s, i) {
          return '<li style="display:flex; align-items:baseline; gap:9px; padding:6px 0; font-size:14px; color:#1c2420; border-bottom:1px solid rgba(28,36,32,.05);">'
            + '<span style="font-weight:700; color:#1f8a4d; min-width:22px; font-variant-numeric:tabular-nums;">' + (i + 1) + ')</span>'
            + '<span style="flex:1; font-variant-numeric:tabular-nums;">' + esc(String(s.question)) + '</span>'
            + '<span style="font-weight:800; color:#1f8a4d; font-variant-numeric:tabular-nums;">' + esc(String(s.answer)) + '</span></li>';
        }).join('');
      }
    }

    state.valid = ok;

    if (checksEl) {
      var named = nameValue().length > 2;
      var checks = [
        { ok: !err, label: 'Code compiles' },
        { ok: ok, label: 'Returns { question, answer }' },
        { ok: ok, label: 'Runs at difficulty 1–5' },
        { ok: named, label: 'Resource is named' }
      ];
      checksEl.innerHTML = checks.map(function (c) {
        return '<div style="display:flex; align-items:center; gap:8px; color:' + (c.ok ? '#3a423b' : '#a8a294') + ';">'
          + '<span style="width:16px; height:16px; border-radius:5px; display:inline-flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; color:#fff; background:'
          + (c.ok ? '#1f8a4d' : '#c8ccc2') + ';">' + (c.ok ? '✓' : '') + '</span>' + c.label + '</div>';
      }).join('');
    }
  }

  // ---- difficulty buttons -----------------------------------------------
  function buildDiffRow() {
    var row = document.getElementById('diff-row');
    if (!row) return;
    row.innerHTML = '';
    for (var d = 1; d <= 5; d++) {
      (function (d) {
        var b = document.createElement('button');
        b.type = 'button';
        b.textContent = d;
        b.style.cssText = 'width:30px; height:30px; border-radius:8px; cursor:pointer; font-family:Bricolage Grotesque,sans-serif; font-weight:700; font-size:13px;'
          + (d === state.diff ? 'border:2px solid #1f8a4d; background:#fff; color:#1c2420;' : 'border:1px solid rgba(28,36,32,.16); background:#fff; color:#7c8278;');
        b.onclick = function () { state.diff = d; buildDiffRow(); run(); };
        row.appendChild(b);
      })(d);
    }
  }

  // ---- type chips --------------------------------------------------------
  function wireTypeChips() {
    var chips = document.querySelectorAll('.st-chip');
    var hidden = document.getElementById('f-type');
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach(function (c) { c.classList.remove('on'); });
        chip.classList.add('on');
        if (hidden) hidden.value = chip.getAttribute('data-type') || 'generator';
      });
    });
  }

  // ---- toast -------------------------------------------------------------
  function toast(msg) {
    var el = document.getElementById('st-toast');
    var m = document.getElementById('st-toast-msg');
    if (!el) return;
    if (m) m.textContent = msg;
    el.classList.remove('hide');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { el.classList.add('hide'); }, 2400);
  }

  // ---- collect form ------------------------------------------------------
  function collectForm() {
    var v = function (id) { var e = document.getElementById(id); return e ? e.value : ''; };
    return {
      name: nameValue(),
      type: v('f-type') || 'generator',
      subject: v('f-subject'),
      year: v('f-year'),
      strand: v('f-strand'),
      objective: v('f-objective').trim(),
      generator_code: ta ? ta.value : ''
    };
  }

  function postForm(url, payload, onOk, onErr) {
    var body = new URLSearchParams();
    Object.keys(payload).forEach(function (k) { body.append(k, payload[k]); });
    fetch(url, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString(),
      credentials: 'same-origin'
    }).then(function (res) {
      return res.json().then(function (data) { return { res: res, data: data }; });
    }).then(function (r) {
      if (r.res.ok && r.data && r.data.ok) onOk(r.data);
      else onErr(r.data);
    }).catch(function () { onErr(null); });
  }

  function onSaveDraft() {
    postForm(cfg.draftUrl, collectForm(),
      function () { toast('Draft saved'); },
      function () { toast('Could not save draft'); });
  }

  function onSubmit() {
    if (!state.valid) { toast('Fix the errors before submitting'); return; }
    var form = collectForm();
    if (form.name.length < 1) { toast('Give your resource a name first'); return; }
    if (!form.generator_code.trim()) { toast('Generator code cannot be empty'); return; }
    postForm(cfg.submitUrl, form,
      function () { toast('Submitted for review — an admin will approve it'); },
      function (data) {
        var msg = (data && data.errors && data.errors.length) ? data.errors.join(' ') : 'Submission failed';
        toast(msg);
      });
  }

  // ---- init --------------------------------------------------------------
  function init() {
    ta = document.getElementById('ed-ta');
    code = document.getElementById('ed-code');
    gutter = document.getElementById('ed-gutter');
    hl = document.getElementById('ed-hl');
    if (!ta) return;

    // Seed from a reviewed submission if present, else the starter template.
    var seedEl = document.getElementById('seed-code');
    ta.value = (cfg.hasSeed && seedEl) ? seedEl.textContent : STARTER;

    ta.addEventListener('input', function () { highlight(); scheduleRun(); });
    ta.addEventListener('scroll', syncScroll);
    ta.addEventListener('keydown', onKey);

    var nameEl = document.getElementById('f-name');
    if (nameEl) {
      nameEl.addEventListener('input', function () { clearTimeout(nameTimer); nameTimer = setTimeout(run, 50); });
    }

    wireTypeChips();
    buildDiffRow();

    var resetBtn = document.getElementById('ed-reset');
    if (resetBtn) resetBtn.addEventListener('click', function () { ta.value = STARTER; highlight(); run(); });
    var runBtn = document.getElementById('ed-run');
    if (runBtn) runBtn.addEventListener('click', run);
    var rerollBtn = document.getElementById('ed-reroll');
    if (rerollBtn) rerollBtn.addEventListener('click', run);
    var saveBtn = document.getElementById('st-save');
    if (saveBtn) saveBtn.addEventListener('click', onSaveDraft);
    var submitBtn = document.getElementById('st-submit');
    if (submitBtn) submitBtn.addEventListener('click', onSubmit);

    highlight();
    run();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
