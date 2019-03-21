<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['player']);
	$player = json_decode($p);
	//$player->height = ltrim(rtrim($player->height));
	//$player->weight = ltrim(rtrim($player->weight));
	//$player->passport = ltrim(rtrim($player->passport));
	//$player->nric = ltrim(rtrim($player->nric));
	//$player->mobileno = ltrim(rtrim($player->mobileno));
	$player->name = str_replace("'","''",$player->name);
	if($player->birthdate=='')
		$player->birthdate="1900-01-01";
	//if($player->height=='')
	//	$player->height=0.0;
	//if($player->weight=='')
	//	$player->weight=0.0;

	$teamid = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$player->competition." and email = '" .$player->email. "' and isactive=1");
	if($row = mysqli_fetch_assoc($teamid)) {
		$idteam = $row['idteam'];
		$query = "select a.participants,(select count(1)  from competitionteamparticipant b where b.idcompetition=".$player->competition." and b.idteam=".$idteam." and b.isactive=1) as playercount from competition a where a.idcompetition=".$player->competition;
		$result=mysqli_query($con,$query);//"select a.participants, count(*) as playercount from competition a, competitionteamparticipant b where a.idcompetition=b.idcompetition and b.idcompetition=".$player->competition." and b.idteam=".$idteam." and b.isactive=1");
		if(mysqli_num_rows($result)>0){
			// for team games, get only the first game not yet started
			if($row = mysqli_fetch_assoc($result)) {
				if ($row['participants']<=$row['playercount'] && $player->oldjersey==""){
					$resp->errorno = 2;
					$resp->message= "Maximimum of ".$row['participants']. " reached. You can no longer add this participant";
					echo json_encode($resp);
					return;
				}
			}
		}else{
			$resp->errorno = 2;
			$resp->message= "Invalid team";
			echo json_encode($resp);
			return;
		}
		if($player->position=='TEAM MANAGER'){ //manager
			$query = "update competitionteam set manager='".$player->name."' where idcompetition=".$player->competition." and idteam=".$idteam;
			mysqli_query($con,$query);

			if($player->oldjersey==""){
				$query = "DELETE competitionteamparticipant WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND position='TEAM MANAGER'";
				mysqli_query($con,$query);
			}

			if($player->jersey=="")
				$player->jersey = -3;
		}elseif($player->position=='COACH'){ //coach
			$query = "update competitionteam set coach='".$player->name."' where idcompetition=".$player->competition." and idteam=".$idteam;
			mysqli_query($con,$query);

			if($player->oldjersey==""){
				$query = "DELETE competitionteamparticipant WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND position='COACH'";
				mysqli_query($con,$query);
			}

			if($player->jersey=="")
				$player->jersey = -2;
		}elseif($player->position=='ASST COACH'){//asst coach
			$query = "update competitionteam set asstcoach='".$player->name."' where idcompetition=".$player->competition." and idteam=".$idteam;
			mysqli_query($con,$query);

			if($player->oldjersey==""){
				$query = "DELETE competitionteamparticipant WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND position='ASST COACH'";
				mysqli_query($con,$query);
			}

			if($player->jersey=="")
				$player->jersey = -1;
		}
		if($player->oldjersey==""){
			//$query = "insert into competitionteamparticipant (idcompetition,idteam,name,jerseyno,position,height,weight,passport,nric,birthdate,email,mobileno,category)values(".$player->competition.",".$idteam.",'".$player->name."','".$player->jersey."','".$player->position."','".$player->height."','".$player->weight."','".$player->passport."','".$player->nric."','".$player->birthdate."','".$player->playeremail."','".$player->mobileno."',".$player->category.")";
			$query = "insert into competitionteamparticipant (idcompetition,idteam,name,jerseyno,position,birthdate,category)values(".$player->competition.",".$idteam.",'".$player->name."','".$player->jersey."','".$player->position."','".$player->birthdate."',".$player->category.")";
			$resp->message= "Participant information added successfully";
		}else{
			// remove from event if category is changed
			if($player->category!=$player->oldcategory){
				$query = "DELETE FROM teameventplayers  WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND jerseyno=".$player->oldjersey." AND idcategory=".$player->oldcategory."";
				mysqli_query($con,$query);
				$query = "DELETE FROM eventplayers  WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND jerseyno=".$player->oldjersey." AND category=".$player->oldcategory."";
				mysqli_query($con,$query);
			}
			//$query = "UPDATE competitionteamparticipant SET name='".$player->name."',jerseyno=".$player->jersey.",position='".$player->position."',height='".$player->height."',weight='".$player->weight."',passport='".$player->passport."',nric='".$player->nric."',birthdate='".$player->birthdate."',email='".$player->playeremail."',mobileno='".$player->mobileno."',category=".$player->category." WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND jerseyno=".$player->oldjersey." AND category=".$player->oldcategory."";
			$query = "UPDATE competitionteamparticipant SET name='".$player->name."',jerseyno=".$player->jersey.",position='".$player->position."',birthdate='".$player->birthdate."',category=".$player->category." WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND jerseyno=".$player->oldjersey." AND category=".$player->oldcategory."";
			mysqli_query($con,$query);
			$query = "UPDATE eventplayers SET jerseyno=".$player->jersey." WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND jerseyno=".$player->oldjersey." AND idcategory=".$player->oldcategory."";
			mysqli_query($con,$query);
			$query = "UPDATE teameventplayers SET jerseyno=".$player->jersey." WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND jerseyno=".$player->oldjersey." AND idcategory=".$player->oldcategory."";
			mysqli_query($con,$query);
			$query = "DELETE FROM teameventplayers  WHERE idcompetition=".$player->competition." and idteam=".$idteam." AND idcategory=".$player->oldcategory." AND position IN('TEAM MANAGER','COACH','ASST COACH')";
			mysqli_query($con,$query);


			$resp->message= "Participant information updated successfully";
		}
		mysqli_query($con,$query);
	}
	echo json_encode($resp);
?>
