<?php
	/*
		retrieve the competitions the team
		participated in
	*/
	include 'config.php';
	include 'checkuser.php';

	class cobj{
		public $event='';
		public $eventid=0;
		public $name='';
		public $jersey='';
		public $category='';
		public $position='';
	}

	$aobj = array();
	$team = $_GET['user'];
	$idcompetition = $_GET['competition'];

	$result = mysqli_query($con,"SELECT idteam FROM competitionteam WHERE email ='".$team."' and idcompetition=".$idcompetition."");

	if($row = mysqli_fetch_assoc($result)) {
		$idteam = $row['idteam'];
		$query = "select a.idgametype,b.name,a.jerseyno,d.description as 'category',c.description,a.position from teameventplayers a, competitionteamparticipant b,gametype c,category d where a.idcompetition=b.idcompetition and a.idteam=b.idteam and a.jerseyno=b.jerseyno and a.idgametype=c.idgametype and a.idcategory=b.category and a.idcategory=d.idcategory and a.idcompetition=".$idcompetition." and a.idteam=".$idteam." and a.isactive=1 and b.isactive=1 order by idgametype,category,jerseyno";
		$result = mysqli_query($con,$query );
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_assoc($result)){
				$obj = new cobj();
				$obj->eventid = $row['idgametype'];
				$obj->event = $row['description'];
				$obj->name = $row['name'];
				$obj->jersey = $row['jerseyno'];
				$obj->category = $row['category'];
				$obj->position = $row['position'];
				$aobj[] = $obj;
			}
		}else{
			$obj = new cobj();
			$aobj[] = $obj;
		}
	}else{
		$obj = new cobj();
		$aobj[] = $obj;
	}
	$resp->data=$aobj;
	echo json_encode($resp);
?>
