var game,scorestamp;
function getGameDetails(){
 
	var competition = getParam("competition");
	var event = getParam("event");
	var category = getParam("category");

	/*
	var xmlhttp = new XMLHttpRequest();
	document.body.style.cursor  = 'wait';
	xmlhttp.open("GET", "php/getmatchreport.php?competition="+competition+"&gameno="+gameno, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getsummary.php?competition="+competition+"&event="+event+"&category="+category+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		game = data.data;
		document.getElementById("lblGender").innerHTML = game.gender+ "'S " + game.event;
		document.getElementById("lblPhase").innerHTML = game.phase;
		document.getElementById("lblVenue").innerHTML = game.venue;
		for (var a in game.groups){
			if (game.groups[a].title!=""){
				document.getElementById(game.groups[a].title).style.display="block";
				var table = document.getElementById("tblmatches"+game.groups[a].name);
				var j;
				for(var i in game.groups[a].matches){
					// hide the match score column
					if(game.groups[a].matches[i].gamecount<3){
						document.getElementById("colmatches"+game.groups[a].name).style.display = "none";
					}

					var row = table.insertRow(table.rows.length);
					var newcell = row.insertCell(0);
					newcell.innerHTML = game.groups[a].matches[i].date;
					newcell.width="50px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(1);
					newcell.innerHTML = game.groups[a].matches[i].time;
					newcell.width="50px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(2);
					newcell.innerHTML = game.groups[a].matches[i].idmatch;
					newcell.width="40px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(3);
					for(j in game.groups[a].teams){
						if(game.groups[a].teams[j].idteam==game.groups[a].matches[i].team1){
							break;
						}
					}
					newcell.innerHTML = game.groups[a].teams[j].noc;
					newcell.width="40px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(4);
					newcell.innerHTML = game.groups[a].teams[j].name;
					newcell.width="100px";
					newcell.style.textAlign = "center";
					for(j in game.groups[a].teams){
						if(game.groups[a].teams[j].idteam==game.groups[a].matches[i].team2){
							break;
						}
					}
					var newcell = row.insertCell(5);
					newcell.innerHTML = game.groups[a].teams[j].noc;
					newcell.width="40px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(6);
					newcell.innerHTML = game.groups[a].teams[j].name;
					newcell.width="100px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(7);
					newcell.innerHTML = game.groups[a].matches[i].score;
					newcell.width="50px";
					newcell.style.textAlign = "center";
					newcell.style.whiteSpace = "pre";
					if(game.groups[a].matches[i].gamecount<3){
						newcell.style.display = "none";
					}
					var newcell = row.insertCell(8);
					newcell.innerHTML = game.groups[a].matches[i].matchscore;
					newcell.width="50px";
					newcell.style.textAlign = "center";
					newcell.style.whiteSpace = "pre";
					var newcell = row.insertCell(9);
					newcell.innerHTML = game.groups[a].matches[i].setscore;
					newcell.width="100px";
					newcell.style.textAlign = "center";
					newcell.style.whiteSpace = "pre";
					var newcell = row.insertCell(10);
					newcell.innerHTML = game.groups[a].matches[i].duration;
					newcell.width="40px";
					newcell.style.textAlign = "center";
				}

				var table = document.getElementById("tblranking"+game.groups[a].name);
				for(var i in game.groups[a].teams){
					var row = table.insertRow(table.rows.length);
					var newcell = row.insertCell(0);
					j=parseInt(i)+1;
					newcell.innerHTML = j;
					newcell.width="20px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(1);
					newcell.innerHTML = game.groups[a].teams[i].name;
					newcell.width="100px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(2);
					newcell.innerHTML = game.groups[a].teams[i].played;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(3);
					newcell.innerHTML = game.groups[a].teams[i].wins;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(4);
					newcell.innerHTML = game.groups[a].teams[i].loss;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(5);
					newcell.innerHTML = game.groups[a].teams[i].points;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(6);
					newcell.innerHTML = game.groups[a].teams[i].setwon;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(7);
					newcell.innerHTML = game.groups[a].teams[i].setloss;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(8);
					newcell.innerHTML = game.groups[a].teams[i].setdiff;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(9);
					newcell.innerHTML = game.groups[a].teams[i].scorewin;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(10);
					newcell.innerHTML = game.groups[a].teams[i].scoreloss;
					newcell.width="30px";
					newcell.style.textAlign = "center";
					var newcell = row.insertCell(11);
					newcell.innerHTML = game.groups[a].teams[i].scorediff;
					newcell.width="30px";
					newcell.style.textAlign = "center";
				}
			}
		}
	});
	document.body.style.cursor  = 'default';
}

function printPage(){
	window.print();
}

function switchPageSolo(page){
	for (i=1;i<=2;i++){
		var res = document.getElementById("page" + i);
		res.style.display="none";
	}
	var res = document.getElementById("page" + page);
	res.style.display="block";

}

function switchPageTeam(page){
	for (i=3;i<=9;i++){
		var res = document.getElementById("page" + i);
		res.style.display="none";
	}
	var res = document.getElementById("page" + page);
	res.style.display="block";

}

function displayLogo(){
	// display the logo
	img = document.getElementById("leftlogo");
	if (img!=null)
		img.src = game.leftlogo +"?random="+new Date().getTime();
	img = document.getElementById("rightlogo");
	if (img!=null)
		img.src = game.rightlogo +"?random="+new Date().getTime();
	img = document.getElementById("sponsor1logo");
	if (img!=null)
		if (game.sponsor1logo!=null)
			img.src = game.sponsor1logo+"?random="+new Date().getTime();
	img = document.getElementById("sponsor2logo");
	if (img!=null)
		if (game.sponsor2logo!=null)
			img.src = game.sponsor2logo +"?random="+new Date().getTime();
	img = document.getElementById("sponsor1logof");
	if (img!=null)
		if (game.sponsor1logo!=null)
			img.src = game.sponsor1logo+"?random="+new Date().getTime();
	img = document.getElementById("sponsor2logof");
	if (img!=null)
		if (game.sponsor2logo!=null)
			img.src = game.sponsor2logo +"?random="+new Date().getTime();
}
