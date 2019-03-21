// Add the following into your HEAD section
var timer = 0;
var hasError=false;
function set_interval() {
	// the interval 'timer' is set as soon as the page loads
	timer = setInterval("auto_logout()", 30 * 60 * 1000);
	// the figure '10000' above indicates how many milliseconds the timer be set to.
	// Eg: to set it to 5 mins, calculate 5min = 5x60 = 300 sec = 300,000 millisec.
	// So set it to 300000
}

function reset_interval() {
	//resets the timer. The timer is reset on each of the below events:
	// 1. mousemove   2. mouseclick   3. key press 4. scroliing
	//first step: clear the existing timer

	if (timer != 0) {
		clearInterval(timer);
		timer = 0;
		// second step: implement the timer again
		timer = setInterval("auto_logout()", 30 * 60 * 1000);
		// completed the reset of the timer
		renewParam();
	}
}

function auto_logout() {
	window.location = "index.php";
	// this function will redirect the user to the logout script
	$.ajax({
		cache:false,
		url: "php/logout.php?user="+getParam("id")+"&token="+getParam("token"),
		dataType: "text"
	}).done(function (data) {
		clearParam();
		alert("Your session has been disconnected due to inactivity.");
	});	
}

function ajaxRequest(){
	var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //activeX versions to check for in IE
	if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
		for (var i=0; i<activexmodes.length; i++){
			try{
				return new ActiveXObject(activexmodes[i]);
			}
			catch(e){//suppress error
			}
		}
	}else if (window.XMLHttpRequest) // if Mozilla, Safari etc
		return new XMLHttpRequest();
	else
		return false;
}
function getParam(val) {
	/*
    var result = "Not found",
        tmp = [];
    location.search
    //.replace ( "?", "" ) 
    // this is better, there might be a question mark inside
    .substr(1)
        .split("&")
        .forEach(function (item) {
        tmp = item.split("=");
        if (tmp[0] === val) result = decodeURIComponent(tmp[1]);
    });
	*/
	/*
    var result = "Not found";
	if(typeof(Storage)!=="undefined"){
		result=sessionStorage.getItem(val);
		if (result==null){
			//if (sessionStorage.length<3){
				location.replace("index.php");
			//}else{
			//	location.replace("main.html");
			//	result = "Not found";				
			//}
		}
	}else{
	  alert("Sorry, your browser does not support web storage...");
	}
    return result;
	*/
	var name = val + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	if(val!="token"){
		location.replace("index.php");
	}else{
		//alert("You have been logged-out due to inactivity!");
		return "";			
	}
}

function renewParam() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        var val = eqPos > -1 ? cookie.substr(eqPos+1, cookie.length) : cookie;
		setParam(name,val);
	}
}

function setParam(param,val){
	//sessionStorage.setItem(param,val);
	
	var d = new Date();
	// set the cookie to expire every 5mins
	d.setTime(d.getTime() + (60 * 60 * 1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = param + "=" + val + ";" + expires + ";"	
}

function removeParam(param){
	//sessionStorage.removeItem(param);
	
	document.cookie = param + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function clearParam() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
		removeParam(name);
	}
}


function checkfile(sender,validExts,height=0,width=0) {;

	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	fileExt = fileExt.toLowerCase();
	if (validExts.indexOf(fileExt) < 0) {
		alert("Invalid file selected, valid files are of " + validExts.toString() + " types. You used a file of type: " + fileExt + ".");
		sender.value="";
		return false;
	}else{
		// for image file
		if(height >0 && width>0){
			var _URL = window.URL || window.webkitURL;
			var file, img;
			if ((file = sender.files[0])) {
				img = new Image();
				img.src = _URL.createObjectURL(file);
				img.onload = function() {
					if(this.width > width || this.height > height){
						alert("Width and height of the file must be equal or less than " + width + "x" + height);
						sender.value="";
						return false;
					}
				};
				img.onerror = function() {
					alert( "not a valid file: " + file.type);
				};


			}			
		}

		return true;
	}

}

function checkUser(){
/*	
	$.ajax({
		cache:false,
		url: "php/checkuser.php?id="+getParam("id")+"&token="+getParam("token")+"&type="+getParam("usertype"),
		dataType: "json"
	}).done(function (data) {
		if (data.errorno!=0){
			alert(data.message);
//			window.stop();
//			window.close();
			location.replace("index.php");
		}
	});	
*/
}

function getUserParam(){
	return "&id="+getParam("id")+"&token="+getParam("token")+"&type="+getParam("usertype");
}

function checkResponse(data){
	if (data.errorno==1){
		clearParam();
		if(hasError==false){
			alert(data.message);
			location.replace("index.php");
			hasError=true;
		}
		throw Error(data.message);
	}else if(data.errorno==2){
		alert(data.message);		
	}else if(data.errorno==0 && data.message != ""){
		alert(data.message);
	}
}