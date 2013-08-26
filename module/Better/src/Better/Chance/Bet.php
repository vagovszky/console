<?php

namespace Better\Chance;

class Bet {
    
    private $driver;
    
    private $login;
    private $password;
    
    const CHANCE_URL = 'https://www.chance.cz/';
    
    public function __construct(\WebDriver $driver) {
        $this->driver = $driver;
    }
    
    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
    
    public function setLogin($login){
        $this->login = $login;
        return $this;
    }
    
    public function bet($odd_id, $money){
        $this->driver->get(self::CHANCE_URL);
        if(!$this->login()) return false; // log in
        if(!$this->logout()) return false; // log out
        $this->driver->close();
        return true;
    }
    
    // -------------------------------------------------------------------------
    
    private function logout(){
        try{
            $this->driver->findElement(\WebDriverBy::id("top_logout"))->click();
            $this->driver->wait(10, 1000)->until(
                \WebDriverExpectedCondition::alertIsPresent()
            );
            $this->driver->switchTo()->alert()->accept();
            return true;
        }catch(\Exception $e){
            $this->driver->close();
            return false;
        }
    }
    
    private function login(){
        try{
            $this->driver->findElement(\WebDriverBy::id("ich_name"))->sendKeys($this->login);
            $this->driver->findElement(\WebDriverBy::id("ich_pwd"))->sendKeys($this->password);
            $this->driver->findElement(\WebDriverBy::id('chanceLoginButton'))->click();
            $this->driver->wait(10, 1000)->until(
                \WebDriverExpectedCondition::presenceOfElementLocated(\WebDriverBy::className("klient_info"))
            );
            return true;
        }catch(\Exception $e){
            $this->driver->close();
            return false;
        }
    }
}