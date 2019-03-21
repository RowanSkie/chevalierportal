<?php
	include 'config.php';
	include 'checkuser.php';

	class PlayerPosition{
		public $ID;
		public $Description;
	}
		
	$result = mysqli_query($con,"SELECT idposition,description FROM playerposition where idposition <8 order by description");
	
	$aPos = array();
	while($row=mysqli_fetch_assoc($result)){
		$pp = new PlayerPosition();
		$pp->ID = $row['idposition'];
		$pp->Description = $row['description'];
		$aPos[] = $pp;
	}
	$resp->data=$aPos;
	echo json_encode($resp);
?>