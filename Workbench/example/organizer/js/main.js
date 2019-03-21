function retrieveCompetitionList(){
	
	var a = getParam("subscription");
	var competitions;
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
	return competitions;
}

function removeCompetition(competition){
	
	var a = getParam("subscription");
	var isDel = confirm("This will remove the competition, event and participant. Proceed?");
	if (isDel){
		/*
		var xmlhttp = new ajaxRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			    document.body.style.cursor  = 'default';
                var res = this.responseText;
				alert (res);
				window.location.href = "main.html";
	        };
		}
		xmlhttp.open("POST", "php/removecompetition.php?subscription="+a+"&competition="+competition, true);
		xmlhttp.send();
		*/
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