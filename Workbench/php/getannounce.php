<?php
  include "config.php";

  $postid='';
  if(isset($_GET["post"])){
    $postid=$_GET["post"];
  }

  class Announcements{
    public $postdate;
    public $enddate;
    public $post;
    public $importance;
}

  $sql="SELECT * FROM schoolportal.announce where date_format(now(), '%Y-%m-%d') between postdate and enddate";

  $result = mysqli_query($con,$sql);
  if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
      $a = new Announcements();
      $a->postdate=$row['postdate'];
      $a->enddate=$row['enddate'];
      $a->post=$row['post'];
      $a->importance=$row['importance'];
      $AnnounceList[]=$a;
    }
  };


    echo json_encode($AnnounceList);
 ?>
