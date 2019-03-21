<?php
	include 'config.php';
	include 'checkuser.php';

	class user{
		public $name;
		public $type;
	}

	$subscription = $_GET['subscription'];
	$members = array();
	$query = "SELECT username, organizertype FROM organizer WHERE idsubscription='".$subscription."' order by organizertype";
	$result = mysqli_query($con,$query);
	while($row=mysqli_fetch_assoc($result)){
		$obj = new user();
		$obj->name = $row['username'];
		$obj->type = $row['organizertype'];
		$members[] = $obj;
	}		 
	
	$resp->data=$members;
	echo json_encode($resp);	
?>

	
