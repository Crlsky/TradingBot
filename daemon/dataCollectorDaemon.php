<?php
    require_once(dirname(__DIR__).'/classes/dataCollector.php');
    
    $timeOfTheEndDay = "23:59";
    $currentTime = date("H:i");

    $dataCollection = new dataCollector();
    $dataCollection->bitbayETHOrderBook();

    if($currentTime==$timeOfTheEndDay)
        $dataCollection->bitbay24ETHRate();

?>