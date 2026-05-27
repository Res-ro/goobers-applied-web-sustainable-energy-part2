<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goobers - Home</title>
    <link rel="stylesheet" href="./styles/styles.css">
  </head>
<body>

    <header class="header">

	<!-- Logo -->
	<div class="item-1">
		<div class="logo_and_title">
		<a href="index.php">
			<img src="images/logo.png" alt="Goobers Logo"
				id="logo" aria-label="Go to homepage"
				title="Take me home!">
		</a>
		<h1>Goobers</h1>
		</div>
	</div>


	<!-- Login button -->
	<div class="item-2" aria-label="Login button">
		<?php if (isset($_SESSION['username'])): ?>
			<div class="session-div">
			<p class="account-name">
			<?php echo htmlspecialchars($_SESSION['username']); ?>
			</p>
			<a href="profile.php" id="profile_button">
			<img src="images/user_icon.png" alt="user_icon"
				width="50" height="50">
			</a>
			</div>
		<?php else: ?>
			<div class="no-session-div">
			<a href="sign_in.php" id="account-button">Log in</a>
			</div>
		<?php endif; ?>
	</div>

     <!-- Navigation Bar -->
      <nav class="item-3" aria-label="Main navigation">
        <ul id="links">
          <li><a href="index.php" aria-current="page">Home</a> </li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="apply.php">Apply</a></li>
          <li><a href="about_us.php">About Us</a></li>
        </ul>
      </nav>
    </header>
