var teamPlayers,teamParticipation,playerPos;
var idManageParticipant,idManageParticipantCategory;
var strGender=new Array("UNKNOWN","MALE","FEMALE");
var strCat=new Array("","MEN","WOMEN");
var header = ["Jersey","Name","Position","DateOfBirth","Height","Weight","Passport","NRIC","E-mail","MobileNo","Gender"];
var aTeamParticipants = [];
function retrieveTeamManagement(){

	var a = getParam("email");
	var b = getParam("competition");
	var teamManagement;
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/getteammanagement.php?user="+a, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getteammanagement.php?user="+a+"&competition="+b+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		teamManagement = data.data;
		setParam("idteam",teamManagement.idteam);
	});
	document.body.style.cursor  = 'default';
	return teamManagement;
}
function addPlayer(){

	var a = getParam("email");
	var usr = getParam("user");
	var c = getParam("competition");

	f = document.forms["player"];
	if (f.position.value<5){
		if (f.jersey.value==""){
			alert("Jersey no. mandatory for players.");
			return false;
		}
	}


	if (idManageParticipant!=f.jersey.value){
		for(i in teamPlayers){
			if (teamPlayers[i].jersey==f.jersey.value && teamPlayers[i].category==f.ddGender.value){
				alert("Jersey no for same gender already added.");
				return false;
			}
		}
	}
	var p = new Object();
	p.oldjersey = idManageParticipant;
	p.oldcategory = idManageParticipantCategory;
	p.competition = c;
	p.email = a;
	p.jersey = f.jersey.value;
	p.name = f.name.value;
	p.category = f.ddGender.value
	p.birthdate = f.birthdate.value;
	p.position = f.position.value;
	//locate the text of the position using the pp object
	/*
	for (var i in pp){
		if (pp[i].ID == f.position.value){
			p.position = pp[i].Description;
			break;
		}
	}

	p.height = f.height.value;
	p.weight = f.weight.value;
	p.passport = f.passport.value;
	p.nric = f.nric.value;
	p.mobileno = encodeURIComponent(f.mobileno.value);
	p.playeremail = f.email.value;*/
	s = JSON.stringify(p);
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.body.style.cursor  = 'default';
                var res = this.responseText;
				if (res==" "){
					//reload window instead of recreating
					//location.reload(true);
					window.location.href("team.html#tab1");
				}else{
					alert(res);
				}
            }
        };
	xmlhttp.open("POST", "php/addplayer.php?player="+s, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/addplayer.php?player="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		if (data.errorno==0){
			//reload window instead of recreating
			//location.reload(true);
			retrieveTeamPlayers();
			getTeamParticipation();
			clearManageParticipant();
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}


function removeParticipant(){

	jersey = idManageParticipant;
	if (jersey=="")
		return;
	var a = getParam("email");
	if (a=="Not found"){
		window.location.href="index.php"
		return false;
	}
	var usr = getParam("user");
	if (usr=="Not found"){
		window.location.href="index.php"
		return false;
	}

	var c = getParam("competition");
	if (c=="Not found"){
		window.location.href="index.php"
		return false;
	}
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.body.style.cursor  = 'default';
                var res = this.responseText;
				if (res==" "){
					//reload window instead of recreating
					//location.reload(true);
					location.replace("team.html#tab1");
					window.location.href("team.html#tab1");
				}else{
					alert(res);
				}
            }
        };
	xmlhttp.open("POST", "php/removeplayer.php?user="+a+"&jersey="+jersey+"&competition="+c, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/removeplayer.php?user="+a+"&jersey="+jersey+"&competition="+c+"&category="+document.getElementById("ddGender").value+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		if (data.errorno==0){
			//reload window instead of recreating
			//location.reload(true);
			retrieveTeamPlayers();
			getTeamParticipation();
			clearManageParticipant();
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

function retrieveCompetition(){

	var a = getParam("subscription");
	if (a=="Not found"){
		window.location.href="index.php"
		return;
	}
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.body.style.cursor  = 'default';
                var res = JSON.parse(this.responseText);
				var sel = document.getElementById("ddCompetition");
				for(i=0;i<res.length;i++){
					sel.options[sel.options.length] = new Option(res[i].Title, res[i].ID);
				}
            }
        };
	xmlhttp.open("POST", "php/getcompetition.php?subscription="+a, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetition.php?subscription="+a+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		var sel = document.getElementById("ddCompetition");
		for(i=0;i<res.length;i++){
			sel.options[sel.options.length] = new Option(res[i].Title, res[i].ID);
		}
	});
	document.body.style.cursor  = 'default';
}

function getCompetition(competition){

	var a = getParam("subscription");
	var competitionInfo;
	//document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
    		document.body.style.cursor  = 'default';
			competitionInfo = JSON.parse(this.responseText);
			for (i in competitionInfo){
				if(competitionInfo[i].ID==competition){
					document.getElementById("lblCompetitionTitle").innerHTML=competitionInfo[i].Title + " - " + competitionInfo[i].Type + " Tournament";
				}
			}
		}
	};
	xmlhttp.open("POST", "php/getcompetition.php?subscription="+a, true);
	xmlhttp.send();
	*/
	$.ajax({
		cache:false,
		url: "php/getcompetition.php?subscription="+a+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		//document.body.style.cursor  = 'default';
		checkResponse(data);
		competitionInfo = data.data;
		for (i in competitionInfo){
			if(competitionInfo[i].ID==competition){
				document.getElementById("lblCompetitionTitle").innerHTML=competitionInfo[i].Title + " - " + competitionInfo[i].Type + " TOURNAMENT";
			}
		}
	});
	//document.body.style.cursor  = 'default';
}

function createPlayerTable(table,idx,category){

	var a = getParam("email");
	var c = getParam("competition");

	if (idx=="" || category==""){
		var tbl = document.getElementById("tblPlayers");
		var rowCount = tbl.rows.length;
		// clear the table contents
		for (var i = 1; i < rowCount; i++) {
			tbl.deleteRow(1);
		}
		return;
	}
	if(teamPlayers==undefined){
		$.ajax({
			cache:false,
			url: "php/getteamplayers.php?user="+a+"&competition="+c+getUserParam(),
			async:false,
			dataType: "json"
		}).done(function (data) {
			document.body.style.cursor  = 'default';
			checkResponse(data);
			teamPlayers = data.data;
		});
	}

	if(teamParticipation==undefined){
		$.ajax({
			cache:false,
			url: "php/getteamparticipation.php?user="+a+"&competition="+c+getUserParam(),
			async:false,
			dataType: "json"
		}).done(function (data) {
			document.body.style.cursor  = 'default';
			checkResponse(data);
			teamParticipation = data.data;
		});
	}
	// clear the table contents
	var rowCount = table.rows.length;
	for (var i = 1; i < rowCount; i++) {
		table.deleteRow(1);
	}

	//put the players on the select obj
	var tp,offset=-1;
	for(k in teamParticipation){
		tp = teamParticipation[k];

		//start of the offset
		if (tp.eventid==gameTypes[idx].id && tp.category==strCat[category]){
			offset=k;
			break;
		}
		else
			offset=-1;
	}



	var maxRow = gameTypes[idx].maxplayers;
	for (i=0;i<maxRow;i++){
		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);

		// object for player positition
		var selPos = document.createElement("select");
		selPos.style.width="100px";
		selPos.style.height="20px";
		selPos.style.fontSize="16px";
		var p;
		for(j in playerPos){
			if (playerPos[j].Description != 'TEAM MANAGER' && playerPos[j].Description != 'COACH' && playerPos[j].Description != 'ASST MANAGER' && playerPos[j].Description != 'ASST COACH')
				selPos.options.add( new Option(playerPos[j].Description ,playerPos[j].Description, true, true) );
		}
		select = document.createElement("select");
		select.style.width="400px";
		select.style.height="20px";
		select.style.fontSize="16px";
		select.options.add( new Option("Choose player " + (i+1) ,"", true, true) );
		var p;
		for(j in teamPlayers){
			p = teamPlayers[j];
			if (p.jersey==null)
				break;
			if (parseInt(p.jersey)>0 && p.position != 'TEAM MANAGER' && p.position != 'COACH' && p.position != 'ASST MANAGER' && p.position != 'ASST COACH' && p.category==category){
				//select.options.add( new Option(p.jersey +" - "+p.name + " (" + p.position +")" ,p.jersey, true, true) );
				select.options.add( new Option(p.jersey + " - " +p.name ,p.jersey, true, true) );
			}
		}
		if (offset!=-1){
			for (j in select.options){
				if(teamParticipation[parseInt(offset)+parseInt(i)]==undefined){
					select.selectedIndex = "";
					selPos.selectedIndex = ""
					break;
				}
				if (select.options[j].value==teamParticipation[parseInt(offset)+parseInt(i)].jersey && teamParticipation[parseInt(offset)+parseInt(i)].eventid==gameTypes[idx].id){
					select.selectedIndex = j;
					selPos.value = teamParticipation[parseInt(offset)+parseInt(i)].position;
					break
				}else{
					select.selectedIndex = "";
					selPos.selectedIndex = ""
				}
			}
		}else
			select.selectedIndex = "";

		var newcell = row.insertCell(0);
		newcell.innerHTML = "Player " + (i+1) + ":";
		newcell.width="66px";
		var newcell = row.insertCell(1);
		newcell.width="250px";
		newcell.appendChild(select);
		var newcell = row.insertCell(2);
		newcell.width="100px";
		newcell.appendChild(selPos);
	}


}
function retrieveTeamPlayers(){

	var a = getParam("email");
	var c = getParam("competition");
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/getteamplayers.php?user="+a+"&competition="+c, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getteamplayers.php?user="+a+"&competition="+c+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		teamPlayers = data.data;
		var table = document.getElementById("tblParticipants");
		var rowCount = table.rows.length;
		for (var i = 1; i < rowCount; i++) {
			table.deleteRow(1);
		}
		// array for exporting
		aTeamParticipants = [];
		aTeamParticipants.push(header);
		aParticipant = [];
		for(var i in teamPlayers){
			tp = teamPlayers[i];
			if (tp.jersey=="")
				break;
			var row = table.insertRow(table.rows.length);

			var newcell = row.insertCell(0);
			//newcell.innerHTML = "<input type=\"submit\" name=\""+ tp.jersey + "\" value=\"Remove\" onclick=\"removePlayer('"+tp.jersey+"')\">";
			newcell.innerHTML = "<a class=\"btnManage\"name=\""+ tp.jersey + "_" + tp.category +"\" href=\"#\" onclick=\"manageParticipant("+ tp.jersey + "," + tp.category + ")\">Edit</a>";
			newcell.width="80px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(1);
			//newcell.innerHTML = "<a href=\"team.html\" onclick=\"loadTeam('"+competitionTeams[i].email+"')\">"  +  competitionTeams[i].name + "</a>";
			aParticipant.push(tp.jersey<=0 ? "" : tp.jersey);
			newcell.innerHTML = tp.jersey<=0 ? "" : tp.jersey;
			newcell.width="70px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(2);
			aParticipant.push(tp.name);
			newcell.innerHTML = tp.name;
			newcell.width="200px";
			newcell.style.textAlign = "left";

			var newcell = row.insertCell(3);
			newcell.innerHTML = strGender[tp.category];
			newcell.width="100px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(4);
			aParticipant.push(tp.position);
			newcell.innerHTML = tp.position;
			newcell.width="170px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(5);
			aParticipant.push(tp.birthdate);
			newcell.innerHTML = tp.birthdate;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			/*
			var newcell = row.insertCell(6);
			aParticipant.push(tp.height);
			newcell.innerHTML = tp.height;
			newcell.width="50px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(7);
			aParticipant.push(tp.weight);
			newcell.innerHTML = tp.weight;
			newcell.width="50px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(8);
			aParticipant.push(tp.passport);
			newcell.innerHTML = tp.passport;
			newcell.width="100px";
			newcell.style.textAlign = "left";

			var newcell = row.insertCell(9);
			aParticipant.push(tp.nric);
			newcell.innerHTML = tp.nric;
			newcell.width="100px";
			newcell.style.textAlign = "left";

			var newcell = row.insertCell(10);
			aParticipant.push(tp.email);
			newcell.innerHTML = tp.email;
			newcell.width="200px";
			newcell.style.textAlign = "left";

			var newcell = row.insertCell(11);
			aParticipant.push(tp.mobileno);
			newcell.innerHTML = "&nbsp"+tp.mobileno;
			newcell.width="100px";
			newcell.style.textAlign = "left";
			*/

			// gender is at the last part of the csv file
			aParticipant.push(strGender[tp.category]);
			aTeamParticipants.push(aParticipant);
		}
	});
	document.body.style.cursor  = 'default';
}

function getPlayerPosition(){

	/*
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "php/getplayerposition.php", false);
        xmlhttp.send();
		return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getplayerposition.php?"+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		playerPos = data.data;
	});
	document.body.style.cursor  = 'default';
	return playerPos;
}

function retrieveTeamCompetition(){

	var a = getParam("email");
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/getteamcompetition.php?user="+a, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	competition = JSON.parse(xmlhttp.responseText);
	*/
	var competition;
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getteamcompetition.php?user="+a+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		competition = data.data;
	});
	document.body.style.cursor  = 'default';
	return competition;
}

function getTeamParticipation(){

	var a = getParam("email");
	var c = getParam("competition");
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/getteamparticipation.php?user="+a+"&competition="+c, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getteamparticipation.php?user="+a+"&competition="+c+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		teamParticipation = data.data;
		var table = document.getElementById("tblEventPlayers");
		var rowCount = table.rows.length;
		for (var i = 1; i < rowCount; i++) {
			table.deleteRow(1);
		}
		for(var i in teamParticipation){
			tp = teamParticipation[i];
			if (tp.jersey=="")
				break;
			var row = table.insertRow(table.rows.length);
			var newcell = row.insertCell(0);
			newcell.innerHTML = tp.event;
			newcell.width="100px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(1);
			newcell.innerHTML = tp.category;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(2);
			newcell.innerHTML = tp.jersey;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(3);
			newcell.innerHTML = tp.name;
			newcell.width="200px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(4);
			newcell.innerHTML = tp.position;
			newcell.width="100px";
			newcell.style.textAlign = "left";		}


	});
	document.body.style.cursor  = 'default';
}
function retrieveGameType(idx){

	var a = getParam("user");
	if (idx==""){
		var tbl = document.getElementById("tblPlayers");
		var rowCount = tbl.rows.length;
		// clear the table contents
		for (var i = 1; i < rowCount; i++) {
			tbl.deleteRow(1);
		}
		removeOptions(document.getElementById("ddGameType"));
		return;
	}

	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/getcompetitiongametypes.php?competition="+idx, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	gameTypes = JSON.parse(xmlhttp.responseText);
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetitiongametypes.php?competition="+idx+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		gameTypes = data.data;
		var sel = document.getElementById("ddGameType");
		removeOptions(sel);
		sel.options[sel.options.length] = new Option("Select an event", "");
		for(i=0;i<gameTypes.length;i++){
			if (gameTypes[i].description==null)
				break;
			sel.options[sel.options.length] = new Option(gameTypes[i].description, i);
		}
	});
	document.body.style.cursor  = 'default';

	/*
	var tbl = document.getElementById("tblPlayers");
	var rowCount = tbl.rows.length;
	// clear the table contents
	for (var i = 1; i < rowCount; i++) {
		tbl.deleteRow(1);
	}
	*/

}

function joinCompetition(competition,gametype,category){

	var a = getParam("email");
	var b = getParam("idteam");

	var playerCount=0;
	var maxPlayer = gameTypes[gametype].maxplayers;
	var minPlayer = gameTypes[gametype].minplayers;
	var aPlayers=[];
	var tbl = document.getElementById("tblPlayers");
	var rowCount = tbl.rows.length;
	for (var i=0;i<rowCount;i++){
		var row = tbl.rows[i];
		if(row.cells[1] != undefined){
			var sb = row.cells[1].childNodes[0];
			var pos = row.cells[2].childNodes[0];
			for(j=0;j<sb.length;j++){
				if (sb[j].selected==true){
					var p;
					for(k in teamPlayers){
						p=teamPlayers[k];
						if(sb[j].value==p.jersey){
							//check first if the player has been added already.
							// very deep loop
							for(l in aPlayers){
								if (aPlayers[l].jersey == p.jersey){
									alert("Duplicate players selected.");
									return;
								}
							}
							p.position = pos.value;
							aPlayers.push(p);
							break;
						}
					}
					break;
				}
			}
		}
	}
	if(aPlayers.length < minPlayer){
		alert("Players below minimum required!");
		return;
	}


	var obj = new Object();
	obj.competition = competition;
	obj.gametype = gameTypes[gametype].id;
	obj.category = category;
	obj.email=a;
	obj.idteam=b;
	obj.players = aPlayers;
	s = JSON.stringify(obj);

	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/joincompetition.php?team="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		document.body.style.cursor  = 'default';
		if (data.errorno==0){
			getTeamParticipation();
			var tbl = document.getElementById("tblPlayers");
			var rowCount = tbl.rows.length;
			// clear the table contents
			for (var i = 1; i < rowCount; i++) {
				tbl.deleteRow(1);
			}
			document.getElementById("ddGameType").value="";
			document.getElementById("ddCategory").value="";
//			alert("You have successfully joined the event");
		}
	});
	document.body.style.cursor  = 'default';
}

function removeOptions(selectbox)
{
    var i;
    for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
    {
        selectbox.remove(i);
    }
}


function manageParticipant(jersey,category){
	document.getElementById("tblManageParticipant").style.display="";
	document.getElementById("tblAddParticipant").style.display="none";
	document.getElementById("tblUploadParticipant").style.display="none";
	var f = document.forms["player"];
	f.reset();
	for(var i in teamPlayers){
		teamPlayer = teamPlayers[i];
		if(teamPlayer.jersey==jersey && teamPlayer.category==category){
			idManageParticipant = jersey;
			idManageParticipantCategory = category;
			f.jersey.value = teamPlayer.jersey<=0 ? "" : teamPlayer.jersey;
			f.name.value = teamPlayer.name;
			for(j in document.getElementById("position").options){
				if (document.getElementById("position").options[j].text==teamPlayer.position){
					f.position.value = document.getElementById("position").options[j].value;
					break;
				}
			}
			f.birthdate.value = teamPlayer.birthdate.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$2-$1");
			f.ddGender.value = teamPlayer.category;
			//f.height.value = teamPlayer.height;
			//f.weight.value = teamPlayer.weight;
			//f.email.value = teamPlayer.email;
			//f.passport.value = teamPlayer.passport;
			//f.nric.value = teamPlayer.nric;
			//var str = teamPlayer.mobileno;
			//f.mobileno.value = str.trim();
			break;
		}
	}
}


function clearManageParticipant(){
	document.getElementById("tblManageParticipant").style.display="none";
	document.getElementById("tblAddParticipant").style.display="";
	document.getElementById("tblUploadParticipant").style.display="";
	f=document.forms['player'];
	f.reset();
	getBirthDate();
	idManageParticipant="";
	idManageParticipantCategory="";
}

function getBirthDate(){
	var date = new Date();
	document.getElementById("birthdate").value = formatDate(date);
}

function exportTeam(){
	var a = getParam("email");
	var c = getParam("competition");

	var ifrm = document.getElementById("hiddenFrame");
    ifrm.src = "php/exportteam.php?user="+a+"&competition="+c;
}

function formatDate(date) {
    var d = new Date("01/01/1980"),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}
