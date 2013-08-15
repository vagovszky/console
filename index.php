#!/usr/bin/env php
<?php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $loader = include(__DIR__ . '/vendor/autoload.php');
}
if (isset($loader)) {
    $loader->add('Zend', __DIR__ . "/vendor/zendframework/zendframework/library");
} else {
    include (__DIR__ . "/vendor/zendframework/zendframework/library/Zend/Loader/AutoloaderFactory.php");
    Zend\Loader\AutoloaderFactory::factory(array(
        'Zend\Loader\StandardAutoloader' => array(
            'autoregister_zf' => true
        )
    ));
}

include('module/Application/config/module.config.php');
include('module/Application/src/Application/Controller/IndexController.php');
include('module/Application/Module.php');

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
Zend\Mvc\Application::init(require(__DIR__ . '/config/application.config.php'))->run();
