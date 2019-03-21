<?php

	include 'config.php';
		
	$query = "delete from teamparticipant where idteam <> 0";
	mysqli_query($con,$query);

	$query = "delete from competition where idcompetition <> 0";
	mysqli_query($con,$query);
	$query = "delete from competitionteam where idcompetition<>1 and idteam <> 0";
	mysqli_query($con,$query);
	$query = "delete from competitionteam where idcompetition=1 and idteam > 7";
	mysqli_query($con,$query);
	$query = "delete from competitionteamparticipant where idcompetition=1 and idteam > 7";
	mysqli_query($con,$query);
	$query = "delete from competitionteamparticipant where idcompetition<>1 and idteam <> 0";
	mysqli_query($con,$query);
	$query = "delete from event where idcompetition <> 0 and idmatch <> \"\"";
	mysqli_query($con,$query);

	$query = "delete from eventplayers where idcompetition <> 0 and idevent <> \"\"";
	mysqli_query($con,$query);
	$query = "delete from eventgamescore where idcompetition <> 0 and idevent <> \"\"";
	mysqli_query($con,$query);	
	$query = "delete from eventgameactivity where idcompetition <> 0";
	mysqli_query($con,$query);	
	$query = "delete from competitiongameType where idcompetition <> 0";
	mysqli_query($con,$query);
	$query = "delete from teameventplayers where idcompetition<>0";
	mysqli_query($con,$query);
	$query = "truncate table competitionteam";
	mysqli_query($con,$query);
	$query = "truncate table competitionteamparticipant";
	mysqli_query($con,$query);
	$query = "truncate table officials";
	mysqli_query($con,$query);
	echo "Reset successful.";

?>
