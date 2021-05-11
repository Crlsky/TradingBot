<?php
    require_once(dirname(__DIR__).'/classes/db.php');
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.bitbay.net/rest/trading/stats/ETH-PLN",
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

        $sql = 'INSERT INTO priceHistory (high, low, vol, r24h, date) 
                VALUES ("'.$array['stats']['h'].'", "'.$array['stats']['l'].'", "'.$array['stats']['v'].'", "'.$array['stats']['r24h'].'", "'.date('Y-m-d H:i:s').'")';
        $db->Insert($sql);
    }



?>