<?php
require_once(dirname(__dir__)."/classes/db.php");
require_once(dirname(__dir__)."/classes/curl.php");

class dataCollector {
    private $curl;
    private $data;

    public function __construct() {
        $this->curl = new Curl();
    }

    public function bitbayETHOrderBook(){
        $url = "https://api.bitbay.net/rest/trading/transactions/ETH-PLN?limit=150";
        $this->curl->setCurlUrl($url);
        $this->data = $this->curl->execCurl();

        if($this->data == "Err" || $this->data['status'] != "Ok")
            return 0;

        $db = new DB();

        foreach($this->data['items'] as $item){
            if(!$db->Get('SELECT * FROM  transactionHistory WHERE bitbay_transaction_id LIKE "'.$item['id'].'"')){
                $sql = 'INSERT INTO transactionHistory (bitbay_transaction_id, date, amount, rate, type) 
                        VALUES ("'.$item['id'].'", "'.date('Y-m-d H:i:s', ($item['t']/1000)).'", "'.$item['a'].'", "'.$item['r'].'", "'.$item['ty'].'")';
                $db->Insert($sql);
            }
        }
    }

    public function bitbay24ETHRate(){
        $url = "https://api.bitbay.net/rest/trading/stats/ETH-PLN";
        $this->curl->setCurlUrl($url);
        $this->data = $this->curl->execCurl();

        if($this->data == "Err" || $this->data['status'] != "Ok")
            return 0;

        $db = new DB();

        $sql = 'INSERT INTO priceHistory (high, low, vol, r24h, date) 
                VALUES ("'.$this->data['stats']['h'].'", "'.$this->data['stats']['l'].'", "'.$this->data['stats']['v'].'", "'.$this->data['stats']['r24h'].'", "'.date('Y-m-d H:i:s').'")';
        $db->Insert($sql);
    }

    public function bitbayRate($market){
        $timeFrom = (time()-60)*1000;
        $timeTo = time()*1000;
        
        $url = "https://api.bitbay.net/rest/trading/candle/history/{$market}/60?from={$timeFrom}&to={$timeTo}";
        $this->curl->setCurlUrl($url);
  

        $this->data = $this->curl->execCurl();
        
        if($this->data['status'] != "Ok")
            return "Err";

        return $this->data['items'][0][1]['o'];
    }
}


?>