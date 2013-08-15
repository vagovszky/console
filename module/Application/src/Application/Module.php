<?php

namespace Application;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface {

    const NAME = 'CLI interface for import XML data';

    public function getConfig() {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConsoleBanner(Console $console) {
        return "================================================================\n".
                self::NAME."\n".
               "================================================================\n";
    }

    public function getConsoleUsage(Console $console) {
        return array("default" => "Do a default action");
    }

}