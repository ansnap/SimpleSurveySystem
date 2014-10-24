<?php

error_reporting(E_ALL);

include 'config.php';

define('DIRSEP', DIRECTORY_SEPARATOR);

$site_path = realpath(dirname(__FILE__)) . DIRSEP;
define('SITE_PATH', $site_path);

spl_autoload_register(function ($class_name) {
    $dirs = ['classes', 'controllers', 'repositories'];

    foreach ($dirs as $dir) {
        $file = SITE_PATH . $dir . DIRSEP . $class_name . '.php';

        if (!file_exists($file)) {
            continue;
        }

        include($file);
        break;
    }
});

// Create registry of neccessary classes
$registry = new Registry();

// Connect to database
$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_user, $db_password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
$registry->set('db', $db);

// Add routing component
$router = new Router($registry, SITE_PATH . 'controllers' . DIRSEP);
$registry->set('router', $router);
$router->runController();
