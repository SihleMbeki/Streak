<?php
// Load Composer autoloader first
require_once __DIR__.'/../vendor/autoload.php';

// Define environment if not already defined
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'testing');
}

// Load CodeIgniter core files
require_once __DIR__.'/../system/core/Common.php';
require_once __DIR__.'/../system/core/Controller.php';
require_once __DIR__.'/../system/core/Input.php';
require_once __DIR__.'/../system/core/Loader.php';
require_once __DIR__.'/../system/core/Output.php';
require_once __DIR__.'/../system/core/Model.php';

// Load base TestCase
require_once __DIR__.'/TestCase.php';