<?php
	ob_start();
	include 'config.php';
	ob_get_clean();

	$filename=$_FILES["scheduleCSV"]["name"];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$idcompetition = $_POST['competition'];
	$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
	if($filename==""){
		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#schedule\">";
		echo "<script>","alert('No file selected.');","</script>";		
	}
	else if(in_array($_FILES['scheduleCSV']['type'],$mimes))
	{
		if(file_exists($_FILES["scheduleCSV"]["name"]))
		{
			unlink ($_FILES["scheduleCSV"]["name"]);
		}

		move_uploaded_file($_FILES["scheduleCSV"]["tmp_name"],"../tmp/$filename");
		$row = 1;
		$error = 0;
		if (($handle = fopen("../tmp/$filename", "r")) !== FALSE) {
			//read the header
			if(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
				$num = count($data);
				if ($num != 18){
					echo "<script>","alert(\"File does not contain valid data\");","</script>";
					echo "<meta http-equiv=refresh content=\"0; URL=../event.html#schedule\">";
					fclose($handle);
					exit;
				}
				$query="DELETE FROM event WHERE idcompetition=".$idcompetition."";
				$result=mysqli_query($con,$query);

				$query="DELETE FROM eventplayers WHERE idcompetition=".$idcompetition."";
				$result=mysqli_query($con,$query);

				$query="DELETE FROM eventgamescore WHERE idcompetition=".$idcompetition."";
				$result=mysqli_query($con,$query);

				
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if ($num != 18){
						echo "<script>","alert(\"Row ".$row." does not contain valid data\");","</script>";
						$error++;
					}else{
						$row++;
						$d0=strtoupper(ltrim(rtrim($data[0]))); //matchno
						$d1=strtoupper(ltrim(rtrim($data[1]))); //courtno
						$d2=strtoupper(ltrim(rtrim(date('Y-m-d',strtotime($data[2]))))); //date
						$d3=strtoupper(ltrim(rtrim($data[3]))); //time
						$d4=strtoupper(ltrim(rtrim($data[4]))); //team a id
						$d5=strtoupper(ltrim(rtrim($data[5]))); //team a email
						$d6=strtoupper(ltrim(rtrim($data[6]))); //team b id
						$d7=strtoupper(ltrim(rtrim($data[7]))); //team b email
						$d8=strtoupper(ltrim(rtrim($data[8]))); //phase
						$d9=strtoupper(ltrim(rtrim($data[9]))); //category
						$d10=ltrim(rtrim($data[10])); //group
						$d11=strtoupper(ltrim(rtrim($data[11]))); //changeside
						$d12=strtoupper(ltrim(rtrim($data[12]))); //matchdesc
						$d13=strtoupper(ltrim(rtrim($data[13]))); //eventtype
						$d14=strtoupper(ltrim(rtrim($data[14]))); //noofset
						$d15=strtoupper(ltrim(rtrim($data[15]))); //noofpoints
						$d16=ltrim(rtrim($data[16])); //prevmatcht1
						$d17=ltrim(rtrim($data[17])); //prevmatcht2
						
						$idteam1="";
						$idteam2="";
						if($d5!="" && $d7!=""){
							// get the id of team 1 and team 2 based on email. if not found throw an error
							$query="SELECT idteam FROM competitionteam WHERE idcompetition=".$idcompetition." AND email='".$d5."'";
							$res=mysqli_query($con,$query);
							if(mysqli_num_rows($res)>0){
								while($r=mysqli_fetch_assoc($res)){
									$idteam1 = $r['idteam'];
								}
							}
							$query="SELECT idteam FROM competitionteam WHERE idcompetition=".$idcompetition." AND email='".$d7."'";
							$res=mysqli_query($con,$query);
							if(mysqli_num_rows($res)>0){
								while($r=mysqli_fetch_assoc($res)){
									$idteam2 = $r['idteam'];
								}
							}
							
						}else{
							$idteam1 = $d4;
							$idteam2 = $d6;
						}

						if ($idteam1!="" && $idteam2!=""){
							// get the game type
							$query="SELECT idgametype,gamecount FROM gametype WHERE description='".$d13."'";
							$res=mysqli_query($con,$query);
							if(mysqli_num_rows($res)>0){
								while($r=mysqli_fetch_assoc($res)){
									$idgametype = $r['idgametype'];
									$gamecount = $r['gamecount'];
								}
								for($ctr=1;$ctr<=$gamecount;$ctr++){
									$query="INSERT INTO event (idcompetition,idmatch,courtno,gameno,date,time,team1,team2,matchdesc,idgametype,noofset,noofpoints,changeside,phase,category,pgroup,prevmatcht1,prevmatcht2) VALUES(".$idcompetition.",'".$d0."',".$d1.",".$ctr.",'".$d2."','".$d3."',".$idteam1.",".$idteam2.",'".$d12."',".$idgametype.",".$d14.",".$d15.",".$d11.",'".$d8."','".$d9."','".$d10."','".$d16."','".$d17."')";
									$res=mysqli_query($con,$query);								
								}

								if($d9=='MEN')
									$cat=1;
								elseif($d9=='WOMEN')
									$cat=2;
								
								$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$d0. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$idcompetition." and idteam=".$idteam1. " and idgametype=".$idgametype." AND idcategory=".$cat." and isactive=1"; 
								mysqli_query($con,$query);
								
								// insert to event match score in advance
								for($j=1;$j<=$gamecount;$j++){
									for($i=1;$i<=$d14;$i++){
										$query = "insert into eventgamescore(idcompetition,idevent,idteam,setno,gameno) values(".$idcompetition.",'".$d0."',".$idteam1.",".$i.",".$j.")";
										mysqli_query($con,$query);
									}
								}
								$query = "insert into eventplayers(idcompetition,idevent,idgametype,idteam,jerseyno,position,idcategory) select idcompetition,'" .$d0. "',idgametype,idteam,jerseyno,position,idcategory from teameventplayers where idcompetition=".$idcompetition." and idteam=".$idteam2. " and idgametype=".$idgametype." AND idcategory=".$cat." and isactive=1"; 
								mysqli_query($con,$query);

								// insert to event match score in advance
								for($j=1;$j<=$gamecount;$j++){
									for($i=1;$i<=$d14;$i++){
										$query = "insert into eventgamescore(idcompetition,idevent,idteam,setno,gameno) values(".$idcompetition.",'".$d0."',".$idteam2.",".$i.",".$j.")";
										mysqli_query($con,$query);		
									}
								}								
								
							}							
						}else{
							$error++;
						}
							
					}
				}				
			}
			fclose($handle);
			echo "<meta http-equiv=refresh content=\"0; URL=../event.html#schedule\">";
			echo "<script>","alert(\"Schedule imported with ".$error." error(s).\");","</script>";
		}else{
			echo "<meta http-equiv=refresh content=\"0; URL=../event.html#schedule\">";
			echo "<script>","alert('Error processing file.');","</script>";			
		}
	}
	else
	{
		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#schedule\">";
		echo "<script>","alert('Invalid file.');","</script>";
	}

?>