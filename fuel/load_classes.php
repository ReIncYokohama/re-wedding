<?php
/**
 * Set error reporting and display errors settings.  You will want to change these when in production.
 */
error_reporting(0);
ini_set('display_errors', 0);


define('DOCROOT', realpath(__DIR__).DIRECTORY_SEPARATOR);
/**
 * Path to the application directory.
 */
define('APPPATH', realpath(__DIR__.'/app/').DIRECTORY_SEPARATOR);

/**
 * Path to the default packages directory.
 */
define('PKGPATH', realpath(__DIR__.'/packages/').DIRECTORY_SEPARATOR);

/**
 * The path to the framework core.
 */
define('COREPATH', realpath(__DIR__.'/core/').DIRECTORY_SEPARATOR);

// Get the start time and memory for use later
defined('FUEL_START_TIME') or define('FUEL_START_TIME', microtime(true));
defined('FUEL_START_MEM') or define('FUEL_START_MEM', memory_get_usage());

// Boot the app
require APPPATH.'bootstrap.php';
