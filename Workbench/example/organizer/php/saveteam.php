<?php

	include 'config.php';
	include 'checkuser.php';

	$t = str_replace("\\","",$_GET['teamdetails']);
	$teamInfo = json_decode($t);

	$competition = $teamInfo->competition;
	$email = $teamInfo->email;
	$password = $teamInfo->password;
	$team = strtoupper($teamInfo->team);
	$noc = strtoupper($teamInfo->noc);
	$category = $teamInfo->category;
	$logo = $teamInfo->logo;

	if($teamInfo->idteam==""){
		$teamid = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and  email = '" .$email. "' and isactive=1");
		if($row = mysqli_fetch_assoc($teamid)) {
			$resp->errorno=2;
			$resp->message= "E-mail already registered.";

		}else{
			/*$teamid = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and  email != '" .$email. "' and isactive=1 and idgroup='".$group."' and idingroup='".$idingroup."'");
			if($row = mysqli_fetch_assoc($teamid)) {
				$resp->errorno=2;
				$resp->message= "Team ID already assigned.";

			}else{*/
				$query = "insert into competitionteam (idcompetition,name,noc,email,password,category,logo)values(".$competition.",'".$team."','".$noc."','".$email."','".$password."',".$category.",'"
				.$logo."')";
				mysqli_query($con,$query);

				$team = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and email = '".$email."' and isactive=1");
				if($row = mysqli_fetch_assoc($team)) {
					$teamNo = $row['idteam'];

					if(!file_exists("../../competitions/".$competition."/")){
						mkdir("../../competitions/".$competition."/", 0777, true);
						chmod("../../competitions/".$competition."/", 0777);
						mkdir("../../competitions/".$competition."/reports/", 0777, true);
						chmod("../../competitions/".$competition."/reports/", 0777);
						mkdir("../../competitions/".$competition."/sponsors/", 0777, true);
						chmod("../../competitions/".$competition."/sponsors/", 0777);
						mkdir("../../competitions/".$competition."/teams/", 0777, true);
						chmod("../../competitions/".$competition."/teams/", 0777);
					}
					if(!file_exists("../../competitions/".$competition."/teams/".$teamNo."/")){
						mkdir("../../competitions/".$competition."/teams/".$teamNo."/", 0777, true);
						chmod("../../competitions/".$competition."/teams/".$teamNo."/", 0777);
					}
					if($teamInfo->customlogo==0){
						if (file_exists("../images/flags/".$logo)){
							copy("../images/flags/".$logo,"../../competitions/".$competition."/teams/".$teamNo."/".$logo."");
						}else{
							copy("../images/flags/nologo.png","../../competitions/".$competition."/teams/".$teamNo."/".$logo."");
						}
					}else{
						rename("../../competitions/".$competition."/teams/".$logo,"../../competitions/".$competition."/teams/".$teamNo."/".$logo."");
					}
					// set the complete path
					$query = "UPDATE competitionteam SET logo='"."../competitions/".$competition."/teams/".$teamNo."/".$logo."' WHERE idcompetition=".$competition." AND idteam=".$teamNo."";
					mysqli_query($con,$query);
					$resp->message= "Team registration successful.";
				}else{
					$resp->errorno=2;
					$resp->message= "Error saving team details.";
				}
			//}
		}
	}else{
		/*
		$teamid = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and  email != '" .$email. "' and isactive=1");
		if($row = mysqli_fetch_assoc($teamid)) {
			$resp->errorno=2;
			$resp->message= "Team ID already assigned.";

		}else*/{
			if(!file_exists("../../competitions/".$competition."/teams/".$teamInfo->idteam."/")){
				mkdir("../../competitions/".$competition."/teams/".$teamInfo->idteam."/", 0777, true);
				chmod("../../competitions/".$competition."/teams/".$teamInfo->idteam."/", 0777);
			}
			if($teamInfo->customlogo==0){
				if(!file_exists("../../competitions/".$competition."/")){
					mkdir("../../competitions/".$competition."/", 0777, true);
					chmod("../../competitions/".$competition."/", 0777);
					mkdir("../../competitions/".$competition."/reports/", 0777, true);
					chmod("../../competitions/".$competition."/reports/", 0777);
					mkdir("../../competitions/".$competition."/sponsors/", 0777, true);
					chmod("../../competitions/".$competition."/sponsors/", 0777);
					mkdir("../../competitions/".$competition."/teams/", 0777, true);
					chmod("../../competitions/".$competition."/teams/", 0777);
				}
				$res = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and email = '".$email."' and isactive=1");
				if($row = mysqli_fetch_assoc($res)) {
					$teamNo = $row['idteam'];
					if(!file_exists("../../competitions/".$competition."/teams/".$teamNo."/")){
						mkdir("../../competitions/".$competition."/teams/".$teamNo."/", 0777, true);
						chmod("../../competitions/".$competition."/teams/".$teamNo."/", 0777);
					}
					// delete existing file(s)
					// to avoid garbage
					$dir = "../../competitions/".$competition."/teams/".$teamInfo->idteam."/";
					$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($it,
								 RecursiveIteratorIterator::CHILD_FIRST);
					foreach($files as $file) {
						if ($file->isDir()){
							rmdir($file->getRealPath());
						} else {
						unlink($file->getRealPath());
						}
					}
					if (file_exists("../images/flags/".$logo)){
						copy("../images/flags/".$logo,"../../competitions/".$competition."/teams/".$teamInfo->idteam."/".$logo."");
					}else{
						copy("../images/flags/nologo.png","../../competitions/".$competition."/teams/".$teamInfo->idteam."/".$logo."");
					}
				}
			}
			$query = "UPDATE competitionteam SET name='".$team."', noc='".$noc."',email='".$email."',category=".$category."";
			if($logo!="")
				$query=$query.",logo='"."../competitions/".$competition."/teams/".$teamInfo->idteam."/".$logo."'";
			if($teamInfo->updatepassword==true){
				$query=$query.",password='".$password."'";
			}
			$query=$query." WHERE idcompetition=".$competition." and idteam=".$teamInfo->idteam."";
			mysqli_query($con,$query);
			$resp->message= "Team information updated successfully.";
		}
	}

	echo json_encode($resp);
?>
