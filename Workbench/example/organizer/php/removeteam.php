<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['param']);
	$param = json_decode($p);

	$query = "DELETE FROM competitionteam where isactive=1 and idcompetition=".$param->competition." AND idteam='".$param->idteam."'";
	$result = mysqli_query($con,$query );		

	$query = "DELETE FROM eventgrouping where idcompetition=".$param->competition." AND idteam=".$param->idteam."";
	$result = mysqli_query($con,$query );	
	
	$query = "DELETE FROM competitionteamparticipant where isactive=1 and idcompetition=".$param->competition." AND idteam='".$param->idteam."'";
	$result = mysqli_query($con,$query );		

	$query = "DELETE FROM event where isactive=1 and idcompetition=".$param->competition." AND team1='".$param->idteam."'";
	$result = mysqli_query($con,$query );		

	$query = "DELETE FROM event where isactive=1 and idcompetition=".$param->competition." AND team2='".$param->idteam."'";
	$result = mysqli_query($con,$query );		

	try{
		$dir = "../../competitions/".$param->competition."/teams/".$param->idteam."/";
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
					 RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
			unlink($file->getRealPath());
			}
		}
		rmdir($dir);			
	}catch(UnexpectedValueException $e){
		$resp->message="Team removed successfully.";
	}catch(Exception $e){
		$resp->message="Team removed successfully.";
	}finally{
		$resp->message="Team removed successfully.";		
	}
	
	
	echo json_encode($resp);
?>
	