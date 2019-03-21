<?php
	include 'config.php';
	include 'checkuser.php';

	class cTeamPlayer{
		public $jersey="";
		public $category=0;
		public $name;
		public $position;
		public $height;
		public $weight;
		public $birthdate;
		public $passport;
		public $nric;
		public $email;
		public $mobileno;
	}

	$aobj = array();
	$team = $_GET['user'];
	$idcompetition = $_GET['competition'];
	$result = mysqli_query($con,"SELECT idteam,manager,coach,asstcoach FROM competitionteam WHERE email ='".$team."' and idcompetition=".$idcompetition." and isactive=1");
	if($row = mysqli_fetch_assoc($result)) {
		$idteam = $row['idteam'];
		$manager = $row['manager'];
		$coach = $row['coach'];
		$asstcoach = $row['asstcoach'];
		$query = "select jerseyno,category,name, position,height,weight,birthdate,passport,nric,email,mobileno from competitionteamparticipant WHERE idcompetition=".$idcompetition." and idteam=".$idteam. " and isactive=1 order by jerseyno";
		$result = mysqli_query($con,$query );
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)){
				$obj = new cTeamPlayer();
				$obj->jersey = $row['jerseyno'];
				$obj->category = $row['category'];
				$obj->name = $row['name'];
				$obj->position = $row['position'];
				$obj->birthdate = date("d/m/Y", strtotime($row['birthdate']));
				$obj->height = $row['height'];
				$obj->weight = $row['weight'];
				$obj->passport = $row['passport'];
				$obj->nric = $row['nric'];
				$obj->email = $row['email'];
				$obj->mobileno = $row['mobileno'];
				$aobj[] = $obj;
			}
		}
	}
	$resp->data=$aobj;
	echo json_encode($resp);
?>
