<?php
	include 'config.php';
	include 'checkuser.php';


	class cScore{
		public $score=0;
	}

	class cSet{
		public $setno;
		public $score;
	}

	class cGame{
		public $gameno;
		public $set;
	}

	class cEvent{
		public $idcompetition="";
		public $idmatch="";
		public $team1;
		public $team2;
	}

	
	$competition = $_GET['competition'];
	$match = $_GET['gameno'];
	
	$event = new cEvent();
	$result = mysqli_query($con,"SELECT * FROM competition where idcompetition=" .$competition. " and isactive=1" );
	if($row = mysqli_fetch_assoc($result)) {
		
		$res = mysqli_query($con,"SELECT a.*,b.description as event,b.gamecount FROM event a, gametype b where a.idcompetition=" .$competition. " and a.idmatch='" .$match."' and a.isactive=1 and  a.idgametype=b.idgametype order by a.gameno" );		

		if(mysqli_num_rows($res)>0){
			$gamecount = "";
			$gameno = "";
			$idteama = "";
			$idteamb = "";
			$noofset = "";
			$teama= array();
			$teamb= array();
			while ($row = mysqli_fetch_assoc($res)){
				$event->idcompetition = $row['idcompetition'];
				$event->idmatch = $row['idmatch'];
				$gamecount = $row['gamecount'];
				$gameno = $row['gameno'];
				$idteama = $row['team1'];
				$idteamb = $row['team2'];
				$noofset = $row['noofset'];
				
				$t1game = new cGame();
				$t2game = new cGame();
				$t1game->gameno = $gameno;
				$t2game->gameno = $gameno;
				for($i=1;$i<=$noofset;$i++){
					$scorea = array();
					$scoreb = array();
					$t1set = new cSet();
					$t2set = new cSet();
					$t1set->setno = $i;
					$t2set->setno = $i;
					$query = "select scoreteama,scoreteamb from eventgameactivity where idcompetition=".$event->idcompetition." and idevent='".$event->idmatch."' and gameno=".$gameno." and setno=".$i." and activity='SCORE' and isvalid=1 order by idgameactivity";
					$result = mysqli_query($con,$query );
					while ($row = mysqli_fetch_assoc($result)){
						$t1score = new cScore();
						$t2score = new cScore();
						$t1score->score = $row['scoreteama'];
						$t2score->score = $row['scoreteamb'];
						$scorea[] = $t1score;
						$scoreb[] = $t2score;
					}
					if(count($scorea)==0){
						$t1score = new cScore();
						$t2score = new cScore();
						$t1score->score = 0;
						$t2score->score = 0;						
						$scorea[] = $t1score;
						$scoreb[] = $t2score;
					}
					$t1set->score = $scorea;
					$t2set->score = $scoreb;
					$t1game->set[] = $t1set;
					$t2game->set[] = $t2set;
				}
				$teama[] = $t1game;
				$teamb[] = $t2game;
			}
						
			$event->team1 = $teama;
			$event->team2 = $teamb;
				
		}
	}
	$resp->data=$event;
	echo json_encode($resp);
?>