<?php
include "config.php";

if(isset($_GET["id"])){
  $idteacher=$_GET["id"];
}

class Students{
  public $id;
  public $firstname;
  public $lastname;
  public $sectionname;
  public $subject;
  public $grade;
}

  $Students=array();
  //$result = mysqli_query($con,"SELECT * FROM schoolportal.subject where subjectname='".$subjectname."'");
  $sql = "select b.id,a.FirstName,a.LastName,d.sectionname,e.subjectname,b.grade from studentlist a, studentclass b, sectionlist d,v_teacher e where a.idstudent=b.studentid and b.sectionid = d.sectionid and b.teacherid=e.idteacher and b.subjectid=e.subjectid and b.teacherid=".$idteacher." order by b.year,b.sectionid,a.lastname,a.firstname";

  $result = mysqli_query($con,$sql);

  if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
      $a = new Students();
      $a->id = $row['id'];
      $a->firstname = $row['FirstName'];
      $a->lastname = $row['LastName'];
      $a->sectionname = $row['sectionname'];
      $a->subject = $row['subjectname'];
      $a->grade = $row['grade'];
      $Students[]=$a;
    }
  }

  echo json_encode($Students);
 ?>
