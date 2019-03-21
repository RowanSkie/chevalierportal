<?php
include "config.php";

$student='';
if(isset($_GET["name"])){
  $student=$_GET["name"];
}

class Students{
  public $studentname;
  public $year;
  public $sectionname;
  public $comment;
  public $grade;

}

  $StudentList=array();
  $result = mysqli_query($con,"SELECT a.studentname,b.year,d.sectionname,c.comment,b.grade FROM studentlist a, studentclass b, subject c, sectionlist d where a.iduser=b.studentid and b.subjectid = c.idsubject and b.sectionid = d.sectionid");
  if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
      $a = new Students();
      $a->studentname=$row['studentname'];
      $a->year=$row['year'];
      $a->sectionname=$row['sectionname'];
      $a->comment=$row['comment'];
      $a->grade=$row['grade'];
      $StudentList[]=$a;
    }
  }

  echo json_encode($StudentList);
 ?>
