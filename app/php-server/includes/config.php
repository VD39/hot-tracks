<?php

$db_server = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "hot-tracks";

try {
    
	$conn = new PDO("mysql:dbname=$db_name;host=$db_server", $db_username, $db_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} 

catch (Exception $e) {
    
	echo 'ERROR: ' . $e->getMessage();  
    
}

?>