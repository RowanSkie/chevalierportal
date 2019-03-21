<?php
	ob_start();
	include 'config.php';
	ob_get_clean();

	$filename=$_FILES["officialCSV"]["name"];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$competition = $_POST['competition'];
	$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
	if($filename==""){
		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#officials\">";
		echo "<script>","alert('No file selected.');","</script>";
	}
//	else if(in_array($_FILES['officialCSV']['type'],$mimes))
//	{
		if(file_exists($_FILES["officialCSV"]["name"]))
		{
			unlink ($_FILES["officialCSV"]["name"]);
		}

		move_uploaded_file($_FILES["officialCSV"]["tmp_name"],"../tmp/$filename");
		$row = 1;
		$error = 0;
		$records = 0;
		if (($handle = fopen("../tmp/$filename", "r")) !== FALSE) {
			Echo "CSV import summary of error(s):<br><br>";

			//read the header
			if(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
				$num = count($data);
				if ($num != 9){
					echo "<script>","alert(\"File does not contain valid data\");","</script>";
					echo "<meta http-equiv=refresh content=\"0; URL=../event.html#officials\">";
					fclose($handle);
					exit;
				}
				$query="DELETE FROM officials WHERE idcompetition=".$competition."";
				$result=mysqli_query($con,$query);

				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if ($num != 9){
						echo "<script>","alert(\"Row ".$row." does not contain valid data\");","</script>";
						$error++;
					}else{
						$row++;
						$officialtype=strtoupper(ltrim(rtrim($data[0])));
						$officialrole=strtoupper(ltrim(rtrim($data[1])));
						$name=strtoupper(ltrim(rtrim(str_replace("'","''",$data[2]))));
						$noc=strtoupper(ltrim(rtrim($data[3])));
						$passport=strtoupper(ltrim(rtrim($data[4])));
						$nric=strtoupper(ltrim(rtrim($data[5])));
						// change date format from mm/dd/yyyy to dd-mm-yyyy
						$data[6]= str_replace('/','-',$data[6]);
						$dob=strtoupper(ltrim(rtrim(date('Y-m-d',strtotime($data[6])))));
						$email=ltrim(rtrim($data[7]));
						$mobile=strtoupper(ltrim(rtrim($data[8])));
						// check the
						$query="SELECT code FROM country WHERE code='".$noc."' LIMIT 1";
						$result=mysqli_query($con,$query);
						if($row=mysqli_fetch_assoc($result)){
							// get the idofficialrole
							$query="SELECT idofficialrole FROM officialrole WHERE idofficialtype='".$officialtype."' AND description='".$officialrole."' LIMIT 1";
							$result=mysqli_query($con,$query);
							if($row=mysqli_fetch_assoc($result)){
								$idofficialrole=$row['idofficialrole'];
								// chief referee
								if ($idofficialrole == 4){
									$query = "UPDATE competition SET chiefreferee='". $name. "' where idcompetition=".$competition."";
									mysqli_query($con,$query);
									$query = "DELETE FROM officials WHERE  idcompetition=".$competition." AND idofficialrole=4";
									mysqli_query($con,$query);
								}
								// technical delegate
								if ($idofficialrole == 1){
									$query = "UPDATE competition SET technicaldelegate='". $name. "' where idcompetition=".$competition."";
									mysqli_query($con,$query);
									$query = "DELETE FROM officials WHERE  idcompetition=".$competition." AND idofficialrole=1";
									mysqli_query($con,$query);
								}
								$query="INSERT INTO officials (idcompetition,idofficialrole,idofficialtype,name,noc,birthdate,passport,nric,email,mobileno) VALUES(".$competition.",".$idofficialrole.",'".$officialtype."','".$name."','".$noc."','".$dob."','".$passport."','".$nric."','".$email."','".$mobile."')";
								$result=mysqli_query($con,$query);
							}else{
								// error for official
								echo "ERROR: Role of ".$name." not found in system. Role in csv set as ".$officialrole."<br>";
								$error++;
							}
							$records++;
						}else{
								// error for official
								echo "ERROR: NOC of ".$name." not found in system. NOC in csv set as ".$noc."<br>";
								$error++;
						}
					}
				}
			}
			fclose($handle);
			if ($error>0){
				echo "<BR>",$records," Officials imported successfully.";
				Echo "<BR><BR><a href='../event.html#officials'>Go Back</a>";
			}else{
				ob_end_clean();
				echo "<meta http-equiv=refresh content=\"0; URL=../event.html#officials\">";
				echo "<script>","alert(\"",$records," Officials imported successfully.\");","</script>";
			}
		}else{
			echo "<meta http-equiv=refresh content=\"0; URL=../event.html#officials\">";
			echo "<script>","alert('Error processing file.');","</script>";
		}
//	}
//	else
//	{
//		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#officials\">";
//		echo "<script>","alert('Invalid file.');","</script>";
//	}

?>
