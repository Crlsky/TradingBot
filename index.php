<?php
require_once(dirname(__DIR__).'/trading/classes/db.php');


$db = new DB();
$sql = 'SELECT * FROM transactionHistory WHERE id = 333';
print($db->Get($sql));
// if(get($sql))
//     var_dump('\n jest');
// else
//     var_dump('\n nie ma');

?>