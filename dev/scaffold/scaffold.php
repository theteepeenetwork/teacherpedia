<?php

/**
 * scaffold.php — stamp out a new teacherpedia resource skeleton with the house
 * pattern and all the print/layout lessons baked in (see dev/RESOURCE_WORKFLOW.md).
 *
 *   php dev/scaffold/scaffold.php <slug> "<Name>" <#accent> <prefix>
 *   e.g. php dev/scaffold/scaffold.php number-pyramids "Number Pyramids" '#2a9d8f' np
 *
 * Writes the controller, view and JS, registers the resource with the print
 * tool, and prints the remaining wiring (route, catalogue entry, save allowlist).
 */

$ROOT = dirname(__DIR__, 2);
$args = array_slice($argv, 1);
if (count($args) < 4) {
    fwrite(STDERR, "usage: php scaffold.php <slug> \"<Name>\" <#accent> <prefix>\n");
    exit(1);
}
[$slug, $name, $accent, $prefix] = $args;

$slug = strtolower(trim($slug));
if (!preg_match('/^[a-z][a-z0-9-]*$/', $slug)) { fwrite(STDERR, "slug must be kebab-case (a-z 0-9 -)\n"); exit(1); }
if ($accent[0] !== '#') { $accent = '#' . $accent; }
$prefix = strtolower(preg_replace('/[^a-z0-9]/i', '', $prefix));
$slug_us = str_replace('-', '_', $slug);
$class = str_replace(' ', '', ucwords(str_replace('-', ' ', $slug)));
$ns = strtoupper($prefix);

$repl = [
    '__SLUG__' => $slug, '__SLUG_US__' => $slug_us, '__CLASS__' => $class,
    '__NAME__' => $name, '__ACCENT__' => $accent, '__PREFIX__' => $prefix, '__NS__' => $ns,
];

$targets = [
    'controller.php.tpl' => "app/Controllers/$class.php",
    'view.php.tpl'       => "app/Views/$slug_us/index.php",
    'script.js.tpl'      => "public_html/assets/js/$slug.js",
];

// Refuse to clobber anything.
foreach ($targets as $dest) {
    if (file_exists("$ROOT/$dest")) { fwrite(STDERR, "refusing to overwrite existing $dest\n"); exit(1); }
}

foreach ($targets as $tpl => $dest) {
    $body = strtr(file_get_contents(__DIR__ . "/templates/$tpl"), $repl);
    @mkdir(dirname("$ROOT/$dest"), 0777, true);
    file_put_contents("$ROOT/$dest", $body);
    echo "created  $dest\n";
}

// Register with the print-preview tool so it auto-covers this resource.
$regPath = "$ROOT/dev/print-preview/resources.json";
$reg = is_file($regPath) ? json_decode(file_get_contents($regPath), true) : [];
if (!is_array($reg)) { $reg = []; }
$reg[$slug] = [
    'view' => "$slug_us/index", 'route' => "/$slug", 'accent' => $accent,
    'wait' => "#$prefix-grid .$prefix-card",
];
file_put_contents($regPath, json_encode($reg, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
echo "registered with dev/print-preview (resources.json)\n";

// Print the remaining manual wiring.
$icon = '▦';
echo "\n--- NEXT: wire these by hand ---\n\n";
echo "1) Route — app/Config/Routes.php (with the activity tools):\n";
echo "   \$routes->get('$slug', '$class::index');\n\n";
echo "2) Catalogue — app/Models/ActivityModel.php catalog() \$base[]:\n";
echo "   ['slug' => '$slug', 'name' => '$name',\n";
echo "    'description' => 'TODO short description.',\n";
echo "    'blurb' => 'TODO 2-4 sentence description for the info page.',\n";
echo "    'icon' => '$icon', 'tags' => 'TODO,Self-marking', 'status' => 'live', 'route' => '/$slug', 'sort_order' => 99,\n";
echo "    'image' => '/assets/images/resources/$slug.png',\n";
echo "    'how' => ['TODO step 1', 'TODO step 2', 'TODO step 3']],\n\n";
echo "3) Saving — app/Controllers/Account.php ALLOWED_ACTIVITIES: add '$slug'.\n\n";
echo "Then follow dev/RESOURCE_WORKFLOW.md from phase 2 (engine first):\n";
echo "  - implement generate()/renderItem() in public_html/assets/js/$slug.js\n";
echo "  - Node-test the engine; lint\n";
echo "  - capture the feature image + print-assess:\n";
echo "      node dev/print-preview/preview.js --slug $slug --png --out public_html/assets/images/resources/$slug.png\n";
echo "      node dev/print-preview/preview.js --slug $slug --out /tmp/$slug.pdf   # check 'pages: N'\n";
