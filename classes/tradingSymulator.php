<?php 
require_once(dirname(__DIR__)."/classes/db.php");
require_once(dirname(__DIR__)."/classes/wallet.php");
require_once(dirname(__DIR__)."/classes/dataCollector.php");
require_once(dirname(__DIR__)."/classes/strategy.php");

class tradingSymulator {
    private $wallet;
    private $strategy;
    private $operationRate;
    private $interval;
    private $data;
    private $db;
    
    public function __construct($walletObj, $strategyObj, $operationRate, $interval){
        $this->wallet = $walletObj;
        $this->strategy = $strategyObj;
        $this->operationRate = $operationRate;
        $this->interval = $interval;
        $this->data = new dataCollector;
        $this->db = new DB();
    }

    public function walletData() {
        return $this->wallet->walletAmmount();
    }

    public function startSymulation(){
        while(true){
            $this->makeTransaction();
            sleep($this->interval*3600);
        }
    }

    public function makeTransaction(){
        $operation = $this->strategy->predict();
        $coinRate = $this->data->bitbayRate('ETH-PLN');
        $predictionRate = abs($this->strategy->predictionRate());

        $currencyAmmount = $this->wallet->CurrencyAmmount();
        $operationCurrency = $currencyAmmount*$predictionRate*$this->operationRate;
        $operationCoin = $operationCurrency/$coinRate;

        if($operation == "Buy")
            $operationCurrency *= -1;
        else
            $operationCoin *= -1;
        
        $this->wallet->setCurrencyAmmount($operationCurrency);
        $this->wallet->setCoinAmmount($operationCoin);

        $this->saveTransaction($operationCurrency, $operationCoin, $operation, $coinRate);
    }

    public function saveTransaction($operationCurrency, $operationCoin, $operation, $coinRate) {
        $coinAmmount = $this->wallet->CoinAmmount();
        $currencyAmmount = $this->wallet->CurrencyAmmount();

        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO tradingTransaction (coin, currency, type, currencyAmmount, coinAmmount, rate, date)
                VALUES ({$operationCoin}, {$operationCurrency}, '{$operation}', {$currencyAmmount}, {$coinAmmount}, {$coinRate}, '{$date}')";

        $this->db->Insert($sql);
    }
}

$wallet = new Wallet('PLN', 1000, 'ETH');
$strategy = new Strategy();
$symulator = new tradingSymulator($wallet, $strategy, 0.05, 0.5);

$symulator->startSymulation();
?>