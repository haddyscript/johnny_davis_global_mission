<?php
/**
 * Ministry page entry point.
 *
 * Accessed directly:  http://example.com/views/johnny-davis-ministry.php  → $base = '../'
 * Accessed via root:  http://example.com/johnny-davis-ministry.php         → $base = ''
 */
$base ??= '../';

require_once __DIR__ . '/../bootstrap.php';

$page = new MinistryPage($base);
$page->render();
