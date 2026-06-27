<?php

/**
 * preview.php — render a resource VIEW to standalone HTML for offline print preview.
 *
 * Boots CodeIgniter without an HTTP request (Boot::bootTest) and renders a view
 * with the asset base URL pointed at a dummy host (http://tp.local/), so the
 * Node side can serve the assets straight from disk and produce a real A4 PDF
 * with no running web server. See README.md.
 *
 *   php dev/print-preview/preview.php <view> [accentHex]   > out.html
 *   e.g. php dev/print-preview/preview.php arithmagons/index '#7b4cc4'
 */

$ROOT   = dirname(__DIR__, 2);
$view   = $argv[1] ?? null;
$accent = $argv[2] ?? '#1f8a4d';

if ($view === null) {
    fwrite(STDERR, "usage: php preview.php <view> [accentHex]\n");
    exit(1);
}

define('FCPATH', $ROOT . '/public_html/');
putenv('CI_ENVIRONMENT=development');
define('ENVIRONMENT', 'development');
chdir($ROOT);

require $ROOT . '/vendor/autoload.php';
require $ROOT . '/app/Config/Paths.php';
$paths = new Config\Paths();

// bootTest expects the path constants already defined (normally the phpunit
// bootstrap does this); define them from the configured directories.
define('APPPATH', realpath($paths->appDirectory) . '/');
define('ROOTPATH', realpath($paths->appDirectory . '/../') . '/');
define('SYSTEMPATH', realpath($paths->systemDirectory) . '/');
define('WRITEPATH', realpath($paths->writableDirectory) . '/');

require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootTest($paths);

// Point asset URLs at a dummy host the Node renderer intercepts and serves
// from public_html — so no web server is needed.
config('App')->baseURL = 'http://tp.local/';

echo view($view, ['accent' => $accent]);
