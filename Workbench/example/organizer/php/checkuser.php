<?php
	include 'config.php';
	$id = $_GET['id'];
	$token = $_GET['token'];
	$usertype = $_GET['type'];
	
	class Response{
		public $data;
		public $errorno=0;
		public $message="";
	}
	
	$resp = new Response();
	
	$query="SELECT token FROM user WHERE id =".$id." AND usertype=".$usertype." AND isactive=1";
	$result = mysqli_query($con,$query);
	if(mysqli_num_rows($result)>0){
		if($row=mysqli_fetch_assoc($result)){
			if($row['token']==null){
				$resp->errorno = 1;
				$resp->message="Please log-in.";			
			}elseif($row['token']!=$token){
				$resp->errorno = 1;
				$resp->message="You have logged-in from a different computer. This session has been terminated.";			
			}else{
				$time = date('Y-m-d H:i:s');
				$result = mysqli_query($con,"UPDATE user SET token='".$token."', lastactivity='".$time."' WHERE id ='".$id."' AND token='".$token."' AND usertype=".$usertype." AND isactive=1");					
			}
		}
	}else{
		$resp->errorno = 1;
		$resp->message="Please log-in.";
	}
	if($resp->errorno!=0){
		echo json_encode($resp);
		exit;
	}
		
?>