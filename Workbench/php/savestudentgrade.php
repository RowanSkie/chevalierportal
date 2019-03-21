<?php
include "config.php";

if(isset($_GET["param"])){
  $param=$_GET["param"];
}else{
  return;
}
// student grade
$si = json_decode($param);

$query = "update schoolportal.studentclass set grade=".$si->grade.",remarks='".$si->remarks."' where id =".$si->id."";
mysqli_query($con,$query);
echo "ok";
 ?>
