<?php

	include 'config.php';
	include 'checkuser.php';

	$p = str_replace("\\","",$_GET['official']);
	$official = json_decode($p);
	$official->type = strtoupper($official->type);
	
	if ($official->role == 4 || $official->role == 5){
		$query = "DELETE FROM officials where idofficial <> 0 and idcompetition=".$official->competition." AND idofficialrole=".$official->role."";
		mysqli_query($con,$query);
		
		// chief referee
		if ($official->role == 4){
			$query = "UPDATE competition SET chiefreferee='". $official->name. "' where idcompetition=".$official->competition."";
			mysqli_query($con,$query);
		}
		// technical delegate
		if ($official->role == 1){
			$query = "UPDATE competition SET technicaldelegate='". $official->name. "' where idcompetition=".$official->competition."";
			mysqli_query($con,$query);
		}
	}

	if($official->idofficial==""){
		$query = "SELECT idcompetition,idofficialrole,name,noc,idofficialtype FROM officials WHERE idcompetition=".$official->competition." AND idofficialrole=".$official->role." AND name='".$official->name."' AND noc='".$official->noc."' And idofficialtype='".$official->type."' and isactive=1";
		$result=mysqli_query($con,$query);
		if(mysqli_num_rows($result)>0){
			$resp->errorno=2;
			$resp->message="The official already exists.";
		}else{
			$query = "insert into officials(idcompetition,idofficialrole,idofficialtype,name,noc,passport,nric,birthdate,email,mobileno)values(".$official->competition.",".$official->role.",'".$official->type."','".$official->name."','".$official->noc."','".$official->passport."','".$official->nric."','".$official->birthdate."','".$official->email."','".$official->mobileno."')";
			mysqli_query($con,$query);
			$resp->message="Official added successfully.";
		}
	}else{
			$query = "UPDATE officials SET idofficialrole=".$official->role.", idofficialtype='".$official->type."', name='".$official->name."', noc='".$official->noc."', passport='".$official->passport."',nric='".$official->nric."',birthdate='".$official->birthdate."',email='".$official->email."', mobileno='".$official->mobileno."' WHERE idcompetition=".$official->competition." AND idofficial=".$official->idofficial." and isactive=1";
			mysqli_query($con,$query);
			$resp->message="Official information updated successfully.";		
	}
	echo json_encode($resp);
	
?>
