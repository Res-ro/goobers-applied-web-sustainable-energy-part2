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
			<div class="dropdown">
				<div class="dropdown-header">
				<p class="dropdown-btn">
				<?php echo htmlspecialchars($_SESSION['username']); ?>
				<div id="profile_button" aria-label="Go to profile">
				</div>
				</div>
				<div class="dropdown-content">
					<ul>
						<li><a href="profile.php">Account Settings</a></li>
						<li><a href="manage.php">Management Page</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		<?php else: ?>
			<div>
			<a href="sign_in.php" class="custom-button" id="account-button">Log in</a>
			</div>
		<?php endif; ?>
	</div>

     <!-- Navigation Bar -->
      <nav class="item-3" aria-label="Main navigation">
      <ul>
          <li><a href="index.php" aria-current="page">Home</a> </li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="apply.php">Apply</a></li>
          <li><a href="about_us.php">About Us</a></li>
        </ul>
      </nav>
    </header>
