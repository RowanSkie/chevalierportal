<?php
	include 'config.php';
	include 'checkuser.php';

	class cEvent{
		public $idmatch;
		public $courtno;
		public $date;
		public $time;
		public $team1;
		public $team2;
		public $gamecount;
		public $score;
		public $matchscore;
		public $setscore='';
		public $duration;
		public $phase;
		public $event;
	}
	class cTeam{
		public $idteam;
		public $name;
		public $noc;
		public $wins=0;
		public $loss=0;
		public $played=0;
		public $points=0;
		public $scorewin=0;
		public $scoreloss=0;
		public $scorediff=0;
		public $setwon=0;
		public $setloss=0;
		public $setdiff=0;
	}

	class cTeamSetWins{
		public $gameno=0;
		public $wins=0;
	}

	class cCompetition{
		public $venue;
		public $leftlogo;
		public $rightlogo;
		public $sponsor1logo;
		public $sponsor2logo;
		public $gender;
		public $groups;
	}

	class cGroups{
		public $title;
		public $name;
		public $matches;
		public $teams;
	}

	$competition = $_GET['competition'];
	$eventtype = $_GET['event'];
	$category = $_GET['category'];

	$comp = new cCompetition();
	$result = mysqli_query($con,"SELECT * FROM competition where idcompetition=" .$competition. " and isactive=1" );
	if($row = mysqli_fetch_assoc($result)) {
		$comp->venue= $row['venue'];
		$comp->leftlogo = $row['leftlogo'];
		$comp->rightlogo = $row['rightlogo'];
		$comp->sponsor1logo = $row['sponsor1logo'];
		$comp->sponsor2logo = $row['sponsor2logo'];
		$comp->gender = $category;
		$comp->phase = 'PRELIMINARY';
		$aGroup = array();

		$query="SELECT description FROM gametype where idgametype=".$eventtype;
		$main = mysqli_query($con,$query);
		if(mysqli_num_rows($main)>0){
			while($row=mysqli_fetch_assoc($main)){
				$comp->event = $row['description'];
			}
		}


		$query="SELECT DISTINCT pgroup from event where idcompetition=".$competition." and idgametype=".$eventtype." and category='".$category."'";
		$main = mysqli_query($con,$query);
		if(mysqli_num_rows($main)>0){
			while($row=mysqli_fetch_assoc($main)){
				$pgroup=$row['pgroup'];
				$group = new cGroups();
				$group->title=$pgroup;
				$group->name=str_replace(" ","",$pgroup);


				$query = "SELECT idteam,name,noc,matchplayed,matchwon,matchlost,matchpoints,setwon,setlost,setdiff,pointswon,pointslost,pointsdiff FROM sportsev_takrawdb.competitionsummary a  WHERE a.idcompetition=".$competition." and a.idgametype=".$eventtype." and a.category='".$category."' and a.pgroup='".$pgroup."'";
				$result = mysqli_query($con,$query);
				$aTeam = array();
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_assoc($result)){
						$obj = new cTeam();
						$obj->idteam = $row['idteam'];
						$obj->name = $row['name'];
						$obj->noc = $row['noc'];
						$obj->wins=$row['matchwon'];
						$obj->loss=$row['matchlost'];
						$obj->played = $row['matchplayed'];
						$obj->points = $row['matchpoints'];
						$obj->scorewin=$row['pointswon'];
						$obj->scoreloss=$row['pointslost'];
						$obj->scorediff=$row['pointsdiff'];
						$obj->setwon=$row['setwon'];
						$obj->setloss=$row['setlost'];
						$obj->setdiff=$row['setdiff'];

						$aTeam[] = $obj;
					}
				}

				$query = "SELECT DISTINCT a.idmatch,a.date,a.time,a.team1,a.team2,b.gamecount FROM event a,gametype b where a.idcompetition=" .$competition. " and a.phase='PRELIMINARY' and a.category='".$category."' and  a.idgametype=b.idgametype and a.pgroup='".$pgroup."' and a.idgametype=".$eventtype." order by a.date,a.time,a.idmatch";
				$result = mysqli_query($con,$query);

				$aMatches = array();
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_assoc($result)){
						$obj = new cEvent();
						$obj->idmatch = $row['idmatch'];
						$obj->gamecount = $row['gamecount'];
						$obj->date = date("d/m/Y", strtotime($row['date']));
						$obj->time = $row['time'];
						$obj->team1 = $row['team1'];
						$obj->team2 = $row['team2'];
						//$winner = $row['winner'];

						// compose the result of the match
						// compose the score of the match
						$res = mysqli_query($con,"select a.gameno,a.idteam,a.setno,a.score as 'team1score',b.gameno,b.idteam,b.setno,b.score as 'team2score' from eventgamescore a, eventgamescore b where a.idcompetition=b.idcompetition and a.idcompetition=".$competition." and a.idevent=b.idevent and a.gameno=b.gameno and a.setno=b.setno and a.idevent='".$obj->idmatch."' and a.idteam=".$obj->team1." and b.idteam=".$obj->team2." group by a.gameno,a.setno");

						$gameno=0;
						if(mysqli_num_rows($res)>0){
							while($row=mysqli_fetch_assoc($res)){
								if ($obj->setscore!='' && $gameno==$row['gameno'])
									$obj->setscore = $obj->setscore.',';
								if ($gameno!=$row['gameno']){
									if($gameno!=0){
										$obj->setscore = $obj->setscore."\r\n";
									}
									$gameno=$row['gameno'];
								}
								$obj->setscore = $obj->setscore .$row['team1score'].':'.$row['team2score'];
							}
						}

						$query ="select CAST(sum(duration)/2 AS UNSIGNED) as duration from eventgamescore where idcompetition=".$competition." and idevent = '".$obj->idmatch."'";

						$res=mysqli_query($con,$query);
						if($row = mysqli_fetch_assoc($res)) {
							$obj->duration = $row['duration'];
						}

						$team1score=0;
						$team2score=0;
						for($i=1;$i<=$obj->gamecount;$i++){
							$team1win=0;
							$team2win=0;
							$query = "SELECT gameno,winner,COUNT(*) as wins FROM eventgamescore WHERE idcompetition=".$competition." and idevent='".$obj->idmatch."' and gameno=".$i." and winner<>0 group by winner,gameno order by gameno";
							$res = mysqli_query($con,$query);
							while($row = mysqli_fetch_assoc($res)){
								if($row['winner']==$obj->team1){
									$team1win = $row['wins'];
								}elseif($row['winner']==$obj->team2){
									$team2win = $row['wins'];
								}
							}
							if ($obj->matchscore!='')
								$obj->matchscore = $obj->matchscore."\r\n";
							$obj->matchscore = $obj->matchscore .$team1win.'-'.$team2win;
							if ($team1win>$team2win)
								$team1score = $team1score + 1;
							elseif($team1win<$team2win)
								$team2score = $team2score + 1;
						}
						$obj->score = $team1score.'-'.$team2score;
						$aMatches[] = $obj;
					}
				}

				$group->matches = $aMatches;
				$group->teams = $aTeam;
				$aGroup[] = $group;
			}
		}
		$comp->groups = $aGroup;

	}


	$resp->data=$comp;
	echo json_encode($resp);
?>
