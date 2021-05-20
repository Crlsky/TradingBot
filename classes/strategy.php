<?php
require_once(dirname(__dir__)."/classes/dataCollector.php");

class Strategy {
    private $method;
    private $stopLoss;

    public function __construct($method, $stopLoss=0) {
        $this->stopLoss = $stopLoss;
    }

    public function predict() {
        $prediction = new dataCollector;
        $this->rate = $prediction->bitbayOrderbookLinealRegression(0.5,0,'Buy');
        
        return $this->operationByRate();
    }

    public function predictionRate() {
        return floatval($this->rate);
    }

    public function operationByRate(){
        if($this->rate >= 0){
            return "Buy";
        }else
            return "Sell";
    }
    
}
?>