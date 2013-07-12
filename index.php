#!/usr/bin/env php
<?php
$zf2Path = __DIR__."/vendor/zendframework/zendframework/library";
require(__DIR__.'/init_autoloader.php');
Zend\Mvc\Application::init(require(__DIR__.'/config/application.config.php'))->run();
