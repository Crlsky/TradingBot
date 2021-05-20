<?php 
require_once(dirname(__DIR__)."/classes/db.php");
require_once(dirname(__DIR__)."/classes/wallet.php");
require_once(dirname(__DIR__)."/classes/dataCollector.php");

class tradingSymulator {
    private $wallet;
    private $strategy;
    private $operationRate;
    private $interval;
    private $data;
    private $db;
    
    public function __construct($walletObj, $startegyObj, $operationRate, $interval){
        $this->wallet = $walletObj;
        $this->strategy = $startegyObj;
        $this->operationRate = $operationRate;
        $this->interval = $interval;
        $this->data = new dataCollector;
        $this->db = new DB();
    }

    public function walletData() {
        return $this->wallet->walletAmmount();
    }

    public function startSimulation(){
        while(true){
            $this->makeTransaction();
            sleep($interval*3600);
        }
    }

    public function makeTransaction(){
        $operation = $this->startegy->predict();
        $coinRate = $this->data->bitbayRate('ETH-PLN');
        $predictionRate = abs($this->strategy->predictionRate());

        $currencyAmmount = $this->wallet->CurrencyAmmount();

        $operationCurrency = $currencyAmmount*$this->$operationRate*$predictionRate;
        $operationCoin = $coinRate*$operationCurrency;

        if($operation == "Buy")
            $operationCurrency *= -1;
        else
            $operationCoin *= -1;
        
        $this->wallet->setCurrencyAmmount($operationCurrency);
        $this->wallet->setCoinAmmount($operationCoin);

        $this->saveTransaction($operationCurrency, $operationCoin);
    }


    public function saveTransaction($) {

        $this->db->insert("INSER INTO tradingTransaction (coin, currency, type, currencyAmmount, coinAmmount, rate, date)
        VALUES ({$operationCoin}, {$operationCurrency}, {$operation}, {$})")
    }

}

$wallet = new Wallet('PLN', 1000, 'ETH');

$simulator = new tradingSymulator($wallet);
var_dump($simulator->walletData());
?>