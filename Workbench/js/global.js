function getParam(val) {
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
