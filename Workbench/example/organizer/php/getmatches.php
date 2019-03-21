<?php
	include 'config.php';
	include 'checkuser.php';

	class cobj{
		public $idmatch;
		public $courtno;
		public $date;
		public $time;
		public $team1;
		public $team2;
		public $phase;
		public $category;
		public $event;
		public $gametype;
		public $noofset;
		public $noofpoints;
		public $changeside;
		public $status='SCHEDULED';
		public $idstatus=0;
		public $finalize=0;
		public $accesscode;
		public $matchdesc;
		public $group;
		public $score='';
		public $match;
		public $prevMatchT1;
		public $prevMatchT2;
	}
	class cteam{
		public $idteam;
		public $name;
		public $noc;
		public $email;
	}
	class cmatches{
		public $teams;
		public $matches;
		public $matchcount=0;
	}

	$competition = $_GET['competition'];
	$gametype = $_GET['gametype'];

	$result = mysqli_query($con,"SELECT DISTINCT idteam, name, noc,email FROM competitionteam WHERE idcompetition=".$competition." and isactive=1");
	$aTeam = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cteam();
		$obj->idteam = $row['idteam'];
		$obj->name = $row['name'];
		$obj->noc = $row['noc'];
		$obj->email = $row['email'];
		$aTeam[] = $obj;
	}

	if ($gametype=="")
		$result = mysqli_query($con,"SELECT DISTINCT a.idmatch,a.courtno,a.date,a.time,a.team1,a.team2,a.phase,a.category,a.pgroup,a.changeside,a.accesscode,a.matchdesc,b.idgametype,b.description as event,a.noofset,a.noofpoints,a.finalize,b.gamecount, a.prevMatchT1, a.prevMatchT2 FROM event a, gametype b where a.idcompetition=" .$competition. " and a.isactive=1 and a.idgametype=b.idgametype order by a.date,a.time,a.courtno");
	else
		$result = mysqli_query($con,"SELECT DISTINCT a.idmatch,a.courtno,a.date,a.time,a.team1,a.team2,a.phase,a.category,a.pgroup,a.changeside,a.accesscode,a.matchdesc,b.idgametype,b.description as event,a.noofset,a.noofpoints,a.finalize,b.gamecount, a.prevMatchT1, a.prevMatchT2 FROM event a, gametype b where a.idcompetition=" .$competition. " and a.idgametype=".$gametype ." and a.isactive=1 and a.idgametype=b.idgametype order by a.date,a.time,a.courtno");

	$aMatches = array();
	while($row=mysqli_fetch_assoc($result)){
		$obj = new cobj();
		$gamecount=$row['gamecount'];
		$obj->idmatch = $row['idmatch'];
		$obj->courtno = $row['courtno'];
		$obj->date = date("d/m/Y", strtotime($row['date']));
		$obj->time = $row['time'];
		$obj->team1 = $row['team1'];
		$obj->team2 = $row['team2'];
		$obj->event = $row['event'];
		$obj->gametype = $row['idgametype'];
		$obj->phase = $row['phase'];
		$obj->category = $row['category'];
		$obj->noofset = $row['noofset'];
		$obj->noofpoints = $row['noofpoints'];
		$obj->matchdesc = $row['matchdesc'];
		$obj->changeside = $row['changeside'];
		$obj->finalize = $row['finalize'];
		$obj->group = $row['pgroup'];
		$obj->prevMatchT1 = $row['prevMatchT1'];
		$obj->prevMatchT2 = $row['prevMatchT2'];
		$res = mysqli_query($con,"SELECT a.status as statno,b.description as status,a.gameno FROM event a,eventstatus b WHERE a.idcompetition=".$competition." AND a.idmatch='".$obj->idmatch."' AND a.isactive=1 AND a.status=b.ideventstatus AND a.status>=0 ORDER BY gameno LIMIT 1");
		if(mysqli_num_rows($res)>0){
			if($r=mysqli_fetch_assoc($res)){
				$pos=strpos($obj->event, "TEAM ");
				if($pos===false){
					$obj->status = $r['status'];
				}else{
					if(($r['statno']==0) AND ($r['gameno']==1))
						$obj->status = $r['status'];
					else
						$obj->status = "REGU".$r['gameno']." ".$r['status'];
				}
				$obj->idstatus = $r['statno'];
			}
		}else{
			$obj->status = "ENDED";
			$obj->idstatus = -1;
		}
		$obj->accesscode = $row['accesscode'];

		for($i=1;$i<=$gamecount;$i++){
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
			if ($obj->match!='')
				$obj->match = $obj->match."\r\n";
			$obj->match = $obj->match .$team1win.'-'.$team2win;
			if($team1win > $team2win){
				$query = "update event set winner=".$obj->team1.",loser=".$obj->team2." where idcompetition=".$competition." and idmatch='".$obj->idmatch."' and gameno=".$i;
				mysqli_query($con,$query);
			}elseif($team1win < $team2win){
				$query = "update event set winner=".$obj->team2.",loser=".$obj->team1." where idcompetition=".$competition." and idmatch='".$obj->idmatch."' and gameno=".$i;
				mysqli_query($con,$query);
			}

		}

		$r = mysqli_query($con,"select a.gameno,a.idteam,a.setno,a.score as team1score,b.gameno,b.idteam,b.setno,b.score as team2score from eventgamescore a, eventgamescore b where a.idcompetition=b.idcompetition and a.idcompetition=".$competition." and a.idevent=b.idevent and a.gameno=b.gameno and a.setno=b.setno and a.idevent='".$obj->idmatch."' and a.idteam=".$obj->team1." and b.idteam=".$obj->team2." group by a.gameno,a.setno");
		if(mysqli_num_rows($r)>0){
			$gameno=0;
			while($row=mysqli_fetch_assoc($r)){
				/*if ($obj->score!='')
					$obj->score = $obj->score.',';
				$obj->score = $obj->score .$row['team1score'].':'.$row['team2score'];
				*/
				if ($obj->score!='' && $gameno==$row['gameno'])
					$obj->score = $obj->score.',';
				if ($gameno!=$row['gameno']){
					if($gameno!=0){
						$obj->score = $obj->score."\r\n";
					}
					$gameno=$row['gameno'];
				}
				$obj->score = $obj->score .$row['team1score'].':'.$row['team2score'];

			}
			//$obj->score=$obj->match.' ('.$obj->score.')';
		}

		$aMatches[] = $obj;
	}

	$aobj = new cmatches();
	$aobj->teams = $aTeam;
	$aobj->matches = $aMatches;

	$query = "select count(1) as matchcount from event where idcompetition=".$competition. " and isactive=1";
	$result = mysqli_query($con,$query );
	if($row = mysqli_fetch_assoc($result)){
		$aobj->matchcount = $row['matchcount'];
	}else
		$aobj->matchcount = 0;



	$resp->data=$aobj;
	echo json_encode($resp);
?>
