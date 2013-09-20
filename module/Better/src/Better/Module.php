<?php
namespace Better;

use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Better\Chance\Bet;
use Better\Chance\WdBet;

class Module implements ConsoleUsageProviderInterface
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
                    __NAMESPACE__ => __DIR__ ,
                ),
            ),
        );
    }
    
    public function getConsoleUsage(Console $console) {
        return array(
            "bet <odd_id> <money>" => "Do a bet using webdriver",
            array("<odd_id>", "odd id"),
            array("<money>","amount of money to bet")
        );
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'chance_better' => function($sm) {
                    $config = $sm->get('Config');
                    //$capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => $config['better']['browser']);
                    //$wd = new \RemoteWebDriver($config['better']['wd_host'], $capabilities);
                    //$better = new Bet($wd);
                    $wd = new \PHPWebDriver_WebDriver($config['better']['wd_host']);
                    $better = new WdBet($wd, $config['better']['browser']);
                    return $better->setLogin($config['better']['chance']['login'])
                                  ->setPassword($config['better']['chance']['password']);
                }
            )
        );
    }
}
