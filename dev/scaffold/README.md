# Resource scaffold

Stamp out a new resource skeleton with the house pattern and every print/layout
lesson already baked in (see [../RESOURCE_WORKFLOW.md](../RESOURCE_WORKFLOW.md)).

```bash
php dev/scaffold/scaffold.php <slug> "<Name>" <#accent> <prefix>
# e.g.
php dev/scaffold/scaffold.php number-pyramids "Number Pyramids" '#2a9d8f' np
```

It writes:

- `app/Controllers/<Class>.php` — thin controller
- `app/Views/<slug_us>/index.php` — view (toolbar partial, print-fill CSS,
  one-page cap, footer pinned)
- `public_html/assets/js/<slug>.js` — JS skeleton: pure engine exposed as
  `window.<NS>` for tests, DOM wiring, **items rendered directly into the grid**
  (no nested-grid bug), compact cards + `align-content:space-between`, measured
  toolbar thumbs, correct save payload

…and registers the resource with `dev/print-preview` so the print tool covers it
immediately. It then prints the three manual wiring steps it can't safely
automate: the **route**, the **catalogue entry** (with `image`/`how` for the
info page), and adding the slug to `Account::ALLOWED_ACTIVITIES`.

## After scaffolding

Follow the playbook from phase 2:

1. Implement `generate()` / `renderItem()` (and `solve()` if relevant) in the JS.
2. Node-test the engine; `php -l` / `node --check`.
3. Add the route, catalogue entry and save-allowlist (printed by the scaffold).
4. Capture the feature image and **print-assess** every item count:
   ```bash
   node dev/print-preview/preview.js --slug <slug> --png \
        --out public_html/assets/images/resources/<slug>.png
   node dev/print-preview/preview.js --slug <slug> --out /tmp/<slug>.pdf   # check "pages: N"
   ```
5. Review, fix, publish.

The skeleton renders a placeholder "a + b = ___" item so the page is viewable
before you write the real logic — replace `generate`/`renderItem`.
