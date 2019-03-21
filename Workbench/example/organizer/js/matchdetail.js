var game;
function getNoc(val,s){
	var sNoc = '', ref;
	if (val != '')
		for(var i in game.referees){
			ref = game.referees[i]
			if (ref.id==val){
				sNoc=ref.noc;
				break;
			}
		}

	document.getElementById(s).value = sNoc;
}

function printGame(show){
	setParam("printOfficials",show)
	printGameDetails();
}
function printGameDetails(){
	var url = "startlist.html";
	window.open(url,"popup","resizeable=yes,scrollbars=yes,width=650,height=1080",false);
}

function isEmpty(str) {
	return (!str || 0 === str.length);
}
function getText(id){
	var el = document.getElementById(id);
	var x = el.innerText;
	if (isEmpty(x))
		x = el.innerHTML;
	return x;
}
function isReserveOK(eventType, resCount){
	var aEvent= new Array("REGU", "DOUBLE", "QUAD","TEAM REGU","TEAM DOUBLE","TEAM QUAD","BEACH REGU", "BEACH TRIO", "BEACH TEAM QUAD");
	//var aMaxReserve= new Array(2, 1, 2, 3,3,3,2, 2, 3);
	// to do
	// check the max reserved of each event
	var aMaxReserve= new Array(2, 1, 2, 3,5,3,2, 2, 3);


	var i =0;
	for (i in aEvent){
		if(aEvent[i]==eventType){
			break;
		}
	}
	if (resCount>aMaxReserve[i]){
		return false;
	}else {
		return true;
	}
}

function saveChanges(game,finalize){

	if (finalize==1){
		var resp = confirm("Finalize changes?\nOK - No further changes can be made. \nCancel - Modification still allowed in the future.");
		if (!resp)
			return;
	}

	// verify the number of reserved players prior to saving
	var resCnt=0;
	for (var i in game.team1.players){
		ps = game.team1.players[i];
		s=document.getElementById( game.team1.idteam + "_" + ps.jersey ).value;
		if ((isEmpty(s)?"-1":s)=="3"){
			resCnt++;
		}
	}
	if (!isReserveOK(game.event,resCnt)){
		alert(game.team1.name + "'s RESERVE player(s) more than the allowable limit");
		return;
	}
	resCnt=0;
	for (var i in game.team2.players){
		ps = game.team2.players[i];
		s=document.getElementById( game.team2.idteam + "_" + ps.jersey ).value;
		if ((isEmpty(s)?"-1":s)=="3"){
			resCnt++;
		}
	}
	if (!isReserveOK(game.event,resCnt)){
		alert(game.team2.name + "'s RESERVE player(s) more than the allowable limit");
		return;
	}

	game.finalize=finalize;
	for (var i in game.team1.players){
		ps = game.team1.players[i];
		delete ps.birthdate;
		delete ps.card;
		delete ps.height;
		delete ps.name,
		delete ps.position;
		delete ps.weight;
		s=document.getElementById( game.team1.idteam + "_" + ps.jersey ).value;
		ps.status = (isEmpty(s)?"-1":s);
		if (game.gamecount>1){
			s=document.getElementById( game.team1.idteam + "_GAME_" + ps.jersey ).value;
			ps.gameno = s;
		}
	}
	for (var i in game.team2.players){
		ps = game.team2.players[i];
		delete ps.birthdate;
		delete ps.card;
		delete ps.height;
		delete ps.name,
		delete ps.position;
		delete ps.weight;
		s=document.getElementById( game.team2.idteam + "_" + ps.jersey ).value;
		ps.status = (isEmpty(s)?"-1":s);
		if (game.gamecount>1){
			s=document.getElementById( game.team2.idteam + "_GAME_" + ps.jersey ).value;
			ps.gameno = s;
		}
	}
	// clear unneccsary fields
	delete game.playerstatus;
	delete game.referees;
	delete game.leftlogo;
	delete game.rightlogo;
	delete game.sponsor1logo;
	delete game.sponsor2logo;
	delete game.venue;
	delete game.day;
	delete game.date;
	delete game.time;
	delete game.matchdesc;
	delete game.venue;
	delete game.courtno;
	delete game.phase;
	delete game.gender;

	s = JSON.stringify(game);

	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.body.style.cursor  = 'default';
			var res = this.responseText;
			alert(res);
			location.reload(true);
		}
	};
	xmlhttp.open("POST", "php/savestartlist.php?gamedetails=" + s, true);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/savestartlist.php?gamedetails=" + s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var tp = data.data;
		location.reload(true);
	});
	document.body.style.cursor  = 'default';
}


function getGameDetails(order){

	var competition = getParam("competition");
	var gameno = getParam("gameno");

	/*
	var xmlhttp = new ajaxRequest();
	//document.body.style.cursor  = 'wait';
	xmlhttp.open("GET", "php/getmatchdetail.php?competition="+competition+"&gameno="+gameno+"&order="+order, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getmatchdetail.php?competition="+competition+"&gameno="+gameno+"&order="+order+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data)
		game = data.data;
	});
	document.body.style.cursor  = 'default';
}

function toggleView(divView){
	var div = document.getElementById(divView);
	if(div.style.display=="block")
		div.style.display="none";
	else
		div.style.display="block"
}

function updateOfficials(game,gameIndex){
	//set noc of officials
	var dd = document.getElementById("ddOfficialReferee");
	var val = game.eventofficials[gameIndex].officialref;
	dd.value = val;
	getNoc(val,"lblNOCOfficialRef");

	dd = document.getElementById("ddReferee");
	val = game.eventofficials[gameIndex].ref;
	dd.value = val;
	getNoc(val,"lblNOCRef");

	dd = document.getElementById("ddAsstReferee");
	val = game.eventofficials[gameIndex].asstref;
	dd.value = val;
	getNoc(val,"lblNOCAsstRef");

	dd = document.getElementById("ddLineReferee1");
	val = game.eventofficials[gameIndex].lineref1;
	dd.value = val;
	getNoc(val,"lblNOCLineRef1");

	dd = document.getElementById("ddLineReferee2");
	val = game.eventofficials[gameIndex].lineref2;
	dd.value = val;
	getNoc(val,"lblNOCLineRef2");

	dd = document.getElementById("ddCourtRef");
	val = game.eventofficials[gameIndex].courtref;
	dd.value = val;
	getNoc(val,"lblCourtRef");
}

function setPlayerStatus(val,lblStatus){
	if (val==255)
		document.getElementById(lblStatus).value=3;
	else{
		if (document.getElementById(lblStatus).value==3)
			document.getElementById(lblStatus).value=2;
	}
}

function setGameStatus(val,lblStatus){
	if (val==3)
		document.getElementById(lblStatus).value=255;
	else{
		if (document.getElementById(lblStatus).value==255)
			document.getElementById(lblStatus).value=1;
	}
}
