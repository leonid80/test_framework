<?php

include_once "init.php";

include_once "system/core.php";

Core::setConfig($config);

Core::initMySQL();

Core::parseURL();

include_once "init_".SIDE.".php";

Core::initController();

Core::initComponents();

Core::createJSList();

Core::renderComponents();

Core::renderLayout();

//debug($_SERVER);
//debug($_SESSION);
//Debug($_POST);
//Debug($_GET);
