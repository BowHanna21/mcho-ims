<?php 

$localhost = "remotemysql.com";
$username = "3ogo2cYOWf";
$password = "bC2xxvMuji";
$dbname = "3ogo2cYOWf";

// create connection
$con = new mysqli($localhost, $username, $password, $dbname);

// check connection
if($con->connect_error) {
	die("connection failed2 : " . $connect->connect_error);
} else {
	//echo "Successfully Connected";
}

?>		