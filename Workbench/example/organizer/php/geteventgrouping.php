<?php
	include 'config.php';
	include 'checkuser.php';

	class cobj{
		public $recid=0;
		public $idgametype=0;
		public $eventname='';
		public $idteam=0;
		public $name="";
		public $category=0;
		public $categorydesc="";
		public $group=0;
		public $team=0;
	}

	$aobj = array();
	$idcompetition = $_GET['competition'];
	
	$result = mysqli_query($con,"SELECT a.idevengrouptctr,a.idgametype,a.idteam,a.idgroup,a.idteamgroup,b.name,a.idcategory,c.description as 'eventname',d.description as 'categorydesc' FROM eventgrouping a,competitionteam b,gametype c,category d WHERE a.idcompetition =".$idcompetition." and a.idteam=b.idteam and a.idgametype=c.idgametype and a.idcategory=d.idcategory order by a.idcategory,a.idgametype,a.idgroup,a.idteamgroup");
	
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cobj();
		$obj->recid = $row['idevengrouptctr'];
		$obj->idgametype = $row['idgametype'];
		$obj->eventname = $row['eventname'];
		$obj->idteam = $row['idteam'];
		$obj->name = $row['name'];
		$obj->category = $row['idcategory'];
		$obj->categorydesc = $row['categorydesc'];
		$obj->group = $row['idgroup'];
		$obj->team = $row['idteamgroup'];
		$aobj[] = $obj;
	}
	$resp->data=$aobj;
	echo json_encode($resp);
?>