<?php
	include_once("image-resize.php");

	$filename=$_FILES["imgfile"]["name"];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$competition = $_POST["competition"];
	if ((($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png") || ($_FILES["imgfile"]["type"] == "image/gif")  || ($_FILES["imgfile"]["type"] == "image/pjpeg")) && ($_FILES["imgfile"]["size"] < 2000000))
	{
		if(!file_exists("../../competitions/".$competition."/sponsors/")){
			mkdir("../../competitions/".$competition."/sponsors/", 0777, true);
			chmod("../../competitions/".$competition."/sponsors/", 0777);
		}
		if(file_exists("../../competitions/".$competition."/sponsors/".$filename)){
			unlink ("../../competitions/".$competition."/sponsors/".$filename);
		}

		//rename($_FILES["imgfile"]["tmp_name"],"../../competitions/".$competition."/sponsors/".$filename);

		$target_file = $_FILES["imgfile"]["tmp_name"];
		$resized_file = "../../competitions/".$competition."/sponsors/".$filename;
		$wmax = 640;
		$hmax = 480;
		$resized = ak_img_resize($target_file, $resized_file, $wmax, $hmax, $ext);

		chmod("../../competitions/".$competition."/sponsors/".$filename,0777);
	}
?>
