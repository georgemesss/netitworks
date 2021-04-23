<?php

use NetItWorks\Controller;
use NetItWorks\Database;

$parentDir = dirname(__DIR__, 1);
require_once $parentDir . '/vendor/autoload.php';
require_once $parentDir . '/config/controller_config.php';

$controller = new Controller(
    $controller_conf['name'],
    $controller_conf['description'],
    $controller_conf['ip'],
    $controller_conf['port'],
    $controller_conf['username'],
    $controller_conf['password'],
    $controller_conf['disabled']
);

$database = new Database(
    $database_conf['ip'],
    $database_conf['port'],
    $database_conf['username'],
    $database_conf['password'],
    $database_conf['disabled']
);

?>