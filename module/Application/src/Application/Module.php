<?php

namespace Application;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface {

    const NAME = 'CLI interface for import XML data';

    public function onBootstrap(MvcEvent $e) {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $controllerLoader = $serviceManager->get('ControllerLoader');
        $controllerLoader->addInitializer(function ($instance) use ($serviceManager) {
                    if (method_exists($instance, 'setEntityManager')) {
                        $instance->setEntityManager($serviceManager->get('doctrine.entitymanager.orm_default'));
                    }
                });
    }
    
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
        return self::NAME."\n";
    }

    public function getConsoleUsage(Console $console) {
        return array("default" => "Do a default action");
    }

}