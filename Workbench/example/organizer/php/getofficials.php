<?php
	include 'config.php';
	include 'checkuser.php';

	$idcompetition = $_GET['competition'];
	class Official{
		public $id;
		public $idrole;
		public $type;
		public $role;
		public $name;
		public $noc;
		public $passport;
		public $nric;
		public $birthdate;
		public $email;
		public $mobileno;
	}

	$result = mysqli_query($con,"SELECT a.idofficial,a.idofficialtype,b.description,a.name,a.noc,a.passport,a.nric,a.birthdate,a.email,a.mobileno,b.idofficialrole FROM officials a, officialrole b WHERE a.idofficialrole=b.idofficialrole AND idcompetition=".$idcompetition." AND a.isactive=1 ORDER BY b.idofficialrole,a.name");

	$aofficial = array();
	while($row=mysqli_fetch_assoc($result)){
		$official = new Official();
		$official->id = $row['idofficial'];
		$official->idrole = $row['idofficialrole'];
		$official->role = $row['description'];
		$official->type = $row['idofficialtype'];
		$official->name = $row['name'];
		$official->noc = $row['noc'];
		$official->passport = $row['passport'];
		$official->nric = $row['nric'];
		$official->birthdate = date("d/m/Y", strtotime($row['birthdate']));
		$official->email = $row['email'];
		$official->mobileno = $row['mobileno'];
		$aofficial[] = $official;
	}

	$resp->data=$aofficial;
	echo json_encode($resp);
?>
