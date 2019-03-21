<?php

	include 'config.php';
	include 'checkuser.php';

	$game = str_replace("\\","",$_GET['gamedetails']);
	$gameInfo = json_decode($game);
	
	$competition = $gameInfo->idcompetition;
	$matchno = $gameInfo->idmatch;
	$team1 = $gameInfo->team1->idteam;
	$team2 = $gameInfo->team2->idteam;
	$gamecount = count($gameInfo->eventofficials);
	$finalize = $gameInfo->finalize;
	$status = 0;

	
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $accesscode = '';
    if ($finalize==1){
		for ($i = 0; $i < 6; $i++) {
			$accesscode = $accesscode . $characters[rand(0, strlen($characters))];
		}
		$status=1;
	}

	
	for($i=0;$i<$gamecount;$i++){
		$officialref = $gameInfo->eventofficials[$i]->officialref;
		$ref = $gameInfo->eventofficials[$i]->ref;
		$asstref = $gameInfo->eventofficials[$i]->asstref;
		$line1ref = $gameInfo->eventofficials[$i]->lineref1;
		$line2ref = $gameInfo->eventofficials[$i]->lineref2;
		$courtref = $gameInfo->eventofficials[$i]->courtref;
		$gameno = $gameInfo->eventofficials[$i]->gameno;
		mysqli_query($con,"update event set status=".$status.",accesscode='".$accesscode."',officialreferee=" .$officialref. ", referee=" .$ref. ",asstreferee=" .$asstref. ",linereferee1 = " .$line1ref. ", linereferee2 = " .$line2ref. ", courtreferee = " .$courtref. ", finalize = " .$finalize."  where idcompetition = ".$competition." and idmatch = '" .$matchno. "' and gameno=".$gameno."");
	}

	mysqli_query($con,"update eventgamescore set score=0,injury=0,timeout=0,duration=0,winner=0  where idcompetition = ".$competition." and idevent = '" .$matchno. "'");
	mysqli_query($con,"delete from eventgameactivity where idcompetition = ".$competition." and idevent = '" .$matchno. "'");
		
	foreach($gameInfo->team1->players as $key => $pl){
		mysqli_query($con,"update eventplayers set status=" .$pl->status. ", gameno=" .$pl->gameno. ", startgameno=" .$pl->gameno. " where idcompetition = " .$competition. " and idevent = '" .$matchno. "' and idteam=" .$team1. " and jerseyno=" .$pl->jersey. "");
	}
	foreach($gameInfo->team2->players as $key => $pl){
		mysqli_query($con,"update eventplayers set status=" .$pl->status. ", gameno=" .$pl->gameno. ", startgameno=" .$pl->gameno. " where idcompetition = " .$competition. " and idevent = '" .$matchno. "' and idteam=" .$team2. " and jerseyno=" .$pl->jersey. "");
	}
	

	$resp->message="Change(s) successful.";
	echo json_encode($resp);	
?>
