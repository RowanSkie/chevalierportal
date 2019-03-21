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
		//percentages go here
		public $stats;
	}

	class cTeamScore{
		public $setno=0;
		public $score=0;
		public $injury=0;
		public $timeout=0;
		public $duration=0;
		public $sub=0;
	}
	class cTeamSetWins{
		public $gameno=0;
		public $wins=0;
	}
	class cTeam{
		public $idteam="";
		public $name="TEAM";
		public $noc="NOC";
		public $manager="";
		public $coach="";
		public $asstcoach="";
		public $asstmanager="";
		public $players="";
		public $gamescore;
		public $setwins;
		public $timeouts;
		public $injuries;
		public $overallscore;
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
		public $noofset;
		public $finalize=0;
		public $leftlogo;
		public $rightlogo;
		public $sponsor1logo;
		public $sponsor2logo;
		public $chiefreferee="";
		public $technicaldelegate="";
		public $team1;
		public $team2;
		public $eventofficials;
		public $referees;
		public $playerstatus;
		public $event="";
		public $gamecount=0;
		public $phase;
		public $gender;
		public $startTime="";
		public $endTime="";
	}

	class cTeamEvents{
		public $gameno=0;
		public $setno=0;
		public $time="";
		public $notes="";
	}
	class cTeamSub{
		public $gameno;
		public $setno;
		public $in;
		public $out;
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
		$event->leftlogo = $row['leftlogo'];
		$event->rightlogo = $row['rightlogo'];
		$event->sponsor1logo = $row['sponsor1logo'];
		$event->sponsor2logo = $row['sponsor2logo'];
		$event->chiefreferee = $row['chiefreferee']==null?"NOT ASSIGNED":$row['chiefreferee'];
		$event->technicaldelegate = $row['technicaldelegate']==null?"NOT ASSIGNED":$row['technicaldelegate'];

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
				$event->noofset = $row['noofset'];
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

		$query="select gameno,setno,score,duration,injury,timeout,sub from eventgamescore where idcompetition=".$competition." and idevent='".$match. "' and idteam=".$teama->idteam." and isactive=1 group by gameno,setno";
		$result = mysqli_query($con,$query);
		$aTeamAScore = array();
		$aTeamAStats = array();
		$i=0;
		while($row = mysqli_fetch_assoc($result)){
			$stat = new cTeamScore();
			$stat->setno = $row['setno'];
			$stat->score = $row['score'];
			$stat->duration = $row['duration'];
			$stat->injury = $row['injury'];
			$stat->timeout = $row['timeout'];
			$stat->sub = $row['sub'];
			$aTeamAStats[] = $stat;
			$i++;
			if($i==$event->noofset){
				$i=0;
				$aTeamAScore[]=$aTeamAStats;
				$aTeamAStats = array();
			}
		}
		$teama->gamescore = $aTeamAScore;

		$aTeamASetWins = array();
		$aTeamBSetWins = array();
		$overallA=0;
		$overallB=0;
		for($i=1;$i<=$event->gamecount;$i++){
			$awins = new cTeamSetWins();
			$bwins = new cTeamSetWins();
			$awins->gameno = $i;
			$bwins->gameno = $i;

			$query = "SELECT gameno,winner,COUNT(*) as wins FROM eventgamescore WHERE idcompetition=".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and winner<>0 group by winner,gameno order by gameno";
			$result = mysqli_query($con,$query);
			while($row = mysqli_fetch_assoc($result)){
				if($row['winner']==$teama->idteam){
					$awins->wins = $row['wins'];
					$overallA++;
					//echo "A:" . $overallA;
				}elseif($row['winner']==$teamb->idteam){
					$bwins->wins = $row['wins'];
					$overallB++;
					//echo "B:" . $overallB;
				}
			}

			$aTeamASetWins[] = $awins;
			$aTeamBSetWins[] = $bwins;
		}
		$teama->setwins = $aTeamASetWins;
		$teamb->setwins = $aTeamBSetWins;

		if ($event->gamecount>1){
			$overallA=0;
			$overallB=0;
			$query = "SELECT  winner FROM event WHERE idcompetition=".$event->idcompetition." and idmatch='".$event->idmatch."' and winner!=0";
			$res = mysqli_query($con,$query);
			while($row = mysqli_fetch_assoc($res)){
				if($row['winner']==$teama->idteam){
					$overallA++;
				}elseif($row['winner']==$teamb->idteam){
					$overallB++;
				}
			}
		}
		$teama->overallscore = $overallA;
		$teamb->overallscore = $overallB;


		//$result = mysqli_query($con,"select a.name,a.noc,a.manager,a.coach,a.asstcoach,b.name as country from competitionteam a, country b where a.noc = b.code and a.idteam=" .$teamb->idteam." and a.isactive=1" );
		$result = mysqli_query($con,"select a.name,a.noc,a.manager,a.coach,a.asstcoach from competitionteam a where a.idcompetition=" .$competition. " and a.idteam=" .$teamb->idteam." and a.isactive=1" );
		if($row = mysqli_fetch_assoc($result)) {
			$teamb->name = $row['name'];
			$teamb->noc = $row['noc'];
			$teamb->manager = $row['manager']==NULL?"":$row['manager'];
			$teamb->coach = $row['coach']==NULL?"":$row['coach'];
			$teamb->asstcoach = $row['asstcoach']==NULL?"":$row['asstcoach'];
			//$teamb->country = $row['country'];
		}

		$query="select gameno,setno,score,duration,injury,timeout,sub from eventgamescore where idcompetition=".$competition." and idevent='".$match. "' and idteam=".$teamb->idteam." and isactive=1 group by gameno,setno";
		$result = mysqli_query($con,$query);
		$aTeamBScore = array();
		$aTeamBStats = array();
		$i=0;
		while($row = mysqli_fetch_assoc($result)){
			$stat = new cTeamScore();
			$stat->setno = $row['setno'];
			$stat->score = $row['score'];
			$stat->duration = $row['duration'];
			$stat->injury = $row['injury'];
			$stat->timeout = $row['timeout'];
			$stat->sub = $row['sub'];
			$aTeamBStats[] = $stat;
			$i++;
			if($i==$event->noofset){
				$i=0;
				$aTeamBScore[]=$aTeamBStats;
				$aTeamBStats = array();
			}
		}
		$teamb->gamescore = $aTeamBScore;

		$cat=0;
		if($event->gender=='MEN'){
			$cat=1;
		}elseif($event->gender=='WOMEN'){
			$cat=2;
		}

		$aTeamA = array();
		$result = mysqli_query($con,"select DISTINCT a.idteam, a.jerseyno, b.name, a.position, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b
	where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.position=b.position and a.idcompetition=b.idcompetition and a.idcategory=b.category and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=".$cat." and a.idteam=".$teama->idteam." and a.isactive=1 and b.isactive=1 order by a.gameno,a.status asc,a.jerseyno asc" );
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
			$player->gameno = $row['gameno'];
			$aTeamA[] = $player;
		}
		$teama->players = $aTeamA;

		$aTeamB = array();
		$result = mysqli_query($con,"select DISTINCT a.idteam, a.jerseyno, b.name, a.position, a.gameno,b.birthdate,b.height,b.weight,a.status,a.isplaying,a.card from eventplayers a, competitionteamparticipant b
	where a.idteam = b.idteam and a.jerseyno = b.jerseyno and a.position=b.position and a.idcompetition=b.idcompetition and a.idcategory=b.category and a.idcompetition=" .$competition. " and a.idevent ='" .$match. "' and a.idcategory=".$cat." and a.idteam=".$teamb->idteam." and a.isactive=1 and b.isactive=1 order by a.gameno,a.status asc,a.jerseyno asc" );
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
			$player->gameno = $row['gameno'];
			$aTeamB[] = $player;
		}
		$teamb->players = $aTeamB;

		// timeouts
		$aTeamATimeout = array();
		$aTeamBTimeout = array();
		for($i=1;$i<=$event->gamecount;$i++){
			$aTOAGame = array();
			$aTOBGame = array();
			for($j=1;$j<=$event->noofset;$j++){
				$aTO = new cTeamEvents();
				$bTO = new cTeamEvents();
				$query = "SELECT json,gametime FROM eventgameactivity WHERE idcompetition =".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and setno=".$j." and activity='TIMEOUT' ORDER BY gametime";
				$result = mysqli_query($con,$query);
				while($row = mysqli_fetch_assoc($result)){
					$aTO->gameno = $i;
					$bTO->gameno = $i;
					$packet = json_decode($row['json']);
					// left team
					if($packet->teamMadeAct==0){
						$aTO->time = $row['gametime'];
						$aTO->setno = $j;
					}elseif($packet->teamMadeAct==1){
						$bTO->time = $row['gametime'];
						$bTO->setno = $j;
					}
				}
				$aTOAGame[]= $aTO;
				$aTOBGame[]= $bTO;
			}
			$aTeamATimeout[]= $aTOAGame;
			$aTeamBTimeout[]= $aTOBGame;
		}
		$teama->timeouts = $aTeamATimeout;
		$teamb->timeouts = $aTeamBTimeout;

		//substitution
		$aTeamASub = array();
		$aTeamBSub = array();
		for($i=1;$i<=$event->gamecount;$i++){
			$aTSAGame= array();
			$aTSBGame= array();
			for($j=1;$j<=$event->noofset;$j++){
				$aTSASet = array();
				$aTSBSet = array();
				$query = "SELECT json,gametime FROM eventgameactivity WHERE idcompetition =".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and setno=".$j." and activity='SUB' ORDER BY gametime";
				$result = mysqli_query($con,$query);
				while($row = mysqli_fetch_assoc($result)){
					$packet = json_decode($row['json']);
					//object Time Out
					$oTO = new cTeamSub();
					$oTO->gameno = $i;
					$oTO->time = $row['gametime'];
					$oTO->setno = $j;
					$oTO->in = $packet->subIn;
					$oTO->out = $packet->subOut;
					if($packet->teamMadeAct==0){
						$aTSASet[] = $oTO;
					}elseif($packet->teamMadeAct==1){
						$aTSBSet[] = $oTO;
					}
				}
				$aTSAGame[] = $aTSASet;
				$aTSBGame[] = $aTSBSet;
			}
			$aTeamASub[] = $aTSAGame;
			$aTeamBSub[] = $aTSBGame;
		}
		$teama->substitution = $aTeamASub;
		$teamb->substitution = $aTeamBSub;



		// start and end time
		$aStartTime = array();
		$aEndTime = array();
		for($i=1;$i<=$event->gamecount;$i++){
			$query = "SELECT DISTINCT json,gametime FROM eventgameactivity WHERE idcompetition =".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and activity='START' ORDER BY gametime";
			$result = mysqli_query($con,$query);
			if($row = mysqli_fetch_assoc($result)) {
				$packet = json_decode($row['json']);
				$aStartTime[] = date("h:i:s A",strtotime($packet->gameStartDateTime));
				//$aStartTime[] = ($packet->gameStartDateTime);

			}else{
				$aStartTime[] = " ";
			}
			$query = "SELECT DISTINCT json,gametime FROM eventgameactivity WHERE idcompetition =".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and activity='END' ORDER BY gametime";
			$result = mysqli_query($con,$query);
			if($row = mysqli_fetch_assoc($result)) {
				$packet = json_decode($row['json']);
				$aEndTime[] = date("h:i:s A",strtotime($packet->SystemGameTime));
				//$aEndTime[] = ($packet->SystemGameTime);
			}else{
				$aEndTime[] = " ";
			}
		}
		// TODO: set the date here
		$event->startTime = $aStartTime;
		$event->endTime = $aEndTime;


		// injury
		$aTeamAInjury = array();
		$aTeamBInjury = array();
		for($i=1;$i<=$event->gamecount;$i++){
			$aTIAGame= array();
			$aTIBGame= array();
			for($j=1;$j<=$event->noofset;$j++){
				$query = "SELECT json,gametime FROM eventgameactivity WHERE idcompetition =".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and setno=".$j." and activity='INJURY END' ORDER BY gametime";
				$result = mysqli_query($con,$query);
				$aoTE=new cTeamEvents();
				$boTE=new cTeamEvents();
				while($row = mysqli_fetch_assoc($result)){
					//$emptyTE =  new cTeamEvents();
					$aoTE->gameno = $i;
					$aoTE->setno = $j;
					$aoTE->time = $row['gametime'];
					//$emptyTE =  new cTeamEvents();
					$boTE->gameno = $i;
					$boTE->setno = $j;
					$boTE->time = $row['gametime'];
					$packet = json_decode($row['json']);
					$data = explode("|",$packet->injDetails);
					$data[4]=str_replace(" M ","m",$data[4]);
					$data[4]=str_replace("  S ","",$data[4])."s";
					// left team
					//echo $data[0].'and'.$teama->noc;
					if(trim($data[0])==trim($teama->noc)){
						$aoTE->notes = ($aoTE->notes!=""?$aoTE->notes."<br><br>":"").'No. '.$data[1].' ('.$data[2].','.$data[4].','.trim($data[3]).')';
						//$aTIBGame[] = $emptyTE;
					}elseif(trim($data[0])==trim($teamb->noc)){
						//$aTIAGame[] = $emptyTE;
						$boTE->notes = ($boTE->notes!=""?$boTE->notes."<br><br>":"").'No. '.$data[1].' ('.$data[2].','.$data[4].','.trim($data[3]).')';
					}
				}
				$aTIAGame[] = $aoTE;
				$aTIBGame[] = $boTE;
			}
			$aTeamAInjury[] = $aTIAGame;
			$aTeamBInjury[] = $aTIBGame;
		}
		$teama->injuries = $aTeamAInjury;
		$teamb->injuries = $aTeamBInjury;

		// card offence
		$aTeamAOffence = array();
		$aTeamBOffence = array();
		for($i=1;$i<=$event->gamecount;$i++){
			$aCOAGame= array();
			$aCOBGame= array();
			for($j=1;$j<=$event->noofset;$j++){
				$query = "SELECT json,gametime FROM eventgameactivity WHERE idcompetition =".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$i." and setno=".$j." and isvalid=1 and activity='CARD' ORDER BY gametime";
				$result = mysqli_query($con,$query);
				$aoTE=new cTeamEvents();
				$boTE=new cTeamEvents();
				while($row = mysqli_fetch_assoc($result)){
					//$emptyTE =  new cTeamEvents();
					$aoTE->gameno = $i;
					$aoTE->setno = $j;
					$aoTE->time = $row['gametime'];
					//$emptyTE =  new cTeamEvents();
					$boTE->gameno = $i;
					$boTE->setno = $j;
					$boTE->time = $row['gametime'];
					$packet = json_decode($row['json']);
					if($packet->cardDetails!=''){
						$data = explode("|",$packet->cardDetails);
						// left team
						if(trim($data[1])==trim($teama->noc)){
							$aoTE->notes = ($aoTE->notes!=""?$aoTE->notes."<br><br>":"").'No. '.$data[2].' ('.$data[0].','.$data[3].')';
						}elseif(trim($data[1])==trim($teamb->noc)){
							$boTE->notes = ($boTE->notes!=""?$boTE->notes."<br><br>":"").'No. '.$data[2].' ('.$data[0].','.$data[3].')';
						}
					}
				}
				$aCOAGame[] = $aoTE;
				$aCOBGame[] = $boTE;
			}
			$aTeamAOffence[] = $aCOAGame;
			$aTeamBOffence[] = $aCOBGame;
		}
		$teama->cardoffence = $aTeamAOffence;
		$teamb->cardoffence = $aTeamBOffence;


		$aReferee = array();
		$result = mysqli_query($con,"SELECT idofficial,name,noc FROM officials where idcompetition=".$competition." and isactive=1");
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
