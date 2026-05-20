<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goobers - Home</title>
    <link rel="stylesheet" href="./styles/styles.css">
  </head>
  <body>

    <header>
      <div id="header">
	      <a href="index.php">
		     <img src="images/logo.png" alt="Goobers Logo" id="logo" aria-label="Go to homepage" title="Take me home!">
        </a>

        <h1>Goobers</h1>
          <!-- Search box -->
        <form id="search-box" action="#" aria-label="Site search">

          <label for="search-input" class="visually-hidden">Search the site</label>

          <input type="text" id="search-input" placeholder="Search..." name="search" pattern="[A-Za-z0-9\s.,'-]{1,100}" maxlength="100" aria-label="Search input field">

          <button type="submit" aria-label="Submit search">
            Search
          </button>

        </form>
      </div>
     <!-- Navigation Bar -->
      <nav id="links" aria-label="Main navigation">
        <ul>
          <li>
            <a href="index.php" aria-current="page">Home</a>
          </li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="apply.php">Apply</a></li>
          <li><a href="about_us.php">About Us</a></li>
        </ul>
      </nav>
    </header>
