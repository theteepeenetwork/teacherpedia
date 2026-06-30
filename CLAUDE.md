# teacherpedia

CodeIgniter 4 app: printable, self-marking KS1–2 maths worksheet generators
(White Rose aligned). Resources are client-side JS that reuse a shared question
bank (`window.TP_GEN`) and print as clean A4.

## Building a resource

Follow **[dev/RESOURCE_WORKFLOW.md](dev/RESOURCE_WORKFLOW.md)** — the end-to-end
playbook. Start a new one with the scaffold:

```bash
php dev/scaffold/scaffold.php <slug> "<Name>" <#accent> <prefix>
```

Guiding principle: **agents for judgement, code for verification.** Don't spend
agent tokens on anything a Node/PHP script can check (correctness, page counts,
integration). See the playbook's token rules.

**Always finish with a print assessment** — a resource isn't done when it
*works*, it's done when it *prints well*:

```bash
node dev/print-preview/preview.js --slug <slug> --out /tmp/<slug>.pdf [--click '<css>']
```

It prints `pages: N` so overflow is caught automatically. Read the PDF and run
the Print checklist in the playbook.

## House pattern (quick reference)

- **Controller** thin → `view('<slug_us>/index', ['accent'=>…])`.
- **Route** in `app/Config/Routes.php`; **catalogue** entry in
  `app/Models/ActivityModel.php::catalog()` (`image` + `how` power the info page
  at `/resource/<slug>`).
- **View** extends `layouts/app`, uses `partials/tool_toolbar` (`prefix`), loads
  `tp-tool.js` then `<slug>.js`.
- **Shared JS** in `tp-tool.js`: `TP_wireYears`, `TP_effDifficulty(year, meter)`,
  `TP_diffDots`, `TP_batch`, `TP_loopCards`, `TP_shuffle`.
- **Saving** POSTs `activity`/`title`/`config` to `/account/save`; the slug must
  be in `Account::ALLOWED_ACTIVITIES`.
- The **how-it-works lives on the info page**, not the worksheet (keep the sheet
  to one task line).

## Layout gotchas (cost real iterations — read before styling a sheet)

1. **Render items directly into the grid element** — never nest a second grid
   inside a grid container, or `align-content`/row-fill silently does nothing.
2. `tp-print.css` forces `.sheet { position:absolute; min-height:0 !important;
   padding:0 !important }` — override with **`!important`** to fill the A4.
3. Fill the page with **compact cards + `align-content:space-between`** (slack
   between rows), not stretched cards (which strand small items in tall cells).
4. **Cap item size** (`max-width` on the SVG) so every item count fits **one
   page** — verify counts with the print tool.
5. Toolbar thumbs (`.diff-thumb`/`.seg-thumb`) are slid by `left`+`width` from
   the active button's offsets (see `maths-maze.js`), not percentages.

## Dev environment notes

- `composer install` then the app boots; this sandbox **kills HTTP servers**, so
  use `dev/print-preview` (renders via `Boot::bootTest` + Playwright request
  interception — **no server**) rather than `spark serve`.
- Avoid foreground `sleep` in shell (it's blocked); wait with `curl --retry`.
- Ignore the `* 2.php` / `*2.js` duplicate files — they're stale copies.
