<?php
session_start();
require_once("settings.php");

//prevents cross site request forgery vulnerability
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}

if (!isset($_SESSION['username'])) {
	header("Location: sign_in.php");
	exit();
}

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

$errormsg = "";

/* HANDLE UPDATE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }
	$current_password  = $_POST["current_password"];
	$new_password      = $_POST["new_password"];
	$confirm_password  = $_POST["confirm_password"];
	$current_username  = $_SESSION['username'];

	//fetch the current stored hash to verify against
	$safe_username = mysqli_real_escape_string($conn, $current_username);
	$result = mysqli_query($conn, "SELECT password FROM user WHERE username = '$safe_username'");
	$user = mysqli_fetch_assoc($result);

	if (!$user) {
		$errormsg = "User not found.";

	} elseif (!password_verify($current_password, $user['password'])) {
		$errormsg = "Current password is incorrect.";

	} elseif (empty($new_password)) {
		$errormsg = "New password cannot be empty.";

	} elseif (strlen($new_password) < 8) {
		$errormsg = "New password must be at least 8 characters.";

	} elseif ($new_password !== $confirm_password) {
		$errormsg = "New passwords do not match.";

	} else {
		//hash the new password before storing
		$hashed = password_hash($new_password, PASSWORD_BCRYPT);
		$update = mysqli_query($conn, "UPDATE user SET password = '$hashed' WHERE username = '$safe_username'");

		if ($update) {
			header("Location: profile.php");
			exit();
		} else {
			$errormsg = "Update failed. Please try again.";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="./styles/edit_pages.css">
  </head>
  <body>

<main class="login-main">

<div class="container-main">

    <div class="container-header">

        <h2>Change Password</h2>
        <p>Update the password for <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

        <form id="passwordForm" class="form-container" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-row">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password">
            </div>

            <div class="form-row">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
            </div>

            <div class="form-row">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

        </form>

	<div class="error-message">
		<?php echo htmlspecialchars($errormsg); ?>
	</div>

    <div class="submit-container">
        <a href="profile.php" class="custom-button back-btn">Back</a>
        <input type="submit"
               form="passwordForm"
               class="custom-button"
               value="Update">
    </div>

</div>
</div>

</main>
<?php include "footer.inc" ?>