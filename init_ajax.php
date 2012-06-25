<?php

include_once "system/core.php";

ini_set("default_charset", "utf-8");

$config = Core::getConfig("config.php");

ini_set('display_errors', $config['php']['php_error']);
error_reporting(E_ALL);

Core::initMySQL($config['db']);


$script=explode(".",$_GET['script']);
include_once "components/{$script[0]}/ajax/{$script[1]}.php";
