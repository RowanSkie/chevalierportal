<?php
	include 'config.php';
	include 'checkuser.php';
	$idorganizer = $_GET['subscription'];

	class Competion{
		public $ID;
		public $Title;
		public $Subtitle;
		public $StartDate;
		public $EndDate;
		public $State;
		public $Country;
		public $NOC;
		public $Venue;
		public $Participants;
		public $LeftLogo;
		public $RightLogo;
		public $Sponsor1Logo;
		public $Sponsor2Logo;
		public $Type;
		public $Groups;
		public $TeamPerGroup;
		public $GameType;
		public $Sanctioned;
	}

	class GameType{
		public $idGameType;
		public $category;
		public $maxgroup;
		public $maxteam;
	}


	$result = mysqli_query($con,"SELECT a.idcompetition,a.title,a.startdate,a.enddate,a.state,a.countrycode,b.name,a.venue,a.participants,a.leftlogo,a.rightlogo,a.sponsor1logo,a.sponsor2logo,a.type,a.sanctioned,a.groups,a.noofteamingroup FROM competition a, country b where a.idsubscription='". $idorganizer."' and a.isactive=1 and a.countrycode=b.code order by a.startdate desc" );

	$aCompetition = array();
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$competition = new Competion();
			$competition->ID = $row['idcompetition'];
			$competition->Title = $row['title'];
			$competition->StartDate = date("d/m/Y", strtotime($row['startdate']));
			$competition->EndDate = date("d/m/Y", strtotime($row['enddate']));
			$competition->State = $row['state'];
			$competition->NOC = $row['countrycode'];
			$competition->Country = $row['name'];
			$competition->Venue = $row['venue'];
			$competition->Participants = $row['participants'];
			$competition->LeftLogo = $row['leftlogo'];
			$competition->RightLogo = $row['rightlogo'];
			$competition->Sponsor1Logo = $row['sponsor1logo'];
			$competition->Sponsor2Logo = $row['sponsor2logo'];
			$competition->Type = $row['type'];
			$competition->Groups = $row['groups'];
			$competition->TeamPerGroup = $row['noofteamingroup'];
			$competition->Sanctioned = $row['sanctioned']==1?true:false;

			$res = mysqli_query($con,"SELECT idgametype,category,maxgroup,maxteam FROM competitiongametype where idcompetition=".$competition->ID."");
			$aGameType = array();
			while($r=mysqli_fetch_assoc($res)){
				$gt = new GameType();
				$gt->idGameType = $r['idgametype'];
				$gt->category = $r['category'];
				$gt->maxgroup = $r['maxgroup'];
				$gt->maxteam = $r['maxteam'];
				$aGameType[] = $gt;
			}
			$competition->GameType = $aGameType;
			$aCompetition[] = $competition;
		}
	}
	$resp->data = $aCompetition;
	echo json_encode($resp);
?>
