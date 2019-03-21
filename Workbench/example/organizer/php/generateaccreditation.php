<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['param']);
	$param = json_decode($p);
	$param->prefix = strtoupper($param->prefix);
	
	$ctr = 1;
	$query = "SELECT idofficial from officials where isactive=1 and idcompetition=".$param->competition."";
	$result = mysqli_query($con,$query );		
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$num = sprintf('%04d',$ctr);
			$idofficial = $row['idofficial'];
			$query="UPDATE officials set accreditationno='".$param->prefix.$num."' where idofficial=".$idofficial."";
			$res = mysqli_query($con,$query );
			$ctr=$ctr+1;
		}
	}
	
	$query = "select idcompetitionteamparticipant from competitionteamparticipant where idcompetition=".$param->competition." and isactive=1 order by idteam,jerseyno";
	$result = mysqli_query($con,$query );		
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$num = sprintf('%04d',$ctr);
			$idcompetitionteamparticipant = $row['idcompetitionteamparticipant'];
			$query="UPDATE competitionteamparticipant set accreditationno='".$param->prefix.$num."' where idcompetitionteamparticipant=".$idcompetitionteamparticipant."";
			$res = mysqli_query($con,$query );
			$ctr=$ctr+1;
		}
	}
	$query = "UPDATE competition set accreditationcounter=".$ctr.", accreditationprefix='".$param->prefix."' where idcompetition=".$param->competition."";
	$result = mysqli_query($con,$query );		
	$resp->message = "Accreditation List generated successfully.";
	echo json_encode($resp);
?>
