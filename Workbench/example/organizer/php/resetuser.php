<?php
	include 'config.php';
	$username = $_GET['user'];
	
	$query="UPDATE user SET token=null WHERE username ='".$username."' AND usertype=2 AND isactive=1";
	$result = mysqli_query($con,$query);
?>