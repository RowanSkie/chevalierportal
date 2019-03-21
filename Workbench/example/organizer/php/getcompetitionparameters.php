<?php
	include 'config.php';
	include 'checkuser.php';

	class Country{
		public $Code;
		public $Code2;
		public $Name;
	}

	class Role{
		public $Code;
		public $Name;
	}

	
	class Parameters{
		public $noc;
		public $officialtech;
		public $officialnontech;
	}
	
	
	$result = mysqli_query($con,"SELECT code,name,code2 FROM country order by name");
	
	$param = new Parameters();
	$aCountry = array();
	while($row=mysqli_fetch_assoc($result)){
		$country = new Country();
		$country->Code = $row['code'];
		$country->Name = $row['code'] . " - " . $row['name'];
		$country->Code2 = $row['code2'];
		$aCountry[] = $country;
	}
	$param->noc = $aCountry;
	
	$result = mysqli_query($con,"SELECT idofficialrole,description FROM officialrole where idofficialtype='TECHNICAL' order by description");
	$aTRole = array();
	while($row=mysqli_fetch_assoc($result)){
		$or = new Role();
		$or->Code = $row['idofficialrole'];
		$or->Name = $row['description'];
		$aTRole[] = $or;
	}
	$param->officialtech = $aTRole;

	$result = mysqli_query($con,"SELECT idofficialrole,description FROM officialrole where idofficialtype='NON-TECHNICAL' order by description");
	$aRole = array();
	while($row=mysqli_fetch_assoc($result)){
		$or = new Role();
		$or->Code = $row['idofficialrole'];
		$or->Name = $row['description'];
		$aRole[] = $or;
	}
	$param->officialnontech = $aRole;
	
	$resp->data=$param;
	echo json_encode($resp);
	
?>