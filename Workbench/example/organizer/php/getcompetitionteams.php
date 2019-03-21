<?php
	include 'config.php';
	include 'checkuser.php';


	class cteam{
		public $idteam;
		public $name;
		public $noc;
		public $email;
		public $category;
		public $idcategory;
		public $logo;
		public $win;
		public $loss;
		public $score;
		public $group;
		public $idingroup;
		public $participants;
		public $players;
	}

	class cgroup{
		public $noofgroups;
		public $teampergroup;

	}

	class ceventparticipant{
		public $gametype=0;
		public $men="-";
		public $women="-";
	}

	$competition = $_GET['competition'];

	$result = mysqli_query($con,"SELECT b.idgroup,b.idingroup,b.idteam, b.name, b.noc,b.email,b.category as idcategory, b.logo,b.win,b.loss,b.totalpoints,c.description as category FROM competitionteam b,category c WHERE idcompetition=".$competition." and b.category=c.idcategory and isactive=1 order by b.noc,b.name");
	$aTeam = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cteam();
		$obj->idteam = $row['idteam'];
		$obj->name = $row['name'];
		$obj->noc = $row['noc'];
		$obj->email = $row['email'];
		$obj->category = $row['category'];
		$obj->idcategory = $row['idcategory'];
		$obj->logo = $row['logo'];
		$obj->win = $row['win'];
		$obj->loss = $row['loss'];
		$obj->score = $row['totalpoints'];
		$obj->group = $row['idgroup'];
		$obj->idingroup = $row['idingroup'];
		$res = mysqli_query($con,"SELECT * FROM competitionteamparticipant WHERE idcompetition=".$competition." and idteam=".$obj->idteam."");
		$obj->participants = mysqli_num_rows($res);
		

		// get the events
		$aPlayers = array();
		$res = mysqli_query($con,"select  idgametype from competitiongametype where idcompetition =".$competition."");
		while($r=mysqli_fetch_assoc($res)){
			$p = new ceventparticipant();
			$p->gametype=$r['idgametype'];
			$re = mysqli_query($con,"select count(distinct jerseyno) as pcount from teameventplayers where idcompetition =".$competition." and idgametype=".$r['idgametype']." and idteam=".$obj->idteam." and idcategory=1");
			$rtmp=mysqli_fetch_assoc($re);
			$p->men = mysqli_num_rows($re)>0?$rtmp['pcount']:"-";
			$re = mysqli_query($con,"select count(distinct jerseyno) as pcount from teameventplayers where idcompetition =".$competition." and idgametype=".$r['idgametype']." and idteam=".$obj->idteam." and idcategory=2");
			$rtmp=mysqli_fetch_assoc($re);
			$p->women = mysqli_num_rows($re)>0?$rtmp['pcount']:"-";
			$aPlayers[] = $p;
		}
		$obj->players=$aPlayers;
		$aTeam[] = $obj;
	}


	$resp->data = $aTeam;
	echo json_encode($resp);
?>
