<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['param']);
	$param = json_decode($p);

	$competition = $param->competition;
	$matchno = $param->matchno;
	$team1 = $param->team1;
	$team2 = $param->team2;
	$team1score = 0;
	$team2score = 0;

	// get the score
	$query = "SELECT sum(score) as totalpoints FROM eventgamescore where idcompetition=".$competition." and idevent='".$matchno."' and idteam=".$team1."";
	$result = mysqli_query($con,$query );
	if($row = mysqli_fetch_assoc($result)) {
		$team1score = $row['totalpoints'];
	}
	// update the score
	$query = "SELECT sum(score) as totalpoints FROM eventgamescore where idcompetition=".$competition." and idevent='".$matchno."' and idteam=".$team2."";
	$result = mysqli_query($con,$query );
	if($row = mysqli_fetch_assoc($result)) {
		$team2score = $row['totalpoints'];
	}

	$query = "SELECT json FROM eventgameactivity where idcompetition=".$competition." and idevent='".$matchno."' and activity='END'";
	$result = mysqli_query($con,$query );
	if($row = mysqli_fetch_assoc($result)) {
		$activity = json_decode($row['json']);
		$lwin=0;
		$lloss=0;
		$rwin=0;
		$rloss=0;
		if($activity->teamWin==1){
			$lwin=1;
			$lloss=0;
			$rwin=0;
			$rloss=1;
		}else{
			$lwin=0;
			$lloss=1;
			$rwin=1;
			$rloss=0;
		}

		// update the score
		$query = "UPDATE competitionteam SET win=win-".$lwin.",loss=loss-".$lloss.",totalpoints = totalpoints - (SELECT sum(score) FROM eventgamescore where idcompetition=".$activity->idCompetition." and idevent='".$activity->idMatch."'  and idteam=".$activity->idTeamLeft.") WHERE idcompetition=".$activity->idCompetition." and idteam=".$activity->idTeamLeft."";
		$result = mysqli_query($con,$query );

		// update the score
		$query = "UPDATE competitionteam SET win=win-".$rwin.",loss=loss-".$rloss.",totalpoints = totalpoints - (SELECT sum(score) FROM eventgamescore where idcompetition=".$activity->idCompetition." and idevent='".$activity->idMatch."'  and idteam=".$activity->idTeamRight.") WHERE idcompetition=".$activity->idCompetition." and idteam=".$activity->idTeamRight."";
		$result = mysqli_query($con,$query );
	}


	mysqli_query($con,"update eventgamescore set score=0,injury=0,timeout=0,duration=0,winner=0,loser=0  where idcompetition = ".$competition." and idevent = '" .$matchno. "'");
	mysqli_query($con,"update event set status=0,finalize=0,winner=0,loser=0,startdate=null,enddate=null,accesscode='' where idcompetition = ".$competition." and idmatch = '" .$matchno. "'");
	mysqli_query($con,"delete from eventgameactivity where idcompetition = ".$competition." and idevent = '" .$matchno. "'");


	$resp->message="Reset successful. Startlist needs to be finalized again.";
	echo json_encode($resp);
?>
