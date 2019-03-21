function register(){
	var f = document.forms["orgReg"];
	if (f.password.value != f.confirmpassword.value){
		alert("Password does not match.");
		return false;
	}	
	
	var tr = new Object();
	tr.email = encodeURIComponent(f.email.value);
	tr.password = encodeURIComponent(f.password.value);
	tr.name = f.name.value;
	tr.nationalid = f.nationalid.value;
	tr.passport = f.passport.value;
	tr.birthdate = f.birthdate.value;
	tr.organization = f.organization.value;
	tr.contactmobile = f.contactmobile.value;
	tr.contactoffice = f.contactoffice.value;
	tr.noc = f.noc.value;
	tr.postalcode = f.postalcode.value;
	tr.address = f.address.value;
	tr.state = f.state.value;
	
	var s=JSON.stringify(tr);

	/*
    var xmlhttp = new ajaxRequest();
    xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var res = JSON.parse(this.responseText);
			alert(res.message);
			if (res.errorno==0)
				location.replace("index.php");
		}
	};

	xmlhttp.open("POST", "php/register.php?organizer=" + s, true);
	xmlhttp.send();
	*/
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/register.php?organizer=" + s,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		if(data.errorno==0)
			location.replace("index.php");
	});
	document.body.style.cursor  = 'default';		return false;
}

function getNocDetails(){
	/*
        var xmlhttp = new ajaxRequest();
		
        xmlhttp.open("GET", "php/getnoc.php", false);
        xmlhttp.send();
		return xmlhttp.responseText;
	*/
	var dat;
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/getcompetitionparticipants.php?user="+email+"&competition="+c,
		async:false,
		dataType: "json"
	}).done(function (data) {
		document.body.style.cursor  = 'default';
		checkResponse(data);
		dat = data.data;
	});
	document.body.style.cursor  = 'default';
	return dat;
}