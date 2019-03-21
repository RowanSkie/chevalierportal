<?php

	include 'config.php';
	include 'checkuser.php';

	$resp->message =  "Team added to group successfully.";

	try{
		$eventgrouping = str_replace("\\","",$_GET['eventgrouping']);
		$tg = json_decode($eventgrouping);
		
		// validations
		$query="SELECT 1 FROM eventgrouping WHERE idcompetition=".$tg->competition." and idgametype=".$tg->idgametype." and idcategory=".$tg->category." and idteam=".$tg->idteam."";
		$result=mysqli_query($con,$query);
		if(mysqli_num_rows($result)==0){
			$query="SELECT 1 FROM eventgrouping WHERE idcompetition=".$tg->competition." and idgametype=".$tg->idgametype." and idcategory=".$tg->category." and idgroup=".$tg->group." and idteamgroup=".$tg->team."";
			$result=mysqli_query($con,$query);
			if(mysqli_num_rows($result)>0){
				$resp->errorno = 2;
				$resp->message = "Change(s) unsuccessful. A team is assigned to the selected group and team.";
			}else{
				// insert
				$query = "INSERT into eventgrouping(idcompetition,idgametype,idteam,idcategory,idgroup,idteamgroup)VALUES(".$tg->competition.",".$tg->idgametype.",".$tg->idteam.",".$tg->category.",".$tg->group.",".$tg->team.")";
				$result=mysqli_query($con,$query);				
			}
		}else{
			$query="SELECT 1 FROM eventgrouping WHERE idcompetition=".$tg->competition." and idgametype=".$tg->idgametype." and idcategory=".$tg->category." and idgroup=".$tg->group." and idteamgroup=".$tg->team." and idteam!=".$tg->idteam."";
			$result=mysqli_query($con,$query);
			if(mysqli_num_rows($result)>0){
				$resp->errorno = 2;
				$resp->message = "Change(s) unsuccessful. A team is assigned to the selected group and team.";
			}else{
				// update
				$query = "UPDATE eventgrouping set idgroup=".$tg->group.", idteamgroup=".$tg->team." where idcompetition=".$tg->competition." and idgametype=".$tg->idgametype." and idcategory=".$tg->category." and idteam=".$tg->idteam."";
				$result=mysqli_query($con,$query);
				$resp->message =  "Updated successfully.";
			}
		}
		
	}catch(Exception $e){
		$resp->errorno = 1;
		$resp->message = $e->getMessage();
	}
	echo json_encode($resp);

?>
