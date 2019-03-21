<?php

	include 'config.php';
	include 'checkuser.php';

	$team = str_replace("\\","",$_GET['team']);
	$teamInfo = json_decode($team);

	$email = $teamInfo->email;
	$competition = $teamInfo->competition;
	$gametype = $teamInfo->gametype;
	$category = $teamInfo->category;
	$idteam = $teamInfo->idteam;
	$isinserted = false;

	//$result = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and email = '" .$email. "' and category=".$category."");
	//if($row = mysqli_fetch_assoc($result)) {
	//	$idteam = $row['idteam'];

		$query = "delete from teameventplayers where idcompetition=" . $competition ." and idteam=".$idteam." and idgametype=" .$gametype. " and idcategory=".$category."";
		$result = mysqli_query($con,$query);

		$strCat='';
		if($category==1)
			$strCat='MEN';
		else
			$strCat='WOMEN';

		foreach ($teamInfo->players as $key => $player){
			$query = "insert into teameventplayers (idcompetition,idgametype,idteam,jerseyno,position,idcategory) values(".$competition.",".$gametype.",".$idteam.",". $player->jersey.",'".$player->position."',".$category.")";
			mysqli_query($con,$query);
			//echo $query;
		}

		//get all events where team is team 1
		$query = "select idmatch from event where idcompetition=" . $competition ." and team1=".$idteam." and idgametype=" .$gametype." and category='".$strCat."' and isactive=1";
		$result = mysqli_query($con,$query);
		while($row=mysqli_fetch_assoc($result)){
			$idmatch = $row['idmatch'];

			// remove playsers that are no longer in the roster
			$query = "delete from eventplayers WHERE idcompetition=".$competition." AND idevent='".$idmatch."' AND idteam=".$idteam." AND idcategory=".$category."";// AND jerseyno NOT IN(SELECT jerseyno from teameventplayers where idcompetition=".$competition." and idteam=".$idteam. " and idgametype=".$gametype." AND idcategory=".$category." and isactive=1)";
			//echo $query. "  //  ";
			mysqli_query($con,$query);
			// add players that are in the roster but not in the event
			$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$idmatch. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$competition." and idteam=".$idteam. " and idgametype=".$gametype." AND idcategory=".$category."";// and isactive=1 AND jerseyno NOT IN(SELECT jerseyno from eventplayers where idcompetition=".$competition." and idevent='".$idmatch."' and idteam=".$idteam. " and idgametype=".$gametype." AND idcategory=".$category." and isactive=1)";
			//echo $query. "  //  ";
			mysqli_query($con,$query);

		}

		//get all events where team is team 1
		$query = "select idmatch from event where idcompetition=" . $competition ." and team2=".$idteam." and idgametype=" .$gametype." and category='".$strCat."' and isactive=1";
		$result = mysqli_query($con,$query);
		while($row=mysqli_fetch_assoc($result)){
			$idmatch = $row['idmatch'];

			// remove playsers that are no longer in the roster
			$query = "delete from eventplayers WHERE idcompetition=".$competition." AND idevent='".$idmatch."' AND idteam=".$idteam." AND idcategory=".$category." AND jerseyno NOT IN(SELECT jerseyno from teameventplayers where idcompetition=".$competition." and idteam=".$idteam. " and idgametype=".$gametype." AND idcategory=".$category." and isactive=1)";
			mysqli_query($con,$query);
			// add players that are in the roster but not in the event
			$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$idmatch. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$competition." and idteam=".$idteam. " and idgametype=".$gametype." AND idcategory=".$category." and isactive=1 AND jerseyno NOT IN(SELECT jerseyno from eventplayers where idcompetition=".$competition." and idevent='".$idmatch."' and idteam=".$idteam. " and idgametype=".$gametype." AND idcategory=".$category." and isactive=1)";
			mysqli_query($con,$query);

		}

		$resp->message="Successfully joined the competition.";
	//}else{
	//	$resp->errorno=2;
	//	$resp->message="Failed to join competition.";
	//}
	echo json_encode($resp);
?>
