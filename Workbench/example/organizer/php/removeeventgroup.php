<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['recid']);

	$query = "DELETE FROM eventgrouping WHERE idevengrouptctr=".$p."";
	$result = mysqli_query($con,$query);
	
	$resp->message="Team successfully removed from Event Grouping.";
	echo json_encode($resp);
?>
	