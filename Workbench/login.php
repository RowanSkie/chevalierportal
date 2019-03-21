<?php
	include 'php/config.php';
  if(isset($_POST["btnLogin"])){
  		$username = $_POST['txtUserName'];
  		$password = $_POST['txtPassword'];

    //echo $username;
    //echo $password;

    $sql="SELECT * FROM v_userlist WHERE email ='".$username."' AND password = '".$password."'";


    //echo $sql;

    $result = mysqli_query($con,$sql);
  	if(mysqli_num_rows($result)>0){
  		$row=mysqli_fetch_assoc($result);
			// save the user type (teacher or student), student id,FirstName and LastName to the browser cookies
			setcookie("user", $username, time() + (3 * 60 * 1000));
			setcookie("id",  $row['userid'], time() + (3 * 60 * 1000)); // save the id of the user for use in their respective homepage
			setcookie("firstname", $row['FirstName'], time() + (3 * 60 * 1000));
			setcookie("lastname", $row['LastName'], time() + (3 * 60 * 1000));
			setcookie("type", $row['type'], time() + (3 * 60 * 1000)); // 1=student,2=teacher
			if ($row['type']==1) // for student
      	header('Location: home.html');
			elseif($row['type']==2) // for teachers
				header('Location: home/teacher.html');

	 }else{
      ?>
      <script>
  		  alert('Invalid username or password. If you have forgotten your password, please report to the IT Department for a replacement password.');
      </script>
  <?php
  	}
  }
?>
<!DOCTYPE html>
<html oncontextmenu="return false">
	<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/login.css">
		<title>School Portal Login</title>
		<script>
			function register(){
				location.replace ("register.html");
			}
		</script>
	</head>
	<body>
      <div class="title">
          <center><img src="pic/chevalier_school.png"></center>
      </div>
			<br><br>
      <div class="login">
				<div class="item">
					<br>
				</div>
				<div class="item">
					<br>
				</div>
				<div class="loginbox">
        <center>
          <br>
          <font size=4>Log In</font>
          <br><br>
          <form method="POST">
    					Email:<br>
                <input style="font-size:13px;width:200px;" type="text" name="txtUserName" placeholder="Email" required><br>
              Password:<br>
    						<input style="font-size:13px;width:200px;;" type="password" name="txtPassword" placeholder="Password" required><br><br>
    							<input class="btn" type="submit" name="btnLogin" value="Login">
    			</form>
          <br>
					<a href="index.html" class="button">Cancel</a>
					<br><br>
        </center>
				</div>
				<div class="item">
					<br>
				</div>
				<div class="item">
					<br>
				</div>
      </div>
  </body>
</html>
