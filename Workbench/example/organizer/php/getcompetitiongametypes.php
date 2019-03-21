<?php
	include 'config.php';
	include 'checkuser.php';

	class gametype{
		public $id;
		public $description;
		public $playing;
		public $reserved;
		public $category;
		public $maxplayers;
		public $maxgroup;
		public $maxteam;
	}
		
	$competition = $_GET['competition'];
	$result = mysqli_query($con,"select a.idgametype, b.description, a.playing, a.reserved, a.category,a.maxgroup,a.maxteam,b.minplayers,b.maxplayers,b.gamecount from competitiongametype a, gametype b where a.idgametype=b.idgametype and idcompetition=".$competition);
	
	$aGT = array();
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			$gt = new gametype();
			$gt->id = $row['idgametype'];
			$gt->description = $row['description'];
			$gt->playing = $row['playing'];
			$gt->reserved = $row['reserved'];
			$gt->category = $row['category'];
			$gt->minplayers = $row['minplayers'];
			$gt->maxplayers = $row['maxplayers'];
			$gt->maxgroup = $row['maxgroup'];
			$gt->maxteam = $row['maxteam'];
			$gt->gamecount = $row['gamecount'];
			$aGT[] = $gt;
		}
	}else{
		$gt = new gametype();
		$aGT[] = $gt;
	}
	$resp->data = $aGT;
	echo json_encode($resp);
?>