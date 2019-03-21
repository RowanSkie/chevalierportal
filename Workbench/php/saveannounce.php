<?php
include "config.php";

if(isset($_GET["param"])){
  $param=$_GET["param"];
}else{
  return;
}
// student grade
$si = json_decode($param);

$query = "insert into schoolportal.announce(postdate,enddate,post,importance) values ('".$si->postdate."','".$si->enddate."','".$si->post."','".$si->importance."')";
mysqli_query($con,$query);

echo $query;
echo "ok";
 ?>
