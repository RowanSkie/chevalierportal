<?php
	include 'config.php';
	include 'checkuser.php';

	class cobj{
		public $ID;
		public $Description;
		public $event;
		public $gamecount;
	}

	$competition = $_GET['competition'];
	//$result = mysqli_query($con,"SELECT DISTINCT a.idmatch,b.description as event, b.gamecount FROM event a, gametype b WHERE a.idcompetition=" .$competition. " AND a.isactive=1 AND a.idgametype=b.idgametype order by a.idgametype,a.date,a.time,a.courtno" );
	$result = mysqli_query($con,"SELECT DISTINCT a.idmatch,b.description as event, b.gamecount FROM event a, gametype b WHERE a.idcompetition=" .$competition. " AND a.isactive=1 AND a.idgametype=b.idgametype order by a.idmatch" );
	
	$aobj = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cobj();
		$obj->ID = $row['idmatch'];
		$obj->Description = $row['idmatch'];
		$obj->event = $row['event'];
		$obj->gamecount = $row['gamecount'];
		$aobj[] = $obj;
	}
	$resp->data=$aobj;
	echo json_encode($resp);	
?>