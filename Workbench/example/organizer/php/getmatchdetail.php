<?php
	include 'config.php';
	include 'checkuser.php';

	class cPlayerStatus{
		public $id="";
		public $description="";
	}

	class cReferees{
		public $id="";
		public $name="";
		public $noc="";
	}
	class cTeamPlayer{
		public $jersey="";
		public $name="";
		public $position="";
		public $birthdate="";
		public $height="";
		public $weight="";
		public $status="";
		public $isplaying=0;
		public $card=0;
		public $gameno=0;
	}
	class cTeam{
		public $idteam="";
		public $name="TEAM";
		public $noc="NOC";
		public $manager="";
		public $coach="";
		public $asstcoach="";
		public $country="";
		public $players="";
	}
	class cEventOfficials{
		public $officialref="";
		public $ref="";
		public $asstref="";
		public $courtref="";
		public $lineref1="";
		public $lineref2="";
		public $gameno=0;
	}
	class cEvent{
		public $idcompetition="";
		public $idmatch="";
		public $courtno=0;
		public $date="1980-00-00";
		public $day="";
		public $time="";
		public $matchdesc="";
		public $venue="";
		public $finalize=0;
		public $leftlogo;
		public $rightlogo;
		public $sponsor1logo;
		public $sponsor2logo;
		public $team1;
		public $team2;
		public $eventofficials;
		public $referees;
		public $playerstatus;
		public $event="";
		public $gamecount=0;
		public $phase;
		public $gender;
	}

	$competition = $_GET['competition'];
	$match = $_GET['gameno'];
	$order = $_GET['order'];

	$event = new cEvent();
	$teama= new cTeam();
	$teamb= new cTeam();
	$event->team1 = $teama;
	$event->team2 = $teamb;

	$result = mysqli_query($con,"SELECT * FROM competition where idcompetition=" .$competition. " and isactive=1" );
	if($row = mysqli_fetch_assoc($result)) {
		$event->venue= $row['venue'];
		$event->leftlogo = $row['leftlogo'];
		$event->rightlogo = $row['rightlogo'];
		$event->sponsor1logo = $row['sponsor1logo'];
		$event->sponsor2logo = $row['sponsor2logo'];
		$countrycode = $row['countrycode'];

		$result = mysqli_query($con,"SELECT a.*,b.description as event,b.gamecount FROM event a, gametype b where a.idcompetition=" .$competition. " and a.idmatch='" .$match."' and a.isactive=1 and a.idgametype=b.idgametype order by a.gameno" );
		$aEventOfficials = array();
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)){
				$event->idcompetition = $row['idcompetition'];
				$event->idmatch = $row['idmatch'];
				$event->courtno = $row['courtno'];
				$event->date = date("d/m/Y", strtotime($row['date']));
				$event->day = date('l', strtotime($row['date']));
				$event->time = $row['time'];
				$event->matchdesc = $row['matchdesc'];
				$event->finalize = $row['finalize'];
				$event->event = $row['event'];
				$event->gamecount = $row['gamecount'];
				$event->phase = $row['phase'];
				$event->gender = $row['category'];
				$teama->idteam = $row['team1'];
				$teamb->idteam = $row['team2'];

				$eventOfficial = new cEventOfficials();
				$eventOfficial->officialref = $row['officialreferee'];
				$eventOfficial->ref = $row['referee'];
				$eventOfficial->asstref = $row['asstreferee'];
				$eventOfficial->courtref = $row['courtreferee'];
				$eventOfficial->lineref1 = $row['linereferee1'];
				$eventOfficial->lineref2 = $row['linereferee2'];
				$eventOfficial->gameno = $row['gameno'];
				$aEventOfficials[] = $eventOfficial;
			}
		}
		$event->eventofficials = $aEventOfficials;

		//$result = mysqli_query($con,"select a.name,a.noc,a.manager,a.coach,a.asstcoach,b.name as country from competitionteam a, country b where a.noc = b.code and a.idteam=" .$teama->idteam." and a.isactive=1" );
		$result = mysqli_query($con,"select a.name,a.noc,a.manager,a.coach,a.asstcoach from competitionteam a where a.idcompetition=" .$competition. " and a.idteam=" .$teama->idteam." and a.isactive=1" );
		if($row = mysqli_fetch_assoc($result)) {
			$teama->name = $row['name'];
			$teama->noc = $row['noc'];
			$teama->manager = $row['manager']==NULL?"":$row['manager'];
			$teama->coach = $row['coach']==NULL?"":$row['coach'];
			$teama->asstcoach = $row['asstcoach']==NULL?"":$row['asstcoach'];
			//$teama->country = $row['country'];
		}

		$result = mysqli_query($con,"select name from country where code='" .$countrycode."'" );
		if($row = mysqli_fetch_assoc($result)) {
			$teama->country = $row['name'];
			$teamb->country = $row['name'];
		}

		$result = mysqli_query($con,"select name from country where code='" .$teama->noc."'" );
		if($row = mysqli_fetch_assoc($result)) {
			$teama->country = $row['name'];
		}

		//$result = mysqli_query($con,"select a.name,a.noc,a.manager,a.coach,a.asstcoach,b.name as country from competitionteam a, country b where a.noc = b.code and a.idteam=" .$teama->idteam." and a.isactive=1" );
		$result = mysqli_query($con,"select a.name,a.noc,a.manager,a.coach,a.asstcoach from competitionteam a where a.idcompetition=" .$competition. " and a.idteam=" .$teamb->idteam." and a.isactive=1" );
		if($row = mysqli_fetch_assoc($result)) {
			$teamb->name = $row['name'];
			$teamb->noc = $row['noc'];
			$teamb->manager = $row['manager']==NULL?"":$row['manager'];
			$teamb->coach = $row['coach']==NULL?"":$row['coach'];
			$teamb->asstcoach = $row['asstcoach']==NULL?"":$row['asstcoach'];
			//$teamb->country = $row['country'];
		}
		$result = mysqli_query($con,"select name from country where code='" .$teamb->noc."'" );
		if($row = mysqli_fetch_assoc($result)) {
			$teamb->country = $row['name'];
		}

		$cat=0;
		if($event->gender=='MEN'){
			$cat=1;
		}elseif($event->gender=='WOMEN'){
			$cat=2;
		}
		$aTeamA = array();
		if ($order==0){
			$result = mysqli_query($con,"select a.idteam, a.jerseyno, b.name, a.position,a.startgameno, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b where a.idteam = b.idteam and a.jerseyno = b.jerseyno and  a.idcompetition=b.idcompetition and a.idcategory=b.category and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=b.category and a.idcategory=".$cat." and a.idteam=".$teama->idteam." and a.isactive=1 and b.isactive=1 order by a.jerseyno" );
		}else{
			$result = mysqli_query($con,"select DISTINCT a.idteam, a.jerseyno, b.name, a.position,a.startgameno, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.idcompetition=b.idcompetition and a.idcategory=b.category and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=b.category and a.idcategory=".$cat." and a.idteam=".$teama->idteam." and a.isactive=b.isactive and b.isactive=1 order by a.startgameno,a.status,a.jerseyno" );
		}
		while ($row = mysqli_fetch_assoc($result)){
			$player = new cTeamPlayer();
			$player->jersey = $row['jerseyno'];
			$player->name = $row['name'];
			$player->position = $row['position'];
			$player->birthdate = $row['birthdate'];
			$player->height = $row['height'];
			$player->weight = $row['weight'];
			$player->status = $row['status'];
			$player->isplaying = $row['isplaying'];
			$player->card = $row['card'];
			$player->gameno = $row['startgameno'];
			$aTeamA[] = $player;
		}
		$teama->players = $aTeamA;

		$aTeamB = array();
		if ($order==0){
			$result = mysqli_query($con,"select a.idteam, a.jerseyno, b.name, a.position,a.startgameno, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.idcompetition=b.idcompetition and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=b.category and a.idcategory=".$cat." and a.idteam=".$teamb->idteam." and a.isactive=1 and b.isactive=1 order by a.jerseyno" );
		}else{
			$result = mysqli_query($con,"select DISTINCT a.idteam, a.jerseyno, b.name, a.position,a.startgameno, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.idcompetition=b.idcompetition and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=b.category and a.idcategory=".$cat." and a.idteam=".$teamb->idteam." and a.isactive=b.isactive and b.isactive=1 order by a.startgameno,a.status,a.jerseyno" );
		}
		while ($row = mysqli_fetch_assoc($result)){
			$player = new cTeamPlayer();
			$player->jersey = $row['jerseyno'];
			$player->name = $row['name'];
			$player->position = $row['position'];
			$player->birthdate = $row['birthdate'];
			$player->height = $row['height'];
			$player->weight = $row['weight'];
			$player->status = $row['status'];
			$player->isplaying = $row['isplaying'];
			$player->card = $row['card'];
			$player->gameno = $row['startgameno'];
			$aTeamB[] = $player;
		}
		$teamb->players = $aTeamB;

		$aReferee = array();
		$result = mysqli_query($con,"SELECT idofficial,name,noc FROM officials where idcompetition=".$competition." AND idofficialtype='TECHNICAL' and isactive=1 ORDER BY name");
		while ($row = mysqli_fetch_assoc($result)){
			$ref = new cReferees();
			$ref->id = $row['idofficial'];
			$ref->name = $row['name'];
			$ref->noc = $row['noc'];
			$aReferee[] = $ref;
		}
		$event->referees = $aReferee;

		$aPStatus = array();
		$result = mysqli_query($con,"SELECT idplayerstatus,description FROM playerstatus");
		while ($row = mysqli_fetch_assoc($result)){
			$ps = new cPlayerStatus();
			$ps->id = $row['idplayerstatus'];
			$ps->description = $row['description'];
			$aPStatus[] = $ps;
		}
		$event->playerstatus = $aPStatus;
	}
	$resp->data=$event;
	echo json_encode($resp);

?>
