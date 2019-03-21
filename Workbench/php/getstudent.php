<?php
include "config.php";

$studentname='';
if(isset($_GET["name"])){
  $studentname=$_GET["name"];
}

class Students{
  public $iduser;
  public $studentname;
}

  $Students=array();
  //$result = mysqli_query($con,"SELECT * FROM schoolportal.subject where subjectname='".$subjectname."'");
  $result = mysqli_query($con,"SELECT * FROM schoolportal.studentlist");
  if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
      $a = new Students();
      $a->iduser=$row['iduser'];
      $a->studentname=$row['studentname'];
      $Students[]=$a;
    }
  }

  echo json_encode($Students);
 ?>
