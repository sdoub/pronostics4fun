<?php
//open connection
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://pronostics4fun.com/execute.sql.query.php");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1);

//prepare the field values being posted to the service
$data = array();
foreach ($_POST as $name => $value){
	$data[$name] = $value;
}

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo $result;
?>
