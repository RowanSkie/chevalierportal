<?php
	include 'config.php';
	include 'checkuser.php';
	
	class cTeamPlayer{
		public $team="";
		public $accreditationno="";
		public $jersey="";
		public $name;
		public $position;
		public $noc;
		public $email;
		public $mobile;
	}

	
	$aobj = array();
	$team = $_GET['user'];
	$idcompetition = $_GET['competition'];
	if($team=='ORGANIZER'){
		// add the organizer
		$query = "SELECT 'ORGANIZER' as 'team',a.idofficial,a.accreditationno,a.name,a.idofficialrole,b.description as 'position',a.noc,a.email,a.mobileno from officials a, officialrole b where a.idofficialrole = b.idofficialrole and a.isactive=1 and a.idcompetition=".$idcompetition;
		$result = mysqli_query($con,$query );		
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)){
				$obj = new cTeamPlayer();
				$obj->team = $row['team'];
				$obj->accreditationno = $row['accreditationno']==null?"<a href='#Accreditation' onclick=\"generateInvAccreditation(0,".$row['idofficial'].")\">Generate</a>":$row['accreditationno'];
				$obj->name = $row['name'];
				$obj->jersey = "";
				$obj->position = $row['position'];
				$obj->noc = $row['noc'];
				$obj->email = $row['email'];
				$obj->mobileno = $row['mobileno'];
				$aobj[] = $obj;
			}
		}
	}else{
		$result = mysqli_query($con,"SELECT idteam,manager,coach,asstcoach FROM competitionteam WHERE email ='".$team."' and idcompetition=".$idcompetition." and isactive=1");
		$idteam="";
		if($row = mysqli_fetch_assoc($result)) {
			$idteam = $row['idteam'];
			$query = "select a.name as team, b.idcompetitionteamparticipant,b.idteam, b.accreditationno, b.jerseyno,b.name, b.position,a.noc,b.email,b.mobileno  from competitionteam a, competitionteamparticipant b WHERE a.idcompetition=b.idcompetition and a.idcompetition=".$idcompetition." and a.idteam=b.idteam and a.idteam=".$idteam. " and b.isactive=1 order by team, jerseyno";
		}else{
			// add the organizer
			$query = "SELECT 'ORGANIZER' as 'team',a.idofficial,a.accreditationno,a.name,a.idofficialrole,b.description as 'position',a.noc,a.email,a.mobileno  from officials a, officialrole b where a.idofficialrole = b.idofficialrole and a.isactive=1 and a.idcompetition=".$idcompetition;
			$result = mysqli_query($con,$query );		
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_assoc($result)){
					$obj = new cTeamPlayer();
					$obj->team = $row['team'];
					$obj->accreditationno = $row['accreditationno']==null?"<a href='#Accreditation' onclick=\"generateInvAccreditation(0,".$row['idofficial'].")\">Generate</a>":$row['accreditationno'];
					$obj->name = $row['name'];
					$obj->jersey = "";
					$obj->position = $row['position'];
					$obj->noc = $row['noc'];
					$obj->email = $row['email'];
					$obj->mobileno = $row['mobileno'];
					$aobj[] = $obj;
				}
			}
			$query = "select a.name as team, b.idcompetitionteamparticipant,b.idteam, b.accreditationno, b.jerseyno,b.name, b.position,a.noc,b.email,b.mobileno  from competitionteam a, competitionteamparticipant b WHERE a.idcompetition=b.idcompetition and a.idcompetition=".$idcompetition." and a.idteam=b.idteam and b.isactive=1 order by team, jerseyno";
		}
		$result = mysqli_query($con,$query );		
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)){
				$obj = new cTeamPlayer();
				$obj->team = $row['team'];
				$obj->accreditationno = $row['accreditationno']==null?"<a href='#Accreditation' onclick=\"generateInvAccreditation(".$row['idteam'].",".$row['idcompetitionteamparticipant'].")\">Generate</a>":$row['accreditationno'];
				$obj->jersey = $row['jerseyno'];
				$obj->name = $row['name'];
				$obj->position = $row['position'];
				$obj->noc = $row['noc'];
				$obj->email = $row['email'];
				$obj->mobileno = $row['mobileno'];
				$aobj[] = $obj;
			}
		}
	}
	$resp->data = $aobj;
	echo json_encode($resp);
?>