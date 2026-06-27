<?php

/**
 * year-coverage.php <slug> — assert a resource only OFFERS the year groups its
 * content is actually appropriate for, and that this range is declared
 * consistently across the toolbar (view) and the catalogue.
 *
 * Why: a resource that leaves the Year selector at the default 1-6 while its
 * content is (say) Y3-6 will happily generate off-curriculum sheets for Y1/Y2 —
 * a bug that slips through if testing only exercises the "intended" years. This
 * check ties the offered range together; the resource's own bespoke validator
 * must then exercise the engine across the WHOLE offered range, asserting
 * curriculum-appropriateness at each year. See dev/RESOURCE_WORKFLOW.md.
 *
 *   php dev/validate/year-coverage.php cross-number
 */

$ROOT = dirname(__DIR__, 2);
$slug = $argv[1] ?? null;
if ($slug === null) { fwrite(STDERR, "usage: php year-coverage.php <slug>\n"); exit(2); }

define('FCPATH', $ROOT . '/public_html/');
putenv('CI_ENVIRONMENT=development');
define('ENVIRONMENT', 'development');
chdir($ROOT);
require $ROOT . '/vendor/autoload.php';
require $ROOT . '/app/Config/Paths.php';
$paths = new Config\Paths();
define('APPPATH', realpath($paths->appDirectory) . '/');
define('ROOTPATH', realpath($paths->appDirectory . '/../') . '/');
define('SYSTEMPATH', realpath($paths->systemDirectory) . '/');
define('WRITEPATH', realpath($paths->writableDirectory) . '/');
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootTest($paths);

$entry = \App\Models\ActivityModel::bySlug($slug);
if ($entry === null) { fwrite(STDERR, "no catalogue entry for '$slug'\n"); exit(2); }

// Catalogue coverage (catalog() defaults live tools to 1-6 when unset).
$catMin = isset($entry['min_year']) && $entry['min_year'] !== null ? (int) $entry['min_year'] : ($entry['status'] === 'live' ? 1 : null);
$catMax = isset($entry['max_year']) && $entry['max_year'] !== null ? (int) $entry['max_year'] : ($entry['status'] === 'live' ? 6 : null);

// Toolbar offered range (tool_toolbar year_min/year_max; default 1-6).
$slugUs = str_replace('-', '_', $slug);
$view = "$ROOT/app/Views/$slugUs/index.php";
$tbMin = 1; $tbMax = 6;
if (is_file($view)) {
    $src = file_get_contents($view);
    if (preg_match("/'year_min'\\s*=>\\s*(\\d+)/", $src, $m)) { $tbMin = (int) $m[1]; }
    if (preg_match("/'year_max'\\s*=>\\s*(\\d+)/", $src, $m)) { $tbMax = (int) $m[1]; }
}

$tb  = 'Y' . $tbMin . '-Y' . $tbMax;
$cat = 'Y' . ($catMin ?? '?') . '-Y' . ($catMax ?? '?');
echo "Resource: $slug\n";
echo "  Toolbar offers:   $tb\n";
echo "  Catalogue covers: $cat\n";

$ok = ($catMin === $tbMin && $catMax === $tbMax);
if ($ok) {
    echo "PASS: year ranges agree ($tb). REQUIREMENT: the bespoke validator must exercise\n";
    echo "      the engine across $tb and assert curriculum-appropriateness at EACH year —\n";
    echo "      if content can't be appropriate at some year, narrow the offered range.\n";
    exit(0);
}
echo "FAIL: toolbar ($tb) and catalogue ($cat) disagree. Set BOTH (tool_toolbar\n";
echo "      year_min/year_max AND catalogue min_year/max_year) to the resource's real\n";
echo "      curriculum span, then re-run.\n";
exit(1);
