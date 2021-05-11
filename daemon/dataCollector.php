<?php
    require_once(dirname(__DIR__).'/classes/db.php');
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.bitbay.net/rest/trading/transactions/ETH-PLN?limit=150",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/json",
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        putInDatabase(json_decode($response,true));
    }


    function putInDatabase($array){
        if($array['status'] != "Ok")
            return 0;

        $db = new DB();

        foreach($array['items'] as $item){
            if(!$db->Get('SELECT * FROM  transactionHistory WHERE bitbay_transaction_id LIKE "'.$item['id'].'"')){
                $sql = 'INSERT INTO transactionHistory (bitbay_transaction_id, date, amount, rate, type) 
                        VALUES ("'.$item['id'].'", "'.date('Y-m-d H:i:s', ($item['t']/1000)).'", "'.$item['a'].'", "'.$item['r'].'", "'.$item['ty'].'")';
                $db->Insert($sql);
            }
        }
    }



?>