var StudentGrades;
var StudentIDForEdit;
// here is were you will populate the select tag
// by creating a function

// you can get the code from my samples i provided you
// this function calls the interface localhost/php/generatesubject.php
function generateSubject(){

  // changes the cursor to hourglass while waiting
  // for the response from interface
	document.body.style.cursor  = 'wait';
	$.ajax({
		cache:false,
		url: "php/generatesubject.php", // the ajax object calls the interface as part of parameter
		dataType: "json"
	}).done(function (data) {
    // data is the variable that contains the response of generatesubject.php
    // data object contains the json from generatesubject.php: [{"subjectid":"101","subjectname":"eng","comment":"English"},{"subjectid":"102","subjectname":"fil","comment":"Filipino"},{"subjectid":"103","subjectname":"math","comment":"Mathematics"},{"subjectid":"104","subjectname":"sci","comment":"Science"},{"subjectid":"105","subjectname":"ap","comment":"Araling Panlipunan"},{"subjectid":"106","subjectname":"cle","comment":"ESP"},{"subjectid":"107","subjectname":"mapeh","comment":"MAPEH"},{"subjectid":"108","subjectname":"tle","comment":"TLE (ICT or COOKERY)"}]
		var sel = document.getElementById("subjectSelection"); //getElementById function gets a reference to the select tag defined in the home.html

    // we traverse the contents of the parsed JSON object stored in the res variable
		for(i=0;i<data.length;i++){
			sel.options[sel.options.length] = new Option(data[i].comment, data[i].subjectid); // adds new options to the select tag (dropdown)
		}
    // set the mouse pointer to default cursor
		document.body.style.cursor  = 'default';
	});
}

// Choose subject from select tag in home.html
function getSubjects(){
  var sel = document.getElementById("subjectSelection"); //getElementById function gets a reference to the select tag defined in the home.html
}


// get the students of a techer given the teachers database ID
// and display in a table
function getMyStudents(idteacher){
	// changes the cursor to hourglass while waiting
  // for the response from interface
	document.body.style.cursor = 'wait';
	$.ajax({
		cache:false,
		url: "../php/getmystudents.php?id="+idteacher, // the ajax object calls the interface as part of parameter
		dataType: "json"
	}).done(function (data) {
    // data is the variable that contains the response of getmystudents.php
		// store in the global variable StudentGrades for future user
		StudentGrades = data;

		var table = document.getElementById("tblStudentGrades"); //getElementById function gets a reference to the select tag defined in the home.html
		var rowCount = table.rows.length;
		for (var i = 1; i < rowCount; i++) {
			table.deleteRow(1);
		}
    // we traverse the contents of the parsed JSON object stored in the res variable
		for(i=0;i<data.length;i++){
				var row = table.insertRow(table.rows.length);
				var newcell = row.insertCell(0);

				// this columne will be used for editing grades when onclick() is triggered
				newcell.innerHTML = "<a href=\"#grades\" onclick=\"manageStudentGrade('" + data[i].id +"')\">View</a>";
				newcell.width="70px";
				newcell.style.textAlign = "center";

				var newcell = row.insertCell(1);
				newcell.innerHTML =  data[i].lastname;
				newcell.style.textAlign = "center";
				newcell.width="100px";
				var newcell = row.insertCell(2);
				newcell.innerHTML = data[i].firstname;
				newcell.width="100px";
				newcell.style.textAlign = "center";
				var newcell = row.insertCell(3);
				newcell.innerHTML = data[i].sectionname;
				newcell.width="50px";
				newcell.style.textAlign = "center";
				var newcell = row.insertCell(4);
				newcell.innerHTML = data[i].grade;
				newcell.width="50px";
				newcell.style.textAlign = "center";
		}
    // set the mouse pointer to default cursor
		document.body.style.cursor = 'default';
	});
}


// displays the Student grade info in the form for updating
function manageStudentGrade(idStudentRec){
	var f = document.forms["formEditGrade"];
	f.reset();
	for(var i in StudentGrades){
		if(StudentGrades[i].id==idStudentRec){
				var divObj = document.getElementById("StudentAccount");
				divObj.style.display = "block";
			StudentIDForEdit = idStudentRec;

			f.studentFirstName.value = StudentGrades[i].firstname;
			f.studentLastName.value = StudentGrades[i].lastname;
			f.studentSection.value = StudentGrades[i].sectionname;
			f.studentGrade.value = StudentGrades[i].grade;
			window.scrollTo(0,document.body.scrollHeight);
			break;
		}
	}
}


// reset the form that contains the student grade info
function clearStudentInfo(){
	var f = document.forms["formEditGrade"];
	f.reset();
	StudentIDForEdit = 0
	var divObj = document.getElementById("StudentAccount");
	divObj.style.display = "none";

}

function saveStudentGrade(){
	var f = document.forms["formEditGrade"];
	var studentInfo = new Object();
	studentInfo.id = StudentIDForEdit;
	studentInfo.grade = f.studentGrade.value;
	studentInfo.remarks = f.studentRemarks.value;

	var param=JSON.stringify(studentInfo);
	document.body.style.cursor= 'wait';
	$.ajax({
		cache:false,
		url: "../php/savestudentgrade.php?param=" + param,
		dataType: "text"
	}).done(function (data) {
		getMyStudents(getParam("id"));
		clearStudentInfo();
		document.body.style.cursor = 'default';
	});
	document.body.style.cursor = 'default';
	return true;

}
