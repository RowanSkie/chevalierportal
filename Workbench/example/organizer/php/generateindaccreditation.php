<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['param']);
	$param = json_decode($p);

	$ctr = 0;
	$prefix = "";
	
	
	$query = "SELECT accreditationcounter,accreditationprefix from competition where isactive=1 and idcompetition=".$param->competition."";
	$result = mysqli_query($con,$query );		
	if($row = mysqli_fetch_assoc($result)) {
		$ctr = $row['accreditationcounter'];
		$prefix = $row['accreditationprefix'];

		$num = sprintf('%04d',$ctr);
		if($param->team==0){
			$query="UPDATE officials set accreditationno='".$prefix.$num."' where idcompetition=".$param->competition." AND idofficial=".$param->index."";
			$res = mysqli_query($con,$query );			
		}else{
			$query="UPDATE competitionteamparticipant set accreditationno='".$prefix.$num."' where idcompetitionteamparticipant=".$param->index."";
			$res = mysqli_query($con,$query );
		}
		$ctr=$ctr+1;

		$query = "UPDATE competition set accreditationcounter=".$ctr." where idcompetition=".$param->competition."";
		$result = mysqli_query($con,$query );		
	}else{
		$resp->errorno=2;
		$resp->message = "Participant not found.";
	}
	echo json_encode($resp);
	
?>
