<?php
session_start();
require_once("settings.php");

if (!isset($_SESSION['username'])) {
    header("Location: sign_in.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

$errormsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }

    $new_email       = trim($_POST["email"]);
    $safe_username   = mysqli_real_escape_string($conn, $_SESSION['username']);

    if ($new_email === "") {
        $errormsg = "Email cannot be empty.";

    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errormsg = "Please enter a valid email address.";

    } elseif (strlen($new_email) > 50) {
        $errormsg = "Email cannot exceed 50 characters.";

    } else {
        $safe_email = mysqli_real_escape_string($conn, $new_email);

        $query  = "UPDATE user SET email = '$safe_email' WHERE username = '$safe_username'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['email'] = $new_email;
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
    <title>Change Email</title>
    <link rel="stylesheet" href="./styles/edit_pages.css">
  </head>
  <body>

<main class="login-main">

<div class="container-main">

    <div class="container-header">

        <h2>Change Email</h2>
        <p>Current: <?php echo htmlspecialchars($_SESSION['email']); ?></p>

        <form id="emailForm" class="form-container" method="post">

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-row">
                <label for="email">New Email:</label>
                <input type="email" id="email" name="email"
                    value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
            </div>

        </form>

        <div class="error-message">
            <?php echo htmlspecialchars($errormsg); ?>
        </div>

        <div class="submit-container">
            <a href="profile.php" class="custom-button back-btn">Back</a>
            <input type="submit"
                   form="emailForm"
                   class="custom-button"
                   value="Update">
        </div>

    </div>
</div>

</main>
<?php include "footer.inc" ?>