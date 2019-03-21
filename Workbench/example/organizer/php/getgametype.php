<?php
	include 'config.php';
	include 'checkuser.php';

	class gametype{
		public $id;
		public $description;
		public $playing;
		public $reserved;
		public $category;
		public $maxgroup=0;
		public $maxteam=0;
	}
		
	$result = mysqli_query($con,"select idgametype, description,playing,reserved FROM gametype order by idgametype");
	
	$aGameType = array();
	while($row=mysqli_fetch_assoc($result)){
		$gt = new gametype();
		$gt->id = $row['idgametype'];
		$gt->description = $row['description'];
		$gt->playing = $row['playing'];
		$gt->reserved = $row['reserved'];
		$gt->category=0;
		$aGameType[] = $gt;
	}
	$resp->data=$aGameType;
	echo json_encode($resp);
?>