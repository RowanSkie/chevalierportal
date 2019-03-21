<?php
	include 'config.php';
	include 'checkuser.php';
	$username = $_GET['user'];
	
	class CCompetition{
		public $id='';
		public $title='';
	}
	
	$resp = new Response();

	$obj = new CCompetition();
	$result = mysqli_query($con,"SELECT a.idcompetition,a.title FROM competition a, competitionteam b WHERE a.idcompetition=b.idcompetition and b.email ='".$username."'");
	
	if($row = mysqli_fetch_assoc($result)) {
		$obj->id=$row['idcompetition'];
		$obj->title=$row['title'];
	}
	$resp->data=$obj;
	echo json_encode($resp);		

?>