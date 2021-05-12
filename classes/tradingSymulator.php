<?php 
require_once(dirname(__DIR__)."/classes/db.php");
require_once(dirname(__DIR__)."/classes/wallet.php");
require_once(dirname(__DIR__)."/classes/dataCollector.php");
echo "<h1>Symulator</h1>";

$eth = new dataCollector();
$ethRate = $eth->bitbayRate("ETH-PLN");

var_dump($ethRate);
?>