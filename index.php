<?php
chdir(__DIR__);

$zf2Path = __DIR__."/vendor/zendframework/zendframework/library";

require 'init_autoloader.php';

Zend\Mvc\Application::init(require 'config/application.config.php')->run();
