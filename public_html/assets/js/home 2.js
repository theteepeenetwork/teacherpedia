/* =============================================================================
 * home.js — hero worksheet "regenerate" preview
 * Port of Home.dc.html's DCLogic hero behaviour to vanilla JS.
 *
 * Builds ~6 random KS2 mental-maths questions into the hero worksheet card,
 * and re-rolls them (with a spinning icon) when "Regenerate" is clicked.
 * ========================================================================== */
(function () {
  'use strict';

  function init() {
    var list  = document.getElementById('heroQs');
    var btn   = document.getElementById('heroRegen');
    var spin  = document.getElementById('heroSpin');
    if (!list) { return; }

    function ri(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a; }
    function fmt(n) { return Number(n).toLocaleString('en-GB'); }

    // Mirrors Home.dc.html heroSet(): six fresh arithmetic questions.
    function heroSet() {
      var a = ri(1000, 9000), b = ri(1000, 9000);
      var c = ri(100, 900), d = ri(11, 49);
      var e = ri(2, 9), f = e * ri(20, 90);
      var g = ri(11, 40), h = ri(11, 40);
      var dens = [2, 4, 5], den = dens[ri(0, 2)], num = ri(1, den - 1), amt = den * ri(10, 40);
      var ps = [10, 20, 25, 50], p = ps[ri(0, 3)], pa = 20 * ri(2, 12);
      return [
        { n: '1)', t: fmt(a) + ' + ' + fmt(b) + ' =' },
        { n: '2)', t: fmt(c) + ' × ' + d + ' =' },
        { n: '3)', t: fmt(f) + ' ÷ ' + e + ' =' },
        { n: '4)', t: g + ' × ' + h + ' =' },
        { n: '5)', t: num + '/' + den + ' of ' + fmt(amt) + ' =' },
        { n: '6)', t: p + '% of ' + fmt(pa) + ' =' }
      ];
    }

    function render() {
      var qs = heroSet();
      list.innerHTML = '';
      qs.forEach(function (q) {
        var li = document.createElement('li');
        li.style.cssText = 'display:flex; gap:9px; align-items:baseline; font-size:14px; color:#26302a; font-variant-numeric:tabular-nums;';

        var num = document.createElement('span');
        num.style.cssText = 'font-weight:700; color:#1f8a4d; min-width:18px;';
        num.textContent = q.n;

        var txt = document.createElement('span');
        txt.textContent = q.t;

        var rule = document.createElement('span');
        rule.style.cssText = 'flex:1; border-bottom:1.5px dotted #cfc9bc; transform:translateY(-3px); margin-left:2px;';

        li.appendChild(num);
        li.appendChild(txt);
        li.appendChild(rule);
        list.appendChild(li);
      });
    }

    var spinning = false;
    function regen() {
      if (spin && !spinning) {
        spinning = true;
        spin.style.transform = 'rotate(360deg)';
        setTimeout(function () {
          spin.style.transition = 'none';
          spin.style.transform = 'none';
          // re-enable the smooth transition on the next frame
          requestAnimationFrame(function () {
            spin.style.transition = 'transform .5s ease';
            spinning = false;
          });
        }, 500);
      }
      render();
    }

    render();
    if (btn) { btn.addEventListener('click', regen); }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
