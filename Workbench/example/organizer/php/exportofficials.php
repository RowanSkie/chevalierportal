<?php
	include 'config.php';
	include 'exporttocsv.php';
	
	$idcompetition = $_GET['competition'];
	$header = array("Type","Role","Name","NOC","Passport","NRIC","DateOfBirth","e-mail","mobileno");

	$result = mysqli_query($con,"SELECT a.idofficial,a.idofficialtype,b.description,a.name,a.noc,a.passport,a.nric,a.birthdate,a.email,a.mobileno,b.idofficialrole FROM officials a, officialrole b WHERE a.idofficialrole=b.idofficialrole AND idcompetition=".$idcompetition." AND a.isactive=1 ORDER BY b.idofficialrole");

	$arec = array();
	$arec[] = $header;
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$aobj = array();
			$aobj[] = $row['idofficialtype'];
			$aobj[] = $row['description'];
			$aobj[] = $row['name'];
			$aobj[] = $row['noc'];
			$aobj[] = $row['passport'];
			$aobj[] = $row['nric'];
			$aobj[] = $row['birthdate'];
			$aobj[] = $row['email'];
			$aobj[] = $row['mobileno'];
			$arec[]= $aobj;
		}
	}else{
		$aobj = array();			
		$arec[] = $aobj;
	}
	$title="";
	$result = mysqli_query($con,"SELECT title FROM competition where idcompetition=" .$idcompetition."");
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$title = $row['title']." ";
		}
	}	
	array_to_csv_download($arec, $filename = $title."officials.csv", $delimiter=",");
?>