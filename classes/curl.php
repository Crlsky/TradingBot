<?php 

class Curl {
    private $curl;

    public function __construct() {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    public function setCurlUrl($url) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
    }

    public function execCurl() {
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);
        curl_close($this->curl);

        if($err)
            return "Err";
        else 
           return json_decode($response,true);
    }

}