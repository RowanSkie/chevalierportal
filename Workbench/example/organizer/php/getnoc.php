<?php
	include 'config.php';
	include 'checkuser.php';

	class Country{
		public $Code;
		public $Name;
	}
		
	$result = mysqli_query($con,"SELECT code,name FROM country order by name");
	
	$aCountry = array();
	while($row=mysqli_fetch_assoc($result)){
		$country = new Country();
		$country->Code = $row['code'];
		$country->Name = $row['code'] . " - " . $row['name'];
		$aCountry[] = $country;
	}

	$resp->data=$aCountry;
	echo json_encode($resp);
?>