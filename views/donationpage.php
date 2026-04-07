<?php
/**
 * Donation page entry point.
 *
 * Accessed directly:  http://example.com/views/donationpage.php  → $base = '../'
 * Accessed via root:  http://example.com/donationpage.php         → $base = ''
 */
$base ??= '../';

require_once __DIR__ . '/../bootstrap.php';

$page = new DonationPage($base);
$page->render();
