<?php
include "config.php";

$studentid='';
if(isset($_GET["studentid"])){
  $studentid=$_GET["studentid"];
  //echo 'parameter subject is defined';
}//else{
//  echo 'parameter subject is NOT defined';
//}

class Subjects{
  public $section;
  public $comment;
  public $grade;
  public $remarks;
}

$listOfSubjects=array();
//$result = mysqli_query($con,"SELECT * FROM schoolportal.subject where subjectname='".$subjectname."'");
$sql = "select c.sectionname,b.comment,a.grade,a.remarks from studentclass a,subject b,sectionlist c where a.subjectid=b.idsubject and a.sectionid=c.sectionid and a.studentid=".$studentid."";
//echo "$sql";
$result = mysqli_query($con,$sql);

if(mysqli_num_rows($result)>0){
  while($row=mysqli_fetch_assoc($result)){
    $a = new Subjects();
    $a->section=$row['sectionname'];
    $a->comment=$row['comment'];
    $a->grade=$row['grade'];
    $a->remarks=$row['remarks'];
    $listOfSubjects[]=$a;
  }
}


echo json_encode($listOfSubjects);
 ?>
