<?php
include "config.php";

$teacher='';
if(isset($_GET["name"])){
  $teacher=$_GET["name"];
}

class Students{
  public $idteacher;
  public $FirstName;
  public $LastName;
  public $subjectid;
}

  $Students=array();
  //$result = mysqli_query($con,"SELECT * FROM schoolportal.subject where subjectname='".$subjectname."'");
  $result = mysqli_query($con,"SELECT * FROM v_teacher");
  if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
      $a = new Students();
      $a->idteacher=$row['idteacher'];
      $a->FirstName=$row['FirstName'];
      $a->LastName=$row['LastName'];
      $a->subjectid=$row['subjectid'];
      $Students[]=$a;
    }
  }

  echo json_encode($Students);
 ?>
