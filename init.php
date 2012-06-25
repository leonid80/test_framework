<?php
/**************************************************************************************
 Here are defined the basic settings required for the application.

 Author: Loginov Leonid
 File revision: 1.0.0

 Copyright (c) 2011 Loginov Leonid
***************************************************************************************/
if (!defined("PATH_SEPARATOR"))
  define("PATH_SEPARATOR", getenv("COMSPEC")? ";" : ":");
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.dirname(__FILE__));

require_once "system/helpers.php";

$config_file = $_SERVER['SERVER_ADDR'] == "127.0.0.1" ? "config.local.ini" : "config.ini";

$config = getConfig($config_file);

if($config['debug']['hacker_console']){
    require_once "system/3d-party/HackerConsole/Main.php";
    new Debug_HackerConsole_Main(true); // Create & attach hacker conole to HTML output.
}


ini_set("default_charset", "utf-8");

ini_set('display_errors', $config['debug']['display_php_error']);
$config['debug']['display_php_error'] ? error_reporting(E_ALL & ~E_DEPRECATED) : error_reporting(0);

define('BASE_HREF', "http://{$_SERVER['HTTP_HOST']}");

define('CRYPT_KEY', 'shdf356DSWJW466');

define('CRYPT_ALG', 'rijndael-256');

define('CRYPT_MODE', 'cbc');
