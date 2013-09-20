<?php

namespace Better\Chance;

interface BetInterface {
    
    public function setPassword($password);

    public function setLogin($login);
    
    public function bet($odd_id, $money);
    
}

