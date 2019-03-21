var game;
function getGameDetails(order){

	var competition = getParam("competition");
	var gameno = getParam("gameno");
	/*
	var xmlhttp = new ajaxRequest();
	//document.body.style.cursor  = 'wait';
	xmlhttp.open("GET", "php/getmatchdetail.php?competition="+competition+"&gameno="+gameno+"&order="+order, false);
	xmlhttp.send();
	//document.body.style.cursor  = 'default';
	return xmlhttp.responseText;
	*/
	$.ajax({
		cache:false,
		url: "php/getmatchdetail.php?competition="+competition+"&gameno="+gameno+"&order="+order+getUserParam(),
		async:false,
		dataType: "json"
	}).done(function (data) {
		checkResponse(data);
		game = data.data;
	});
}

function printPage(){
	window.print();
}

function switchPage(page){
	for (i=1;i<=6;i++){
		var res = document.getElementById("page" + i);
		res.style.display="none";

	}
	for (i=4;i<=6;i++){
		var res = document.getElementById("lnk" + i);
		res.setAttribute("href","#");

	}
	var res = document.getElementById("page" + page);
	res.style.display="block";

	var res = document.getElementById("lnk" + page);
	res.removeAttribute("href");

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
