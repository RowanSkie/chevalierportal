var competitions;

function retrieveCountry(){

	document.body.style.cursor  = 'wait';
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    document.body.style.cursor  = 'default';
            var res = JSON.parse(this.responseText);
				var sel = document.getElementById("ddCompetition");
				for(i=0;i<res.length;i++){
					sel.options[sel.options.length] = new Option(res[i].Title, res[i].ID);
				}
				return false;
            }
        };
	xmlhttp.open("POST", "php/getcompetition.php?user="+a+getUserParam(), true);
	xmlhttp.send();
}

function removeCompetition(competition){

	var a = getParam("subscription");
	var isDel = confirm("This will remove the competition, event and participant. Proceed?");
	if (isDel){
		/*
		document.body.style.cursor  = 'wait';
		var xmlhttp = new ajaxRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			    document.body.style.cursor  = 'default';
                var res = this.responseText;
				alert (res);
				window.location.href = "competition.html"
	        };
		}
		xmlhttp.open("POST", "php/removecompetition.php?subscription="+a+"&competition="+competition, true);
		xmlhttp.send();*/
		document.body.style.cursor  = 'wait';
		$.ajax({
			cache:false,
			url: "php/removecompetition.php?subscription="+a+"&competition="+competition+getUserParam(),
			dataType: "json"
		}).done(function (data) {
			document.body.style.cursor  = 'default';
			checkResponse(data);
			window.location.href = "main.html";
		});
		document.body.style.cursor  = 'default';

	}

}

function saveCompetition(){

	var a = getParam("user");
	var b = getParam("subscription");

	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!

	var yyyy = today.getFullYear();
	if(dd<10){
		dd='0'+dd
	}
	if(mm<10){
		mm='0'+mm
	}
	var today = new Date(yyyy+'/'+mm+'/'+dd);

	var f = document.forms["competition"];
	var sd = new Date(f.startdate.value);
	var ed = new Date(f.enddate.value);
	if(getParam("mode")==0){
		if (sd<today){
			alert("Start date cannot be earlier than present date.");
			return false;
		}
		if (ed<sd){
			alert("End date cannot be earlier than start date.");
			return false;
		}
	}

	var competitionDetails = new Object();
	competitionDetails.mode = getParam("mode");
	competitionDetails.modeID = getParam("modeID");
	competitionDetails.user = a;
	competitionDetails.subscription = b;
	competitionDetails.title = f.tournament.value;
	competitionDetails.venue = f.venue.value;
	competitionDetails.state = f.state.value;
	competitionDetails.noc = f.noc.value;
	competitionDetails.startdate = f.startdate.value;
	competitionDetails.enddate = f.enddate.value;
	competitionDetails.type = f.type.value;
	//competitionDetails.groups = f.groups.value;
	//competitionDetails.noofteamingroup = f.teamcount.value;
	competitionDetails.participants = f.participants.value;
	competitionDetails.sanctioned = f.sanctioned.checked;

	var aGameType=[];
	// parse the set of game types available in the competition
	// reuse the var gameType initially called during page init
	var tbl = document.getElementById("tblGameType");
	var rowCount = tbl.rows.length;
	for (var i=2;i<rowCount;i++){
		var row = tbl.rows[i];
		var cb = row.cells[1].childNodes[0];
		var m = row.cells[2].childNodes[0];
		var f = row.cells[3].childNodes[0];
		var g = row.cells[4].childNodes[1];
		var t = row.cells[5].childNodes[1];

		if(null != cb && true == cb.checked) {
			for(var j in gameType){
				var gt = gameType[j];
				if (gt.id == cb.name){
					if(!m.checked && !f.checked){
						alert("No category selected for " + gt.description);
						return false;
					}
					gt.category=0;
					if (m.checked) // mens category (id = 1)
						gt.category += 1;
					if (f.checked) // womens category (id = 2)
						gt.category += 2;
					if(g[g.selectedIndex].value==""){
						alert("No No. of Groups for " + gt.description);
						return false;
					}
					gt.maxgroup = g[g.selectedIndex].value;
					gt.maxteam = t.value;
					aGameType.push(gt);
				}
			}
		}
	}

	if (aGameType.length==0){
		alert("No event selected.");
		return false;
	}
	competitionDetails.gametype = aGameType;
	var competition = JSON.stringify(competitionDetails);
	/*
	document.body.style.cursor  = 'wait';
	var competition = JSON.stringify(competitionDetails);
	var xmlhttp = new ajaxRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.body.style.cursor  = 'default';
                var res = JSON.parse(this.responseText);
				alert(res.message);
				if (res.errorno==0)
					location.replace("main.html");
            }
        };
	xmlhttp.open("POST", "php/savecompetition.php?competition="+competition, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/savecompetition.php?competition="+competition+getUserParam(),
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		window.location.href = "main.html";
	});
	document.body.style.cursor  = 'default';
	return false;
}


function getNocDetails(){

	/*
	var xmlhttp = new ajaxRequest();
	document.body.style.cursor  = 'wait';
	xmlhttp.open("GET", "php/getnoc.php", false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	var dat;
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getnoc.php?"+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		dat = data.data
		checkResponse(data);
	});
	document.body.style.cursor  = 'default';
	return dat;
}

function getGameType(){
 
/*
	var xmlhttp = new ajaxRequest();
	document.body.style.cursor  = 'wait';
	xmlhttp.open("GET", "php/getgametype.php", false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
*/
	var dat;
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getgametype.php?"+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		dat = data.data
		checkResponse(data);
	});
	document.body.style.cursor  = 'default';
	return dat;
}

function retrieveCompetitionList(){
 
	var a = getParam("subscription");
	if (a=="Not found"){
		window.location.href = "index.php"
		return;
	}
	/*
	document.body.style.cursor  = 'wait';
	var xmlhttp = new ajaxRequest();
	xmlhttp.open("POST", "php/getcompetition.php?subscription="+a, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/

	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetition.php?subscription="+a+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		competitions = data.data;
	});
	document.body.style.cursor  = 'default';

}


function getFormattedDate(date) {
	var year = date.getFullYear();
	var month = (1 + date.getMonth()).toString();
	month = month.length > 1 ? month : '0' + month;
	var day = date.getDate().toString();
	day = day.length > 1 ? day : '0' + day;
	return year + '-' + month + '-' + day;
}

function populateForm(){
	competitionId = getParam("modeID");
	var competition=null;
	//retrive from competitions
	for(var i in competitions){
		if(competitions[i].ID==competitionId){
			competition=competitions[i];
		}
	}
	f = document.forms["competition"];
	f.tournament.value=competition.Title;
	f.venue.value=competition.Venue;
	f.state.value=competition.State;
	f.noc.value=competition.NOC;
	f.startdate.value=competition.StartDate.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$2-$1");;
	f.enddate.value=competition.EndDate.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$2-$1");;
	f.type.value=competition.Type;
	//f.groups.value=competition.Groups;
	//f.teamcount.value=competition.TeamPerGroup;
	f.participants.value=competition.Participants;
	f.sanctioned.checked=competition.Sanctioned;
	// parse the set of game types available in the competition
	// reuse the var gameType initially called during page init
	var tbl = document.getElementById("tblGameType");
	var rowCount = tbl.rows.length;
	for (var i=2;i<rowCount;i++){
		var row = tbl.rows[i];
		row.cells[1].childNodes[0].checked=false;
		row.cells[2].childNodes[0].checked=false;
		row.cells[3].childNodes[0].checked=false;
	}
	for (var i=2;i<rowCount;i++){
		var row = tbl.rows[i];
		var cb = row.cells[1].childNodes[0];
		var m = row.cells[2].childNodes[0];
		var f = row.cells[3].childNodes[0];
		var g = row.cells[4].childNodes[1];
		var t = row.cells[5].childNodes[1];
		if(null != cb) {
			for(var j in competition.GameType){
				var gt = competition.GameType[j];
				if (gt.idGameType == cb.name){
					cb.checked=true;
					if (parseInt((gt.category) & 1) == 1)
						m.checked = true
					if (parseInt((gt.category) & 2) == 2)
						f.checked = true
					for(var k=0;k<g.length;k++){
						if(g[k].value==gt.maxgroup){
							g[k].selected=true;
							break;
						}
					}
					t.value = gt.maxteam;
				}
			}
		}
	}
}

function displayWarning(isChecked){
	if(isChecked)
		alert("Any sanctioned events by ASTAF or ISTAF will be mandatory to have random Doping Tests.");
}
