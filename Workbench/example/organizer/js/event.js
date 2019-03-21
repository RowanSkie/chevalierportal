var gameTypes;
var matches;
var competitionTeams;
var param;
var competitionInfo;
var manageIDMatch="";
var manageIDMatchIndex=0;
var manageIDOfficial="";
var manageIDTeam="";
var manageIDEventGroup="";
var eventGrouping;
var strGroups= new Array("Group A", "Group B", "Group C", "Group D", "Group E", "Group F", "Group G", "Group H");
var strGrpCode= new Array("A", "B", "C", "D", "E", "F", "G", "H");
var strRank= new Array("1ST", "2ND", "3RD", "4TH", "5TH");
var strCategory= new Array("", "MEN", "WOMEN");

function getNoc(val,s){
	var sNoc = '', team;
	if (val != '')
		for(var i in competitionDetails.teams){
			team = competitionDetails.teams[i]
			if (team.idteam==val){
				sNoc=team.noc;
				break;
			}
		}

	document.getElementById(s).innerHTML = sNoc;
}

function retrieveTeamPlayers(email){

	var c = getParam("competition");
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetitionparticipants.php?user="+email+"&competition="+c+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		var tp = data.data;
		var table = document.getElementById("tblParticipants");
		var rowCount = table.rows.length;
		for (var i = 0; i < rowCount; i++) {
			table.deleteRow(0);
		}
		for(var i in tp){
			var row = table.insertRow(table.rows.length);
			var newcell = row.insertCell(0);
			newcell.innerHTML = tp[i].team;
			newcell.width="200px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(1);
			newcell.innerHTML = tp[i].accreditationno;
			newcell.width="150px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(2);
			newcell.innerHTML = tp[i].name;
			newcell.width="200px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(3);
			newcell.innerHTML = tp[i].position;
			newcell.width="170px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(4);
			newcell.innerHTML = tp[i].noc;
			newcell.width="70px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(5);
			newcell.innerHTML = tp[i].email;
			newcell.width="200px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(6);
			newcell.innerHTML = tp[i].mobileno;
			newcell.width="100px";
			newcell.style.textAlign = "center";
		}
		document.body.style.cursor  = 'default';
	});
	document.body.style.cursor  = 'default';
}


function saveMatch(){

	var a = getParam("user");
	var competition = getParam("competition");
	var f = document.forms["createevent"];

	if (f.team1.value==f.team2.value){
		alert("Team 1 and team 2 cannot be the same.");
		return false;
	}
	if (f.category.value==""){
		alert("Please select a category");
		return false;
	}

	for(var i in competitionDetails.matches){
		if (i == manageIDMatchIndex)
			continue;
		match = competitionDetails.matches[i];
		if(match.idmatch.toUpperCase() == f.matchno.value.toUpperCase()){
			alert("Duplicate match no. Please select another one.");
			return false;
		}
	}

	//eventDetail object
	var ed = new Object();
	ed.oldmatchid = manageIDMatch;
	ed.competition = competition;
	ed.description = f.description.value;
	ed.gametype = document.getElementById("ddGameType").value;
	for (var i in gameTypes){
		if (gameTypes[i].id == ed.gametype){
			ed.gamecount = gameTypes[i].gamecount;
			break;
		}
	}
	ed.phase = f.phase.value;
	ed.category =  strCategory[parseInt(f.category.value)];
	if(document.getElementById("phase").value=='PRELIMINARY'){
		ed.team1 = f.team1.value;
		ed.team2 = f.team2.value;
		ed.prevMatchT1 = "";
		ed.prevMatchT2 = "";
	}else{
		rd = document.getElementsByName("rdEventType");
		for(var i = 0; i < rd.length; i++){
			if(rd[i].checked){
			eventMode = rd[i].value;
			}
		}
		if(eventMode=="PrevMatch"){
			ed.team1 = -1;
			ed.prevMatchT1 = f.team1.value;
			ed.team2 = -2;
			ed.prevMatchT2 = f.team2.value;
		}else if(eventMode=="Single"){
			ed.team1 = f.team1.value;
			ed.team2 = f.team2.value;
			ed.prevMatchT1 = "";
			ed.prevMatchT2 = "";
		}else if(eventMode=="Ladder"){
			ed.team1 = -3;
			ed.team2 = -4;
			ed.prevMatchT1 = f.team1.value;
			ed.prevMatchT2 = f.team2.value;
		}

	}
	ed.matchno = f.matchno.value;
	ed.courtno = f.courtno.value;
	ed.date = f.startdate.value;
	ed.time = f.time.value;
	ed.set = f.setno.value;
	ed.points=f.points.value;
	ed.side=f.changeside.value;
	ed.group=f.group.value;
	var s = JSON.stringify(ed);
	document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
    			document.body.style.cursor  = 'default';
                var res = JSON.parse(this.responseText);
				alert(res.message);
				if (res.errorno==0){
					retrieveMatches(competition,document.getElementById("ddViewGameType").value);
					retrieveMatch(competition);
					f.description.value="";
					document.getElementById("ddGameType").value="";
					f.phase.value="";
					f.category.value="";
					f.team1.value="";
					f.team2.value="";
					f.matchno.value="";
					f.courtno.value="";
					f.date.value=getStartDate();
					f.time.value="";
					f.setno.value=3;
					f.points.value=15;
					f.changeside.value=2;
				}
			}
        };
	xmlhttp.open("POST", "php/saveevent.php?eventdetail="+s, true);
	xmlhttp.send();
	*/
	$.ajax({
		cache:false,
		url: "php/saveevent.php?eventdetail="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		res=data;
		if (res.errorno==0){
			retrieveMatches(competition,document.getElementById("ddViewGameType").value);
			retrieveMatch(competition);
			manageIDMatch = "";
			manageIDMatchIndex=0;
			document.forms["createevent"].reset();
			document.forms["createevent"].team1.value="";
			document.forms["createevent"].team2.value="";
			$('#team1').prop("disabled", false);
			$('#team2').prop("disabled", false);
			$('#ddGameType').prop("disabled", false);
			// disable radio button
			getStartDate();
			clearManageSched();
			window.location.href="event.html#schedule";
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}


function getCompetition(competition){

	var a = getParam("subscription");
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
				// display the logo
				img = document.getElementById("leftlogo");
				if (img!=null)
					img.src = competitionInfo[i].LeftLogo+"?random="+new Date().getTime();
				img = document.getElementById("rightlogo");
				if (img!=null)
					img.src = competitionInfo[i].RightLogo+"?random="+new Date().getTime();
				img = document.getElementById("sponsor1logo");
				if (img!=null)
					if (competitionInfo[i].Sponsor1Logo!=null)
						img.src = competitionInfo[i].Sponsor1Logo+"?random="+new Date().getTime();
				img = document.getElementById("sponsor2logo");
				if (img!=null)
					if (competitionInfo[i].Sponsor2Logo!=null)
						img.src = competitionInfo[i].Sponsor2Logo+"?random="+new Date().getTime();
				//document.getElementById("startdate").value=competitionInfo[i].StartDate;
				if(competitionInfo[i].Type=="INTERNATIONAL"){
					if(document.getElementById("rowInternational")!=null)
						document.getElementById("rowInternational").style.display="block";
					toggleTeamCode(true);
				}else{
					if(document.getElementById("rowInternational")!=null)
						document.getElementById("rowInternational").style.display="none";
					toggleTeamCode(false);
				}
			}
		}
		getStartDate();
	});
	//document.body.style.cursor  = 'default';
}

function getCompetitionType(competition){
	for (i in competitionInfo){
		if(competitionInfo[i].ID==competition){
			return competitionInfo[i].Type;
		}
	}
}


function registerTeam(players){

	var competition = getParam("competition");

	var f=document.forms["addteam"];
	if (f.password.value != f.confirmpassword.value){
		alert("Password does not match.");
		return false;
	}
	// team registration object
	var tr = new Object();
	tr.idteam = manageIDTeam;
	f.idteam.value=manageIDTeam;
	f.competition.value=competition;
	tr.updatepassword = document.getElementById("updatePassword").checked;
	tr.competition = competition;
	tr.email = f.email.value;
	tr.password = f.password.value;
	tr.team = f.teamname.value;
	//tr.group = f.ddTeamGroup.value;
	//tr.idingroup = f.ddTeamIDInGroup.value;
	if(document.getElementById("rowTeamNOC").style.display=="table-row"){
		tr.noc = document.getElementById("ddNoc").value;
		tr.logo = getCode2(tr.noc).toLowerCase() + ".png";
		tr.customlogo=0;
	}else{
		tr.noc = f.teamcode.value;
		tr.customlogo=1;

		var file_data = $('#customLogo').prop('files')[0];
		if(file_data!=null){
			var form_data = new FormData();
			form_data.append('customLogo', file_data);
			form_data.append('competition', competition);
			form_data.append('idteam', manageIDTeam);
			form_data.append('noc', tr.noc);
			$.ajax({
				url: 'php/uploadlogo.php', // point to server-side PHP script
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				async: false,
				success: function(php_script_response){
					tr.logo=php_script_response.toLowerCase(); // display response from the PHP script, if any
				},
				error: function() {
          alert("There was an error. Try again please!");
        }
			});
		}else{
			tr.logo="";
		}
	}
	tr.category = document.getElementById("teamcategory").value;
	tr.players = players;
	var s=JSON.stringify(tr);

	/*
    var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res = this.responseText;
			alert(res);
			displayTeams();
			f.email.value="";
			f.password.value="";
			f.confirmpassword.value="";
			f.teamname.value="";
			if(getCompetitionType(competition)=='INTERNATIONAL')
				document.getElementById("ddNoc").value = "";
			else
				f.teamcode.value = "";
			document.getElementById("teamcategory").value="";
		}
	}

	xmlhttp.open("POST", "php/saveteam.php?teamdetails=" + s, true);
	xmlhttp.send();
	return false;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/saveteam.php?teamdetails=" + s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		if(data.errorno==0){
			displayTeams();
			clearManageTeam();
			document.forms["addteam"].reset();
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

/*
function getNocDetails(){
        var xmlhttp = new ajaxRequest();

        xmlhttp.open("GET", "php/getnoc.php", false);
        xmlhttp.send();
		return xmlhttp.responseText;
}
*/

function getCompetitionParam(){

	/*
        var xmlhttp = new ajaxRequest();

        xmlhttp.open("GET", "php/getcompetitionparameters.php", false);
        xmlhttp.send();
		return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetitionparameters.php?"+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		param = data.data;
		//populate noc dropdown
		var dropdown = $('#ddONoc');
		// Clear drop down list
		$(dropdown).empty();
		var $option = $("<option />");
		$option.attr("value", "").text("Select a NOC");
		$(dropdown).append($option);
		// Add option to drop down list
		$(dropdown).append($option);
		$(param.noc).each(function () {
			// Create option
			var $option = $("<option />");
			// Add value and text to option
			$option.attr("value", this.Code).text(this.Name);
			// Add option to drop down list
			$(dropdown).append($option);
		});

		var dropdown = $('#ddNoc');
		// Clear drop down list
		$(dropdown).empty();
		var $option = $("<option />");
		$option.attr("value", "").text("Select a NOC");
		$(dropdown).append($option);
		// Add option to drop down list
		$(dropdown).append($option);
		$(param.noc).each(function () {
			// Create option
			var $option = $("<option />");
			// Add value and text to option
			$option.attr("value", this.Code).text(this.Name);
			// Add option to drop down list
			$(dropdown).append($option);
		});
	});
	document.body.style.cursor  = 'default';
}

function retrieveCompetitionTeams(){

	var competition = getParam("competition");
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetitionteams.php?competition="+competition+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		competitionTeams=data.data;
		var dropdown = $('#ddParticipant');
		// Clear drop down list
		$(dropdown).empty();
		var $option = $("<option />");
		$option.attr("value", "").text("Select a Team");
		$(dropdown).append($option);
		var $option = $("<option />");
		$option.attr("value", "ORGANIZER").text("ORGANIZER");
		// Add option to drop down list
		$(dropdown).append($option);
		$(competitionTeams).each(function () {
			// Create option
			var $option = $("<option />");
			// Add value and text to option
			$option.attr("value", this.email).text(this.name);
			// Add option to drop down list
			$(dropdown).append($option);
		});

		var table = document.getElementById("tblTeam");
		var rowCount = table.rows.length;
		for (var i = 0; i < rowCount; i++) {
			table.deleteRow(0);
		}
		for(var i in competitionTeams){
			var row = table.insertRow(table.rows.length);
			/*var newcell = row.insertCell(0);
			newcell.innerHTML = competitionTeams[i].win;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(1);
			newcell.innerHTML = competitionTeams[i].loss;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(2);
			newcell.innerHTML = competitionTeams[i].score;
			newcell.width="50px";
			newcell.style.textAlign = "center";*/
			var newcell = row.insertCell(0);
			//newcell.innerHTML = "<input type=\"submit\" name=\"idteam_"+ competitionTeams[i].email + "\" value=\"Manage\" onclick=\"removeTeam('" +competitionTeams[i].idteam+ "')\">";
			newcell.innerHTML = "<a class=\"btnManage\"name=\idteam_"+ competitionTeams[i].email + "\" href=\"#teams\" onclick=\"manageTeam('" + competitionTeams[i].idteam +"')\">Edit</a>";

			newcell.width="70px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(1);
			newcell.innerHTML =  "<div class=\"w3-container\"><img class=\"w3-image\" src=\"" + competitionTeams[i].logo + "?random="+new Date().getTime() + "\" height=\"50\" width=\"50\"></div>";
			newcell.style.textAlign = "center";
			newcell.width="100px";
			var newcell = row.insertCell(2);
			newcell.innerHTML = "<a href=\"team.html\" onclick=\"loadTeam('"+competitionTeams[i].email+"')\">"  +  competitionTeams[i].name + "</a>";
			newcell.width="170px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(3);
			newcell.innerHTML = competitionTeams[i].noc;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(4);
			newcell.innerHTML = competitionTeams[i].category;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(5);
			newcell.innerHTML = competitionTeams[i].email;
			newcell.width="200px";
			newcell.style.textAlign = "left";

			var cellCnt = 6;
			for(var j in competitionTeams[i].players){
				var newcell = row.insertCell(cellCnt);
				newcell.innerHTML = competitionTeams[i].players[j].men > 0? competitionTeams[i].players[j].men : "-";
				newcell.width="60px";
				newcell.style.textAlign = "center";
				cellCnt++;
				var newcell = row.insertCell(cellCnt);
				newcell.innerHTML = competitionTeams[i].players[j].women > 0? competitionTeams[i].players[j].women : "-";
				newcell.width="60px";
				newcell.style.textAlign = "center";
				cellCnt++;
			}

		}

		document.body.style.cursor  = 'default';
	});
	document.body.style.cursor  = 'default';
}
function retrieveMatches(competition,gametype){

	var gt;
	if (gametype=="")
		gt="";
	else
		gt = gameTypes[gametype].id;
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getmatches.php?competition="+competition+"&gametype="+gt+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		competitionDetails = data.data;
		matches= competitionDetails.matches;
		// clear the table contents
		var table = document.getElementById("tblmatches");
		var rowCount = table.rows.length;
		for (var i = 0; i < rowCount; i++) {
			table.deleteRow(0);
		}
		var match,team;
		for(var i in competitionDetails.matches){
			match = competitionDetails.matches[i];

			var row = table.insertRow(table.rows.length);
			var newcell = row.insertCell(0);
			if (parseInt(match.finalize) ==0)
				newcell.innerHTML = "<a class=\"btnManage\"name=\""+ match.idmatch +"\" href=\"#schedule\" onclick=\"manageEvent('" + match.idmatch +"'," + i + ")\">Edit</a>";
			else
				newcell.innerHTML = "<a class=\"btnManage\"name=\""+ match.idmatch +"\" href=\"#schedule\" onclick=\"resetEvent('" + match.idmatch +"')\">Reset</a>";
			newcell.width="80px";
			newcell.style.textAlign = "center";


			var newcell = row.insertCell(1);
			newcell.innerHTML = "<a class=\"btnManage\"name=\""+ match.idmatch +"\" href=\"matchdetail.html\" onclick=\"loadGameDetail('"+match.idmatch+"')\">SL</a>  <a class=\"btnManage\"name=\""+ match.idmatch +"\" href='#' onclick=\"loadMatchReport(getParam('competition'),'"+match.idmatch+"')\">MR</a>";
			newcell.width="100px";
			newcell.style.textAlign = "center";

			var newcell = row.insertCell(2);
			newcell.innerHTML = match.date;
			newcell.width="70px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(3);
			newcell.innerHTML = match.time;
			newcell.width="70px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(4);
			newcell.innerHTML = match.idmatch;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(5);
			newcell.innerHTML = match.courtno;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var cell1 = row.insertCell(6);
			cell1.width="150px";
			cell1.style.textAlign = "center";
			var cell2 = row.insertCell(7);
			cell2.width="150px";
			cell2.style.textAlign = "center";
			for(var j in competitionDetails.teams){
				team = competitionDetails.teams[j];
				if(team.idteam==match.team1){
					cell1.innerHTML  = team.name;
				}
				if(team.idteam==match.team2){
					cell2.innerHTML = team.name;
				}
				if (match.category==strCategory[1]){
					cell1.style.color = 'green';
					cell2.style.color = 'green';
				}else if (match.category==strCategory[2]){
					cell1.style.color = 'red';
					cell2.style.color = 'red';
				}
				if(match.team1=="-1"){
					cell1.innerHTML  = "WINNER of " + match.prevMatchT1;
					cell1.style.color='black';
				}
				if(match.team2=="-2"){
					cell2.innerHTML  = "WINNER of " + match.prevMatchT2;
					cell2.style.color='black';
				}
				if(match.team1=="-3"){
					var str = match.prevMatchT1
					var tmp = str.split("|");
					cell1.innerHTML  = strRank[tmp[1]-1] +" OF " + tmp[0].toUpperCase();
					cell1.style.color='black';
				}
				if(match.team2=="-4"){
					var str = match.prevMatchT2
					var tmp = str.split("|");
					cell2.innerHTML  = strRank[tmp[1]-1] +" OF " + tmp[0].toUpperCase();
					cell2.style.color='black';
				}
			}
			var newcell = row.insertCell(8);
			newcell.innerHTML = match.event;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(9);
			newcell.innerHTML = match.category;
			newcell.width="90px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(10);
			newcell.innerHTML = match.phase;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(11);
			newcell.innerHTML = match.group;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(12);
			newcell.innerHTML = match.changeside;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(13);
			newcell.innerHTML = match.noofset;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(14);
			newcell.innerHTML = match.noofpoints;
			newcell.width="50px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(15);
			newcell.innerHTML = match.status;
			newcell.width="150px";
			newcell.style.textAlign = "center";
			if (match.idstatus==-1)
				newcell.style.color="red";
			else if (match.idstatus==2)
				newcell.style.color="green";
			else if (match.idstatus==1)
				newcell.style.color="blue";

			var newcell = row.insertCell(16);
			newcell.innerHTML = match.match;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			newcell.style.whiteSpace = "pre";
			var newcell = row.insertCell(17);
			newcell.innerHTML = match.score;
			newcell.width="200px";
			newcell.style.textAlign = "center";
			newcell.style.whiteSpace = "pre";
			/*var newcell = row.insertCell(17);
			newcell.innerHTML = match.accesscode;
			newcell.width="100px";
			newcell.style.textAlign = "center";*/
		}

		tt = document.getElementById('txtMatchNo');
		/*
		dd1 = document.getElementById('team1');
		dd2 = document.getElementById('team2');
		removeOptions(dd1);
		removeOptions(dd2);
		for (var i in competitionDetails.teams){
			team = competitionDetails.teams[i];
			dd1.options[dd1.options.length] = new Option(team.name, team.idteam);
			dd2.options[dd2.options.length] = new Option(team.name, team.idteam);
		}
		dd1.options.selectedIndex = -1;
		dd2.options.selectedIndex = -1;
		*/
		// clear the contents
		getNoc("","lblTeam1");
		getNoc("","lblTeam2");
	});
	document.body.style.cursor  = 'default';
}

function retrieveMatch(idx){

	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		matches = JSON.parse(this.responseText);
		var sel1 = document.getElementById("ddMatch1");
		sel1.options.length = 0;
		var sel2 = document.getElementById("ddMatch2");
		sel2.options.length = 0;
		for(i=0;i<matches.length;i++){
			sel1.options[sel1.options.length] = new Option(matches[i].Description, matches[i].ID);
			sel2.options[sel2.options.length] = new Option(matches[i].Description, matches[i].ID);
		}
		document.body.style.cursor  = 'default';
		}
	};
	xmlhttp.open("POST", "php/getmatch.php?competition="+idx, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getmatch.php?competition="+idx+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		matches = data.data;
		var sel1 = document.getElementById("ddMatch1");
		sel1.options.length = 0;
		//var sel2 = document.getElementById("ddMatch2");
		//sel2.options.length = 0;
		for(i=0;i<matches.length;i++){
			sel1.options[sel1.options.length] = new Option(matches[i].Description, matches[i].ID);
			//sel2.options[sel2.options.length] = new Option(matches[i].Description, matches[i].ID);
		}
		document.body.style.cursor  = 'default';
	});
	document.body.style.cursor  = 'default';
}

function retrieveGameType(idx){

	// clear the table contents
	var table = document.getElementById("tblmatches");
	var rowCount = table.rows.length;
	for (var i = 1; i < rowCount; i++) {
		table.deleteRow(1);
	}
	if (idx==""){
		return;
	}
	var a = getParam("user");

	document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.body.style.cursor= 'default';
			gameTypes = JSON.parse(this.responseText);
			var sel = document.getElementById("ddViewGameType");
			removeOptions(sel);
			if (gameTypes.length > 1)
				sel.options[sel.options.length] = new Option("All Events", "");
			for(i=0;i<gameTypes.length;i++){
				sel.options[sel.options.length] = new Option(gameTypes[i].description, i);
			}
			var sel = document.getElementById("ddGameType");
			removeOptions(sel);
			sel.options[sel.options.length] = new Option("Select an Event", "");
			for(i=0;i<gameTypes.length;i++){
				sel.options[sel.options.length] = new Option(gameTypes[i].description, i);
			}
		}
	};
	xmlhttp.open("POST", "php/getcompetitiongametypes.php?competition="+idx, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetitiongametypes.php?competition="+idx+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		gameTypes = data.data;
		var sel = document.getElementById("ddViewGameType");
		removeOptions(sel);
		if (gameTypes.length > 1)
			sel.options[sel.options.length] = new Option("All Events", "");
		for(i=0;i<gameTypes.length;i++){
			sel.options[sel.options.length] = new Option(gameTypes[i].description, i);
		}
		var sel = document.getElementById("ddGameType");
		removeOptions(sel);
		sel.options[sel.options.length] = new Option("Select an Event", "");
		for(i=0;i<gameTypes.length;i++){
			sel.options[sel.options.length] = new Option(gameTypes[i].description, gameTypes[i].id);
		}
		var sel = document.getElementById("ddCSGameType");
		removeOptions(sel);
		sel.options[sel.options.length] = new Option("Select an Event", "");
		for(i=0;i<gameTypes.length;i++){
			sel.options[sel.options.length] = new Option(gameTypes[i].description, gameTypes[i].id);
		}
		var sel = document.getElementById("ddViewEGGameType");
		removeOptions(sel);
		sel.options[sel.options.length] = new Option("Select an Event", "");
		for(i=0;i<gameTypes.length;i++){
			sel.options[sel.options.length] = new Option(gameTypes[i].description, gameTypes[i].id);
		}
		var sel = document.getElementById("ddEGGameType");
		removeOptions(sel);
		sel.options[sel.options.length] = new Option("Select an Event", "");
		for(i=0;i<gameTypes.length;i++){
			sel.options[sel.options.length] = new Option(gameTypes[i].description, gameTypes[i].id);
		}
	});
	document.body.style.cursor  = 'default';
}

function getGameType(idx){

	if (idx==""){
		return;
	}
	var a = getParam("user");

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

function addOfficial(){

	var competition = getParam("competition");

	f = document.forms["officials"];
	var official = new Object();
	official.idofficial = manageIDOfficial;
	official.name = f.officialname.value;
	official.type = f.ddType.value;
	official.role = f.ddOR.value;
	official.noc = f.ddONoc.value;
	official.birthdate = f.birthdate.value;
	official.email = f.email.value;
	official.passport = f.passport.value;
	official.nric = f.nric.value;
	official.mobileno = encodeURIComponent(f.mobileno.value.trim());
	official.competition = competition;
	var s = JSON.stringify(official);
	document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
        xmlhttp.onreadystatechange = function() {
			document.body.style.cursor= 'default';
			var res = this.responseText;
			if (res.trim()!=""){
				if (res.trim()=="OK"){
					getOfficials();
					f.officialname.value="";
					f.ddType.value = "";
					f.ddOR.value="";
					f.ddONoc.value="";
					f.birthdate.value="";
					f.email.value="";
					f.passport.value="";
					f.mobileno.value="";
				}else{
					alert(res);
				}
			}
        };
	xmlhttp.open("POST", "php/addofficial.php?official="+s, true);
	xmlhttp.send();
	return true;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/addofficial.php?official="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		if (data.errorno==0){
			getOfficials();
			f.reset();
			clearManageOfficial();
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

function getOfficials(){

	var competition = getParam("competition");
	if (competition=="Not found"){
		window.location.href="index.php"
		return;
	}
	document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
        xmlhttp.onreadystatechange = function() {
			document.body.style.cursor= 'default';
			// clear the table contents
			var table = document.getElementById("tblOfficials");
			var rowCount = table.rows.length;
			for (var i = 0; i < rowCount; i++) {
				table.deleteRow(0);
			}
			if (this.responseText!=""){
				officials = JSON.parse(this.responseText);
				for(var i in officials){
					var row = table.insertRow(table.rows.length);
					var newcell = row.insertCell(0);
					newcell.innerHTML = officials[i].type;
					newcell.width="170px";
					newcell.style.textAlign = "left";
					var newcell = row.insertCell(1);
					newcell.innerHTML = officials[i].role;
					newcell.width="170px";
					newcell.style.textAlign = "left";
					var newcell = row.insertCell(2);
					newcell.innerHTML = officials[i].name;
					newcell.width="200px";
					newcell.style.textAlign = "left";
					var newcell = row.insertCell(3);
					newcell.innerHTML = officials[i].noc;
					newcell.width="70px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(4);
					newcell.innerHTML = "<input type=\"submit\" name=\""+ officials[i].role +"_"+ officials[i].name +"\" value=\"Remove\" onclick=\"removeOfficial('" + officials[i].idrole +"','" + officials[i].name + "','" + officials[i].noc + "')\">";
					newcell.width="70px";
					newcell.style.textAlign = "center";
				}
			}
        };
	xmlhttp.open("POST", "php/getofficials.php?competition="+competition, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getofficials.php?competition="+competition+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		// clear the table contents
		var table = document.getElementById("tblOfficials");
		var rowCount = table.rows.length;
		for (var i = 0; i < rowCount; i++) {
			table.deleteRow(0);
		}
		officials = data.data;
		for(var i in officials){
			var row = table.insertRow(table.rows.length);
			var newcell = row.insertCell(0);
			//newcell.innerHTML = "<input type=\"submit\" name=\""+ officials[i].role +"_"+ officials[i].name +"\" value=\"Remove\" onclick=\"removeOfficial('" + officials[i].idrole +"','" + officials[i].name + "','" + officials[i].noc + "')\">";
			newcell.innerHTML = "<a class=\"btnManage\"name=\""+ officials[i].role +"_"+ officials[i].name +"\" href=\"#officials\" onclick=\"manageOfficial("+ officials[i].id + ")\">Edit</a>";
			newcell.width="70px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(1);
			newcell.innerHTML = officials[i].type;
			newcell.width="150px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(2);
			newcell.innerHTML = officials[i].role;
			newcell.width="170px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(3);
			newcell.innerHTML = officials[i].name;
			newcell.width="200px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(4);
			newcell.innerHTML = officials[i].noc;
			newcell.width="70px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(5);
			newcell.innerHTML = officials[i].passport;
			newcell.width="100px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(6);
			newcell.innerHTML = officials[i].nric;
			newcell.width="100px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(7);
			newcell.innerHTML = officials[i].birthdate;
			newcell.width="100px";
			newcell.style.textAlign = "center";
			var newcell = row.insertCell(8);
			newcell.innerHTML = officials[i].email;
			newcell.width="200px";
			newcell.style.textAlign = "left";
			var newcell = row.insertCell(9);
			newcell.innerHTML = "&nbsp"+officials[i].mobileno;;
			newcell.width="100px";
			newcell.style.textAlign = "left";
		}
	});
	document.body.style.cursor  = 'default';
}

function removeOfficial(){

	var competition = getParam("competition");
	var official = new Object();
	official.competition = competition;
	official.idofficial = manageIDOfficial;
	var s = JSON.stringify(official);
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/removeofficial.php?official="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		if(data.errorno==0){
			getOfficials();
			clearManageOfficial();
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

function retrieveCategory(name,val){
	var cat = document.getElementById(name);
	removeOptions(cat);
	// also populate the group based on event
	var dropdown = $('#group');
	// Clear drop down list
	$(dropdown).empty();
	//populate the Grouping in Teams tab
	var $option = $("<option />");
	$option.attr("value", "").text("Select a Group ID");
	$(dropdown).append($option);
	// Add option to drop down list
	if (val==""){
		return;
	}
	cat.options[cat.options.length] = new Option("Select a Category", "");
	for (var i in gameTypes){
		if(gameTypes[i].id==val){
			/*if(val==3){
				cat.options[cat.options.length] = new Option(strCategory[1], strCategory[1]);
				cat.options[cat.options.length] = new Option(strCategory[2], strCategory[2]);
			}else{*/
				if((gameTypes[i].category & 1)==1 ) // Mens category (id = 1)
					cat.options[cat.options.length] = new Option(strCategory[1],1);
				if((gameTypes[i].category & 2)==2 ) // Womens category (id = 2)
					cat.options[cat.options.length] = new Option(strCategory[2],2);
			//}
			if(cat.options.length==2)
				cat.selectedIndex = 1;
			break;
		}
	}

	for(var i in gameTypes){
		if(gameTypes[i].id==document.getElementById("ddGameType").value){
			$(dropdown).append($option);
			for(j = 0; j<gameTypes[i].maxgroup; j++){
				// Create option
				var $option = $("<option />");
				// Add value and text to option
				$option.attr("value", strGroups[j]).text(strGroups[j].toUpperCase());
				// Add option to drop down list
				$(dropdown).append($option);
			}
			break;
		}
	}
}

function uploadImage(){
	f = document.forms["uploadlogo"];
    var uploader = new Uploader(f);
	uploader.send();
}

function getLogo(index){
	var competition = getParam("competition");

	if (competitionInfo==undefined)
		competitionInfo=getCompetition(competition);

	for (i in competitionInfo){
		if(competitionInfo[i].ID==competition){
			if(index==1)
				return competitionInfo[i].LeftLogo;
			else if(index==2)
				return competitionInfo[i].RightLogo;
			else if(index==3)
				return competitionInfo[i].Sponsor1Logo;
			else if(index==4)
				return competitionInfo[i].Sponsor2Logo;
		}
	}
}

function getStartDate(){
	var competition = getParam("competition");

	var date = new Date();

	/*
	for (i in competitionInfo){
		if(competitionInfo[i].ID==competition){
			document.getElementById("startdate").value=competitionInfo[i].StartDate;
		}
	}
	*/
	document.getElementById("startdate").value = formatDate(date);
}

function getEndDate(){
	var competition = getParam("competition");

	for (i in competitionInfo){
		if(competitionInfo[i].ID==competition){
			return competitionInfo[i].EndDate;
		}
	}
}

function changeRole(val){
	var obj = document.getElementById("ddOR");
	removeOptions(obj);
	obj.options[obj.options.length] = new Option("Select a Role", "");
	if (val=="")return;
	if (val=="TECHNICAL"){
		for (var i in param.officialtech){
			obj.options[obj.options.length] = new Option(param.officialtech[i].Name, param.officialtech[i].Code);
		}
	}else if(val=="NON-TECHNICAL"){
		for (var i in param.officialnontech){
			obj.options[obj.options.length] = new Option(param.officialnontech[i].Name, param.officialnontech[i].Code);
		}
	}
}

function generateAccreditation(prefix){

	var competition = getParam("competition");
	if (competition=="Not found"){
		window.location.href="index.php"
		return;
	}

	if(prefix==""){
		alert("Prefix mandatory.");
		return;
	}

	var param = new Object();
	param.competition = competition;
	param.prefix = prefix;
	var s = JSON.stringify(param);

	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/generateaccreditation.php?param="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		var res = data.data;
		if (data.errorno==0){
			retrieveTeamPlayers("");
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

function generateInvAccreditation(team,index){

	var competition = getParam("competition");
	if (competition=="Not found"){
		window.location.href="index.php"
		return;
	}
	var param = new Object();
	param.competition = competition;
	param.team = team;
	param.index = index;
	var s = JSON.stringify(param);
	document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
        xmlhttp.onreadystatechange = function() {
			document.body.style.cursor= 'default';
			var res = this.responseText;
			if (res.trim()!=""){
				if (res.trim()=="OK"){
					retrieveTeamPlayers("");
				}else{
					alert(res);
					return true;
				}
			}
        };
	xmlhttp.open("POST", "php/generateindaccreditation.php?param="+s, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/generateindaccreditation.php?param="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		if (data.errorno==0){
			retrieveTeamPlayers("");
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

function displayTeams(){
	var c = getParam("competition");
	retrieveCompetitionTeams();
	retrieveMatches(getParam("competition"),"");
}

function removeEvent(){

	var competition = getParam("competition");
	if (competition=="Not found"){
		window.location.href="index.php"
		return;
	}
	var param = new Object();
	param.competition = competition;
	param.idmatch = manageIDMatch;
	var s = JSON.stringify(param);
	document.body.style.cursor  = 'wait';
	/*
	var xmlhttp = new ajaxRequest();
        xmlhttp.onreadystatechange = function() {
			document.body.style.cursor= 'default';
			var res = this.responseText;
			retrieveMatches(competition,document.getElementById("ddViewGameType").value);
			retrieveMatch(competition);
        };
	xmlhttp.open("POST", "php/removeevent.php?param="+s, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/removeevent.php?param="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		if(data.errorno==0){
			retrieveMatches(competition,document.getElementById("ddViewGameType").value);
			retrieveMatch(competition);
			clearManageSched();
			window.location.href="event.html#schedule";
		}
	});
	document.body.style.cursor  = 'default';
}

function removeTeam(){
	var competition = getParam("competition");
	if (competition=="Not found"){
		window.location.href="index.php"
		return;
	}

	var param = new Object();
	param.competition = competition;
	param.idteam = manageIDTeam;
	var s = JSON.stringify(param);
	document.body.style.cursor  = 'wait';
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/removeteam.php?param="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		displayTeams();
		retrieveMatches(competition,document.getElementById("ddViewGameType").value);
		retrieveMatch(competition);
		clearManageTeam();
		window.location.href="event.html#team"
	});
	document.body.style.cursor  = 'default';
}

function toggleTeamCode(val){
	if(val==true){
		document.getElementById("ddNoc").setAttribute("required","required");
		document.getElementById("ddNoc").required=true;
		document.getElementById("teamcode").removeAttribute("required");
		document.getElementById("teamcode").required=false;
		document.getElementById("rowTeamNOC").style.display="table-row";
		document.getElementById("rowTeamCode").style.display="none";
		document.getElementById("rowTeamCodeLogo").style.display="none";
	}else{
		document.getElementById("ddNoc").removeAttribute("required");
		document.getElementById("ddNoc").required=false;
		document.getElementById("teamcode").setAttribute("required","required");
		document.getElementById("teamcode").required=true;
		document.getElementById("rowTeamNOC").style.display="none";
		document.getElementById("rowTeamCode").style.display="table-row";
		document.getElementById("rowTeamCodeLogo").style.display="table-row";
	}
	//document.forms["addteam"].reset();
}

function manageEvent(idmatch,index){
	document.getElementById("tblManageSched").style.display="";
	document.getElementById("tblAddSched").style.display="none";
	manageIDMatch=idmatch;
	manageIDMatchIndex=index;
	var f = document.forms["createevent"];
	f.reset();
	for(var i in competitionDetails.matches){
		match = competitionDetails.matches[i];
		if(match.idmatch==idmatch){
			$('#team1').prop("disabled", false);
			$('#team2').prop("disabled", false);
			$('#tblEventType').prop("disabled", false);
			$('#group').prop("disabled", false);
			// retrieve all categories
			f.phase.value = match.phase;
			setMatchType(f.phase.value);
			if(f.phase.value != "PRELIMINARY"){
				document.getElementById("tblUpdateRanking").style.display="none";
				rd.style.display="none";
				re.style.display="none";
				if(match.team1=="-1" ||match.team2=="-2" ){
					rd = document.getElementsByName("rdEventType");
					rd[1].checked=true;
				}else if(match.team1=="-3" ||match.team2=="-4" ){
					rd = document.getElementsByName("rdEventType");
					rd[0].checked=true;
					document.getElementById("tblUpdateRanking").style.display="table";
				} else{
					// disable radio button
					rd = document.getElementsByName("rdEventType");
					for(var i = 0; i < rd.length; i++){
						rd[i].checked=false;
					}
				}
			}
			document.getElementById("ddGameType").value = parseInt(match.gametype); // zero based offset
			retrieveCategory("category",3);
			f.group.value = match.group;
			// 1=MEN,2=WOMEN
			f.category.value = (match.category==strCategory[1]?1:2);
			populateTeam(f.category.value);

			f.description.value=match.matchdesc;
			f.startdate.value = match.date.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$2-$1");
			f.time.value = match.time;
			if(match.team1=="-1"){
				f.team1.value = match.prevMatchT1;
			}
			if(match.team1=="-3"){
				f.team1.value = match.prevMatchT1;
			}
			if(match.team2=="-2"||match.team2=="-4"){
				f.team2.value = match.prevMatchT2;
			}
			if(match.team2=="-4"){
				f.team2.value = match.prevMatchT2;
			}
			//newcell.innerHTML = match.idmatch;
			for(var j in competitionDetails.teams){
				team = competitionDetails.teams[j];
				if(team.idteam==match.team1)
					f.team1.value = match.team1;
				if(team.idteam==match.team2)
					f.team2.value = match.team2;
			}
			getNoc(f.team1.value,'lblTeam1');
			getNoc(f.team2.value,'lblTeam2');
			f.matchno.value = match.idmatch;
			f.courtno.value = match.courtno;

			f.changeside.value = match.changeside;
			f.setno.value = match.noofset;
			f.points.value = match.noofpoints;
			f.group.value = match.group;
			//newcell.innerHTML = match.status;
			//newcell.innerHTML = match.accesscode;
			//newcell.innerHTML = "<a class=\"btnManage\"name=\""+ match.idmatch +"\" href=\"#\" onclick=\"manageEvent('" + match.idmatch +"')\">Manage</a>";
			$('#team1').prop("disabled", true);
			$('#team2').prop("disabled", true);
			$('#phase').prop("disabled", true);
			$('#ddGameType').prop("disabled", true);
			$('#category').prop("disabled", true);
			$('#tblEventType').prop("disabled", true);
			$('#group').prop("disabled", true);
			window.scrollTo(0,10000);
			break;
		}
	}
}

function clearManageSched(){
	document.getElementById("tblManageSched").style.display="none";
	document.getElementById("tblAddSched").style.display="";
	f=document.forms['createevent'];
	f.reset();
	f.team1.value="";
	f.team2.value="";
	$('#tblEventType').prop("disabled", false);
	$('#group').prop("disabled", false);
	$('#team1').prop("disabled", false);
	$('#team2').prop("disabled", false);
	$('#phase').prop("disabled", false);
	$('#ddGameType').prop("disabled", false);
	$('#category').prop("disabled", false);
	document.getElementById("prelimGroup").style.display="none";
	document.getElementById("eventType").style.display="none";
	manageIDMatch = "";
	manageIDMatchIndex=0;
	getStartDate();
	getNoc("",'lblTeam1');
	getNoc("",'lblTeam2');
	rd = document.getElementsByName("rdEventType");
	for(var i = 0; i < rd.length; i++){
		rd[i].checked=false;
	}
	removeOptions(document.getElementById('team1'));
	removeOptions(document.getElementById('team2'));
	document.getElementById("tblUpdateRanking").style.display="none";
}

function manageOfficial(idofficial){
	document.getElementById("tblManageOfficial").style.display="";
	document.getElementById("tblAddOfficial").style.display="none";
	document.getElementById("tblUploadOfficial").style.display="none";
	var f = document.forms["officials"];
	f.reset();
	for(var i in officials){
		official = officials[i];
		if(official.id==idofficial){
			manageIDOfficial = idofficial;
			f.officialname.value = official.name;
			f.ddType.value = official.type;
			changeRole(f.ddType.value);
			f.ddOR.value = official.idrole;
			f.ddONoc.value = official.noc;
			f.birthdate.value = official.birthdate.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$2-$1");;
			f.email.value = official.email;
			f.passport.value = official.passport;
			f.nric.value = official.nric;
			var str = official.mobileno;
			f.mobileno.value = str.trim();
			break;
		}
	}
}


function clearManageOfficial(){
	document.getElementById("tblManageOfficial").style.display="none";
	document.getElementById("tblAddOfficial").style.display="";
	document.getElementById("tblUploadOfficial").style.display="";
	f=document.forms['officials'];
	f.reset();
	manageIDOfficial="";
}

function manageTeam(idteam){
	document.getElementById("tblManageTeam").style.display="";
	document.getElementById("tblAddTeam").style.display="none";
	$('#password').prop("disabled", true);
	$('#confirmpassword').prop("disabled", true);
	document.getElementById("updatePassword").defaultChecked=false;
	var f = document.forms["addteam"];
	f.reset();
	for(var i in competitionTeams){
		team = competitionTeams[i];
		if(team.idteam==idteam){
			manageIDTeam = idteam;

			// if the noc is in the ddNoc values
			// else its using a user defined noc
			var isDD=false;
			for(var i in document.getElementById("ddNoc").options){
				if(document.getElementById("ddNoc").options[i].value==team.noc){
					isDD=true;
					break;
				}
			}
			document.getElementById("useNOC").checked=isDD;
			toggleTeamCode(isDD);
			if(isDD)
				f.ddNoc.value = team.noc;
			else
				f.teamcode.value = team.noc;
			//f.ddTeamGroup.value = team.group;
			//f.ddTeamIDInGroup.value = team.idingroup;
			f.teamcategory.value = team.idcategory;
			f.email.value = team.email;
			f.teamname.value = team.name;
			f.password.value ="****";
			f.confirmpassword.value ="****";
			window.scrollTo(0,document.body.scrollHeight);
			break;
		}
	}
}


function clearManageTeam(){
	document.getElementById("tblManageTeam").style.display="none";
	document.getElementById("tblAddTeam").style.display="";
	$('#password').prop("disabled", false);
	$('#confirmpassword').prop("disabled", false);
	f=document.forms['addteam'];
	f.reset();
	manageIDTeam="";
}

function togglePassword(value){
	$('#password').prop("disabled", !value);
	$('#confirmpassword').prop("disabled", !value);
	f=document.forms['addteam'];
	if(value){
		f.password.value ="";
		f.confirmpassword.value ="";
	}else{
		f.password.value ="****";
		f.confirmpassword.value ="****";
	}
}

function getCode2(noc){
	code2 = "";
	for(i in param.noc){
		if(param.noc[i].Code == noc){
			code2=param.noc[i].Code2;
			break;
		}
	}
	return code2;
}

function resetEvent(idmatch){
	alert("Information (score, substitutions, injuries, timeout etc.) for an on-going event will be cleared.")
	var competition = getParam("competition");
	var param = new Object();
	param.competition = competition;
	param.matchno = idmatch;
	for(var i in competitionDetails.matches){
		if(competitionDetails.matches[i].idmatch==idmatch){
			param.team1=competitionDetails.matches[i].team1;
			param.team2=competitionDetails.matches[i].team2;
			break;
		}
	}
	var s = JSON.stringify(param);
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/resetevent.php?param="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		retrieveMatches(competition,document.getElementById("ddViewGameType").value);
		retrieveCompetitionTeams();
	});
	document.body.style.cursor  = 'default';
}

function uploadSponsor(){
	competition = getParam("competition");
	var file_data = $('#imgfile').prop('files')[0];
	if(file_data!=null){
		var form_data = new FormData();
		form_data.append('imgfile', file_data);
		form_data.append('competition', competition);
		$.ajax({
			url: 'php/uploadsponsor.php', // point to server-side PHP script
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			async: false,
			success: function(data){
				getSponsorImages();
				f=document.forms['uploadsponsor'];
				f.reset();
			}
		});
	}else{
		alert("No file selected");
	}
	return false;
}

function getSponsorImages(){
	var competition = getParam("competition");
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getsponsorimages.php?competition="+competition+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		res=data;
		var table = document.getElementById("tblSponsors");
		var rowCount = table.rows.length;
		for (var i = 0; i < rowCount; i++) {
			table.deleteRow(0);
		}
		for(var i in data.data){
			var row = table.insertRow(table.rows.length);
			var newcell = row.insertCell(0);
			newcell.innerHTML = "<div class=\"w3-container\"><img class=\"w3-image\" src=\"" + data.data[i] + "?random="+new Date().getTime() + "\" height=\"200\" width=\"250\"></div>"
			newcell.style.textAlign = "center";
			newcell.width="250px";
			var newcell = row.insertCell(1);
			newcell.innerHTML = "<a class=\"btnManage\" href=\"#sponsors\" onclick=\"removeSponsor('" + data.data[i] +"')\">Remove</a>";
			newcell.style.textAlign = "center";
			newcell.width="70px";
		}

	});
	document.body.style.cursor  = 'default';
	return false;
}

function removeSponsor(file){
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/removesponsorimage.php?filename="+file+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		getSponsorImages();
		f=document.forms['uploadsponsor'];
		f.reset();
	});
	document.body.style.cursor  = 'default';
	return false;
}

function generateCS(event,category){
	competition = getParam("competition");
	category = strCategory[parseInt(category)];
	loadSummary(competition,event,category);
}

function populateTeam(category){
	if (category=="") return;
	var phase = document.getElementById("phase").value;
	var gameType = document.getElementById("ddGameType").value;
	var prevPhase = "";
	var dd1 = document.getElementById("team1");
	var dd2 = document.getElementById("team2");
	removeOptions(dd1);
	removeOptions(dd2);
	if (phase=='PRELIMINARY'){
		group = document.getElementById("group").value;
		for (var i in eventGrouping){
			team = eventGrouping[i];
			if ((team.category==category) && group==strGroups[team.group-1] && team.idgametype==gameType){
				dd1.options[dd1.options.length] = new Option("T" + team.team +  " (" + team.name  + ")", team.idteam);
				dd2.options[dd2.options.length] = new Option("T" + team.team +  " (" + team.name  + ")", team.idteam);
			}
		}
	}else{
		if (phase =='QUARTER FINAL'){
			prevPhase = 'PRELIMINARY';
		}else if (phase =='SEMI FINAL'){
			prevPhase = 'QUARTER FINAL';
		}else if (phase =='FINAL'){
			prevPhase = 'SEMI FINAL';
		}
		rd = document.getElementsByName("rdEventType");
		var eventMode='';
		for(var i = 0; i < rd.length; i++){
			if(rd[i].checked){
				eventMode = rd[i].value;
				break;
			}
		}
		if(eventMode=="PrevMatch"){
			for (var i in competitionDetails.matches){
				var match =  competitionDetails.matches[i];
				if (match.phase==prevPhase && gameType==match.gametype && match.category==strCategory[parseInt(category)]){
					dd1.options[dd1.options.length] = new Option("WINNER of " + match.idmatch, match.idmatch);
					dd2.options[dd2.options.length] = new Option("WINNER of " + match.idmatch, match.idmatch);
				}
			}
		}else if(eventMode=="Ladder"){
			for (var i in gameTypes){
				if(gameTypes[i].id==gameType){
					//populate the Grouping in Teams tab
					var d1 = $('#team1');
					var d2 = $('#team2');
					// Clear drop down list
					$(d1).empty();
					$(d2).empty();
					for(var j = 1; j<=gameTypes[i].maxgroup; j++){
						for(var k=1; k<=3; k++){
							// Create option
							var $opt1 = $("<option />");
							var $opt2 = $("<option />");
							// Add value and text to option
							$opt1.attr("value", strGroups[j-1]+"|"+k).text(strRank[k-1] + " OF " +strGroups[j-1].toUpperCase());
							$opt2.attr("value", strGroups[j-1]+"|"+k).text(strRank[k-1] + " OF " +strGroups[j-1].toUpperCase());
							// Add option to drop down list
							$(d1).append($opt1);
							$(d2).append($opt2);
						}
					}
					break;
				}
			}
		}else{
			var dropdown = $('#team1');
			// Clear drop down list
			$(dropdown).empty();
			$(competitionTeams).each(function () {
				if(this.category==strCategory[parseInt(category)]||this.category=='BOTH'){
					// Create option
					var $option = $("<option />");
					// Add value and text to option
					$option.attr("value", this.idteam).text(this.name);
					// Add option to drop down list
					$(dropdown).append($option);
				}
			});
			var dropdown = $('#team2');
			// Clear drop down list
			$(dropdown).empty();
			$(competitionTeams).each(function () {
				if(this.category==strCategory[parseInt(category)]||this.category=='BOTH'){
					// Create option
					var $option = $("<option />");
					// Add value and text to option
					$option.attr("value", this.idteam).text(this.name);
					// Add option to drop down list
					$(dropdown).append($option);
				}
			});
		}



	}
	dd1.options.selectedIndex = -1;
	dd2.options.selectedIndex = -1;
}

function setMatchType(phase){
	rd = document.getElementById("prelimGroup");
	re = document.getElementById("eventType");
	if (phase==''){
		re.style.display="none";
		rd.style.display="none";
		return;
	}

	if (phase=='PRELIMINARY'){
		rd.style.display="table-row";
		re.style.display="none";
		document.getElementById("group").required = true;
	}else{
		re.style.display="table-row";
		rd.style.display="none";
		document.getElementById("group").required = false;
	}
	/*
	document.getElementById("ddGameType").value="";
	document.getElementById("category").value="";
	*/
}

function populateEventGroupTeam(){
	var event=document.getElementById("ddEGGameType").value;
	var category=document.getElementById("ddEGCategory").value;
	if(category=="" || event=="")
		return;
	for(var i in gameTypes){
		if(gameTypes[i].id==event){
			var dropdown = $('#ddEGTeam');
			// Clear drop down list
			$(dropdown).empty();
			var $option = $("<option />");
			$option.attr("value", "").text("Select a Team");
			$(dropdown).append($option);
			$(competitionTeams).each(function () {
				if(this.idcategory==category || this.idcategory==3){
					// Create option
					var $option = $("<option />");
					// Add value and text to option
					$option.attr("value", this.idteam).text(this.name);
					// Add option to drop down list
					$(dropdown).append($option);
				}
			});

			//populate the Grouping in Teams tab
			var dropdown = $('#ddEGGroup');
			// Clear drop down list
			$(dropdown).empty();
			var $option = $("<option />");
			$option.attr("value", "").text("Select a Group ID");
			$(dropdown).append($option);
			// Add option to drop down list
			$(dropdown).append($option);
			for(j = 1; j<=gameTypes[i].maxgroup; j++){
				// Create option
				var $option = $("<option />");
				// Add value and text to option
				$option.attr("value", j).text(strGroups[j-1].toUpperCase());
				// Add option to drop down list
				$(dropdown).append($option);
			}
			//populate the Grouping in Teams tab
			var dropdown = $('#ddEGTeamIDInGroup');
			// Clear drop down list
			$(dropdown).empty();
			var $option = $("<option />");
			$option.attr("value", "").text("Select a Team ID");
			$(dropdown).append($option);
			// Add option to drop down list
			$(dropdown).append($option);
			for(j = 1; j<=gameTypes[i].maxteam; j++){
				// Create option
				var $option = $("<option />");
				// Add value and text to option
				$option.attr("value", j).text("TEAM " + j);
				// Add option to drop down list
				$(dropdown).append($option);
			}
			break;
		}
	}

}

function addTeamEventGroup(){
	var f = document.forms["formEventGroup"];
	//eventDetail object
	var eg = new Object();
	eg.competition = getParam("competition");
	eg.idgametype = f.ddEGGameType.value;
	eg.idteam = f.ddEGTeam.value;
	eg.group = f.ddEGGroup.value;
	eg.team = f.ddEGTeamIDInGroup.value;
	eg.category = f.ddEGCategory.value;

	var s = JSON.stringify(eg);
	document.body.style.cursor  = 'wait';


	$.ajax({
		cache:false,
		url: "php/saveeventgroup.php?eventgrouping="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		res=data;
		if (res.errorno==0){
			retrieveEventGrouping("");
			$('#ddEGGameType').prop("disabled", false);
			$('#ddEGTeam').prop("disabled", false);
			$('#ddEGCategory').prop("disabled", false);
			var f = document.forms["formEventGroup"];
			f.ddEGGameType.value = "";
			f.ddEGTeam.value = "";
			f.ddEGGroup.value = "";
			f.ddEGTeamIDInGroup.value = "";
			f.ddEGCategory.value = "";
			document.getElementById("tblAddEventGroup").style.display="";
			document.getElementById("tblManageEventGroup").style.display="none";
			window.location.href="event.html#groups";
		}
	});
	document.body.style.cursor  = 'default';
	return false;
}

function retrieveEventGrouping(event){
	var a=getParam("competition");
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/geteventgrouping.php?competition="+a+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		eventGrouping=data.data;
		if (data.errorno==0){
			displayEventGrouping(event);
		}
	});
	document.body.style.cursor  = 'default';
}

function displayEventGrouping(event){
	var table = document.getElementById("tblTeamEventGroup");
	var rowCount = table.rows.length;
	for (var i = 0; i < rowCount; i++) {
		table.deleteRow(0);
	}
	for(var i in eventGrouping){
		if(event!=""){
			if(eventGrouping[i].idgametype!= event)
				continue;
		}
		var row = table.insertRow(table.rows.length);
		var newcell = row.insertCell(0);
		newcell.innerHTML = "<a class=\"btnManage\"name=\ideventgroup_"+ eventGrouping[i].recid + "\" href=\"#group\" onclick=\"manageEventGroup('" + eventGrouping[i].recid +"')\">Edit</a>";
		newcell.width="70px";
		newcell.style.textAlign = "center";
		var newcell = row.insertCell(1);
		newcell.innerHTML = eventGrouping[i].eventname;
		newcell.width="100px";
		newcell.style.textAlign = "center";
		var newcell = row.insertCell(2);
		newcell.innerHTML = eventGrouping[i].categorydesc;
		newcell.width="100px";
		newcell.style.textAlign = "center";
		var newcell = row.insertCell(3);
		newcell.innerHTML = eventGrouping[i].name;
		newcell.width="170px";
		newcell.style.textAlign = "left";
		var newcell = row.insertCell(4);
		newcell.innerHTML = strGroups[eventGrouping[i].group -1].toUpperCase();
		newcell.width="100px";
		newcell.style.textAlign = "center";
		var newcell = row.insertCell(5);
		newcell.innerHTML = "TEAM " + eventGrouping[i].team;
		newcell.width="200px";
		newcell.style.textAlign = "center";

	}
}

function manageEventGroup(recordId){
	document.getElementById("tblAddEventGroup").style.display="none";
	document.getElementById("tblManageEventGroup").style.display="";
	$('#ddEGGameType').prop("disabled", true);
	$('#ddEGTeam').prop("disabled", true);
	$('#ddEGCategory').prop("disabled", true);
	var f = document.forms["formEventGroup"];
	for(var i in eventGrouping){
		if(eventGrouping[i].recid==recordId){
			manageIDEventGroup=recordId;
			f.ddEGGameType.value = eventGrouping[i].idgametype;
			populateEventGroupTeam(eventGrouping[i].idgametype,eventGrouping[i].category);
			f.ddEGTeam.value = eventGrouping[i].idteam;
			f.ddEGGroup.value = eventGrouping[i].group;
			f.ddEGTeamIDInGroup.value = eventGrouping[i].team;
			f.ddEGCategory.value = eventGrouping[i].category;
			window.scrollTo(0,document.body.scrollHeight);
			break;
		}
	}
}

function clearManageEGTeam(){
	$('#ddEGGameType').prop("disabled", false);
	$('#ddEGTeam').prop("disabled", false);
	$('#ddEGCategory').prop("disabled", false);
	var f = document.forms["formEventGroup"];
	f.ddEGGameType.value = "";
	f.ddEGTeam.value = "";
	f.ddEGGroup.value = "";
	f.ddEGTeamIDInGroup.value = "";
	document.getElementById("tblAddEventGroup").style.display="";
	document.getElementById("tblManageEventGroup").style.display="none";
	manageIDEventGroup="";
}

function removeEGTeam(){
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/removeeventgroup.php?recid="+manageIDEventGroup+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		if(data.errorno==0){
			manageIDEventGroup="";
			retrieveEventGrouping("");
			$('#ddEGGameType').prop("disabled", false);
			$('#ddEGTeam').prop("disabled", false);
			$('#ddEGCategory').prop("disabled", false);
			var f = document.forms["formEventGroup"];
			f.ddEGGameType.value = "";
			f.ddEGTeam.value = "";
			f.ddEGGroup.value = "";
			f.ddEGTeamIDInGroup.value = "";
			document.getElementById("tblAddEventGroup").style.display="";
			document.getElementById("tblManageEventGroup").style.display="none";
		}
	});
	document.body.style.cursor  = 'default';

}

function clearCategory(){
	document.getElementById("category").selectedIndex = "";
}

function updateRanking(){
	var dd1 = document.getElementById("team1").value;
	var dd2 = document.getElementById("team2").value;

	var eg = new Object();
	eg.competition = getParam("competition");
	eg.idgametype = document.getElementById("ddGameType").value;
	eg.category = strCategory[parseInt(document.getElementById("category").value)];
	var tmp = dd1.split("|");
	eg.idteam1group = tmp[0];
	eg.idteam1rank = tmp[1];
	var tmp = dd2.split("|");
	eg.idteam2group = tmp[0];
	eg.idteam2rank = tmp[1];
	var s = JSON.stringify(eg);

	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getgroupteamranking.php?param="+s+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		if(data.errorno==0){
			var grouping = data.data;
			if(grouping.length!=2){
				alert("Team Grouping not properly configured.");
				return;
			}

			if(grouping[0].length < parseInt(eg.idteam1rank)){
				alert(eg.idteam1group + " does not have enough number of teams.");
				return;
			}
			if(grouping[1].length < parseInt(eg.idteam2rank)){
				alert(eg.idteam2group + " does not have enough number of teams.");
				return;
			}
			//populate the Grouping in Teams tab
			var d1 = $('#team1');
			// Clear drop down list
			$(d1).empty();
			var tr = grouping[0];
			for(var j in tr){
				if(tr[j].group==eg.idteam1group){
					var $opt1 = $("<option />");
					$opt1.attr("value", tr[j].idteam).text(tr[j].name);
					$(d1).append($opt1);

				}

			}
			var d2 = $('#team2');
			$(d2).empty();

			var tr = grouping[1];
			for(var j in tr){
				if(tr[j].group==eg.idteam2group){
					var $opt2 = $("<option />");
					$opt2.attr("value", tr[j].idteam).text(tr[j].name);
					$(d2).append($opt2);
				}

			}
			document.getElementById("team1").selectedIndex = parseInt(eg.idteam1rank)-1;
			document.getElementById("team2").selectedIndex = parseInt(eg.idteam2rank)-1;
			// switch the mode to single to save properly
			rd = document.getElementsByName("rdEventType");
			rd[2].checked = true;

		}
	});
	document.body.style.cursor  = 'default';
}

function exportOfficials(){
	var c = getParam("competition");

	var ifrm = document.getElementById("hiddenFrame");
    ifrm.src = "php/exportofficials.php?competition="+c;
}

function exportSchedule(){
	var c = getParam("competition");

	var ifrm = document.getElementById("hiddenFrame");
    ifrm.src = "php/exportschedule.php?competition="+c;
}


function enableSection(sec){
	document.getElementById("section1").style.display="none";
	document.getElementById("section2").style.display="none";
	document.getElementById("section3").style.display="none";
	document.getElementById("section4").style.display="none";
	document.getElementById("section5").style.display="none";
	//document.getElementById("section6").style.display="none";
	document.getElementById("section7").style.display="none";
	document.getElementById("section8").style.display="none";
	document.getElementById("section9").style.display="none";
	document.getElementById("section"+sec).style.display="block";
}

function highlightSection(){
	var sPageURL = window.location.href;
	var sURLVariables = sPageURL.split('#');
	if (sURLVariables.length != 2){
		window.location.href("event.html#schedule");
		document.getElementById("section1").style.display="none";
		document.getElementById("section2").style.display="none";
		document.getElementById("section3").style.display="block";
		document.getElementById("section4").style.display="none";
		document.getElementById("section5").style.display="none";
		//document.getElementById("section6").style.display="none";
		document.getElementById("section7").style.display="none";
		document.getElementById("section8").style.display="none";
		document.getElementById("section9").style.display="none";
		return;
	}

	if(sURLVariables[1]=="team"){
		enableSection(1);
	}else if(sURLVariables[1]=="groups"){
		enableSection(2);
	}else if(sURLVariables[1]=="schedule"){
		enableSection(3);
	}else if(sURLVariables[1]=="summary"){
		enableSection(4);
	//}else if(sURLVariables[1]=="forms"){
	//	enableSection(6);
	}else if(sURLVariables[1]=="officials"){
		enableSection(7);
	}else if(sURLVariables[1]=="accreditation"){
		enableSection(8);
	}else if(sURLVariables[1]=="sponsors"){
		enableSection(5);
	}else if(sURLVariables[1]=="report"){
		enableSection(9);
	}else{
		window.location.href("event.html#schedule");
		document.getElementById("section1").style.display="none";
		document.getElementById("section2").style.display="none";
		document.getElementById("section3").style.display="block";
		document.getElementById("section4").style.display="none";
		document.getElementById("section5").style.display="none";
		//document.getElementById("section6").style.display="none";
		document.getElementById("section7").style.display="none";
		document.getElementById("section8").style.display="none";
		document.getElementById("section9").style.display="none";
	}

}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}
