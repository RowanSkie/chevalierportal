<?php
	ob_start();
	include 'config.php';
	ob_get_clean();
	include_once("image-resize.php");

	$filename=$_FILES["imgfile"]["name"];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$logo = $_POST['logo'];
	$competition = $_POST['competition'];
	$filename = $logo.".".$ext;
	if($ext==""){
		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#report\">";
		echo "<script>","alert('No file selected.');","</script>";
	}
	else if ((($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png")  || ($_FILES["imgfile"]["type"] == "image/pjpeg")))
	{
		if(file_exists($_FILES["imgfile"]["name"]))
		{
		  unlink ($_FILES["imgfile"]["name"]);
		}

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

		//move_uploaded_file($_FILES["imgfile"]["tmp_name"],"../../competitions/".$competition."/reports/".$filename);

		$target_file = $_FILES["imgfile"]["tmp_name"];
		$resized_file = "../../competitions/".$competition."/reports/".$filename;
		$wmax = 640;
		$hmax = 480;
		$resized = ak_img_resize($target_file, $resized_file, $wmax, $hmax, $ext);


		if ($logo=="left"){
			mysqli_query($con,"UPDATE competition set leftlogo='../competitions/".$competition."/reports/".$filename."' WHERE idcompetition=".$competition."");
		}elseif ($logo=="right"){
			mysqli_query($con,"UPDATE competition set rightlogo='../competitions/".$competition."/reports/".$filename."' WHERE idcompetition=".$competition."");

		}elseif ($logo=="sponsor1"){
			mysqli_query($con,"UPDATE competition set sponsor1logo='../competitions/".$competition."/reports/".$filename."' WHERE idcompetition=".$competition."");

		}elseif ($logo=="sponsor2"){
			mysqli_query($con,"UPDATE competition set sponsor2logo='../competitions/".$competition."/reports/".$filename."' WHERE idcompetition=".$competition."");
		}
		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#report\">";
		}
	else
	{
		echo "<script>","alert(Invalid file.);","</script>";
		echo "<meta http-equiv=refresh content=\"0; URL=../event.html#report\">";
	}

?>
