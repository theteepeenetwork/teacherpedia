# Resource development workflow

How we build a new printable resource for teacherpedia (Code Breaker, Maths
Maze, Arithmagon Triangles, …) so it's **correct**, fits the **house pattern**,
and **prints well** — the same way every time, flexibly to whatever the content
is.

The guiding principle, learned the hard way:

> **Agents for judgement, code for verification.** Never spend agent/LLM tokens
> on anything a script can check. Reserve agents for design, review and other
> subjective work; let deterministic Node/PHP scripts prove correctness, page
> counts and integration.

---

## What a "resource" is

A resource is a **printable, self-marking activity** generated client-side that
reuses the shared question bank (`window.TP_GEN`, ~450 generators returning
`{question, answer}` and sometimes an SVG). The pieces:

| Piece | Where |
|---|---|
| Controller (thin) | `app/Controllers/<Name>.php` → `view('<slug_us>/index', ['accent'=>…])` |
| Route | `app/Config/Routes.php` (`$routes->get('<slug>', '<Name>::index')`) |
| Catalogue entry | `app/Models/ActivityModel.php` → `catalog()` `$base[]` |
| View | `app/Views/<slug_us>/index.php` (extends `layouts/app`, uses `partials/tool_toolbar`) |
| Client JS | `public_html/assets/js/<slug>.js` (loaded after `tp-tool.js`) |
| Info page | auto — `/resource/<slug>` renders from the catalogue (`image` + `how`) |
| Saving | POST `/account/save` with `activity`/`title`/`config`; slug must be in `Account::ALLOWED_ACTIVITIES` |

Use `dev/scaffold` to stamp out a skeleton with all of this wired correctly.

---

## The phases

### 0 · Scope & pattern study  *(main loop, ~0 agent tokens)*
Read **one** existing resource end-to-end (`Maths Maze` is a good model):
controller, view, `app/Views/partials/tool_toolbar.php`,
`public_html/assets/js/tp-tool.js`, `public_html/assets/css/tp-print.css`. Pin
the exact files you'll touch. Decide the slug, name, prefix, accent colour.

### 1 · Design  *(1 agent, or inline)*
A concept spec: the mechanic, how it draws on the question bank, why it engages
children, the **objective + Below/Meeting/Exceeding mapping**, the self-marking
mechanism, and the print format. (For a batch of ideas, a small research/refine
team; for one resource, one designer or do it yourself.)

### 2 · Engine first  *(main loop, 0 tokens)*
Write the **pure logic** and expose it for tests (`window.<NS> = { generate,
solve, … }`) **before any DOM**. The generator contract:
- returns `{ qtn: <non-empty string>, ans: <non-empty> }`, optionally `qhtml`
  (inline SVG) for visuals;
- the answer is **objectively correct and uniquely determined** by the question;
- depends only on the in-scope helpers (`ri, pick, gcd, fmt, frac, words,
  toRoman, shuffle`) — define any SVG/escaping helper **inside** your code so it
  is self-contained (don't reference private file helpers);
- vanilla ES5-ish, no build step;
- enough randomised variety; never a degenerate/ambiguous item.

### 3 · Verify in code  *(script, ~0 tokens)*
Node-stress the engine **before** wiring UI:
```bash
node -e 'global.window={}; require("./public_html/assets/js/<slug>.js");
  /* run generate()/solve() a few hundred×: assert correct, unique, no
     undefined/NaN/null, varied */'
```
Lint: `php -l` on PHP, `node --check` on JS. Fail fast here.

### 4 · Wire UI  *(main loop)*
Controller, route, catalogue entry, view, JS DOM — mirror the reference
resource. Critical details in **Integration checklist** and **Layout gotchas**
below. Saving posts `activity`/`title`/`config`; add the slug to
`Account::ALLOWED_ACTIVITIES`.

### 5 · Info page + strip on-sheet instructions
The how-it-works lives on the **info page**, not the worksheet. Add `image`
(`/assets/images/resources/<slug>.png`) and `how` (3–5 step strings) to the
catalogue entry; reduce the sheet to a **single short task line**.

### 6 · Review  *(1 adversarial agent)*
Independent reviewer checks what code can't: answer correctness on sampled
output, house-pattern integration, copy accuracy (claims true for every mode),
edge cases, B&W legibility. Returns a precise issue list.

### 7 · Print assessment  *(script + eyes)*  ← **do not skip**
A resource isn't done when it *works*; it's done when it **prints well**. Use
the print tool to render the **real A4 page** at each meaningful setting (min /
typical / max item counts, **worksheet and answer-key tabs**):
```bash
node dev/print-preview/preview.js --slug <slug> --out /tmp/<slug>.pdf \
     --click '<css for a variant>'
```
It prints `pages: N` (overflow caught automatically). Read the PDF and run the
**Print checklist** below.

### 8 · Fix loop
Address review + print issues, re-run the deterministic checks, until both pass.

### 9 · Publish
Commit, push to the working branch, open/refresh a **draft PR**.

---

## Integration checklist

- [ ] Route added; tool page returns 200; tool routes unchanged.
- [ ] Catalogue entry in `ActivityModel::catalog()` (`slug, name, description,
      icon, tags, status:'live', route, sort_order, image, how`).
- [ ] Browse card links to `/resource/<slug>` (live resources go via the info page).
- [ ] Info page `/resource/<slug>` 200: feature image, How-it-works, "Open
      resource" link to the tool. Unknown slug → 404.
- [ ] View loads `tp-tool.js` **then** `<slug>.js`; sets `TP_SAVE_URL`/`TP_LOGIN_URL`.
- [ ] Toolbar `partials/tool_toolbar` with a `prefix`; JS binds the matching ids
      (`<prefix>-years`/`data-yr`, `<prefix>-difficulty`/`data-diff`,
      `<prefix>-diff-label`, `<prefix>-eyebrow-diff`, `<prefix>-tabs`/`data-tab`,
      `<prefix>-save`/`-print`/`-regen`/`-regen-icon`).
- [ ] Year/difficulty via `TP_wireYears` + `TP_effDifficulty(year, meter)`.
- [ ] **Year range matches the resource's real curriculum span.** If the content
      isn't appropriate at the extremes (e.g. multi-digit / ×÷ puzzles aren't
      KS1), set `tool_toolbar` `year_min`/`year_max` (disables out-of-range year
      chips) **and** the catalogue `min_year`/`max_year` (Browse filter), and
      clamp the year defensively in the engine. Don't leave it 1–6 by default.
      Verify with `php dev/validate/year-coverage.php <slug>` (toolbar must equal
      catalogue), and make the **bespoke validator exercise EVERY offered year**
      (year_min..year_max), asserting curriculum-appropriateness at each — not a
      hand-picked subset. (This is the test that catches "offers a year whose
      content isn't on its curriculum"; if a year can't be made appropriate,
      narrow the offered range.)
- [ ] Save posts `activity:'<slug>'` + `title` + `config`; slug in
      `Account::ALLOWED_ACTIVITIES`.
- [ ] **Every UI control actually changes the output.** Toggle each setting
      (operation chips, modes, counts) and assert the generated content reflects
      it — a control that's offered but filtered out downstream (or never
      re-renders) is a silent no-op. Test it in Node where you can.

## Print checklist  *(via dev/print-preview)*

- [ ] Fits the **expected page count at every setting** (watch `pages: N` — a
      bigger item count must not silently spill to a 2nd page).
- [ ] The page is **filled** — no large dead space, and none **inside** the
      cards/items.
- [ ] Footer sits at the **bottom** of the page.
- [ ] Items are **evenly distributed**; captions/labels attached to their item.
- [ ] Reads cleanly in **black-and-white** (given/blank cells distinguishable;
      operators/keys visible without relying on colour).
- [ ] Answer-key tab is unambiguous.

## Layout gotchas  *(these cost real iterations — heed them)*

1. **Render items DIRECTLY into the grid element.** If your container already
   has the grid class/`display:grid`, do **not** wrap the items in a second grid
   inside it — the inner grid swallows the real rows and `align-content` /
   row-fill silently does nothing. Set `--cols` on the grid element and append
   item cards straight in.
2. **`tp-print.css` isolates the sheet** with
   `.sheet { position:absolute; min-height:0 !important; padding:0 !important }`.
   To fill the printable A4 you must override with **`!important`** on your own
   sheet class (e.g. `min-height: 255mm !important; display:flex !important;
   flex-direction:column !important`).
3. **Fill the page with compact cards + distributed rows**, not stretched cards.
   Stretching rows (`grid-auto-rows:1fr` + `height:100%`) leaves small items
   stranded in tall cells. Instead keep cards compact and use
   `align-content: space-between` on the (real) grid so the slack falls between
   rows. Footer pins with `margin-top` after the grid.
4. **Cap the item size so every count fits one page.** Wide (2-column) layouts
   make items tall enough to overflow to a 2nd page; cap with `max-width` on the
   item SVG (centred) so 4-/6-/9-up all stay single-page. Verify with the tool.
5. **Toolbar thumbs are measured, not percentaged.** Slide `.diff-thumb` /
   `.seg-thumb` by setting `left` **and** `width` from the active button's
   `offsetLeft`/`offsetWidth` (see `maths-maze.js`); the shared CSS gives them no
   width. Don't invent classes (`seg-on`) that have no CSS.
6. **Self-contained SVG.** Visual generators must define their own SVG/escaping
   helpers so they behave identically in isolation tests and in the file.

---

## Token-efficiency rules

- **Verifiable → code; generative/subjective → agents.** The single biggest saver.
- **Engine before UI** — catch maths bugs in a 0-token Node run, not after building a page.
- **Agents return structured data; the main loop integrates** — avoids parallel
  file-write conflicts and re-reads.
- **Give each agent only the context it needs.**
- **Right-size the team:** one reviewer for one artifact; fan out per-item only
  when items are genuinely independent *and* not centrally verifiable. A single
  new resource ≈ 2 agents (design + review) plus deterministic scripts.
- **Recover deterministically:** if an agent run dies mid-way (e.g. session
  limit), harvest its structured output and finish the checkable parts in code.

---

## Definition of done

Engine Node-verified · lints clean · integration checklist green · info page +
instructions stripped · adversarial review passed · **print checklist green at
every setting (correct page counts)** · committed and in a draft PR.

---

## Tooling

- `dev/scaffold/scaffold.php` — stamp out a new resource skeleton (all of the
  above wired correctly). See `dev/scaffold/README.md`.
- `dev/print-preview/preview.js` — render the real A4 print PDF (or a PNG), no
  server needed. See `dev/print-preview/README.md`.
- `dev/validate/year-coverage.php <slug>` — assert the toolbar's offered year
  range equals the catalogue's (so a resource can't silently offer a year its
  content isn't on the curriculum for).
- `dev/validate/<slug>-validate.js` — each resource ships a **bespoke correctness
  validator** (the generic gate proves it *prints*, not that the *content* is
  right). It runs the engine across the **full offered year range** and every
  difficulty, asserting the answer is correct, the activity's invariants hold
  (e.g. crossword intersections agree), and clues are curriculum-appropriate for
  that year. See `dev/validate/cn-validate.js` for the pattern.
