<?php

	include 'config.php';

	$org = $_GET['organizer'];
	$orgInfo = json_decode($org);

	class Response{
		public $data;
		public $errorno=0;
		public $message="";
	}
	
	$resp = new Response();
	$resp->errorno = 0;
	$resp->message =  "Your registration was successful. Please check your email for verification.";

	try{
		$teamid = mysqli_query($con,"select username from orgregistration where username = '" .$orgInfo->email. "' and isactive=1");
		if($row = mysqli_fetch_assoc($teamid)) {
			$resp->errorno = 2;
			$resp->message = "E-mail already used.";		
			
		}else{
			$query = "insert into orgregistration (username,password,name,nationalid,passport,birthdate,organization,contacthp,contactoffice,address,state,country,postalcode)
			values('".$orgInfo->email."','".$orgInfo->password."','".$orgInfo->name."','".$orgInfo->nationalid."','".$orgInfo->passport."','".$orgInfo->birthdate."','".$orgInfo->organization.
			"','".$orgInfo->contactmobile."','".$orgInfo->contactoffice."','".$orgInfo->address."','".$orgInfo->state."','".$orgInfo->noc."','".$orgInfo->postalcode."')";

			mysqli_query($con,$query);
			$resp->message = "Thank you for registering.";
		}
	}catch(Exception $e){
		$resp->errorno = 1;
		$resp->message = $e->getMessage();
	}
	echo json_encode($resp);
?>
