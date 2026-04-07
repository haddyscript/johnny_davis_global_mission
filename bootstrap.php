<?php
/**
 * Simple PSR-style autoloader for the /classes directory.
 * Maps class names (e.g. MinistryPage) to /classes/MinistryPage.php.
 */
spl_autoload_register(function (string $class): void {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
