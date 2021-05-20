<?php

class Wallet {
    private $currencyAmmount;
    private $currencyName;
    private $coinAmmount;
    private $coinName;

    public function __construct($name, $ammount, $coinName, $coinCurrency = 0) {
        $this->currencyAmmount = $ammount;
        $this->currencyName = $name;
        $this->coinAmmount = $coinCurrency;
        $this->coinName = $coinName;
    }

    public function walletAmmount() {
        return array(
            $this->currencyName => $this->currencyAmmount,
            $this->coinName => $this->coinAmmount);    
    }
     
    public function CurrencyAmmount() {
        return floatval($this->currencyAmmount);
    }

    public function CoinAmmount() {
        return floatval($this->coinAmmount);
    }

    public function setCurrencyAmmount($ammount) {
        if($this->currencyAmmount+$ammount < 0)
            print("You don not have that much money.<br/>");
        else
            $this->currencyAmmount = $this->currencyAmmount+$ammount;
    }

    public function setCoinAmmount($ammount) {
        if($this->coinAmmount+$ammount <0)
            print("You don not have that much coins.<br/>");
        else
            $this->coinAmmount = $this->coinAmmount+$ammount;
    }
}

?>