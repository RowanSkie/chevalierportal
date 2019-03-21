<?php
$servername = "localhost";
$username = "root";
$password = "DB@dm1n";
$database = "sportsev_takrawdb";

// Create connection
$con = mysqli_connect($servername, $username, $password,$database);

if(!$con){
	die("Connection failed: " . mysqli_connect_error());

}

?> 
