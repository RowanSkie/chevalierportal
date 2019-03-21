<?php
	include 'checkuser.php';
	$sponsors = array();
	$competition = $_GET["competition"];

	if(!file_exists("../../competitions/".$competition."/sponsors/")){
		$resp->errorno= 2;
		$resp->message= "Error retrieving sponsors!";
	}
	$dir = "../../competitions/".$competition."/sponsors/";
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
				 RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
		if (!$file->isDir()){
			$path = str_replace("../../","../",$file->getPathname());
			$sponsors[] = str_replace("\\","/",$path);
		}
	}
	$resp->data = $sponsors;
	echo json_encode($resp);
?>