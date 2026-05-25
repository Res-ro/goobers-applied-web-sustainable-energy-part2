<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

<main>

<div class="login-container">
	<div class="item item-1">
		<div class="logo">
			<img src="images/logo.png" alt="logo_image" height="50%">
		</div>

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
				<input type="submit" value="Login">
			</div>
		</form>
	</div>
</div>

</main>

<?php

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
		header("Location: profile.php");
		exit();
	} else {
		echo "<h1>Incorrect details.</h1>";
	}
}

?>

<?php include "footer.inc" ?>
