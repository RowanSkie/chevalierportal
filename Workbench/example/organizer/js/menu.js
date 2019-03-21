// This file contains the javascript functions 
function loadCompetition(mode,id) {
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");
	var b = getParam("subscription");

	setParam("user",a);
	setParam("subscription",b);
	setParam("mode",mode);
	setParam("modeID",id);
}

function loadEvent(competition,tabno) {
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");

	/*
	if (competition==""){
		alert("Please select a competition");
		window.location.href = "main.html";
		return;
	}*/
	setParam("competition",competition);
	removeParam("email");
	removeParam("printOfficials");

}

function loadTeam(email) {
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");
	var competition = getParam("competition");	
	setParam("email",email);
	location.replace("team.html");	
}


function logout() {
	// this function will redirect the user to the logout script
	$.ajax({
		cache:false,
		url: "php/logout.php?user="+getParam("id")+"&token="+getParam("token"),
		dataType: "text"
	}).done(function (data) {
		clearParam();
		window.location = "index.php";
	});	
}


function loadHome() {
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");
	removeParam("competition");
	removeParam("gameno");
	removeParam("email");
}

function loadAccount() {
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");
	removeParam("competition");
	removeParam("gameno");
	removeParam("email");
}

function loadGameDetail(gameno){
	var competition = getParam("competition");
	var a = getParam("user");
	if (gameno==""){
		alert("Please select a match.");
		return;
	}
	if (matches==null||matches==undefined)
		return;
	setParam("gameno",gameno);
	location.replace("matchdetail.html");
}

function loadMatchReport(competition,gameno){
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");
	if (gameno==""){
		alert("Please select a match.");
		return;
	}
	setParam("gameno",gameno);
	var url = "matchreport.html";
	window.open(url,"popup","resizeable=yes,scrollbars=yes,width=650,height=1080",false);
}

function loadSummary(competition,event,category){
	document.body.style.cursor  = 'wait';	
	var a = getParam("user");
	if (event==""){
		alert("Please select an event.");
		return;
	}
	if (category==""){
		alert("Please select a category.");
		return;
	}
	setParam("event",event);
	setParam("category",category);
	var url = "summary.html";
	window.open(url,"popup","resizeable=yes,scrollbars=yes,width=650,height=1080",false);
}
