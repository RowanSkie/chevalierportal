<?php
	include 'config.php';
	include 'checkuser.php';


	class cTeam{
		public $idteam;
		public $name;
		public $win=0;
		public $loss=0;
		public $group="";
	}
	
	$p = str_replace("\\","",$_GET['param']);
	$g = json_decode($p);

	$query = "select DISTINCT b.idteam,c.name,c.noc,(select count(winner) from event where idcompetition=a.idcompetition and pgroup=a.pgroup and idgametype=a.idgametype and winner=b.idteam) as win, (select count(loser) from event where idcompetition=a.idcompetition and pgroup=a.pgroup and  idgametype=a.idgametype and loser=b.idteam) as loss from event a,eventgamescore b,competitionteam c where a.idcompetition=b.idcompetition  and a.idmatch=b.idevent and b.idteam=c.idteam and a.phase='PRELIMINARY' and a.idcompetition=".$g->competition." and a.idgametype=".$g->idgametype." and a.category='".$g->category."' and a.pgroup='".$g->idteam1group."' order by win desc,loss asc,name limit 3";

	$result = mysqli_query($con,$query);

	$agroup = array();
	$aobj = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cTeam();
		$obj->idteam = $row['idteam'];
		$obj->name = $row['name'];
		$obj->win = $row['win'];
		$obj->loss = $row['loss'];
		$obj->group = $g->idteam1group;
		$aobj[] = $obj;
	}
	$agroup[] = $aobj;

	$query = "select DISTINCT b.idteam,c.name,c.noc,(select count(winner) from event where idcompetition=a.idcompetition and pgroup=a.pgroup and idgametype=a.idgametype and winner=b.idteam) as win, (select count(loser) from event where idcompetition=a.idcompetition and pgroup=a.pgroup and  idgametype=a.idgametype and loser=b.idteam) as loss from event a,eventgamescore b,competitionteam c where a.idcompetition=b.idcompetition  and a.idmatch=b.idevent and b.idteam=c.idteam and a.phase='PRELIMINARY' and a.idcompetition=".$g->competition." and a.idgametype=".$g->idgametype." and a.category='".$g->category."' and a.pgroup='".$g->idteam2group."' order by win desc,loss asc,name limit 3";
	$result = mysqli_query($con,$query);

	$aobj = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cTeam();
		$obj->idteam = $row['idteam'];
		$obj->name = $row['name'];
		$obj->win = $row['win'];
		$obj->loss = $row['loss'];
		$obj->group = $g->idteam2group;
		$aobj[] = $obj;
	}
	$agroup[] = $aobj;
	
	$resp->data=$agroup;
	echo json_encode($resp);	
?>

	
