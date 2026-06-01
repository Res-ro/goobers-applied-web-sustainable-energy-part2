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
<title>Goobers - Profile</title>
<main>


<div class="profile-main">
<h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>



<div class="account-settings">

<h2>Account Settings</h2>

    <div class="settings-box">

      <!-- Username -->
      <div class="settings-row">
        <span class="settings-label">Username:</span>
        <span class="settings-value"><?php echo htmlspecialchars($username); ?></span>
        <a class="custom-button" href="edit_username.php">Edit</a>
      </div>

      <!-- Email -->
      <div class="settings-row">
        <span class="settings-label">Email:</span>
        <span class="settings-value"><?php echo htmlspecialchars($email); ?></span>
        <a class="custom-button" href="edit_email.php">Edit</a>
      </div>

      <!-- Password -->
      <div class="settings-row">
        <span class="settings-label">Password:</span>
        <span class="settings-value">••••••••</span>
        <a class="custom-button" href="edit_password.php">Edit</a>
      </div>

    </div>

</div>
</div>




<!-- <div class="change-email"> -->
<!-- <p>Is your email still <?php echo $email ?>?</p> -->

<!-- 	<div class="login-container"> -->
<!-- 	<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"> -->
<!-- 			<div class="form-row"> -->
<!-- 				<label for="new_email"> -->
<!-- 				Enter a new email: </label> -->
<!-- 				<input type="text" name="new_email"> -->
<!-- 			</div> -->
<!-- 			<input type="submit"> -->
<!-- 	</form> -->
<!-- 	</div> -->
<!-- </div> -->
</main>

<?php include "footer.inc" ?>
