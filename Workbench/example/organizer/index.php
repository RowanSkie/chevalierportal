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
	if(isset($_POST["btnLogin"])){
		$username = $_POST['txtUserName'];
		$password = $_POST['txtPassword'];
		
		$time = date('Y-m-d H:i:s');

		// check login details
		$result = mysqli_query($con,"SELECT a.id,b.idsubscription,b.groupcode FROM user a, organizer b WHERE a.username ='".$username."' AND a.password = '".$password."' AND a.usertype=2 AND a.isactive=1 and a.username=b.username");	
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_assoc($result);
			$user->user=$username;
			$user->id = $row['id'];
			$user->subscription = $row['idsubscription'];
			$user->groupcode = $row['groupcode'];		
			$user->token = uniqid(rand(), true);
			$result = mysqli_query($con,"UPDATE user SET token='".$user->token."', lastactivity='".$time."' WHERE username ='".$username."' AND usertype=2 AND isactive=1");

			setcookie("user", $username, time() + (3 * 60 * 1000)); 			
			setcookie("id", $user->id, time() + (3 * 60 * 1000)); 			
			setcookie("subscription", $user->subscription, time() + (3 * 60 * 1000)); 			
			setcookie("groupcode", $user->groupcode, time() + (3 * 60 * 1000)); 			
			setcookie("token", $user->token, time() + (3 * 60 * 1000)); 			
			setcookie("usertype", "2", time() + (3 * 60 * 1000)); 			
			header("Location: main.html");
			}
		else{
		?>
			<script>
				alert("Invalid username or password.");
			</script>
		<?php
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
		<script type="text/javascript" src="js/global.js?filever=<?=filesize('js/global.js')?>"></script>
		<link rel="stylesheet" type="text/css" href="css/index.css?version=1.0">
		<link rel="stylesheet" type="text/css" href="css/center.css?version=1.0">
		<title>Organizer</title>
		<script>		
			function register(){
				location.replace ("register.html");
			}			
		</script>
	</head>
	<body onload="clearParam()">
		<div class="center">
			<h1>Organizer Login</h1>
			<table style="position:relative; margin-left:auto;margin-right:auto;top:100px;">
				<tr>
					<td colspan="2" style="text-align:center;">
						 <img id="loginImage" src="images/gmsytemlogo.jpg" style="width:200px;height:240px;"> 
					</td>
				</tr>
			</table>
			<br>
			<form method="POST">
				<table style="position:relative; margin-left:auto;margin-right:auto;top:100px;">
					<tr>
						<td>Username:</td>
						<td><input style="font-size:20px;width:200px;" type="text" name="txtUserName" placeholder="Username" required>
						</td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input style="font-size:20px;width:200px;;" type="password" name="txtPassword" placeholder="Password" required>
						</td>
					</tr>
				</table><br><br>
				<table style="position:relative;margin-left:auto;margin-right:auto;top:100px;">
					<tr>
						<td>
							<input class="btn" type="submit" name="btnLogin" value="Login">
						</td>
					</tr>
				</table>
			</form>
			<!--table style="position:relative;margin-left:auto;margin-right:auto;top:100px;">
				<tr>
					<td>
						<input class="btn" type="submit" name="btnRegister" onclick="register()" value="Register">
					</td>
				</tr>
			</table-->
		</div>
	</body>
</html>