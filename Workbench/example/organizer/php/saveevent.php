<?php

	include 'config.php';
	include 'checkuser.php';

	$resp->message =  "Event successfully created.";

	try{
		$eventdetail = str_replace("\\","",$_GET['eventdetail']);
		$matchInfo = (array)json_decode($eventdetail);

		$idcompetition = $matchInfo['competition'];
		$matchdesc = strtoupper(str_replace("'","\'",$matchInfo['description']));
		$phase = $matchInfo['phase'];
		$category = $matchInfo['category'];
		$team1 = $matchInfo['team1'];
		$team2 = $matchInfo['team2'];
		$courtno = strtoupper($matchInfo['courtno']);
		$matchno = strtoupper(str_replace("'","\'",$matchInfo['matchno']));
		$date = date('Y-m-d',strtotime($matchInfo['date']));
		$time = $matchInfo['time'];
		$set = $matchInfo['set'];
		$points = $matchInfo['points'];
		$changeside = $matchInfo['side'];
		$gametype = $matchInfo['gametype'];
		$gamecount = $matchInfo['gamecount'];
		$oldmatchid = $matchInfo['oldmatchid'];
		$group = $matchInfo['group'];
		$prevMatchT1 = $matchInfo['prevMatchT1'];
		$prevMatchT2 = $matchInfo['prevMatchT2'];
		$cat=0;
		if($category=='MEN')
			$cat=1;
		elseif($category=='WOMEN')
			$cat=2;
	
		// for new events
		if($oldmatchid==""){
			// check if the date provided is within the duration of the competition
			$query = "select * from competition where idcompetition=".$idcompetition." and date('" .$date. "') between startdate and enddate";
			$result = mysqli_query($con,$query );
			if($row = mysqli_fetch_assoc($result)){
			
				for($i=1;$i<=$gamecount;$i++){
					$query = "insert into event(idcompetition,idmatch,courtno,date,time,team1,team2,noofset,noofpoints,changeside,idgametype,matchdesc,category,phase,gameno,pgroup,prevMatchT1,prevMatchT2) values(".$idcompetition.",'".$matchno."',".$courtno.",'".$date."','".$time."',".$team1.",".$team2.",".$set.",".$points.",".$changeside.",".$gametype.",'".$matchdesc."','".$category."','".$phase."',".$i.",'".$group."','".$prevMatchT1."','".$prevMatchT2."')";
					mysqli_query($con,$query);
				}
				
				$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$matchno. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$idcompetition." and idteam=".$team1. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1"; 
				mysqli_query($con,$query);
				
				// insert to event match score in advance
				for($j=1;$j<=$gamecount;$j++){
					for($i=1;$i<=$set;$i++){
						$query = "insert into eventgamescore(idcompetition,idevent,idteam,setno,gameno) values(".$idcompetition.",'".$matchno."',".$team1.",".$i.",".$j.")";
						mysqli_query($con,$query);
					}
				}
				$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$matchno. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$idcompetition." and idteam=".$team2. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1"; 
				mysqli_query($con,$query);

				// insert to event match score in advance
				for($j=1;$j<=$gamecount;$j++){
					for($i=1;$i<=$set;$i++){
						$query = "insert into eventgamescore(idcompetition,idevent,idteam,setno,gameno) values(".$idcompetition.",'".$matchno."',".$team2.",".$i.",".$j.")";
						mysqli_query($con,$query);		
					}
				}
			}else{
				$resp->errorno = 2;
				$resp->message = "Event date is not within the duration of the tournament.";
			}
		}else{
			$query = "UPDATE event set team1=".$team1.",team2=".$team2.",idmatch='".$matchno."', courtno=".$courtno.", date='".$date."', time='".$time."', noofset=".$set.", noofpoints=".$points.", changeside=".$changeside.",matchdesc='".$matchdesc."', category='".$category."' ,phase='".$phase."',pgroup='".$group."', prevMatchT1='".$prevMatchT1."', prevMatchT2='".$prevMatchT2."' WHERE idcompetition=".$idcompetition." AND idmatch='".$oldmatchid."'";
			mysqli_query($con,$query);

			$query = "update eventplayers set idevent='".$matchno."' WHERE idcompetition=".$idcompetition." AND idevent='".$oldmatchid."'";
			//$query = "delete from eventplayers WHERE idcompetition=".$idcompetition." AND idevent='".$oldmatchid."'";
			mysqli_query($con,$query);

			// remove playsers that are no longer in the roster
			$query = "delete from eventplayers WHERE idcompetition=".$idcompetition." AND idevent='".$matchno."' AND idteam=".$team1." AND idcategory=".$cat." AND jerseyno NOT IN(SELECT jerseyno from teameventplayers where idcompetition=".$idcompetition." and idteam=".$team1. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1)";
			mysqli_query($con,$query);
			// add players that are in the roster but not in the event
			$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$matchno. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$idcompetition." and idteam=".$team1. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1 AND jerseyno NOT IN(SELECT jerseyno from eventplayers where idcompetition=".$idcompetition." and idevent='".$matchno."' and idteam=".$team1. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1)";			
			mysqli_query($con,$query);
		
		
			// remove playsers that are no longer in the roster
			$query = "delete from eventplayers WHERE idcompetition=".$idcompetition." AND idevent='".$matchno."' AND idteam=".$team2." AND idcategory=".$cat."  AND jerseyno NOT IN(SELECT jerseyno from teameventplayers where idcompetition=".$idcompetition." and idteam=".$team2. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1)";
			mysqli_query($con,$query);
			// add players that are in the roster but not in the event
			$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$matchno. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$idcompetition." and idteam=".$team2. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1 AND jerseyno NOT IN(SELECT jerseyno from eventplayers where idcompetition=".$idcompetition." and idevent='".$matchno."' and idteam=".$team2. " and idgametype=".$gametype." AND idcategory=".$cat." and isactive=1)";
			mysqli_query($con,$query);
		

			$query = "DELETE FROM eventgamescore WHERE idcompetition=".$idcompetition." AND idevent='".$oldmatchid."'";
			mysqli_query($con,$query);
			
			for($j=1;$j<=$gamecount;$j++){
				for($i=1;$i<=$set;$i++){
					$query = "insert into eventgamescore(idcompetition,idevent,idteam,setno,gameno) values(".$idcompetition.",'".$matchno."',".$team1.",".$i.",".$j.")";
					mysqli_query($con,$query);		
					$query = "insert into eventgamescore(idcompetition,idevent,idteam,setno,gameno) values(".$idcompetition.",'".$matchno."',".$team2.",".$i.",".$j.")";
					mysqli_query($con,$query);		
				}
			}			
			$resp->message =  "Event successfully updated.";
		}
	}catch(Exception $e){
		$resp->errorno = 1;
		$resp->message = $e->getMessage();
	}
	echo json_encode($resp);

?>
