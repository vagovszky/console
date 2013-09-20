<?php

namespace Better\Chance;

class WdBet implements BetInterface{

    private $login;
    private $password;
    
    protected $driver;
    protected $browser = 'firefox';
    protected $session = NULL;

    const CHANCE_URL = 'https://www.chance.cz/';
    const LIST_URL = 'https://www.chance.cz/kurzove-sazky/nabidka?obdobi=2&radit=2,1&vypisovat=2&pozadavek=vypis';
    const WAIT_TIME = 40;
    const WAIT_PERIOD = 2000;

    public function __construct(\PHPWebDriver_WebDriver $driver, $browser = "firefox") {
        $this->driver = $driver;
        $this->browser = $browser;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setLogin($login) {
        $this->login = $login;
        return $this;
    }


    public function bet($odd_id, $money) {
        try {
            $this->session = $this->driver->session($this->browser);
            $this->session->implicitlyWait(3);
            $this->session->open(self::CHANCE_URL);

            $this->login();
            $this->selectBet($odd_id);
            $this->setMoney($money);
            $this->doBet();
            $this->logout();

            $this->session->close();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->session->close();
            return false;
        }
    }

    // -------------------------------------------------------------------------

    private function doBet() {
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, 'i_a_zaslat_tiket')->click();
        $w = new \PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elements(\PHPWebDriver_WebDriverBy::CLASS_NAME, 'message_ok'));
            }
        );
    }

    private function setMoney($money) {
        $element_sazka_input = $this->session->element(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input.sazka[name="sazka-1"]');
        $element_sazka_input->clear();
        $element_sazka_input->sendKeys((string) $money);
        sleep(2);
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, 'i_tiket_obsah')->click();
        sleep(2);
        $w = new \PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elements(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, '#i_a_zaslat_tiket:not(.disabled)'));
            }
        );
    }

    private function selectBet($odd_id) {
        $this->session->open(self::LIST_URL);
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, "tip_$odd_id")->click();
        $w = new \PHPWebDriver_WebDriverWait($this->session);
        $e = $w->until(
            function($session) {
                return $session->element(\PHPWebDriver_WebDriverBy::NAME, 'sazka-1');
            }
        );        
        sleep(3);
    }

    private function logout() {
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, 'top_logout')->click();
        sleep(10);
    }

    private function login() {
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, "ich_name")->clear();
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, "ich_name")->sendKeys($this->login);
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, "ich_pwd")->clear();
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, "ich_pwd")->sendKeys($this->password);
        $this->session->element(\PHPWebDriver_WebDriverBy::ID, "chanceLoginButton")->click();
        $w = new \PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elements(\PHPWebDriver_WebDriverBy::CLASS_NAME, "klient_info"));
            }
        );
    }

}