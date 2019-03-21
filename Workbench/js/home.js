// here is were you will populate the select tag
// by creating a function

// you can get the code from my samples i provided you
// this function calls the interface localhost/php/generatesubject.php
function generateSubject(studentid){

  // changes the cursor to hourglass while waiting
  // for the response from interface
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/generatesubject.php?studentid="+studentid, // the ajax object calls the interface as part of parameter
		dataType: "json"
	}).done(function (data) {
    // we traverse the contents of the parsed JSON object stored in the res variable
		var table = document.getElementById("tblStudentGrades"); //getElementById function gets a reference to the select tag defined in the home.html
		var rowCount = table.rows.length;
		for (var i = 1; i < rowCount; i++) {
			table.deleteRow(1);
		}
    // we traverse the contents of the parsed JSON object stored in the res variable
		for(i=0;i<data.length;i++){
				var row = table.insertRow(table.rows.length);
				// this columne will be used for editing grades when onclick() is triggered
				var newcell = row.insertCell(0);
				newcell.innerHTML = data[i].comment;
				newcell.width="200px";
				newcell.style.textAlign = "center";
				var newcell = row.insertCell(1);
				newcell.innerHTML = data[i].grade;
				newcell.width="50px";
				newcell.style.textAlign = "center";
		}
    // set the mouse pointer to default cursor
		document.body.style.cursor  = 'default';
	});
}
