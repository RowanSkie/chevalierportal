<?php

	include 'config.php';
	include 'checkuser.php';

	$filename = str_replace("../","../../",$_GET['filename']);
	unlink($filename);
	
	$resp->message="File removed successfully.";
	echo json_encode($resp);
?>
	