#!/usr/bin/env node
/**
 * preview.js — render a teacherpedia resource's REAL printed A4 page (or a PNG)
 * with NO web server, for visual print assessment in the resource workflow.
 *
 * Pipeline: PHP renders the view to HTML (preview.php) with asset URLs on a
 * dummy host -> Playwright loads it, fulfilling every asset from public_html via
 * request interception -> Chromium prints it to an A4 PDF (the true print
 * output, honouring @page) and reports the page count.
 *
 * Usage:
 *   node dev/print-preview/preview.js --slug arithmagons --out /tmp/ag.pdf \
 *        --click '#ag-count [data-count="9"]'
 *   node dev/print-preview/preview.js --view code_breaker/index --route /code-breaker \
 *        --accent '#2a6fdb' --wait '.sheet' --out /tmp/cb.pdf
 *
 * Flags: --slug (known resource) | --view + --route + --accent + --wait,
 *        --click '<css>' (repeatable, e.g. choose a count / open the answer key),
 *        --out <path> (.pdf default; --png for a screenshot of the .sheet),
 *        --width/--height (viewport px for --png).
 *
 * Requires the playwright npm module: `cd dev/print-preview && npm install`
 * (browsers are expected to be pre-installed; see README.md).
 */
const { execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..', '..');
const FCPATH = path.join(ROOT, 'public_html');

// Known resources. Built-ins plus anything in resources.json (the scaffold
// registers new resources there automatically), so the print tool auto-covers
// every resource without editing this file.
const RESOURCES = (() => {
  const r = {
    arithmagons:    { view: 'arithmagons/index', route: '/arithmagons', accent: '#7b4cc4', wait: '#ag-grid .ag-card' },
    'code-breaker': { view: 'code_breaker/index', route: '/code-breaker', accent: '#2a6fdb', wait: '.sheet' },
  };
  try {
    const f = path.join(__dirname, 'resources.json');
    if (fs.existsSync(f)) { Object.assign(r, JSON.parse(fs.readFileSync(f, 'utf8'))); }
  } catch (e) { /* ignore a malformed registry */ }
  return r;
})();

const argv = process.argv.slice(2);
const opt = (name, def) => { const i = argv.indexOf('--' + name); return i > -1 ? argv[i + 1] : def; };
const multi = (name) => argv.reduce((a, v, i) => (v === '--' + name ? a.concat(argv[i + 1]) : a), []);
const flag = (name) => argv.includes('--' + name);

const slug = opt('slug');
const reg = slug ? RESOURCES[slug] : null;
if (slug && !reg) { console.error('Unknown --slug "' + slug + '". Known: ' + Object.keys(RESOURCES).join(', ')); process.exit(1); }

const view = opt('view', reg && reg.view);
const route = opt('route', reg ? reg.route : '/preview');
const accent = opt('accent', reg ? reg.accent : '#1f8a4d');
const wait = opt('wait', reg && reg.wait);
const clicks = multi('click');
const png = flag('png');
const out = opt('out', path.join(ROOT, 'writable', (slug || 'preview') + (png ? '.png' : '.pdf')));
const width = parseInt(opt('width', '820'), 10);
const height = parseInt(opt('height', '1400'), 10);

if (!view) { console.error('Need --slug <known> or --view <viewPath> (+ --route).'); process.exit(1); }

// 1) Render the view to HTML via PHP (no server).
const html = execFileSync('php', [path.join(__dirname, 'preview.php'), view, accent], { encoding: 'utf8', maxBuffer: 1 << 25 });

// 2) Find the pre-installed Chromium (PLAYWRIGHT_BROWSERS_PATH), else let Playwright decide.
function findChromium() {
  const base = process.env.PLAYWRIGHT_BROWSERS_PATH;
  if (base && fs.existsSync(base)) {
    for (const d of fs.readdirSync(base)) {
      if (/^chromium-\d/.test(d)) {
        const p = path.join(base, d, 'chrome-linux', 'chrome');
        if (fs.existsSync(p)) return p;
      }
    }
  }
  return undefined;
}
const exe = findChromium();

const TYPES = { '.css': 'text/css', '.js': 'application/javascript', '.png': 'image/png', '.svg': 'image/svg+xml', '.json': 'application/json', '.woff2': 'font/woff2', '.woff': 'font/woff', '.jpg': 'image/jpeg', '.jpeg': 'image/jpeg' };

(async () => {
  const { chromium } = require('playwright');
  const browser = await chromium.launch(exe ? { executablePath: exe } : {});
  const page = await browser.newPage({ viewport: { width, height, deviceScaleFactor: 2 } });

  await page.route('**/*', (r) => {
    const u = new URL(r.request().url());
    if (u.hostname === 'tp.local') {
      if (u.pathname === route) return r.fulfill({ contentType: 'text/html', body: html });
      const f = path.join(FCPATH, u.pathname);
      if (fs.existsSync(f) && fs.statSync(f).isFile()) {
        return r.fulfill({ contentType: TYPES[path.extname(f)] || 'application/octet-stream', body: fs.readFileSync(f) });
      }
      return r.fulfill({ status: 404, body: '' });
    }
    return r.abort(); // external (e.g. web-font CDNs) — skip, fall back to system fonts
  });

  await page.goto('http://tp.local' + route, { waitUntil: 'load' });
  if (wait) { try { await page.waitForSelector(wait, { timeout: 8000 }); } catch (e) { console.error('warn: wait selector not found: ' + wait); } }
  for (const c of clicks) { try { await page.click(c); } catch (e) { console.error('warn: click failed: ' + c); } }
  await page.waitForTimeout(300);

  if (png) {
    const el = await page.$('.sheet');
    if (el) { await el.screenshot({ path: out }); } else { await page.screenshot({ path: out, fullPage: true }); }
  } else {
    await page.emulateMedia({ media: 'print' });
    await page.pdf({ path: out, format: 'A4', printBackground: true, preferCSSPageSize: true });
    const pages = (fs.readFileSync(out).toString('latin1').match(/\/Type\s*\/Page[^s]/g) || []).length;
    console.log('pages: ' + pages);
  }
  await browser.close();
  console.log('wrote ' + out);
})().catch((e) => { console.error(e.message); process.exit(1); });
