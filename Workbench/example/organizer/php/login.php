<?php
	include 'config.php';
	$username = $_GET['user'];
	$password = $_GET['password'];
	$token = $_GET['token'];
	class User{
		public $id=0;
		public $subscription="";		
		public $groupcode="";
		public $token="";
		public $usertype=2;
		public $errorno=0;
		public $message="";
	}
	$user = new User();
	$time = date('Y-m-d H:i:s');

	// check login details
	$result = mysqli_query($con,"SELECT a.id,b.idsubscription,b.groupcode FROM user a, organizer b WHERE a.username ='".$username."' AND a.password = '".$password."' AND a.usertype=2 AND a.isactive=1 and a.username=b.username");	
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_assoc($result);
		$user->id = $row['id'];
		$user->subscription = $row['idsubscription'];
		$user->groupcode = $row['groupcode'];		
	}
	else{
		$user->errorno = 1;
		$user->message="Invalid username or password.";
	}

	/* retrieve organizer detail*/
	if ($user->errorno==0){
		/*
		$query="SELECT token,lastactivity FROM user WHERE username ='".$username."' AND usertype=".$user->usertype." AND isactive=1";
		$result = mysqli_query($con,$query);
		if(mysqli_num_rows($result)>0){
			if($row=mysqli_fetch_assoc($result)){
				if(($row['token']!=$token) && ($row['lastactivity']!=null)){
					//check time difference
					$d1 = new DateTime($time);
					$d2 = new DateTime($row['lastactivity']);
					$interval = $d1->diff($d2);
					if ($interval->i < 5){
						$user->errorno = 1;
						$user->message="You are currently logged-in from a different session. Multiple sessions not allowed.";
					}
				}else{
					$user->token = uniqid(rand(), true);
					$result = mysqli_query($con,"UPDATE user SET token='".$user->token."', lastactivity='".$time."' WHERE username ='".$username."' AND usertype=2 AND isactive=1");				
				}
			}
		}
		*/
		$user->token = uniqid(rand(), true);
		$result = mysqli_query($con,"UPDATE user SET token='".$user->token."', lastactivity='".$time."' WHERE username ='".$username."' AND usertype=2 AND isactive=1");				
	}
	echo json_encode($user);

?>