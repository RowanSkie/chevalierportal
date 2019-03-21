<?php
	ob_start();
	include 'php/config.php';
	ob_get_clean();

	class User{
		public $user="";
		public $id=0;
		public $subscription="";
		public $groupcode="";
		public $token="";
		public $usertype=2;
		public $errorno=1;
		public $message="";
	}

	$user = new User();
	if(isset($_POST["btnUpdate"])){
		$password = $_POST['txtPassword'];
		$newpassword = $_POST['txtNewPassword'];
		$confirmpassword = $_POST['txtConfirmPassword'];

		$time = date('Y-m-d H:i:s');

		// check login details
		if($newpassword!=$confirmpassword){
		?>
			<script>
				alert("New Password and Confirm Password does not match");
			</script>
		<?php
		}else{
			//TO DO:
			// password encryption using sha256

			$user = $_COOKIE['user'];
			$username = $_POST["ddUser"];
			$groupcode = $_COOKIE['groupcode'];
			$subscription = $_COOKIE['subscription'];
			$query = "SELECT 1 FROM organizer a,user b WHERE a.username= b.username and a.username='".$username."' AND b.password='".$password."' AND a.groupcode='".$groupcode."' AND a.idsubscription='".$subscription."'";
			$result = mysqli_query($con,$query);
			if(mysqli_num_rows($result)>0){
				$query = "UPDATE user a,organizer b SET a.password='".$newpassword."' WHERE a.username=b.username AND b.idsubscription='".$subscription."' AND a.username='".$username."'";
				$result = mysqli_query($con,$query);
				if($username==$user){
				?>
					<script>
						alert("Password changed successfully. Please log-in again to continue.");
						window.location = "index.php"
					</script>
				<?php
				}else{
				?>
					<script>
						alert("Password changed successfully.");
					</script>
				<?php
				}
			}else{
				?>
					<script>
						alert("Current password invalid.");
					</script>
				<?php
			}
		}
	}

?>
<!DOCTYPE html>
<html oncontextmenu="return false">
	<head>
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<META HTTP-EQUIV="Expires" CONTENT="-1">
		<meta http-equiv="expires" content="timestamp">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<META NAME="viewport" CONTENT="width=device-width, initial-scale=1.0">
		<script src="js/modernizr-2.6.2.min.js"></script>
		<script src="js/jquery.js"></script>
		<script src="js/jquery.ui.timepicker.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/global.js?version=1.0"></script>
		<script type="text/javascript" src="js/account.js?version=1.0"></script>
		<link rel="stylesheet" type="text/css" href="css/account.css?version=1.0">
		<link rel="stylesheet" type="text/css" href="css/menu.css?version=1.0">
		<link rel="stylesheet" type="text/css" href="css/center.css?version=1.0">
		<title>Account Management</title>
	</head>
	<body>
		<ul style="font-family:helvetica;background-color: #333;">
			<!--li class="icon">
				<a>&#9776;</a>
			</li-->
			<li class="dropdown"><a>Home</a>
				<div class="dropdown-content">
					<a href="index.php" onclick=logout()>Logout</a>
				</div>
			</li>
			<li><a href="main.html" onclick="loadHome()">Back</a></li>
		</ul>
		<div class="center">
			<h1>Update Password</h1>
			<form method="POST">
				<table style="position:relative; margin-left:auto;margin-right:auto;top:100px;">
					<tr>
						<td>Organizer/Referee</td>
						<td id="spacer">:</td>
						<td>
							<select id="ddUser" name="ddUser" style="width:200px;font-size:14px;" required>
							</select>
						</td>
					</tr>
					<tr>
						<td>Current Password</td>
						<td id="spacer">:</td>
						<td><input style="font-size:20px;width:200px;;" type="password" name="txtPassword" required>
						</td>
					</tr>
				</table>
				<br>
				<table style="position:relative; margin-left:auto;margin-right:auto;top:100px;">
					<tr>
						<td>New Password</td>
						<td id="spacer">:</td>
						<td><input style="font-size:20px;width:200px;;" type="password" name="txtNewPassword" required>
						</td>
					</tr>
					<tr>
						<td>Confirm Password</td>
						<td id="spacer">:</td>
						<td><input style="font-size:20px;width:200px;;" type="password" name="txtConfirmPassword" required>
						</td>
					</tr>
				</table><br><br>
				<table style="position:relative;margin-left:auto;margin-right:auto;top:100px;">
					<tr>
						<td>
							<input class="btn" type="submit" name="btnUpdate" value="Update">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<script>
			getUserList();
		</script>
	</body>
</html>
