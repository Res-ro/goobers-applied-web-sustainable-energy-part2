<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['username'])) {
	header("Location: sign_in.php");
	exit();
}

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

$errormsg = "";

/* HANDLE UPDATE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$new_username = trim($_POST["username"]);
	$current_username = $_SESSION['username'];

	// basic safety check
	if ($new_username === "") {
		$errormsg = "Username cannot be empty.";
	} else {

		// update username everywhere it appears
		$query = "UPDATE user SET username = '$new_username' WHERE username = '$current_username'";
		$result = mysqli_query($conn, $query);

		if ($result) {

			// update session
			$_SESSION['username'] = $new_username;

			header("Location: profile.php");
			exit();

		} else {
			$errormsg = "Update failed.";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Username</title>
    <link rel="stylesheet" href="./styles/edit_username.css">
  </head>
  <body>

<main class="login-main">

<div class="container-main">

    <!-- CREAM BOX -->
    <div class="container-header">

        <h2>Change Username</h2>
        <p>Current: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

        <form id="usernameForm" class="form-container" method="post">

            <div class="form-row">
                <label for="username">New Username:</label>
                <input type="text" name="username"
                    value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
            </div>

        </form>


	<div class="error-message">
		<?php echo $errormsg; ?>
	</div>
    <!-- OUTSIDE BUTTON (BOTTOM RIGHT) -->
    <div class="submit-container">
	<a href="profile.php" class="custom-button back-btn">Back</a>
        <input type="submit"
               form="usernameForm"
               class="custom-button"
               value="Update">
    </div>


</div>
    </div>

</main>
<?php include "footer.inc" ?>
