<?php
namespace Import;

use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Import\Chance\Parser;
use Import\Chance\Importer;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

class Module implements ConsoleUsageProviderInterface, DependencyIndicatorInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
    
    public function getConsoleUsage(Console $console) {
        return array("import" => "Do a import from XML source");
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'importer' => function($sm) {
                    $config = $sm->get('Config');
                    $sources = $config['sources'];
                    $importer = new Importer(new Parser());
                    $importer->setSources($sources);
                    return $importer->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                }
            )
        );
    }
    
    public function getModuleDependencies(){
        return array('DoctrineORMModule', 'Database');
    }
}
