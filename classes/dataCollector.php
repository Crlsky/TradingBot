<?php
require_once(dirname(__dir__)."/classes/db.php");

class dataCollector {
    private $curl;
    private $data;

    public function __construct() {
        $this->curl = curl_init();
    }

    private function setCurlUrl($url) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->$curl, CURLOPT_RETURNTRANSFER, true);
    }

    // return transfer - true not working - idk
    public function execCurl() {
        $this->data = curl_exec($this->curl);
        $err = curl_error($this->curl);
        curl_close($this->curl);

        if($err)
            $this->data = "Err";
        else 
            $this->data = json_decode($response,true);
    }

    public function bitbayOrderBook($url){
        $this->setCurlUrl($url);
        $this->execCurl();

        if($this->data == "Err" || $this->data['status'] != "Ok")
            return 0;

        $db = new DB();

        foreach($this->data['items'] as $item){
            if(!$db->Get('SELECT * FROM  transactionHistory WHERE bitbay_transaction_id LIKE "'.$item['id'].'"')){
                $sql = 'INSERT INTO transactionHistory (bitbay_transaction_id, date, amount, rate, type) 
                        VALUES ("'.$item['id'].'", "'.date('Y-m-d H:i:s', ($item['t']/1000)).'", "'.$item['a'].'", "'.$item['r'].'", "'.$item['ty'].'")';
                var_dump($sql."\n");
                //$db->Insert($sql);
            }
        }
    }
}

$a = new dataCollector();

$a->bitbayOrderBook("https://api.bitbay.net/rest/trading/transactions/ETH-PLN?limit=10");


?>