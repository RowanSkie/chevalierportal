<?php

	include 'config.php';
	include 'checkuser.php';

	$subscription = str_replace("\\","",$_GET['subscription']);
	$competition = str_replace("\\","",$_GET['competition']);

	$query = "DELETE FROM competition where idcompetition=".$competition." and idsubscription=".$subscription."";
	mysqli_query($con,$query);
	
	$query = "DELETE FROM competitionteam where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM competitionteamparticipant where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM competitiongametype where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM event where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM eventgameactivity where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM eventgamescore where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM eventplayers where idcompetition=".$competition."";
	mysqli_query($con,$query);

	$query = "DELETE FROM officials where idcompetition=".$competition."";
	mysqli_query($con,$query);
		
	$dir = "../../competitions/".$competition."/";
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
	$resp->message = "Competition, event and participants removed successfully.";
	echo json_encode($resp);
?>
