<?php
session_start();
require_once("settings.php");

$errormsg = "";

if (isset($_SESSION['errormsg'])) {
	$errormsg = $_SESSION['errormsg'];
	unset($_SESSION['errormsg']);
}

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$username = $_POST["username"];
	$password = $_POST["password"];

	$query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
	$result = mysqli_query($conn, $query);
	$user = mysqli_fetch_assoc($result);

	if ($user) {

		$_SESSION['username'] = $user['username'];
		$_SESSION['email'] = $user['email'];
		$logLogin = "INSERT INTO login_history (username, login_time)
					 VALUES ('$username', NOW())";
		mysqli_query($conn, $logLogin);
		header("Location: profile.php");
		exit();
	
	} else {
		$_SESSION['errormsg'] = "Invalid input, try again...";
		header("Location: sign_in.php");
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="./styles/loginpage.css">
  </head>
  <body>



<main class="login-main">

<div class="login-container">
	<div class="item item-1">
<a href=".">
		<div class="logo">
			<img src="images/logo.png" alt="logo_image">
		</div>
</a>

		<div class="sign-in-message">
			<h2>Sign in</h2>
			<p>To access your welcome page</p>
		</div>
	</div>

	<div class="item item-2">
	<form class="form-container" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
			<div class="inputs-container">
				<div class="form-row">
					<label for="username">Username: </label>
					<input type="text" name="username">
				</div>

				<div class="form-row">
					<label for="password">Password: </label>
					<input type="password" name="password">
				</div>
			</div>
			<div class="submit-container">
				<div class="error-message">
					<?php echo $errormsg; ?>
				</div>
				<input type="submit" class="custom-button" value="Login">
			</div>
		</form>
	</div>
</div>

</main>


<?php include "footer.inc" ?>
