<?php
	include 'config.php';
	$username = $_GET['user'];
	$token = $_GET['token'];
	
	$query="UPDATE user SET token=null,lastactivity=null WHERE id ='".$username."' AND usertype=2 AND isactive=1 AND token='".$token."'";
	$result = mysqli_query($con,$query);
?>