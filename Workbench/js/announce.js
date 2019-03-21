function getAnnounce(){

  // changes the cursor to hourglass while waiting
  // for the response from interface
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "../php/getAnnounce.php", // the ajax object calls the interface as part of parameter
		dataType: "json"
	}).done(function (data) {
    // we traverse the contents of the parsed JSON object stored in the res variable
		var table = document.getElementById("tblAnnounce"); //getElementById function gets a reference to the select tag defined in the home.html
		var rowCount = table.rows.length;
		for (var i = 1; i < rowCount; i++) {
			table.deleteRow(1);
		}
    // we traverse the contents of the parsed JSON object stored in the res variable
		for(i=0;i<data.length;i++){
				var row = table.insertRow(table.rows.length);

				var newcell = row.insertCell(0);
				newcell.innerHTML = data[i].post;
				newcell.width="250px";
				newcell.style.textAlign = "center";
		}
    // set the mouse pointer to default cursor
		document.body.style.cursor  = 'default';
	});
}

var AnnouncementPost;
var AnnouncementID;

function postAnnounce(){
  	var f = document.forms["formEditAnnounce"];
  	var announceinfo = new Object();
  	announceinfo.postdate = f.postdate.value;
  	announceinfo.enddate = f.enddate.value;
  	announceinfo.post = f.post.value;
  	announceinfo.importance = f.importance.value;

  	var param=JSON.stringify(announceinfo);
  	document.body.style.cursor= 'wait';
  	$.ajax({
  		cache:false,
  		url: "../php/saveannounce.php?param=" + param,
  		dataType: "text"
  	}).done(function (data) {
  		getAnnounce();
  		document.body.style.cursor = 'default';
  	});
  	document.body.style.cursor = 'default';
  	return false;
}
