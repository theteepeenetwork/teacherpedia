# Print preview — visual print assessment for resources

A resource isn't done when it *works*; it's done when it **prints well**. This
tool renders a resource's **real A4 page** (Chromium PDF, honouring the print
CSS and `@page`) so you can eyeball space usage, footer position, pagination and
B&W legibility — the things an HTML "does it render" check can't catch.

It needs **no web server** (handy in sandboxes that block long-running servers):
PHP renders the view to HTML with asset URLs on a dummy host, then Playwright
serves those assets from `public_html/` via request interception and prints the
page.

## One-time setup

```bash
cd dev/print-preview
# browsers are expected to be pre-installed; don't let npm fetch them
PLAYWRIGHT_SKIP_BROWSER_DOWNLOAD=1 npm install
```

The renderer auto-detects a Chromium under `$PLAYWRIGHT_BROWSERS_PATH`; if that
isn't set it falls back to Playwright's default (run `npx playwright install
chromium` once if you have no browser at all). Composer deps must be installed
(`composer install`) so CodeIgniter can boot.

## Usage

```bash
# A known resource, default layout -> A4 PDF + page count
node dev/print-preview/preview.js --slug arithmagons --out /tmp/ag.pdf

# Drive variants with --click (repeatable): choose 9 puzzles, open the answer key
node dev/print-preview/preview.js --slug arithmagons --out /tmp/ag9.pdf \
  --click '#ag-count [data-count="9"]'

# Any view by hand (for a resource not in the registry yet)
node dev/print-preview/preview.js \
  --view code_breaker/index --route /code-breaker --accent '#2a6fdb' \
  --wait '.sheet' --out /tmp/cb.pdf

# A PNG screenshot of the on-screen sheet instead of the print PDF
node dev/print-preview/preview.js --slug arithmagons --png --out /tmp/ag.png
```

Then open the PDF (or, in an agent/CI context, read it back and look at it). The
command prints `pages: N` so 1-page-vs-overflow is checked automatically.

Add new resources to the `RESOURCES` map at the top of `preview.js`
(`view`, `route`, `accent`, `wait` selector).

## Where it fits in the resource workflow

Insert a **Print Assessment** stage between Review and Publish:

1. Render the A4 PDF at each meaningful setting (min / typical / max item counts;
   worksheet **and** answer-key tabs).
2. Auto-check: expected page count, no overflow.
3. Visually check: the page is filled (no large dead space), the footer sits at
   the bottom, nothing is clipped or cramped, and it reads in black-and-white.
4. Any failure loops back to the engineer, like the correctness gate does.
