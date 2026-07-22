<?php

if (!defined('ABSPATH')) {
    exit;
}

// Shared helpers
require_once __DIR__ . '/helpers.php';

// Core graph class
require_once __DIR__ . '/classes/graph.php';

// Load all schema classes
foreach (glob(__DIR__ . '/classes/*.php') as $file) {

    // Skip Graph because it's already loaded
    if (basename($file) === 'graph.php') {
        continue;
    }

    require_once $file;
}

// Register wp_head hook
require_once __DIR__ . '/schema.php';