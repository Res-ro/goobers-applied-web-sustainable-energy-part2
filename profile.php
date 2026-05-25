<?php session_start();
require_once("settings.php");

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}
$username = $_SESSION['username'];
$email = $_SESSION['email'];
 ?>
<?php include "header.inc" ?>

<h1>Welcome, <?php echo $username ?></h1>
<p>Is your email still <?php echo $email ?>?</p>
<main>
	<div class="login-container">
	<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
			<div class="form-row">
				<label for="new_email">
				Enter a new email: </label>
				<input type="text" name="new_email">
			</div>
			<input type="submit">
		</form>
	</div>
</main>

<?php
$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$new_email = $_POST["new_email"];
	$query = "UPDATE user SET email = '$new_email' WHERE username = '$username'";

	$result = mysqli_query($conn, $query);

	if ($result) {
		$_SESSION['email'] = $new_email;
		header("Location: profile.php");
		exit();
	} else {
		echo "<h1>Bad input.</h1>";
	}
}
?>
<?php include "footer.inc" ?>
