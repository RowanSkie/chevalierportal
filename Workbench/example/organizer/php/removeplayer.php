<?php

	include 'config.php';
	include 'checkuser.php';

	$email = str_replace("\\","",$_GET['user']);
	$competition = str_replace("\\","",$_GET['competition']);
	$jersey = str_replace("\\","",$_GET['jersey']);
	$category = str_replace("\\","",$_GET['category']);


	$teamid = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and email = '" .$email. "' and isactive=1");
	if($row = mysqli_fetch_assoc($teamid)) {
		$idteam = $row['idteam'];
		$query = "DELETE FROM competitionteamparticipant where idcompetition=".$competition." and idteam=".$idteam." and jerseyno=".$jersey." and category=".$category;
		mysqli_query($con,$query);
		$query = "DELETE FROM eventplayers where idcompetition=".$competition." and idteam=".$idteam." and jerseyno=".$jersey." and idcategory=".$category;
		mysqli_query($con,$query);
		$query = "DELETE FROM teameventplayers where idcompetition=".$competition." and idteam=".$idteam." and jerseyno=".$jersey." and idcategory=".$category;
		mysqli_query($con,$query);
		if($jersey==-1){ //manager
			$query = "update competitionteam set manager='' where idcompetition=".$competition." and idteam=".$idteam." and isactive=1";
			mysqli_query($con,$query);			
		}elseif($jersey==-2){ //coach
			$query = "update competitionteam set coach='' where idcompetition=".$competition." and idteam=".$idteam." and isactive=1";
			mysqli_query($con,$query);			
		}elseif($jersey==-3){//asst coach
			$query = "update competitionteam set asstcoach='' where idcompetition=".$competition." and idteam=".$idteam." and isactive=1";
			mysqli_query($con,$query);			
		}
		$resp->message= "Player removed successfully.";		
	}else{
		$resp->errorno=2;
		$resp->message="Error: team not found.";
	}
	
	echo json_encode($resp);

?>
