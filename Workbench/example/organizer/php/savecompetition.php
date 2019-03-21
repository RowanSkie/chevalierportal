<?php

	include 'config.php';
	include 'checkuser.php';

	$resp->message="Tournament created successfully.";

	try{
		$competition = str_replace("\\","",$_GET['competition']);
		$competitionInfo = json_decode($competition);

		$user = $competitionInfo->user;
		$subscription = $competitionInfo->subscription;
		$title = ltrim(rtrim(str_replace("'","''",$competitionInfo->title)));
		$noc = $competitionInfo->noc;
		$venue = ltrim(rtrim(str_replace("'","''",$competitionInfo->venue)));
		$state = ltrim(rtrim(str_replace("'","''",$competitionInfo->state)));
		$startdate = $competitionInfo->startdate;
		$enddate = $competitionInfo->enddate;
		$type = $competitionInfo->type;
		$participants = $competitionInfo->participants;
		$sanctioned = $competitionInfo->sanctioned==true?1:0;

		if ($competitionInfo->mode==0){
			$query = "insert into competition(idsubscription,title,countrycode,state,venue,startdate,enddate,username,type,participants,sanctioned)values(".$subscription.",'".$title."','".$noc."','".$state."','".$venue."','".$startdate."','".$enddate."','".$user."','".$type."',".$participants.",".$sanctioned.")";
		}else{
			$query = "update competition set idsubscription=".$subscription.",title='".$title."',countrycode='".$noc."',state='".$state."',venue='".$venue."',startdate='".$startdate."',enddate='".$enddate."',username='".$user."',type='".$type."',participants=".$participants.",sanctioned=".$sanctioned." WHERE idcompetition=".$competitionInfo->modeID."";
			mysqli_query($con,$query);
			$query = "DELETE FROM competitiongametype WHERE idcompetition=".$competitionInfo->modeID."";

			$resp->message="Tournament updated successfully.";
		}
		mysqli_query($con,$query);
		$query = "select idcompetition from competition where idsubscription=".$subscription." and title='".$title."' and state='".$state."' and countrycode='".$noc."' and venue='".$venue."' and startdate='".$startdate."' and enddate='".$enddate."'";
		$result=mysqli_query($con,$query);
		if($row = mysqli_fetch_assoc($result)) {
			$idcompetition = $row['idcompetition'];
			if ($competitionInfo->mode==0){
				$leftlogo = "../competitions/".$idcompetition."/reports/gmsytemlogo.jpg";
				$rightlogo = "../competitions/".$idcompetition."/reports/istaf.png";
				$query = "update competition set leftlogo='".$leftlogo."',rightlogo='".$rightlogo."' WHERE idcompetition=".$idcompetition."";
				mysqli_query($con,$query);

			}

			foreach($competitionInfo->gametype as $key => $gt){
				$query = "insert into competitiongametype(idcompetition,idgametype,playing,reserved,category,maxgroup,maxteam)values(".$idcompetition.",".$gt->id.",".$gt->playing.",".$gt->reserved.",".$gt->category.",".$gt->maxgroup.",".$gt->maxteam.")";
				mysqli_query($con,$query);
			}
			// Prepare default logos
			if(!file_exists("../../competitions/"))
				mkdir("../../competitions/", 0777, true);
			if(!file_exists("../../competitions/".$idcompetition."/")){
				mkdir("../../competitions/".$idcompetition."/", 0777, true);
				chmod("../../competitions/".$idcompetition."/", 0777);
				mkdir("../../competitions/".$idcompetition."/reports", 0777, true);
				chmod("../../competitions/".$idcompetition."/reports", 0777);
				mkdir("../../competitions/".$idcompetition."/sponsors", 0777, true);
				chmod("../../competitions/".$idcompetition."/sponsors", 0777);
				mkdir("../../competitions/".$idcompetition."/teams", 0777, true);
				chmod("../../competitions/".$idcompetition."/teams", 0777);
				copy("../images/gmsytemlogo.jpg","../../competitions/".$idcompetition."/reports/gmsytemlogo.jpg");
				copy("../images/istaf.png","../../competitions/".$idcompetition."/reports/istaf.png");
				copy("../images/index.html","../../competitions/".$idcompetition."/reports/index.html");
			}
		}else{
			$resp->errorno = 2;
			$resp->message = "Change(s) unsuccessful. Tournament not created.";
		}
	}catch(Exception $e){
		$resp->errorno = 2;
		$resp->message = $e->getMessage();
	}
	echo json_encode($resp);
?>
