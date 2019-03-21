<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['param']);
	$param = json_decode($p);
	$param->idmatch = strtoupper($param->idmatch);

	//$query = "UPDATE event SET isactive =0 where isactive=1 and idcompetition=".$param->competition." AND idmatch='".$param->idmatch."' AND team1=".$param->team1." AND team2=".$param->team2."";
	$query = "DELETE FROM event WHERE idcompetition=".$param->competition." AND idmatch='".$param->idmatch."'";
	$result = mysqli_query($con,$query );		

	$query = "DELETE FROM eventgameactivity WHERE idcompetition=".$param->competition." AND idevent='".$param->idmatch."'";
	$result = mysqli_query($con,$query );		
	
	$query = "DELETE FROM eventgamescore WHERE idcompetition=".$param->competition." AND idevent='".$param->idmatch."'";
	$result = mysqli_query($con,$query );		

	$query = "DELETE FROM eventplayers WHERE idcompetition=".$param->competition." AND idevent='".$param->idmatch."'";
	$result = mysqli_query($con,$query );		
	
	$resp->message="Event removed successfully.";
	echo json_encode($resp);
?>
	