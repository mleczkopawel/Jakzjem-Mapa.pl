<?php

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
date_default_timezone_set("Europe/Warsaw");
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

ini_set("error_reporting", E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_STRICT);
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
define('ROOT_PATH', dirname(__DIR__));

if (strpos(APPLICATION_ENV, "dev") !== FALSE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
