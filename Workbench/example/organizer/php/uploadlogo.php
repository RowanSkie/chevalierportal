<?php
	include_once("image-resize.php");

	$filename=$_FILES["customLogo"]["name"];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$competition = $_POST["competition"];
	$team = $_POST["idteam"];
	$logo = $_POST["noc"];
	$filename = strtolower($logo.".".$ext);
	if ((($_FILES["customLogo"]["type"] == "image/jpeg") || ($_FILES["customLogo"]["type"] == "image/png")  || ($_FILES["customLogo"]["type"] == "image/pjpeg")) && ($_FILES["customLogo"]["size"] < 2000000))
	{
		if(!file_exists("../../competitions/".$competition."/")){
			mkdir("../../competitions/".$competition."/");
			mkdir("../../competitions/".$competition."/reports/");
			mkdir("../../competitions/".$competition."/sponsors/");
			mkdir("../../competitions/".$competition."/teams/");
		}
		if(!file_exists("../../competitions/".$competition."/teams/".$team."/")){
			mkdir("../../competitions/".$competition."/teams/".$team."/");
		}

		/*
		// delete existing file(s)
		// to avoid garbage files
		$dir = "../../competitions/".$competition."/teams/".$team."/";
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
					 RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
			unlink($file->getRealPath());
			}
		}*/
		// this will not be moved if the team directory is not yet created.
		//rename($_FILES["customLogo"]["tmp_name"],"../../competitions/".$competition."/teams/".$team."/".$filename);

		$target_file = $_FILES["customLogo"]["tmp_name"];
		$resized_file = "../../competitions/".$competition."/teams/".$team."/".$filename;
		$wmax = 640;
		$hmax = 480;
		$resized = ak_img_resize($target_file, $resized_file, $wmax, $hmax, $ext);

		chmod("../../competitions/".$competition."/teams/".$team."/".$filename,0666);
	}
	echo $filename;
?>
