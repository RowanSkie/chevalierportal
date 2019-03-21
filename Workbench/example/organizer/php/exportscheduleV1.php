<?php
	ob_start();
	include 'config.php';
	ob_get_clean();
	include 'exporttocsv.php';
	
	$idcompetition = $_GET['competition'];
	$header = array("Match No","Court No","Date","Time","Team A ID","Team A email","Team B ID","Team B email","Phase","Category","Group","Change Side","Match Description","Event Type","No of Set","No of Points","PrevMatchTA","PrevMatchTB");

	
	$result = mysqli_query($con,"SELECT DISTINCT a.idmatch,a.courtno,a.date,a.time,a.team1,a.team2,a.phase,a.category,a.pgroup,a.changeside,a.accesscode,a.matchdesc,b.idgametype,b.description as event,a.noofset,a.noofpoints,a.finalize,b.gamecount, a.prevMatchT1, a.prevMatchT2 FROM event a, gametype b,competitionteam  where a.idcompetition=" .$idcompetition. " and a.isactive=1 and a.idgametype=b.idgametype order by a.date,a.time,a.idmatch");

	$arec = array();
	$arec[] = $header;
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$aobj = array();
			$aobj[] = $row['idmatch'];
			$aobj[] = $row['courtno'];
			$aobj[] = $row['date'];
			$aobj[] = $row['time'];
			$res = mysqli_query($con,"SELECT email FROM competitionteam where idcompetition=" .$idcompetition." and idteam=".$row['team1']."");
			$r=mysqli_fetch_assoc($res);			
			$aobj[] = $row['team1'];
			$aobj[] = $r['email'];
			$res = mysqli_query($con,"SELECT email FROM competitionteam where idcompetition=" .$idcompetition." and idteam=".$row['team2']."");
			$r=mysqli_fetch_assoc($res);
			$aobj[] = $row['team2'];
			$aobj[] = $r['email'];
			$aobj[] = $row['phase'];
			$aobj[] = $row['category'];
			$aobj[] = $row['pgroup'];
			$aobj[] = $row['changeside'];
			$aobj[] = $row['matchdesc'];
			$aobj[] = $row['event'];
			$aobj[] = $row['noofset'];
			$aobj[] = $row['noofpoints'];
			$aobj[] = $row['prevMatchT1'];
			$aobj[] = $row['prevMatchT2'];
			$arec[]= $aobj;
		}
	}else{
		$aobj = array();			
		$arec[] = $aobj;
	}
	
	$title="";
	$result = mysqli_query($con,"SELECT title FROM competition where idcompetition=" .$idcompetition."");
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$title = $row['title']." ";
		}
	}
	array_to_csv_download($arec, $filename = $title."schedule.csv", $delimiter=",");
?>