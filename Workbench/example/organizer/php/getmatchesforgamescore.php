<?php
	include 'config.php';

	class cobj{
		public $ID="";
		public $Description="";
		public $event="";
		public $gamecount="";
		public $gameno="";
	}
	$competition = $_GET['competition'];
	$result = mysqli_query($con,"SELECT DISTINCT a.idmatch,b.description as event, b.gamecount FROM event a, gametype b WHERE a.idcompetition=" .$competition. " AND a.isactive=1 AND a.status>0 AND a.finalize=1 AND a.idgametype=b.idgametype order by a.idgametype,a.date,a.time,a.courtno" );

	$aobj = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cobj();
		$obj->ID = $row['idmatch'];
		$obj->Description = $row['idmatch'];
		$obj->event = $row['event'];
		$obj->gamecount = $row['gamecount'];		
		$query = "SELECT gameno FROM event WHERE idcompetition=" .$competition. " AND isactive=1 and status>0 AND idmatch='".$obj->ID."' LIMIT 1";
		$res=mysqli_query($con,$query);
		while($r=mysqli_fetch_assoc($res)){
			$obj->gameno = $r['gameno'];
		};
		$aobj[] = $obj;
	}
	echo json_encode($aobj);
?>