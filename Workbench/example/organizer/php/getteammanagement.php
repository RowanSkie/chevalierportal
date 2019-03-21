<?php
	include 'config.php';
	include 'checkuser.php';
	
	class CTeamManagement{
		public $idteam;
		public $name;
		public $noc;
		public $category;
		public $manager;
		public $coach;
		public $asstcoach;
	}


	$team = $_GET['user'];
	$idcompetition = $_GET['competition'];
	$result = mysqli_query($con,"SELECT idteam FROM competitionteam WHERE idcompetition=".$idcompetition." and email ='".$team."'");
	if($row = mysqli_fetch_assoc($result)) {
		$idteam = $row['idteam'];
		//use the details from the most recent competition
		$query = "select a.name,a.noc,a.manager,a.coach,a.asstcoach,b.description as category from competitionteam a,category b where idteam=".$idteam." and a.category=b.idcategory order by idcompetition desc";
		$result = mysqli_query($con,$query );		
		if(mysqli_num_rows($result)>0){
			if($row=mysqli_fetch_assoc($result)){
				$obj = new CTeamManagement();
				$obj->idteam = $idteam;
				$obj->name = $row['name'];
				$obj->noc = $row['noc'];
				$obj->category = $row['category'];
				$obj->manager = $row['manager'];
				$obj->coach = $row['coach'];
				$obj->asstcoach = $row['asstcoach'];
			}else{
				$obj = new CTeamManagement();
			}
		}else{
			$obj = new CTeamManagement();
		}
	}else{
		$obj = new CTeamManagement();
	}
	$resp->data=$obj;
	echo json_encode($resp);
?>