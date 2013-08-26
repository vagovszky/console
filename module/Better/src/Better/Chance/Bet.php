<?php

namespace Better\Chance;

class Bet {

    private $driver;
    private $login;
    private $password;

    const CHANCE_URL = 'https://www.chance.cz/';
    const LIST_URL = 'https://www.chance.cz/kurzove-sazky/nabidka?obdobi=2&radit=2,1&vypisovat=2&pozadavek=vypis';

    public function __construct(\WebDriver $driver) {
        $this->driver = $driver;
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
        $this->driver->get(self::CHANCE_URL);
        if (!$this->login()) return false; // log in
        if (!$this->selectBet($odd_id)) return false; // select bet
        if (!$this->setMoney($money)) return false; // select bet
        if (!$this->doBet()) return false; // select bet
        if (!$this->logout()) return false; // log out
        $this->driver->close();
        return true;
    }

    public function __destruct() {
        $this->driver->quit();
    }
    // -------------------------------------------------------------------------

    private function doBet(){
        try {
            $this->driver->findElement(\WebDriverBy::id("i_a_zaslat_tiket"))->click();
            $this->driver->wait(12, 1000)->until(
                    \WebDriverExpectedCondition::presenceOfElementLocated(\WebDriverBy::className("message_ok"))
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function setMoney($money){
        try {
            $this->driver->findElement(\WebDriverBy::name("sazka-1"))->clear();
            $this->driver->findElement(\WebDriverBy::name("sazka-1"))->sendKeys($money);
            $this->driver->findElement(\WebDriverBy::id("i_tiket_obsah"))->click();
            $this->driver->wait(10, 1000)->until(
                    \WebDriverExpectedCondition::elementToBeClickable(\WebDriverBy::id("i_a_zaslat_tiket"))
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function selectBet($odd_id) {
        try {
            $this->driver->get(self::LIST_URL);
            $this->driver->wait(10, 1000)->until(
                    \WebDriverExpectedCondition::presenceOfElementLocated(\WebDriverBy::id("souteze"))
            );
            $this->driver->findElement(\WebDriverBy::id("tip_$odd_id"))->click();
            $this->driver->wait(10, 1000)->until(
                    \WebDriverExpectedCondition::presenceOfElementLocated(\WebDriverBy::id("i_a_zaslat_tiket"))
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function logout() {
        try {
            $this->driver->findElement(\WebDriverBy::id("top_logout"))->click();
            $this->driver->wait(10, 1000)->until(
                    \WebDriverExpectedCondition::alertIsPresent()
            );
            $this->driver->switchTo()->alert()->accept();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function login() {
        try {
            $this->driver->findElement(\WebDriverBy::id("ich_name"))->clear();
            $this->driver->findElement(\WebDriverBy::id("ich_name"))->sendKeys($this->login);
            $this->driver->findElement(\WebDriverBy::id("ich_pwd"))->clear();
            $this->driver->findElement(\WebDriverBy::id("ich_pwd"))->sendKeys($this->password);
            $this->driver->findElement(\WebDriverBy::id('chanceLoginButton'))->click();
            $this->driver->wait(10, 1000)->until(
                    \WebDriverExpectedCondition::presenceOfElementLocated(\WebDriverBy::className("klient_info"))
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}