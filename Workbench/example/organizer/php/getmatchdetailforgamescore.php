<?php
	include 'config.php';
	include 'checkuser.php';

	class cTeamPlayer{
		public $jersey="";
		public $status="";
		public $gameno=0;

	}
	class cTeam{
		public $idteam="";
		public $name="TEAM";
		public $noc="NOC";
		public $country="";
		public $playing="";
		public $reserved="";
		public $teamreserved="";
		public $logo="";
	}

	class cEvent{
		public $idcompetition="";
		public $comptitle="";
		public $idmatch="";
		public $courtno=0;
		public $date="1980-00-00";
		public $day="";
		public $time="";
		public $matchdesc="";
		public $venue="";
		public $event="";
		public $gamecount=0;
		public $gameno=0;
		public $phase="";
		public $category="";
		public $noofset=0;
		public $noofpoints=0;
		public $changeside=0;
		public $team1;
		public $team2;
		public $json="";
	}
	$competition = $_GET['competition'];
	$match = $_GET['gameno'];

	$event = new cEvent();
	$teama= new cTeam();
	$teamb= new cTeam();
	$event->team1 = $teama;
	$event->team2 = $teamb;

	$result = mysqli_query($con,"SELECT * FROM competition where idcompetition=" .$competition. " and isactive=1" );
	if($row = mysqli_fetch_assoc($result)) {
		$event->venue= $row['venue'];
		$event->comptitle= $row['title'];

		$result = mysqli_query($con,"SELECT a.*,b.description as event,b.gamecount FROM event a, gametype b where a.idcompetition=" .$competition. " and a.idmatch='" .$match."' and a.isactive=1 and a.status>=0 and a.idgametype=b.idgametype order by a.gameno LIMIT 1" );

		if(mysqli_num_rows($result)>0){
			// for team games, get only the first game not yet started
			if($row = mysqli_fetch_assoc($result)) {
				$event->idcompetition = $row['idcompetition'];
				$event->idmatch = $row['idmatch'];
				$event->courtno = $row['courtno'];
				$event->date = date("d/m/Y", strtotime($row['date']));
				$event->day = date('l', strtotime($row['date']));
				$event->time = $row['time'];
				$event->matchdesc = $row['matchdesc'];
				$event->event = $row['event'];
				$event->gamecount = $row['gamecount'];
				$event->gameno = $row['gameno'];
				$event->phase = $row['phase'];
				$event->noofset = $row['noofset'];
				$event->noofpoints = $row['noofpoints'];
				$event->changeside = $row['changeside'];
				$event->category = $row['category'];
				$teama->idteam = $row['team1'];
				$teamb->idteam = $row['team2'];
			}

			$query = "SELECT json FROM eventgameactivity WHERE idcompetition=".$event->idcompetition." AND idevent='".$event->idmatch."' AND gameno=".$event->gameno." AND isvalid=1 ORDER BY dbtrxndate DESC";
			$result = mysqli_query($con,$query);
			if(mysqli_num_rows($result)>0){
				// for team games, get only the first game not yet started
				if($row = mysqli_fetch_assoc($result)) {
					$event->json = $row['json'];
				}
			}

			// team details
			$query = "SELECT name,noc,logo FROM competitionteam WHERE idcompetition=".$event->idcompetition." AND idteam=".$teama->idteam."";
			$result = mysqli_query($con,$query);
			if(mysqli_num_rows($result)>0){
				// for team games, get only the first game not yet started
				if($row = mysqli_fetch_assoc($result)) {
					$teama->name = $row['name'];
					$teama->noc = $row['noc'];
					$teama->logo = $row['logo'];
				}
			}

			$query = "SELECT name,noc,logo FROM competitionteam WHERE idcompetition=".$event->idcompetition." AND idteam=".$teamb->idteam."";
			$result = mysqli_query($con,$query);
			if(mysqli_num_rows($result)>0){
				// for team games, get only the first game not yet started
				if($row = mysqli_fetch_assoc($result)) {
					$teamb->name = $row['name'];
					$teamb->noc = $row['noc'];
					$teamb->logo = $row['logo'];
				}
			}

			$cat=0;
			if($event->category=='MEN'){
				$cat=1;
			}elseif($event->category=='WOMEN'){
				$cat=2;
			}

			$aPlaying = array();
			$aReserved = array();
			$aTeamReserved = array();
			$result = mysqli_query($con,"select DISTINCT a.idteam, a.jerseyno, b.name, a.position, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b
			where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.position=b.position and a.idcompetition=b.idcompetition and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=".$cat." and a.gameno in(". $event->gameno.",255) and a.idteam=".$teama->idteam." and a.isactive=1 and b.isactive=1 order by a.jerseyno asc" );
			while ($row = mysqli_fetch_assoc($result)){
				$player = new cTeamPlayer();
				$player->jersey = $row['jerseyno'];
				$player->status = $row['status'];
				$player->gameno = $row['gameno'];
				if($player->gameno==$event->gameno && $player->status!=3)
					$aPlaying[] = $player;
				if($player->gameno==$event->gameno && $player->status==3)
					$aReserved[] = $player;
				if($player->gameno==255)
					$aTeamReserved[] = $player;
			}
			$teama->playing = $aPlaying;
			$teama->reserved = $aReserved;
			$teama->teamreserved = $aTeamReserved;

			$aBPlaying = array();
			$aBReserved = array();
			$aBTeamReserved = array();
			$result = mysqli_query($con,"select DISTINCT a.idteam, a.jerseyno, b.name, a.position, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b
			where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.position=b.position and a.idcompetition=b.idcompetition and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=".$cat." and a.gameno in(". $event->gameno.",255) and a.idteam=".$teamb->idteam." and a.isactive=1 and b.isactive=1 order by a.jerseyno asc" );
			while ($row = mysqli_fetch_assoc($result)){
				$player = new cTeamPlayer();
				$player->jersey = $row['jerseyno'];
				$player->status = $row['status'];
				$player->gameno = $row['gameno'];
				if($player->gameno==$event->gameno && $player->status!=3)
					$aBPlaying[] = $player;
				if($player->gameno==$event->gameno && $player->status==3)
					$aBReserved[] = $player;
				if($player->gameno==255)
					$aBTeamReserved[] = $player;
			}
			$teamb->playing = $aBPlaying;
			$teamb->reserved = $aBReserved;
			$teamb->teamreserved = $aBTeamReserved;
			}
		}
	echo json_encode($event);
?>
