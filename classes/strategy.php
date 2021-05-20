<?php
require_once(dirname(__dir__)."/classes/dataCollector.php");

class Strategy {
    private $method;
    private $stopLoss;
    private $rate;
    
    public function __construct($method=1, $stopLoss=0) {
        $this->method = $method;
        $this->stopLoss = $stopLoss;
    }

    public function predict() {
        switch($this->method){
            case 1:
                $prediction = new dataCollector;
                $this->rate = $prediction->bitbayOrderbookLinealRegression(0.5,0,'Buy');
                break;
        }
            
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