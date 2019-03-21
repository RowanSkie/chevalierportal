<?php
	include 'config.php';
	include 'exporttocsv.php';

	$team = $_GET['user'];
	$idcompetition = $_GET['competition'];
	$header = array("Jersey","Name","Position","DateOfBirth","Gender");//,"Height","Weight","Passport","NRIC","E-mail","MobileNo","Gender");
	$query = "SELECT idteam,name FROM competitionteam WHERE email ='".$team."' and idcompetition=".$idcompetition." and isactive=1";
	$result = mysqli_query($con,$query);
	if($row = mysqli_fetch_assoc($result)) {
		$idteam = $row['idteam'];
		$name =  $row['name'];
		$query = "select jerseyno,category,name, position,height,weight,birthdate,passport,nric,email,mobileno from competitionteamparticipant WHERE idcompetition=".$idcompetition." and idteam=".$idteam. " and isactive=1 order by jerseyno";
		$result = mysqli_query($con,$query );
		$arec = array();
		$arec[] = $header;
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)){
				$aobj = array();
				if($row['jerseyno']>1)
					$aobj[]= $row['jerseyno'];
				else
					$aobj[]= "";
				$aobj[]= $row['name'];
				$aobj[]= $row['position'];
				$aobj[]= $row['birthdate'];
				//$aobj[]= $row['height'];
				//$aobj[]= $row['weight'];
				//$aobj[]= $row['passport'];
				//$aobj[]= $row['nric'];
				//$aobj[]= $row['email'];
				//$aobj[]= $row['mobileno'];
				if($row['category']==1)
					$aobj[]= 'MALE';
				else
					$aobj[]= 'FEMALE';
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
				$title = $row['title']."-";
			}
		}
		array_to_csv_download($arec, $filename = $title.$name.".csv", $delimiter=",");
	}
?>
