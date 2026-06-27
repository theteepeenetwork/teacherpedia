export const meta = {
  name: 'new-resource',
  description: 'Build a new teacherpedia resource end-to-end (scaffold → implement → review → print-assess → fix) per dev/RESOURCE_WORKFLOW.md',
  whenToUse: 'When creating a brand-new printable resource type. Pass a brief as args: {slug, name, accent, prefix, concept, objectives}.',
  phases: [
    { title: 'Design', detail: 'refine the concept + engine design' },
    { title: 'Implement', detail: 'scaffold, code the engine/view, wire route+catalogue+allowlist, Node-test' },
    { title: 'Review & Print', detail: 'adversarial review + real-A4 print assessment; fix until both pass' },
  ],
}

const A = (typeof args === 'string' ? JSON.parse(args) : args) || {};
const brief = [
  'RESOURCE BRIEF:',
  'slug: ' + (A.slug || '(choose a kebab-case slug)'),
  'name: ' + (A.name || '(choose a display name)'),
  'accent: ' + (A.accent || '#1f8a4d'),
  'prefix: ' + (A.prefix || '(short id prefix, e.g. np)'),
  'concept: ' + (A.concept || '(describe the activity and how it self-marks)'),
  'objectives: ' + (A.objectives || '(which White Rose strands/years + Below/Meeting/Exceeding)'),
].join('\n');

const RULES = [
  'Follow dev/RESOURCE_WORKFLOW.md and CLAUDE.md exactly. Key rules:',
  '- Agents for judgement, code for verification — Node/PHP scripts prove correctness, page counts, integration.',
  '- Engine first: a pure generate()/solve() exposed as window.<NS>, returning {qtn,ans} (single correct answer); Node-test BEFORE wiring UI.',
  '- Render items DIRECTLY into the grid element (never a nested grid). Compact cards + align-content:space-between. Cap item max-width so every count fits ONE A4 page. Override tp-print.css .sheet{min-height:0!important} with !important.',
  '- Save posts activity/title/config; add the slug to Account::ALLOWED_ACTIVITIES.',
  '- How-it-works goes on the info page (catalogue image/how), NOT the sheet.',
  '- Print-assess the REAL A4 via: node dev/print-preview/preview.js --slug <slug> --out /tmp/<slug>.pdf [--click ...]  (it prints "pages: N").',
].join('\n');

// ---------- DESIGN ----------
phase('Design');
const design = await agent(
  [
    'You are the resource DESIGNER. Produce a concrete build spec for this resource: the mechanic, how it draws on the question bank, the engine design (what generate()/solve() compute and how the answer is uniquely determined), the on-sheet item layout, the info-page how-it-works steps, and the Below/Meeting/Exceeding differentiation.',
    '', brief, '', RULES,
  ].join('\n'),
  { label: 'design', phase: 'Design' }
);

// ---------- IMPLEMENT ----------
phase('Implement');
const IMPL_SCHEMA = {
  type: 'object', additionalProperties: false,
  required: ['filesChanged', 'enginePasses', 'pageCounts', 'notes'],
  properties: {
    filesChanged: { type: 'array', items: { type: 'string' } },
    enginePasses: { type: 'boolean', description: 'Node engine stress test passed (correct, unique, no undefined/NaN, varied)' },
    pageCounts: { type: 'string', description: 'pages reported by the print tool at each item count' },
    notes: { type: 'string' },
  },
};
const impl = await agent(
  [
    'You are the IMPLEMENTER. Build the resource.',
    '', brief, '', 'DESIGN:\n' + design, '', RULES,
    '',
    'Steps:',
    '1. Scaffold:  php dev/scaffold/scaffold.php <slug> "<Name>" <accent> <prefix>',
    '2. Implement generate()/solve()/renderItem() in public_html/assets/js/<slug>.js per the design; keep it self-contained.',
    '3. Add the route, catalogue entry (with image/how) and the Account ALLOWED_ACTIVITIES slug (the scaffold prints these).',
    '4. Node-test the engine (a few hundred runs: correct, unique, no undefined/NaN, varied). Lint php -l / node --check.',
    '5. Capture the feature image and print-assess EVERY item count:',
    '     node dev/print-preview/preview.js --slug <slug> --png --out public_html/assets/images/resources/<slug>.png',
    '     node dev/print-preview/preview.js --slug <slug> --out /tmp/<slug>.pdf   # and per count via --click',
    'Return files changed, whether the engine test passed, and the page counts. Do NOT claim done unless the engine passes and every count is 1 page.',
  ].join('\n'),
  { label: 'implement', phase: 'Implement', schema: IMPL_SCHEMA }
);

// ---------- REVIEW & PRINT loop ----------
phase('Review & Print');
const REVIEW_SCHEMA = {
  type: 'object', additionalProperties: false,
  required: ['pass', 'issues', 'report'],
  properties: {
    pass: { type: 'boolean' },
    issues: { type: 'array', items: { type: 'string' } },
    report: { type: 'string' },
  },
};
function review(round) {
  return agent(
    [
      'You are an adversarial REVIEWER. Verify the resource WORKS and PRINTS WELL; do not rubber-stamp.',
      '', brief, '', RULES,
      '',
      'Do all of this:',
      '1. Re-run the engine Node test and lints.',
      '2. Run the print tool for each item count and the answer-key tab; confirm "pages: N" is the expected single page at every setting, and READ the PDFs.',
      '3. Apply the Print checklist (page filled, no dead space inside cards, footer at bottom, items evenly distributed with captions attached, B&W legible) and the Integration checklist from the playbook.',
      '4. Check answer correctness on sampled output and that on-sheet copy is accurate; how-it-works is on the info page.',
      'pass=true only if everything holds; else list precise, actionable issues.',
    ].join('\n'),
    { label: 'review:r' + round, phase: 'Review & Print', schema: REVIEW_SCHEMA }
  );
}
function fix(round, rep) {
  return agent(
    [
      'You are the IMPLEMENTER. Fix every issue the reviewer found, re-run the engine test, lints and the print tool until they pass.',
      '', brief, '', RULES, '',
      'REVIEWER REPORT:\n' + rep.report, 'ISSUES:\n- ' + (rep.issues || []).join('\n- '),
    ].join('\n'),
    { label: 'fix:r' + round, phase: 'Review & Print', schema: IMPL_SCHEMA }
  );
}

let rev = await review(1), round = 1;
while (rev && !rev.pass && round < 4) {
  round++;
  await fix(round, rev);
  rev = await review(round);
}
return { design, impl, finalReview: rev, rounds: round };
