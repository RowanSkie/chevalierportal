// This file contains the javascript functions 
function login() {
	var token = getParam("token");
	var usr = document.getElementById("txtUserName").value;
	var pass = document.getElementById("txtPassword").value;
		document.body.style.cursor  = 'wait';
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				document.body.style.cursor  = 'default';
                var res = this.responseText;
				res = JSON.parse(res);
				if(res.errorno==0){
					setParam("user",usr);
					setParam("id",res.id);
					setParam("subscription",res.subscription);
					setParam("groupcode",res.groupcode);
					setParam ("token",res.token);
					setParam ("usertype",res.usertype);
					window.location.href = "main.html";
				}else{
					alert(res.message);
					location.replace("index.php");
				}
				
            }
        };
        xmlhttp.open("POST", "php/login.php?user=" + usr + "&password=" + pass+"&token="+token, true);
        xmlhttp.send();
}

function register(){
	location.replace ("register.html");
}
