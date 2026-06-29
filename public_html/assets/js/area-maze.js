/* =============================================================================
 * area-maze.js — Area Maze (Menseki Meiro).
 * -----------------------------------------------------------------------------
 * A large rectangle is recursively GUILLOTINE-split (each cut slices one
 * rectangle into two with a single edge-to-edge line, recursing) into N leaf
 * rectangles with INTEGER sides. The figure is drawn SCHEMATIC (rectilinear,
 * internal divisions shown) and labelled "Not drawn to scale" — pieces are NOT
 * proportional, so children cannot measure with a ruler; they must reason.
 *
 * Clues: some pieces show their AREA (cm²) and some segments show their LENGTH
 * (cm). Exactly ONE region/segment is the target '?'. The solver chains
 * area = length x width deductions across shared edges:
 *   - a piece with a known area and one side gives the other side (area / side);
 *   - a piece with both sides gives its area;
 *   - guillotine cuts mean collinear segments sum (parent edge = its two parts)
 *     and the two children of a cut share the perpendicular dimension.
 *
 * Difficulty is reached by GREEDY CLUE MINIMISATION: start from a fully-labelled
 * figure and remove clues at random while the target is still uniquely forced by
 * whole-number propagation, down to the year+meter budget.
 *
 * solve() is BOTH the uniqueness oracle (used by minimisation) AND the source of
 * the answer-key deduction chain, so "shown answer" and "forced answer" can
 * never disagree.
 *
 * Year band is the curriculum ceiling (Y4-6 only; area-as-multiplication is Y4+);
 * the 1-5 meter tunes WITHIN the year, never past it. Y4 never asks a missing
 * length. Engine clamps year to 4..6 defensively.
 *
 * Determinism: a seedable PRNG (mulberry32) means a saved {year,difficulty,
 * count,seed} re-prints the IDENTICAL sheet.
 *
 * Pure engine exposed as window.TP_AM for Node tests; DOM wiring runs in-browser.
 * Self-contained per the engine rules. See dev/RESOURCE_WORKFLOW.md.
 * ========================================================================== */
(function () {
  'use strict';

  // ---- seedable PRNG (mulberry32) -----------------------------------------
  function mulberry32(seed) {
    var a = seed >>> 0;
    return function () {
      a |= 0; a = (a + 0x6D2B79F5) | 0;
      var t = Math.imul(a ^ (a >>> 15), 1 | a);
      t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
      return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
  }
  function makeRng(seed) { return mulberry32((seed >>> 0) || 1); }

  function ri(rng, a, b) { return a + Math.floor(rng() * (b - a + 1)); }
  function pick(rng, arr) { return arr[Math.floor(rng() * arr.length)]; }
  function shuffle(rng, arr) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(rng() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; }
    return a;
  }

  // ---- year band config ----------------------------------------------------
  // The 1-5 meter `eff` tunes WITHIN the year row; never past the year ceiling.
  // Returns { leaves, chain, chainLo, chainHi, sideMax, allowLength }.
  //   leaves      : target leaf count for the guillotine tree
  //   chain       : target number of deduction steps to the target
  //   chainLo/Hi  : the ACCEPTANCE window for the chain length (tight, so the meter
  //                 genuinely separates d1 from d5 instead of both landing in a
  //                 wide overlapping band)
  //   sideMax     : max integer side length on the outer rectangle's cuts
  //   allowLength : may the target be a missing LENGTH? (Y4 = never)
  //
  // Each year defines its [chainMin..chainMax] curriculum window; the meter LERPs
  // the target across it (so eff 1 -> chainMin, eff 5 -> chainMax) and the leaf
  // count / magnitude scale alongside. The acceptance window is the target ± 0 at
  // the extremes and ± 1 only in the middle, so a low meter can never produce the
  // same chain length as a high meter within the same year.
  function bandFor(year, eff) {
    year = Math.max(4, Math.min(6, year | 0));
    eff = Math.max(1, Math.min(5, eff | 0));
    var t = (eff - 1) / 4;   // 0 at d1 .. 1 at d5
    function lerp(a, b) { return Math.round(a + (b - a) * t); }

    // Per-year envelopes (chain min/max are the curriculum bounds for the year).
    var cfg = (year === 4)
      // Y4: 3-4 pieces, 1-2 step chains, small magnitudes, AREA target only.
      ? { leafLo: 3, leafHi: 4, chainMin: 1, chainMax: 2, sideLo: 8, sideHi: 12, allowLength: false }
      : (year === 5)
        // Y5: 4-5 pieces, 2-3 steps, larger magnitudes, area OR length.
        ? { leafLo: 4, leafHi: 5, chainMin: 2, chainMax: 3, sideLo: 12, sideHi: 16, allowLength: true }
        // Y6: 5-6 pieces, 3-4 steps, largest magnitudes, mixed targets.
        : { leafLo: 5, leafHi: 6, chainMin: 3, chainMax: 4, sideLo: 14, sideHi: 18, allowLength: true };

    var chain = lerp(cfg.chainMin, cfg.chainMax);
    // PIN the acceptance window to the exact lerped target so each meter step forces
    // a specific chain length — that is what makes d1 strictly easier than d5 within
    // a year (a ±1 window would re-overlap the meter into a single band, the no-op
    // the reviewer flagged). The 200-attempt figure search reliably hits an exact
    // length. (Y4 area targets are floored at 2 by anti-degeneracy, so Y4 tunes via
    // leaf count + magnitude instead — see leaves/sideMax, which also lerp.)
    return {
      leaves: lerp(cfg.leafLo, cfg.leafHi),
      chain: chain,
      chainLo: chain,
      chainHi: chain,
      sideMax: lerp(cfg.sideLo, cfg.sideHi),
      allowLength: cfg.allowLength
    };
  }

  // ---- guillotine tree -----------------------------------------------------
  // A node tiles a rectangle. Leaves carry {x,y,w,h}. Internal nodes carry a
  // cut: orientation 'V' (vertical line, splits width) or 'H' (horizontal line,
  // splits height), a position, and two children. Coordinates are integers on a
  // grid; sibling children of a cut share the full perpendicular dimension and a
  // parent side equals the sum of its two child parts along the cut axis.
  function buildTree(rng, rect, leafTarget, sideMax) {
    var nextId = 0;
    var leaves = [];

    function leaf(r) {
      var nd = { id: nextId++, leaf: true, x: r.x, y: r.y, w: r.w, h: r.h };
      leaves.push(nd);
      return nd;
    }

    // Recursive split. `budget` = how many leaves this subtree should aim for.
    function split(r, budget) {
      if (budget <= 1 || (r.w < 4 && r.h < 4)) { return leaf(r); }

      // Choose orientation: cut perpendicular to the longer side (so pieces stay
      // shapely), with jitter. 'V' splits width (needs w >= 4); 'H' splits height.
      var canV = r.w >= 4, canH = r.h >= 4;
      var orient;
      if (canV && canH) {
        var preferV = r.w >= r.h;
        orient = (rng() < 0.78) ? (preferV ? 'V' : 'H') : (preferV ? 'H' : 'V');
      } else if (canV) { orient = 'V'; }
      else if (canH) { orient = 'H'; }
      else { return leaf(r); }

      var dim = orient === 'V' ? r.w : r.h;
      // Cut position p in [2, dim-2] so neither part is a 1-wide sliver.
      var p = ri(rng, 2, dim - 2);

      // Split the leaf budget between the two parts, biased by part size, with at
      // least 1 each. The larger part may carry more leaves.
      var leftBudget = Math.max(1, Math.min(budget - 1, ri(rng, 1, budget - 1)));
      var rightBudget = budget - leftBudget;

      var a, b;
      if (orient === 'V') {
        a = { x: r.x, y: r.y, w: p, h: r.h };
        b = { x: r.x + p, y: r.y, w: r.w - p, h: r.h };
      } else {
        a = { x: r.x, y: r.y, w: r.w, h: p };
        b = { x: r.x, y: r.y + p, w: r.w, h: r.h - p };
      }
      return {
        id: nextId++, leaf: false, orient: orient,
        x: r.x, y: r.y, w: r.w, h: r.h,
        a: split(a, leftBudget), b: split(b, rightBudget)
      };
    }

    var root = split(rect, leafTarget);
    return { root: root, leaves: leaves };
  }

  // ---- collect the "facts" of a tree --------------------------------------
  // Every node (leaf or internal) is a rectangle with a ground-truth width and
  // height. We expose two clue universes:
  //   - rect clues: each LEAF's area (= w*h).
  //   - seg clues : the LENGTH of each distinct primitive boundary/grid segment
  //                 that bounds the figure or a cut. We model lengths abstractly
  //                 as the WIDTHS and HEIGHTS of nodes (a node's width is the
  //                 length of its top/bottom edge; its height the length of its
  //                 left/right edge). For propagation we only need the variable
  //                 set {node.w, node.h} and the relations among them.
  //
  // VARIABLES (the unknowns the solver works in):
  //   For every node n:  W[n] (its width)  and  H[n] (its height).
  //   For every leaf  l:  A[l] (its area).
  //
  // RELATIONS:
  //   R1/R2  area: A[l] = W[l] * H[l]      (leaf only)
  //   R3 (sum, along the cut axis):
  //        orient V: W[parent] = W[a] + W[b]
  //        orient H: H[parent] = H[a] + H[b]
  //   R4 (shared perpendicular dimension):
  //        orient V: H[parent] = H[a] = H[b]
  //        orient H: W[parent] = W[a] = W[b]
  //
  // A clue fixes one variable. The target removes one. solve() propagates.
  function nodesOf(tree) {
    var out = [];
    (function walk(n) { out.push(n); if (!n.leaf) { walk(n.a); walk(n.b); } })(tree.root);
    return out;
  }

  // ---- solver / uniqueness oracle / chain builder --------------------------
  // clues: a set of fixed variables. Each is { kind:'W'|'H'|'A', id }.
  // target: { kind:'W'|'H'|'A', id } — the one we want forced.
  // truth: a map of true values, keyed `kind+':'+id`, used only to validate
  //        consistency (the propagator derives values from relations, never from
  //        truth directly; truth lets us record sane chain text).
  //
  // Returns { value, chain } when the target is uniquely forced by whole-number
  // propagation, else null. chain is an ordered list of human steps, each:
  //   { type, text, result }  (text already formatted, result is the value).
  function solve(tree, clueList, target) {
    var nodes = nodesOf(tree);
    var byId = {};
    nodes.forEach(function (n) { byId[n.id] = n; });
    var labelMap = tree._labels || {};

    // value store + provenance: prov[key] = { result, step|null, deps:[keys] }
    var known = {};
    var prov = {};
    function key(kind, id) { return kind + ':' + id; }
    function has(k) { return known.hasOwnProperty(k); }
    function val(k) { return has(k) ? known[k] : null; }

    // Set a derived value once. Records the step and its dependency keys so the
    // chain can be back-traced to ONLY the steps the target depends on. Returns
    // true if newly set, false if already known (then asserts agreement).
    function derive(kind, id, v, step, deps) {
      var k = key(kind, id);
      if (has(k)) { return known[k] === v; }
      known[k] = v;
      prov[k] = { result: v, step: step, deps: deps || [] };
      return true;
    }

    // edge/area description for chain text — anchored to a leaf where possible so
    // the reader can find the edge on the figure.
    function pieceLabel(id) { return labelMap[id] || 'a region'; }
    function edgeLabel(id, kind) {
      var n = byId[id];
      // Leaves use "Piece X"; internal regions use their leaf-span label ("the
      // block A+B" / "the whole rectangle") so each edge in the chain names a
      // region the reader can outline on the figure (never two identical labels).
      var base = pieceLabel(id);
      if (n && n.id === tree.root.id) { base = 'the whole rectangle'; }
      return base + (kind === 'W' ? ' width' : ' height');
    }

    // seed clues (givens — no chain step, no deps).
    for (var i = 0; i < clueList.length; i++) {
      var c = clueList[i];
      var kk = key(c.kind, c.id);
      known[kk] = trueVal(byId[c.id], c.kind);
      prov[kk] = { result: known[kk], step: null, deps: [] };
    }

    // Propagate to fixpoint.
    var changed = true, guard = 0;
    while (changed && guard < 800) {
      changed = false; guard++;
      for (var ni = 0; ni < nodes.length; ni++) {
        var n = nodes[ni];
        if (n.leaf) {
          var wk = key('W', n.id), hk = key('H', n.id), ak = key('A', n.id);
          var aw = val(wk), ah = val(hk), aa = val(ak);
          // R1: area = w x h
          if (aa === null && aw !== null && ah !== null) {
            if (derive('A', n.id, aw * ah, {
              op: 'mul',
              text: pieceLabel(n.id) + ': area = ' + aw + ' × ' + ah + ' = ' + (aw * ah) + ' cm²',
              result: aw * ah
            }, [wk, hk])) { changed = true; }
          } else if (aw === null && ah !== null && aa !== null && ah !== 0 && aa % ah === 0) {
            // R2: width = area / height
            if (derive('W', n.id, aa / ah, {
              op: 'divArea',
              text: pieceLabel(n.id) + ': width = ' + aa + ' ÷ ' + ah + ' = ' + (aa / ah) + ' cm',
              result: aa / ah
            }, [ak, hk])) { changed = true; }
          } else if (ah === null && aw !== null && aa !== null && aw !== 0 && aa % aw === 0) {
            // R2: height = area / width
            if (derive('H', n.id, aa / aw, {
              op: 'divArea',
              text: pieceLabel(n.id) + ': height = ' + aa + ' ÷ ' + aw + ' = ' + (aa / aw) + ' cm',
              result: aa / aw
            }, [ak, wk])) { changed = true; }
          }
        } else {
          var sumKind = n.orient === 'V' ? 'W' : 'H';
          var eqKind = n.orient === 'V' ? 'H' : 'W';
          if (propSum(sumKind, n.id, n.a.id, n.b.id)) { changed = true; }
          if (propEq(eqKind, n.id, n.a.id)) { changed = true; }
          if (propEq(eqKind, n.id, n.b.id)) { changed = true; }
          if (propEq(eqKind, n.a.id, n.b.id)) { changed = true; }
        }
      }
    }

    // R3 collinear sum/difference along a cut: par = a + b (any two give third).
    function propSum(kind, pid, aid, bid) {
      var pk = key(kind, pid), ak = key(kind, aid), bk = key(kind, bid);
      var p = val(pk), a2 = val(ak), b2 = val(bk);
      if (p === null && a2 !== null && b2 !== null) {
        return derive(kind, pid, a2 + b2, {
          op: 'sumLen',
          text: edgeLabel(pid, kind) + ' = ' + a2 + ' + ' + b2 + ' = ' + (a2 + b2) + ' cm',
          result: a2 + b2
        }, [ak, bk]);
      }
      if (a2 === null && p !== null && b2 !== null && p - b2 >= 0) {
        return derive(kind, aid, p - b2, {
          op: 'diffLen',
          text: edgeLabel(aid, kind) + ' = ' + p + ' − ' + b2 + ' = ' + (p - b2) + ' cm',
          result: p - b2
        }, [pk, bk]);
      }
      if (b2 === null && p !== null && a2 !== null && p - a2 >= 0) {
        return derive(kind, bid, p - a2, {
          op: 'diffLen',
          text: edgeLabel(bid, kind) + ' = ' + p + ' − ' + a2 + ' = ' + (p - a2) + ' cm',
          result: p - a2
        }, [pk, ak]);
      }
      return false;
    }
    // R4 shared perpendicular dimension across a cut: equal edges.
    function propEq(kind, id1, id2) {
      var k1 = key(kind, id1), k2 = key(kind, id2);
      var v1 = val(k1), v2 = val(k2);
      if (v1 !== null && v2 === null) {
        return derive(kind, id2, v1, {
          eq: true, op: 'sharedEq',
          text: edgeLabel(id2, kind) + ' = ' + edgeLabel(id1, kind) + ' = ' + v1 + ' cm (shared edge)',
          result: v1
        }, [k1]);
      }
      if (v2 !== null && v1 === null) {
        return derive(kind, id1, v2, {
          eq: true, op: 'sharedEq',
          text: edgeLabel(id1, kind) + ' = ' + edgeLabel(id2, kind) + ' = ' + v2 + ' cm (shared edge)',
          result: v2
        }, [k2]);
      }
      return false;
    }

    var tk = key(target.kind, target.id);
    if (!has(tk)) { return null; }

    // Back-trace the chain: only the derived steps the TARGET depends on, in a
    // topological order ending at the target. This prunes incidental derivations
    // (minimisation may have forced extra values not on the target's path).
    //
    // Equality steps ("shared edge") just rename a value. A run of them through
    // intermediate (invisible) regions reads as noise, so we COLLAPSE pure
    // pass-through equalities: an eq step is kept only when it carries new
    // information to the reader — i.e. its dependency is a GIVEN clue (a labelled
    // length on the figure) — and dropped when its dependency is itself a derived
    // step (the value already appears on the line that produced it).
    var chain = [];
    var emitted = {};
    (function walk(k) {
      if (emitted[k]) { return; }
      emitted[k] = true;
      var pv = prov[k];
      if (!pv || !pv.step) { return; }      // a given clue: no step
      pv.deps.forEach(function (dk) { walk(dk); });
      if (pv.step.eq && k !== tk) {
        // Collapse a pure-equality step UNLESS it produces the target. Keep it
        // only when it carries new information (its dependency is a given clue);
        // otherwise the value is already stated by the line that produced it.
        var allGiven = pv.deps.every(function (dk) { return !prov[dk] || !prov[dk].step; });
        if (!allGiven) { return; }
      }
      chain.push({ text: pv.step.text, result: pv.step.result, op: pv.step.op });
    })(tk);

    // The terminal line must NAME the target so the reader lands on the right
    // piece/edge. When the target is a leaf whose dimension was forced by a
    // shared edge (an eq step we'd otherwise phrase via the block), restate it
    // explicitly as the leaf's own dimension.
    var tn = byId[target.id];
    if (chain.length) {
      var last = chain[chain.length - 1];
      var wantName = target.kind === 'A'
        ? pieceLabel(target.id)
        : edgeLabel(target.id, target.kind);
      if (tn && tn.leaf && last.text.indexOf(wantName) !== 0 && last.text.indexOf(pieceLabel(target.id)) < 0) {
        // This terminal line only RESTATES the target's value under its own name; it
        // carries no new arithmetic. Tag it 'restate' so chain-op analysis ignores it
        // and looks at the genuine producing steps above.
        if (target.kind === 'A') {
          chain.push({ text: pieceLabel(target.id) + ': area = ' + known[tk] + ' cm²', result: known[tk], op: 'restate' });
        } else {
          chain.push({ text: edgeLabel(target.id, target.kind) + ' = ' + known[tk] + ' cm', result: known[tk], op: 'restate' });
        }
      }
    }

    return { value: known[tk], chain: chain };
  }

  // ground-truth value of a variable from the tree geometry.
  function trueVal(node, kind) {
    if (kind === 'W') { return node.w; }
    if (kind === 'H') { return node.h; }
    // area
    return node.w * node.h;
  }

  // ---- assign piece letters (leaves) for labels ----------------------------
  function assignLabels(tree) {
    var labels = {};
    var letterOf = {};
    var L = 'ABCDEFGHIJKLMNOP';
    var i = 0;
    tree.leaves.forEach(function (l) { var ltr = L.charAt(i++); labels[l.id] = 'Piece ' + ltr; letterOf[l.id] = ltr; });
    // internal nodes: a leaf-span label ("strip A+B") so chain edges name a
    // region the reader can find on the figure (its outline = the listed pieces).
    nodesOf(tree).forEach(function (n) {
      if (n.leaf) { return; }
      var ls = [];
      (function collect(m) { if (m.leaf) { ls.push(letterOf[m.id]); } else { collect(m.a); collect(m.b); } })(n);
      labels[n.id] = (ls.length === tree.leaves.length) ? 'the whole rectangle' : ('the block ' + ls.join('+'));
      n._span = ls.length;
    });
    tree._labels = labels;
    tree._letterOf = letterOf;
    return labels;
  }

  // ---- greedy clue minimisation -------------------------------------------
  // Build the full clue universe (every leaf area + every node width/height),
  // pick a target, then greedily remove clues while the target stays uniquely
  // forced, until the chain length reaches the band budget (or no safe removal
  // remains). Returns { clues, target, value, chain } or null.
  function minimise(rng, tree, target, chainBudget) {
    var nodes = nodesOf(tree);

    // Clue universe: only VISIBLE quantities — every LEAF's area, width and
    // height, plus the OUTER rectangle's width and height (the root). Internal
    // region edges are never clued (they aren't drawn); they only ever appear as
    // intermediate steps in the deduction chain. This keeps clues and the target
    // on things a child can point to on the figure.
    var rootId = tree.root.id;
    var universe = [];
    nodes.forEach(function (n) {
      var visible = n.leaf || n.id === rootId;
      if (!visible) { return; }
      if (!(target.kind === 'W' && target.id === n.id)) { universe.push({ kind: 'W', id: n.id }); }
      if (!(target.kind === 'H' && target.id === n.id)) { universe.push({ kind: 'H', id: n.id }); }
      if (n.leaf && !(target.kind === 'A' && target.id === n.id)) { universe.push({ kind: 'A', id: n.id }); }
    });

    // Must be solvable from the full universe (it always is — fully labelled).
    var base = solve(tree, universe, target);
    if (!base) { return null; }

    // Greedy removal to FULL MINIMALITY: shuffle the universe and try to drop
    // each clue; keep every removal that leaves the target still uniquely forced.
    // The minimal clue set has no redundant clue (removing any breaks
    // uniqueness), which is exactly what makes the deduction CHAIN as long as the
    // figure allows — the longer the chain, the harder the puzzle. buildPuzzle
    // then accepts only puzzles whose chain length lands in the year's budget
    // window, so the meter/year tunes difficulty without over- or under-minimising.
    var clues = universe.slice();
    var shuffled = shuffle(rng, universe.slice());

    for (var i = 0; i < shuffled.length; i++) {
      var cand = shuffled[i];
      var trial = clues.filter(function (c) { return !(c.kind === cand.kind && c.id === cand.id); });
      if (trial.length === clues.length) { continue; } // already removed
      var res = solve(tree, trial, target);
      if (res && res.value !== null) { clues = trial; }   // still forced — keep removal
    }

    var finalRes = solve(tree, clues, target);
    if (!finalRes || finalRes.value === null) { return null; }
    return { clues: clues, target: target, value: finalRes.value, chain: finalRes.chain };
  }

  // ---- build one puzzle ----------------------------------------------------
  // Returns a renderable puzzle object, or null on a degenerate attempt.
  function buildPuzzle(rng, year, eff, isExample) {
    year = Math.max(4, Math.min(6, year | 0));
    var band = bandFor(year, eff);

    // Outer rectangle sized so leaf integer sides land in the magnitude band.
    // We work on a grid whose outer dims are within sideMax (so every edge
    // length <= sideMax). leafTarget leaves.
    var outerW = ri(rng, Math.max(6, band.sideMax - 4), band.sideMax);
    var outerH = ri(rng, Math.max(6, band.sideMax - 4), band.sideMax);

    var tree = buildTree(rng, { x: 0, y: 0, w: outerW, h: outerH }, band.leaves, band.sideMax);
    // Reject if we didn't get close to the target leaf count.
    if (tree.leaves.length < Math.max(3, band.leaves - 1)) { return null; }
    if (tree.leaves.length > band.leaves + 1) { return null; }

    assignLabels(tree);

    // Choose a target. Y4 = area only. Y5/Y6 = area or length per allowLength.
    var nodes = nodesOf(tree);
    var leaves = tree.leaves;
    var target;
    var wantLength = band.allowLength && rng() < (year === 6 ? 0.5 : 0.4);
    if (wantLength) {
      // target a LENGTH: a leaf's width or height (these are the most natural to
      // ask, and always forced by an area+other-side chain).
      var leaf = pick(rng, leaves);
      target = { kind: rng() < 0.5 ? 'W' : 'H', id: leaf.id };
    } else {
      // target a leaf AREA.
      var leafA = pick(rng, leaves);
      target = { kind: 'A', id: leafA.id };
    }

    var mn = minimise(rng, tree, target, band.chain);
    if (!mn) { return null; }

    // ANTI-DEGENERACY: an AREA target must be DERIVED across at least one shared
    // edge — it must NOT be a single adjacent multiply of two directly-clued own
    // sides (that is no Area Maze, every other piece is decoration). Reject any
    // area target whose OWN width AND height are both directly given as clues; the
    // target's sides must be forced through the figure (an area÷side division or a
    // shared-edge sum), guaranteeing real chaining. Length targets are intrinsically
    // derived (you can't clue an unknown), so this only constrains area targets.
    if (target.kind === 'A') {
      var haveW = false, haveH = false;
      for (var ci = 0; ci < mn.clues.length; ci++) {
        var cc = mn.clues[ci];
        if (cc.id === target.id && cc.kind === 'W') { haveW = true; }
        if (cc.id === target.id && cc.kind === 'H') { haveH = true; }
      }
      if (haveW && haveH) { return null; }
    }

    // Chain-length window: bandFor gives a TIGHT acceptance window [chainLo,chainHi]
    // that the meter slides across the year's curriculum range, so a low meter can
    // never yield the same chain length as a high meter in the same year (the SPEC
    // requires the 1-5 meter to genuinely tune WITHIN the year). Too-short and
    // too-long figures are rejected so buildOne retries a fresh one.
    var loChain = Math.max(1, band.chainLo);
    var hiChain = band.chainHi;
    // AREA targets are floored at 2 steps by anti-degeneracy (derive a side, then
    // multiply) — never a single given×given. When the year's chain target is 1
    // (Y4 easiest), an area puzzle can't reach it, so widen its window up to 2 so a
    // valid easy area puzzle is still accepted (Y4 then tunes via leaves/magnitude).
    if (target.kind === 'A') { hiChain = Math.max(hiChain, 2); if (loChain < 2) { loChain = Math.min(2, hiChain); } }
    if (mn.chain.length < loChain || mn.chain.length > hiChain) { return null; }

    // Validate: every value is a whole number and within band magnitude.
    if (mn.value == null || mn.value !== (mn.value | 0) || mn.value < 0) { return null; }

    // FIX 1 — DEFINING MECHANIC: every non-example puzzle must USE area reasoning.
    // The deduction chain to the target must contain at least one AREA-REASONING
    // step: an l×w multiply ('mul', produces cm²) or an area÷side division
    // ('divArea'). Lengths are only ever added/subtracted (sumLen/diffLen) or
    // copied across a shared edge (sharedEq) — never divided — so a chain that
    // reaches its (length) target by pure length arithmetic is NOT an Area Maze.
    // Reject and let buildOne regenerate (the liveness fallback always fills the
    // sheet). Examples are exempt (the worked demo shows everything regardless).
    // FIX 2 — also require at least one GENUINE ARITHMETIC step (mul/divArea/
    // sumLen/diffLen), never a chain of pure shared-edge copies. We tag each step
    // in solve() with step.op and test the tag (not text) for robustness.
    if (!isExample) {
      var hasArea = mn.chain.some(function (st) { return st.op === 'mul' || st.op === 'divArea'; });
      if (!hasArea) { return null; }
      var hasArith = mn.chain.some(function (st) {
        return st.op === 'mul' || st.op === 'divArea' || st.op === 'sumLen' || st.op === 'diffLen';
      });
      if (!hasArith) { return null; }
    }

    // EXAMPLE clarity: the worked example shows EVERY value, so if the solved
    // target equals another value already shown on the figure the demonstrator
    // sees two identical-looking labels (e.g. two pieces both "24 cm²"). That is
    // momentarily confusing, so reject an example whose target collides with any
    // other shown quantity of the same kind. (Plain puzzles only reveal the target
    // as '?', so a coincidental match isn't visible — this guard is example-only.)
    if (isExample) {
      if (target.kind === 'A') {
        // every leaf area is shown on the example; reject if another leaf shares it.
        for (var li2 = 0; li2 < leaves.length; li2++) {
          if (leaves[li2].id === target.id) { continue; }
          if (leaves[li2].w * leaves[li2].h === mn.value) { return null; }
        }
      } else {
        // a length target: reject if any shown length clue equals the answer.
        for (var ci2 = 0; ci2 < mn.clues.length; ci2++) {
          var cl = mn.clues[ci2];
          if (cl.kind === 'A') { continue; }
          if (cl.id === target.id && cl.kind === target.kind) { continue; }
          if (trueVal(nodes.filter(function (nn) { return nn.id === cl.id; })[0], cl.kind) === mn.value) { return null; }
        }
      }
    }

    // Build render model.
    var byId = {};
    nodes.forEach(function (n) { byId[n.id] = n; });
    var clueSet = {};
    mn.clues.forEach(function (c) { clueSet[c.kind + ':' + c.id] = true; });

    // Leaf rectangles for drawing (with normalised coords already integer).
    var rects = leaves.map(function (l) {
      var label = tree._labels[l.id];
      return {
        id: l.id, label: label,
        x: l.x, y: l.y, w: l.w, h: l.h,
        area: l.w * l.h,
        shownArea: !!clueSet['A:' + l.id],
        isTargetArea: target.kind === 'A' && target.id === l.id
      };
    });

    // Segment clues: we render shown widths/heights as edge labels. To keep the
    // figure readable we only surface the OUTER edge lengths and the LEAF edge
    // lengths that are clued (internal node W/H clues map onto shared grid
    // segments). We compute, per shown W/H clue, a segment to draw.
    var segs = [];
    nodes.forEach(function (n) {
      // width clue -> a horizontal segment along the node's top edge.
      if (clueSet['W:' + n.id]) {
        segs.push({ kind: 'W', id: n.id, x: n.x, y: n.y, len: n.w, horiz: true,
          isTarget: false });
      }
      if (clueSet['H:' + n.id]) {
        segs.push({ kind: 'H', id: n.id, x: n.x, y: n.y, len: n.h, horiz: false,
          isTarget: false });
      }
    });
    // target segment (a length target) drawn as '?'
    var targetSeg = null;
    if (target.kind === 'W' || target.kind === 'H') {
      var tn = byId[target.id];
      targetSeg = {
        kind: target.kind, id: target.id,
        x: tn.x, y: tn.y,
        len: target.kind === 'W' ? tn.w : tn.h,
        horiz: target.kind === 'W'
      };
    }

    return {
      year: year,
      leafCount: leaves.length,
      example: !!isExample,
      outer: { w: outerW, h: outerH },
      rects: rects,
      segs: segs,
      target: { kind: target.kind, id: target.id, isLength: target.kind !== 'A' },
      targetSeg: targetSeg,
      answer: mn.value,
      chain: mn.chain.map(function (s) { return { text: s.text, result: s.result, op: s.op }; }),
      labels: tree._labels
    };
  }

  // ---- whole-sheet generation ----------------------------------------------
  // opts = { year, difficulty(1-5), count(2|3|4), seed }.
  // Returns { items:[puzzle…], year, difficulty, count, seed }.
  // Item 0 is always a fully-worked EXAMPLE (all values shown, target solved).
  function generateSheet(opts) {
    opts = opts || {};
    var year = Math.max(4, Math.min(6, (opts.year || 4) | 0));
    var meter = Math.max(1, Math.min(5, (opts.difficulty || 3) | 0));
    var count = Math.max(2, Math.min(4, (opts.count || 4) | 0));
    var seed = (opts.seed != null) ? (opts.seed >>> 0) : (Math.floor(Math.random() * 0xffffffff) >>> 0);
    var rng = makeRng(seed);
    // The YEAR already fixes the curriculum envelope (leaf/chain/magnitude bounds),
    // so the 1-5 meter tunes WITHIN that envelope directly — we do NOT apply the
    // shared TP_effDifficulty year-shift here (it would compress the meter to a
    // narrow eff range, e.g. Y6 -> eff 3..5, making d1 and d5 indistinguishable).
    // bandFor lerps its leaf/chain/magnitude targets across the meter 1..5.
    var eff = meter;

    var items = [];
    for (var idx = 0; idx < count; idx++) {
      // No worked example — every card is a real problem (kept compact so 4 fit
      // one A4). The how-it-works on the info page covers the method.
      var p = buildOne(rng, year, eff, false);
      items.push(p);
    }

    return {
      items: items,
      year: year, difficulty: meter, count: count, seed: seed
    };
  }

  // Build one puzzle with an attempt cap + deterministic sub-seed fallback so a
  // sheet always fills.
  function buildOne(rng, year, eff, isExample) {
    for (var attempt = 0; attempt < 200; attempt++) {
      var p = buildPuzzle(rng, year, eff, isExample);
      if (p) { return p; }
    }
    // last resort: a minimal hand-constructed 3-piece puzzle (always valid).
    return fallbackPuzzle(rng, year, eff, isExample);
  }

  // A guaranteed-constructible 3-leaf puzzle: a vertical cut then a horizontal
  // cut of one part. Always produces a forced area target.
  function fallbackPuzzle(rng, year, eff, isExample) {
    var band = bandFor(year, eff);
    var W = ri(rng, 6, band.sideMax), H = ri(rng, 6, band.sideMax);
    var p1 = ri(rng, 2, W - 2);
    var p2 = ri(rng, 2, H - 2);
    // tree: root V cut at p1 -> A (left, full height), B (right, H cut at p2)
    var tree = {
      root: {
        id: 4, leaf: false, orient: 'V', x: 0, y: 0, w: W, h: H,
        a: { id: 0, leaf: true, x: 0, y: 0, w: p1, h: H },
        b: {
          id: 5, leaf: false, orient: 'H', x: p1, y: 0, w: W - p1, h: H,
          a: { id: 1, leaf: true, x: p1, y: 0, w: W - p1, h: p2 },
          b: { id: 2, leaf: true, x: p1, y: p2, w: W - p1, h: H - p2 }
        }
      },
      leaves: []
    };
    tree.leaves = nodesOf(tree).filter(function (n) { return n.leaf; });
    assignLabels(tree);

    // Try each leaf as the area target and keep the first NON-DEGENERATE minimal
    // result (target's own W and H are not both clued, so the answer is genuinely
    // derived across a shared edge — a real Area Maze, not a single multiply).
    var target = { kind: 'A', id: tree.leaves[0].id };
    var mn = null;
    var tryLeaves = shuffle(rng, tree.leaves.slice());
    for (var tli = 0; tli < tryLeaves.length; tli++) {
      var tcand = { kind: 'A', id: tryLeaves[tli].id };
      var cand = minimise(rng, tree, tcand, band.chain);
      if (!cand) { continue; }
      var bw = false, bh = false;
      cand.clues.forEach(function (c) {
        if (c.id === tcand.id && c.kind === 'W') { bw = true; }
        if (c.id === tcand.id && c.kind === 'H') { bh = true; }
      });
      if (!(bw && bh)) { target = tcand; mn = cand; break; }
    }
    if (!mn) {
      // Last resort: a hand-built non-degenerate clue set on this fixed tree.
      // Target Piece A (id 0, p1 x H). Clue the OUTER width & height, plus the
      // areas of B (id 1) and C (id 2). Then: A height = outer height (shared
      // edge); the B+C block width = B area ÷ B height and = C area ÷ C height,
      // forced equal; A width = outer width − that block width; A area = derived
      // width × height. The target's own sides are NEVER directly clued.
      target = { kind: 'A', id: 0 };
      var clues = [
        { kind: 'W', id: 4 }, { kind: 'H', id: 4 }, // outer rectangle
        { kind: 'A', id: 1 }, { kind: 'A', id: 2 }  // pieces B and C areas
      ];
      var res = solve(tree, clues, target);
      if (res && res.value != null) {
        mn = { clues: clues, target: target, value: res.value, chain: res.chain };
      } else {
        // absolute floor: clue everything EXCEPT the target's own W and H so the
        // area is still derived (never a both-sides multiply).
        var clues2 = [];
        nodesOf(tree).forEach(function (n) {
          if (n.leaf && n.id !== target.id) { clues2.push({ kind: 'A', id: n.id }); }
          if (!(n.id === target.id)) { clues2.push({ kind: 'W', id: n.id }); clues2.push({ kind: 'H', id: n.id }); }
        });
        var res2 = solve(tree, clues2, target);
        mn = { clues: clues2, target: target, value: res2 ? res2.value : (tree.leaves[0].w * tree.leaves[0].h),
               chain: (res2 && res2.chain && res2.chain.length) ? res2.chain
                 : [{ text: tree._labels[target.id] + ': area = ' + tree.leaves[0].w + ' × ' + tree.leaves[0].h + ' = ' + (tree.leaves[0].w * tree.leaves[0].h) + ' cm²', result: tree.leaves[0].w * tree.leaves[0].h }] };
      }
    }
    var nodes = nodesOf(tree), byId = {};
    nodes.forEach(function (n) { byId[n.id] = n; });
    var clueSet = {};
    mn.clues.forEach(function (c) { clueSet[c.kind + ':' + c.id] = true; });
    var rects = tree.leaves.map(function (l) {
      return { id: l.id, label: tree._labels[l.id], x: l.x, y: l.y, w: l.w, h: l.h,
        area: l.w * l.h, shownArea: !!clueSet['A:' + l.id],
        isTargetArea: target.kind === 'A' && target.id === l.id };
    });
    var segs = [];
    nodes.forEach(function (n) {
      if (clueSet['W:' + n.id]) { segs.push({ kind: 'W', id: n.id, x: n.x, y: n.y, len: n.w, horiz: true }); }
      if (clueSet['H:' + n.id]) { segs.push({ kind: 'H', id: n.id, x: n.x, y: n.y, len: n.h, horiz: false }); }
    });
    return {
      year: year, leafCount: tree.leaves.length, example: !!isExample,
      outer: { w: W, h: H }, rects: rects, segs: segs,
      target: { kind: 'A', id: target.id, isLength: false }, targetSeg: null,
      answer: mn.value, chain: mn.chain.map(function (s) { return { text: s.text, result: s.result, op: s.op }; }),
      labels: tree._labels
    };
  }

  // ---- expose engine -------------------------------------------------------
  if (typeof window !== 'undefined') {
    window.TP_AM = {
      generateSheet: generateSheet,
      buildPuzzle: buildPuzzle,
      solve: solve,
      minimise: minimise,
      bandFor: bandFor,
      makeRng: makeRng,
      nodesOf: nodesOf
    };
  }

  /* ====== DOM (browser only) ============================================== */
  if (typeof document === 'undefined') { return; }

  var ACCENT = '#a23b5e';
  function $(id) { return document.getElementById(id); }
  function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

  var state = {
    year: 4, difficulty: 3, count: 4, tab: 'puzzle',
    seed: (Math.floor(Math.random() * 0xffffffff) >>> 0),
    sheet: null
  };
  var els = {};

  function rebuild(opts) {
    opts = opts || {};
    if (opts.newSeed) { state.seed = (Math.floor(Math.random() * 0xffffffff) >>> 0); }
    state.sheet = generateSheet({
      year: state.year, difficulty: state.difficulty, count: state.count, seed: state.seed
    });
    render();
  }

  // ---- SVG figure rendering (self-contained) -------------------------------
  // We draw a SCHEMATIC rectilinear figure. Pieces are NOT to scale: we lay them
  // out on the tree's integer grid but the figure is captioned "Not drawn to
  // scale" so the grid proportions carry no metric meaning. Each leaf shows its
  // area (or '?'); clued segments show their length on the relevant edge.
  function figureSVG(p, revealed) {
    var solved = revealed || p.example;

    // SCHEMATIC, NOT TO SCALE: instead of true integer proportions (which strand
    // labels in razor-thin pieces), we EQUALISE the layout — every distinct cut
    // line becomes an evenly spaced grid line. This guarantees each piece is big
    // enough for its label and reinforces "not drawn to scale" (the figure is
    // explicitly captioned as such, so the uniform spacing carries no metric
    // meaning — children must reason from the numbers, not measure).
    var xs = [], ys = [];
    function addEdge(arr, v) { if (arr.indexOf(v) < 0) { arr.push(v); } }
    p.rects.forEach(function (r) { addEdge(xs, r.x); addEdge(xs, r.x + r.w); addEdge(ys, r.y); addEdge(ys, r.y + r.h); });
    addEdge(xs, 0); addEdge(xs, p.outer.w); addEdge(ys, 0); addEdge(ys, p.outer.h);
    xs.sort(function (a, b) { return a - b; });
    ys.sort(function (a, b) { return a - b; });
    var xRank = {}, yRank = {};
    xs.forEach(function (v, i) { xRank[v] = i; });
    ys.forEach(function (v, i) { yRank[v] = i; });
    var nCols = xs.length - 1, nRows = ys.length - 1;

    // ALL length labels live in the GUTTERS outside the figure (top gutter for
    // widths, left gutter for heights), on tick-marked dimension lines that point
    // back to the exact span. This guarantees a clue NEVER overlaps area text or
    // another clue inside the figure. The gutters are sized to stack several
    // parallel dimension lines (one "lane" per distinct edge position).
    // Lane depth grows with how many distinct cut lines could carry a clue.
    // Gutters hold stacked dimension lanes (one lane = 13u deep) PLUS room for the
    // number pill that sits centred on the outermost lane (a height pill is ~36u
    // wide, so the left gutter needs an extra ~22u beyond the deepest lane so the
    // pill never pokes back into the figure).
    // Gutters hold the stacked dimension lanes. They were sized off the raw grid-
    // line count (every distinct x/y), which over-reserves space (most lines carry
    // no clue) and, combined with tall figures, blew the figure height past A4. We
    // size gutters off how many lanes can ACTUALLY be needed: a width clue lives on
    // a distinct y-row top edge, a height clue on a distinct x-col left edge, but
    // labels pack into shared lanes, so a couple of lanes suffice in practice.
    // Caps keep the gutters bounded regardless of grid size.
    // LANE = vertical spacing between stacked dimension lines; must clear the pill
    // height (~16u) so two lanes never touch. PILLCLR = clearance from the figure
    // edge to the SHALLOWEST height-pill's centre (a height pill is ~22u half-wide,
    // so we keep its right edge off the rectangle border — no collision with area
    // text). Gutters are LANE-driven and capped so they never balloon.
    var LANE = 16, PILLCLR = 30;
    var maxTopLanes = Math.min(3, Math.max(1, nRows));
    var maxLeftLanes = Math.min(3, Math.max(1, nCols));
    var topGut = 12 + maxTopLanes * LANE;
    var leftGut = PILLCLR + maxLeftLanes * LANE;
    var rGut = 10, bGut = 10;

    // INTERIOR sizing: scale the whole interior to fit a bounded box (LANDSCAPE-
    // biased — wider than tall — so cards stay short and several stack on one A4).
    // We pick one uniform cell size from the limiting dimension so the figure never
    // grows tall when it has many rows: the interior fits inside IW x IH, and a
    // floor keeps small figures legible. preserveAspectRatio letterboxes the rest.
    var IW = 300, IH = 210;          // interior bounding box (figure only, no gutters)
    var cellMin = 30, cellMax = 58;
    var cell = Math.min(IW / Math.max(1, nCols), IH / Math.max(1, nRows));
    cell = Math.max(cellMin, Math.min(cellMax, cell));
    var cellW = cell, cellH = cell;
    var W = nCols * cellW, H = nRows * cellH;
    var svgW = W + leftGut + rGut, svgH = H + topGut + bGut;

    function X(gx) { return leftGut + xRank[gx] * cellW; }
    function Y(gy) { return topGut + yRank[gy] * cellH; }

    var parts = [];
    parts.push('<svg viewBox="0 0 ' + svgW + ' ' + svgH + '" class="am-svg" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">');
    parts.push('<rect x="0" y="0" width="' + svgW + '" height="' + svgH + '" fill="#fff"/>');

    // leaf rectangles. Area text is placed in the UPPER portion of each cell so it
    // can never collide with an on-edge length label that sits on the cell's
    // bottom/left grid lines.
    p.rects.forEach(function (r) {
      var rx = X(r.x), ry = Y(r.y), rx2 = X(r.x + r.w), ry2 = Y(r.y + r.h);
      var rw = rx2 - rx, rh = ry2 - ry;
      var fill = r.isTargetArea ? 'rgba(162,59,94,0.08)' : '#fff';
      parts.push('<rect x="' + rx + '" y="' + ry + '" width="' + rw + '" height="' + rh + '" fill="' + fill + '" stroke="#1a1a1a" stroke-width="1.2"/>');
      var cx = rx + rw / 2, cy = ry + rh / 2;
      // shrink the area font if the piece is narrow so text never overflows.
      function fitFont(txt, base) {
        var approx = txt.length * base * 0.56;
        if (approx <= rw - 6) { return base; }
        return Math.max(8, Math.floor(base * (rw - 6) / approx));
      }
      if (r.isTargetArea) {
        var ttxt = solved ? (p.answer + ' cm²') : '?';
        var fs = fitFont(ttxt, 16);
        parts.push('<text x="' + cx + '" y="' + (cy + fs * 0.34) + '" text-anchor="middle" font-size="' + fs + '" font-weight="800" fill="' + ACCENT + '" font-family="system-ui,sans-serif">' + ttxt + '</text>');
      } else if (r.shownArea || solved) {
        var atxt = r.area + ' cm²';
        var afs = fitFont(atxt, 12);
        parts.push('<text x="' + cx + '" y="' + (cy + afs * 0.34) + '" text-anchor="middle" font-size="' + afs + '" font-weight="700" fill="#1a1a1a" font-family="system-ui,sans-serif">' + atxt + '</text>');
      }
    });

    // outer border (bold, on top)
    parts.push('<rect x="' + X(0) + '" y="' + Y(0) + '" width="' + W + '" height="' + H + '" fill="none" stroke="#1a1a1a" stroke-width="2"/>');

    // ---- LENGTH LABELS (all in the gutters, never over the figure) ---------
    // Each length clue measures ONE node's edge (a width = a top-edge span; a
    // height = a left-edge span). We draw it as an engineering DIMENSION:
    //   - two thin EXTENSION lines from the span's endpoints out into the gutter,
    //   - a DIMENSION line parallel to the span, sitting in a free gutter "lane",
    //   - a TICK at each end, and the number on a white pill on the dimension line.
    // Because every label lives outside the figure, it can never collide with area
    // text. Widths stack in the TOP gutter, heights in the LEFT gutter; we assign
    // each its own lane (offset from the figure) and de-conflict lanes so two
    // labels never share a line. The extension lines + ticks make it unambiguous
    // which exact span each number measures — even on a shared internal edge.
    var topLanes = [];   // each: array of [startX,endX] occupied at that lane depth
    var leftLanes = [];

    function reserveLane(lanes, a, b) {
      // find the shallowest lane whose occupied ranges don't overlap [a,b].
      for (var li = 0; li < lanes.length; li++) {
        var ok = true;
        for (var r = 0; r < lanes[li].length; r++) {
          var seg = lanes[li][r];
          if (!(b < seg[0] - 6 || a > seg[1] + 6)) { ok = false; break; }
        }
        if (ok) { lanes[li].push([a, b]); return li; }
      }
      lanes.push([[a, b]]);
      return lanes.length - 1;
    }
    function ticksH(ax, bx, y, color) {
      parts.push('<line x1="' + ax + '" y1="' + (y - 3) + '" x2="' + ax + '" y2="' + (y + 3) + '" stroke="' + color + '" stroke-width="1.1"/>');
      parts.push('<line x1="' + bx + '" y1="' + (y - 3) + '" x2="' + bx + '" y2="' + (y + 3) + '" stroke="' + color + '" stroke-width="1.1"/>');
    }
    function ticksV(ay, by, x, color) {
      parts.push('<line x1="' + (x - 3) + '" y1="' + ay + '" x2="' + (x + 3) + '" y2="' + ay + '" stroke="' + color + '" stroke-width="1.1"/>');
      parts.push('<line x1="' + (x - 3) + '" y1="' + by + '" x2="' + (x + 3) + '" y2="' + by + '" stroke="' + color + '" stroke-width="1.1"/>');
    }
    function pill(cx, cy, txt, fill, fs, bold) {
      var halfW = (txt.length * fs * 0.30) + 4;
      var halfH = fs * 0.70;
      parts.push('<rect x="' + (cx - halfW) + '" y="' + (cy - halfH) + '" width="' + (halfW * 2) + '" height="' + (halfH * 2) + '" rx="3" fill="#fff" stroke="' + fill + '" stroke-width="0.8"/>');
      parts.push('<text x="' + cx + '" y="' + (cy + fs * 0.34) + '" text-anchor="middle" font-size="' + fs + '" font-weight="' + bold + '" fill="' + fill + '" font-family="system-ui,sans-serif">' + txt + '</text>');
    }
    function lenLabel(s, txt, fill, fs, bold, accent) {
      var lineColor = accent ? ACCENT : '#9aa0a6';
      if (s.horiz) {
        var ax = X(s.x), bx = X(s.x + s.len), spanY = Y(s.y);
        var lane = reserveLane(topLanes, ax, bx);
        var dy = topGut - 9 - lane * LANE;   // dimension line y (deeper lane = higher)
        // FIX 3: CLIP extension lines to the figure BORDER. A width clue can sit on
        // an interior top edge (s.y > 0); drawing the extension from that interior y
        // out to the gutter would run a vertical line DOWN through the cells (and
        // their area labels) between the gutter and that edge — a visible
        // strike-through. So we anchor the extension at the figure's OUTER top edge
        // (Y(0)) instead of the clued edge's interior y. The line then lives wholly
        // in the TOP GUTTER (between Y(0) and the dimension line) and never enters a
        // cell; the ticks on the dimension line still pin the exact span.
        var figTop = Y(0);
        parts.push('<line x1="' + ax + '" y1="' + (figTop - 1) + '" x2="' + ax + '" y2="' + dy + '" stroke="' + lineColor + '" stroke-width="0.6"/>');
        parts.push('<line x1="' + bx + '" y1="' + (figTop - 1) + '" x2="' + bx + '" y2="' + dy + '" stroke="' + lineColor + '" stroke-width="0.6"/>');
        parts.push('<line x1="' + ax + '" y1="' + dy + '" x2="' + bx + '" y2="' + dy + '" stroke="' + lineColor + '" stroke-width="0.9"/>');
        ticksH(ax, bx, dy, lineColor);
        pill((ax + bx) / 2, dy, txt, fill, fs, bold);
      } else {
        var ay = Y(s.y), by = Y(s.y + s.len), spanX = X(s.x);
        var laneV = reserveLane(leftLanes, ay, by);
        // pill is centred on the lane line; the shallowest lane sits PILLCLR inside
        // the gutter (dx = leftGut - PILLCLR) so its pill's right edge (centre + ~22u
        // half-width) stays clear of X(0) — no overlap with the rectangle border or
        // in-figure area text. Deeper lanes step further out.
        var dx = (leftGut - PILLCLR) - laneV * LANE;
        // FIX 3: CLIP extension lines to the figure BORDER. A height clue can sit on
        // an interior left edge (s.x > 0); drawing the extension from that interior x
        // out to the left gutter would run a horizontal line ACROSS the cells (and
        // their area labels) to the LEFT of that edge — the reported '5 cm' line
        // through '25 cm²' strike-through. So we anchor the extension at the figure's
        // OUTER left edge (X(0)) instead of the clued edge's interior x. The line
        // then lives wholly in the LEFT GUTTER (between X(0) and the dimension line)
        // and never enters a cell; the ticks still pin the exact span.
        var figLeft = X(0);
        parts.push('<line x1="' + (figLeft - 1) + '" y1="' + ay + '" x2="' + dx + '" y2="' + ay + '" stroke="' + lineColor + '" stroke-width="0.6"/>');
        parts.push('<line x1="' + (figLeft - 1) + '" y1="' + by + '" x2="' + dx + '" y2="' + by + '" stroke="' + lineColor + '" stroke-width="0.6"/>');
        parts.push('<line x1="' + dx + '" y1="' + ay + '" x2="' + dx + '" y2="' + by + '" stroke="' + lineColor + '" stroke-width="0.9"/>');
        ticksV(ay, by, dx, lineColor);
        pill(dx, (ay + by) / 2, txt, fill, fs, bold);
      }
    }

    // Reserve lanes nearest the figure for BOUNDARY edges (so the outer
    // dimensions read first), then interior, then the target on top.
    var segOrder = p.segs.slice().sort(function (a, b) {
      var ab = (a.horiz ? a.y === 0 : a.x === 0) ? 0 : 1;
      var bb = (b.horiz ? b.y === 0 : b.x === 0) ? 0 : 1;
      return ab - bb;
    });
    segOrder.forEach(function (s) { lenLabel(s, s.len + ' cm', '#374151', 10, 700, false); });

    // target segment '?' (length target) — drawn last so its pill sits on top.
    if (p.targetSeg) {
      lenLabel(p.targetSeg, solved ? (p.answer + ' cm') : '?', ACCENT, 12, 800, true);
    }

    parts.push('</svg>');
    return parts.join('');
  }

  function cardHTML(p, idx, revealed) {
    var isEx = p.example;
    var solved = revealed || isEx;
    var tag = isEx ? '<span class="am-tag">Example</span>' : '';
    var heading = 'Puzzle ' + (idx + 1) + (isEx ? ' — Example' : '');
    var html = '<figure class="am-card' + (isEx ? ' am-card-example' : '') + '">';
    html += '<div class="am-card-head"><span class="am-card-no">' + esc(heading) + '</span>' + tag + '</div>';
    html += '<div class="am-fig">' + figureSVG(p, revealed) + '</div>';
    html += '<div class="am-foot-note">Not drawn to scale</div>';
    // deduction chain: shown on the answer key, AND on the worked example card.
    if (solved && p.chain && p.chain.length) {
      var chainHTML = '<ol class="am-chain">';
      p.chain.forEach(function (st) { chainHTML += '<li>' + esc(st.text) + '</li>'; });
      chainHTML += '</ol>';
      var lab = p.target.isLength ? 'missing length' : 'missing area';
      html += '<div class="am-chain-wrap"><div class="am-chain-title">Deduction — ' + lab + '</div>' + chainHTML + '</div>';
    }
    html += '</figure>';
    return html;
  }

  function render() {
    if (!state.sheet) { return; }   // nothing built yet (e.g. tab set during init)
    if (els.eyebrowDiff && window.TP_diffDots) { els.eyebrowDiff.textContent = window.TP_diffDots(state.difficulty); }
    if (els.eyebrowKs) { els.eyebrowKs.textContent = 'KS2 · Year ' + state.year; }
    var revealed = state.tab === 'answers';
    var sheet = state.sheet;
    var count = sheet.items.length;
    // Column count is chosen so EVERY count yields >=2 rows — that is what lets
    // align-content:space-between actually distribute the cards down the page and
    // fill the A4 (a single row leaves space-between with nothing to space). So:
    //   count 2 -> 1 column (2 rows, stacked)
    //   count 3 -> 2 columns (2 rows: example full-width, then 2)
    //   count 4 -> 2 columns (2 rows of 2)
    // Rows hug their content (CSS grid-auto-rows:min-content); the figure is sized
    // large and plain cards carry a min-height matched to the chain-bearing example
    // so both rows are tall and even — figures fill the cards (no dead band) and the
    // small remaining slack is shed as inter-row gaps (align-content:space-between).
    var cols = (count === 2) ? 1 : 2;
    els.grid.style.setProperty('--am-cols', cols);
    els.grid.setAttribute('data-count', count);
    // Dense compaction on the ANSWER KEY (every key carries deduction chains, so
    // keep them tight to guarantee ONE A4 at any count) and on ALL 4-up sheets
    // (the tallest figures — a full-size 4-up worksheet can otherwise spill to a
    // 2nd page on the bigger Y5/Y6 figures).
    els.grid.classList.toggle('am-dense', revealed || count >= 4);

    var html = '';
    sheet.items.forEach(function (p, i) { html += cardHTML(p, i, revealed); });
    els.grid.innerHTML = html;

    if (els.intro) {
      els.intro.textContent = 'Work out each missing value. Not drawn to scale.';
    }
  }

  // ---- toolbar wiring ------------------------------------------------------
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
    moveThumb(els.diffThumb, $('am-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    rebuild();
  }
  function setTab(tab) {
    state.tab = tab;
    moveThumb(els.tabThumb, $('am-tabs'), '[data-tab]', tab === 'answers' ? 1 : 0);
    render();
  }
  function regen() {
    if (els.spin) { els.spin.style.transform = 'rotate(360deg)'; setTimeout(function () { els.spin.style.transform = 'rotate(0deg)'; }, 500); }
    rebuild({ newSeed: true });
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
    form.append('title', 'Area Maze');
    form.append('activity', 'area-maze');
    form.append('config', JSON.stringify({
      year: state.year, difficulty: state.difficulty, count: state.count, seed: state.seed
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

  function restoreFromSaved() {
    if (typeof window === 'undefined' || !window.TP_SAVED || !window.TP_SAVED.config) { return false; }
    var cfg = window.TP_SAVED.config;
    if (cfg.year) { state.year = Math.max(4, Math.min(6, cfg.year | 0)); }
    if (cfg.difficulty) { state.difficulty = Math.max(1, Math.min(5, cfg.difficulty | 0)); }
    if (cfg.count) { state.count = Math.max(2, Math.min(4, cfg.count | 0)); }
    if (cfg.seed != null) { state.seed = cfg.seed >>> 0; }
    return true;
  }

  function init() {
    els.grid = $('am-grid');
    els.diffThumb = $('am-difficulty') ? $('am-difficulty').querySelector('.diff-thumb') : null;
    els.diffLabel = $('am-diff-label');
    els.eyebrowDiff = $('am-eyebrow-diff');
    els.eyebrowKs = $('am-eyebrow-ks');
    els.tabThumb = $('am-tabs') ? $('am-tabs').querySelector('.seg-thumb') : null;
    els.intro = $('am-intro');
    els.spin = $('am-regen-icon');
    if (els.spin) { els.spin.style.transition = 'transform .5s ease'; }
    els.toast = $('am-toast');

    var yearEl = $('am-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    var restored = restoreFromSaved();

    var y0 = window.TP_wireYears ? window.TP_wireYears('am', function (y) { state.year = Math.max(4, Math.min(6, y)); rebuild(); }) : null;
    if (y0 && !restored) { state.year = Math.max(4, Math.min(6, y0)); }
    if (restored) {
      var yWrap = $('am-years');
      if (yWrap) {
        Array.prototype.forEach.call(yWrap.querySelectorAll('[data-yr]'), function (b) {
          b.classList.toggle('chip-on', Number(b.getAttribute('data-yr')) === state.year);
        });
      }
    }

    var cnt = $('am-count');
    if (cnt) {
      Array.prototype.forEach.call(cnt.querySelectorAll('[data-count]'), function (b) {
        b.addEventListener('click', function () { state.count = Number(b.getAttribute('data-count')); setOnState(cnt, 'data-count', state.count); rebuild(); });
      });
      setOnState(cnt, 'data-count', state.count);
    }

    Array.prototype.forEach.call($('am-difficulty').querySelectorAll('[data-diff]'), function (b) {
      b.addEventListener('click', function () { setDiff(parseInt(b.getAttribute('data-diff'), 10)); });
    });
    Array.prototype.forEach.call($('am-tabs').querySelectorAll('[data-tab]'), function (b) {
      b.addEventListener('click', function () { setTab(b.getAttribute('data-tab')); });
    });

    $('am-save').addEventListener('click', onSave);
    $('am-print').addEventListener('click', function () { window.print(); });
    $('am-regen').addEventListener('click', regen);

    // reflect restored difficulty/count on the controls
    moveThumb(els.diffThumb, $('am-difficulty'), '[data-diff]', state.difficulty - 1);
    if (els.diffLabel && window.TP_diffDots) { els.diffLabel.textContent = window.TP_diffDots(state.difficulty); }
    if (cnt) { setOnState(cnt, 'data-count', state.count); }

    setTab('puzzle');
    rebuild();
  }

  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); }
  else { init(); }
})();
