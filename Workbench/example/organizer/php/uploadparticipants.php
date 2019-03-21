<?php
	ob_start();
	include 'config.php';
	ob_get_clean();

	$filename=$_FILES["teamCSV"]["name"];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$competition = $_POST['competition'];
	$email = $_POST['email'];
	$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
	if($filename==""){
		echo "<meta http-equiv=refresh content=\"0; URL=../team.html\">";
		echo "<script>","alert('No file selected.');","</script>";
	}
//	else if(in_array($_FILES['teamCSV']['type'],$mimes))
//	{
		if(file_exists($_FILES["teamCSV"]["name"]))
		{
			unlink ($_FILES["teamCSV"]["name"]);
		}

		move_uploaded_file($_FILES["teamCSV"]["tmp_name"],"../tmp/$filename");
		$rec = 1;
		$error = 0;
		if (($handle = fopen("../tmp/$filename", "r")) !== FALSE) {
			//read the header
			if(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
				$num = count($data);
				if ($num != 11){
					echo "<script>","alert(\"File does not contain valid data\");","</script>";
					echo "<meta http-equiv=refresh content=\"0; URL=../team.html\">";
					fclose($handle);
					exit;
				}
				$teamid = mysqli_query($con,"select idteam from competitionteam where idcompetition=".$competition." and email = '" .$email. "' and isactive=1");
				if($row = mysqli_fetch_assoc($teamid)) {
					$idteam = $row['idteam'];

					$query="DELETE FROM competitionteamparticipant WHERE idcompetition=".$competition." AND idteam=".$idteam."";
					$result=mysqli_query($con,$query);

					$query="UPDATE competitionteam set manager='',coach='',asstcoach='' WHERE idcompetition=".$competition." AND idteam=".$idteam."";
					$result=mysqli_query($con,$query);

					$rec++;
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$errors = array_filter($data);
						$num = count($errors);
						echo "<script>","alert(\"Line ".$data." columns\");","</script>"; 
						if ($num < 10){
							echo "<script>","alert(\"Line ".$rec."(".$num.") does not contain valid data\");","</script>";
							$error++;
						}else{
							$jersey=strtoupper(ltrim(rtrim($data[0])));
							$name=strtoupper(ltrim(rtrim(str_replace("'","''",$data[1]))));
							$position=strtoupper(ltrim(rtrim($data[2])));
							// change date format from mm/dd/yyyy to dd-mm-yyyy
							$data[3]= str_replace('/','-',$data[3]);
							if (strtoupper(ltrim(rtrim($data[3])))!='')
								$birthdate=strtoupper(ltrim(rtrim(date('Y-m-d',strtotime($data[3])))));
							else
								$birthdate="1900-01-01";
							$height=strtoupper(ltrim(rtrim($data[4])));
							$weight=strtoupper(ltrim(rtrim($data[5])));
							$passport=strtoupper(ltrim(rtrim($data[6])));
							$nric=strtoupper(ltrim(rtrim($data[7])));
							$playeremail=ltrim(rtrim($data[8]));
							$mobileno=strtoupper(ltrim(rtrim($data[9])));
							$gender=strtoupper(ltrim(rtrim($data[10])));
							if($height=='')
								$height=0.0;
							if($weight=='')
								$weight=0.0;
							if($gender=="MALE")
								$cat=1;
							elseif($gender=="FEMALE")
								$cat=2;
							else
								$cat=0;

							if($position=='TEAM MANAGER'){ //manager
								$query = "update competitionteam set manager='".$name."' where idcompetition=".$competition." and idteam=".$idteam;
								mysqli_query($con,$query);

								if($jersey=="")
									$jersey = -3;
							}elseif($position=='COACH'){ //coach
								$query = "update competitionteam set coach='".$name."' where idcompetition=".$competition." and idteam=".$idteam;
								mysqli_query($con,$query);


								if($jersey=="")
									$jersey = -2;
							}elseif($position=='ASST COACH'){//asst coach
								$query = "update competitionteam set asstcoach='".$name."' where idcompetition=".$competition." and idteam=".$idteam;
								mysqli_query($con,$query);

								if($jersey=="")
									$jersey = -1;
							}

							$query = "insert into competitionteamparticipant (idcompetition,idteam,name,jerseyno,position,height,weight,passport,nric,birthdate,email,mobileno,category)values(".$competition.",".$idteam.",'".$name."','".$jersey."','".$position."','".$height."','".$weight."','".$passport."','".$nric."','".$birthdate."','".$playeremail."','".$mobileno."',".$cat.")";
							mysqli_query($con,$query);
						}
						$rec++;
					}

					$query="DELETE FROM eventplayers WHERE idcompetition=".$competition." AND idteam=".$idteam." and jerseyno NOT IN (SELECT jerseyno FROM competitionteamparticipant WHERE idcompetition=".$competition." AND idteam=".$idteam.")";
					$result=mysqli_query($con,$query);

					$query="DELETE FROM teameventplayers WHERE idcompetition=".$competition." AND idteam=".$idteam." and jerseyno NOT IN (SELECT jerseyno FROM competitionteamparticipant WHERE idcompetition=".$competition." AND idteam=".$idteam.")";
					$result=mysqli_query($con,$query);
				}

			}
			fclose($handle);
			echo "<meta http-equiv=refresh content=\"0; URL=../team.html\">";
			echo "<script>","alert(\"Participants loaded successfully with ".$error." error(s).\");","</script>";
		}else{
			echo "<meta http-equiv=refresh content=\"0; URL=../team.html\">";
			echo "<script>","alert('Error processing file.');","</script>";
		}
//	}
//	else
//	{
//		echo "<meta http-equiv=refresh content=\"0; URL=../team.html\">";
//		echo "<script>","alert('Invalid file.');","</script>";
//	}

?>
