<?php
$servername = "localhost";
$username = "root";
$password = "Group2";
$database = "schoolportal";

// Create connection
$con = mysqli_connect($servername, $username, $password,$database);

if(!$con){
	die("Connection failed: " . mysqli_connect_error());

}

?>
