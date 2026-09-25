<?php
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (require __DIR__ . '/sitemap_entries.php' as [$loc, , , $mod, $pri])
    echo "  <url><loc>" . e($loc) . "</loc>" . ($mod ? "<lastmod>$mod</lastmod>" : '') . "<priority>$pri</priority></url>\n";
echo "</urlset>\n";
