var game,scorestamp;
function getGameDetails(){
 
	var competition = getParam("competition");
	var gameno = getParam("gameno");

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
		url: "php/getmatchreport.php?competition="+competition+"&gameno="+gameno+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		game = data.data;
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
	/*
	// last minute addition, disable to minimize
	// recode
	// TODO: optimize code
	var res = document.getElementById("page10");
	res.style.display="none";
	*/
	var res = document.getElementById("page" + page);
	res.style.display="block";

	if(page==2){
		document.getElementById("lnksolo" + page).removeAttribute("href");
		document.getElementById("lnksolo1").setAttribute("href","#");
	}else{
		document.getElementById("lnksolo" + page).removeAttribute("href");
		document.getElementById("lnksolo2").setAttribute("href","#");

	}
}

function switchPageTeam(page){
	for (i=3;i<=9;i++){
		var res = document.getElementById("page" + i);
		res.style.display="none";
		document.getElementById("lnk" + i).setAttribute("href","#");
	}
	/*
	// last minute addition, disable to minimize
	// recode
	// TODO: optimize code
	var res = document.getElementById("page10");
	res.style.display="none";
	*/
	var res = document.getElementById("page" + page);
	res.style.display="block";
	document.getElementById("lnk" + page).removeAttribute("href");
}

function getScoreStamp(){
	var competition = getParam("competition");
	var gameno = getParam("gameno");
	/*
	var xmlhttp = new XMLHttpRequest();
	document.body.style.cursor  = 'wait';
	xmlhttp.open("GET", "php/getscorestamp.php?competition="+competition+"&gameno="+gameno, false);
	xmlhttp.send();
	document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getscorestamp.php?competition="+competition+"&gameno="+gameno+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		scorestamp = data.data;
	});
	document.body.style.cursor  = 'default';
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
