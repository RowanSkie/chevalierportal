<?php

	include 'config.php';
	include 'checkuser.php';

	$official = json_decode(str_replace("\\","",$_GET['official']));
	
	// get official role
	$query="SELECT idofficialrole FROM officials WHERE idcompetition=".$official->competition." AND idofficial=".$official->idofficial."";
	$result=mysqli_query($con,$query);
	if($row=mysqli_fetch_assoc($result)){
		$idofficialrole=$row['idofficialrole'];
		
		// chief referee
		if ($idofficialrole == 4){
			$query = "UPDATE competition SET chiefreferee='' where idcompetition=".$official->competition."";
			mysqli_query($con,$query);
		}
		// technical delegate
		if ($idofficialrole == 1){
			$query = "UPDATE competition SET technicaldelegate='' where idcompetition=".$official->competition."";
			mysqli_query($con,$query);
		}
		
		
		$query = "DELETE FROM officials WHERE idcompetition=".$official->competition." AND idofficial=".$official->idofficial."";
		mysqli_query($con,$query);
		
		$query = "DELETE FROM officials WHERE idcompetition=".$official->competition." AND idofficial=".$official->idofficial."";
		mysqli_query($con,$query);

		$resp->message="Official removed successfully.";
		echo json_encode($resp);
	}else{
		$resp->errorno = 2;
		$resp->message="Unable to remove official.";		
	}
	
?>
